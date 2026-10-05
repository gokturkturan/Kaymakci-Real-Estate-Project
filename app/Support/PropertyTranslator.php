<?php

namespace App\Support;

use App\Models\Property;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Auto-translates Property title/description from German into the site's
 * other locales using the free, keyless MyMemory Translation API
 * (https://mymemory.translated.net/). No account/API key required.
 *
 * None of these locales have an admin input field — title_{locale}/
 * description_{locale} are fully system-managed and re-synced whenever the
 * German source text changes, purely so the storefront doesn't have to call
 * the translation API on every page view.
 *
 * Rich-text formatting (bold/italic/size from the admin's Quill editor) is
 * only reliable in German. MyMemory does usually carry inline tags like
 * <strong> through to the translation, but not deterministically — a given
 * sentence can come back with the tag silently dropped depending on how much
 * the target language reorders words, and retrying an identical query
 * returns the same result. Rather than ship inconsistent formatting, the
 * description is stripped to plain text before translation, so EN/PL/SK/RO
 * are always plain (but always correct) and only the German source keeps
 * formatting.
 */
class PropertyTranslator
{
    private const SOURCE_LOCALE = 'de';

    /** Locales the storefront can display besides the German source. */
    private const TARGET_LOCALES = ['en', 'pl', 'sk', 'ro'];

    /** MyMemory's anonymous tier rejects queries longer than ~500 bytes. */
    private const MAX_CHUNK_LENGTH = 450;

    /**
     * Fill in any missing/stale translations for a just-saved Property.
     * Call this right after Property::create() or $property->update().
     */
    public static function syncTranslations(Property $property): void
    {
        $titleChanged = $property->wasRecentlyCreated || $property->wasChanged('title');
        $descriptionChanged = $property->wasRecentlyCreated || $property->wasChanged('description');

        foreach (self::TARGET_LOCALES as $locale) {
            $titleField = "title_{$locale}";
            $descriptionField = "description_{$locale}";

            if ($titleChanged || empty($property->{$titleField})) {
                if ($translated = self::translate($property->title, $locale)) {
                    $property->{$titleField} = $translated;
                }
            }

            if ($descriptionChanged || empty($property->{$descriptionField})) {
                $plainDescription = self::stripFormatting($property->description);
                if ($translated = self::translate($plainDescription, $locale)) {
                    $property->{$descriptionField} = $translated;
                }
            }
        }

        if ($property->isDirty()) {
            $property->save();
        }
    }

    /**
     * HTML -> plain text, turning block boundaries into spaces so content
     * from different paragraphs doesn't run together.
     */
    public static function stripFormatting(?string $html): string
    {
        $withBreaks = preg_replace('/<\/(p|div|li|h[1-6])>|<br\s*\/?>/i', ' ', (string) $html);
        return trim(preg_replace('/\s+/', ' ', strip_tags($withBreaks)));
    }

    public static function translate(?string $text, string $targetLocale): ?string
    {
        $text = trim((string) $text);
        if ($text === '') {
            return null;
        }

        $translatedChunks = [];
        foreach (self::splitIntoChunks($text) as $chunk) {
            $translated = self::translateChunk($chunk, $targetLocale);
            if ($translated === null) {
                // Bail rather than save a half-translated string.
                return null;
            }
            $translatedChunks[] = $translated;
        }

        return implode(' ', $translatedChunks);
    }

    private static function translateChunk(string $chunk, string $targetLocale): ?string
    {
        try {
            $response = Http::withoutVerifying()->timeout(10)->get('https://api.mymemory.translated.net/get', [
                'q' => $chunk,
                'langpair' => self::SOURCE_LOCALE . '|' . $targetLocale,
            ]);

            if (!$response->successful()) {
                Log::warning("PropertyTranslator: HTTP {$response->status()} translating to '{$targetLocale}'");
                return null;
            }

            $translated = $response->json('responseData.translatedText');

            if (!is_string($translated) || $translated === '') {
                return null;
            }

            // MyMemory signals rate-limit/quota issues inside a 200 OK body.
            if (str_contains($translated, 'QUERY LENGTH LIMIT') || str_contains($translated, 'MYMEMORY WARNING')) {
                Log::warning("PropertyTranslator: MyMemory limit for '{$targetLocale}': {$translated}");
                return null;
            }

            return html_entity_decode($translated, ENT_QUOTES);
        } catch (\Throwable $e) {
            Log::error("PropertyTranslator failed for '{$targetLocale}': " . $e->getMessage());
            return null;
        }
    }

    /**
     * Break text into pieces under MyMemory's per-request length limit.
     * Descriptions are HTML (Quill output): splitting on </p> boundaries
     * first means inline tags like <strong>/<span> never get separated
     * from their closing tag across a chunk. Falls back to sentence- and
     * then word-boundary splitting for a single paragraph that's still
     * too long on its own.
     */
    private static function splitIntoChunks(string $text): array
    {
        if (mb_strlen($text) <= self::MAX_CHUNK_LENGTH) {
            return [$text];
        }

        $paragraphs = preg_split('/(?<=<\/p>)/i', $text, -1, PREG_SPLIT_NO_EMPTY) ?: [$text];
        $chunks = [];
        $current = '';

        foreach ($paragraphs as $paragraph) {
            if ($current !== '' && mb_strlen($current) + mb_strlen($paragraph) > self::MAX_CHUNK_LENGTH) {
                $chunks[] = $current;
                $current = '';
            }

            if (mb_strlen($paragraph) > self::MAX_CHUNK_LENGTH) {
                foreach (self::splitBySentence($paragraph) as $piece) {
                    $chunks[] = $piece;
                }
                continue;
            }

            $current .= $paragraph;
        }

        if ($current !== '') {
            $chunks[] = $current;
        }

        return $chunks;
    }

    private static function splitBySentence(string $text): array
    {
        $sentences = preg_split('/(?<=[.!?])\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY) ?: [$text];
        $chunks = [];
        $current = '';

        foreach ($sentences as $sentence) {
            if ($current !== '' && mb_strlen($current) + mb_strlen($sentence) + 1 > self::MAX_CHUNK_LENGTH) {
                $chunks[] = trim($current);
                $current = '';
            }

            if (mb_strlen($sentence) > self::MAX_CHUNK_LENGTH) {
                foreach (self::wrapWords($sentence) as $piece) {
                    $chunks[] = $piece;
                }
                continue;
            }

            $current = $current === '' ? $sentence : $current . ' ' . $sentence;
        }

        if ($current !== '') {
            $chunks[] = trim($current);
        }

        return $chunks;
    }

    private static function wrapWords(string $text): array
    {
        $words = explode(' ', $text);
        $chunks = [];
        $current = '';

        foreach ($words as $word) {
            if ($current !== '' && mb_strlen($current) + mb_strlen($word) + 1 > self::MAX_CHUNK_LENGTH) {
                $chunks[] = $current;
                $current = '';
            }
            $current = $current === '' ? $word : $current . ' ' . $word;
        }

        if ($current !== '') {
            $chunks[] = $current;
        }

        return $chunks;
    }
}
