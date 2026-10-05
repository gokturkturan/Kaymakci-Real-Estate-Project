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
                if ($translated = self::translate($property->description, $locale)) {
                    $property->{$descriptionField} = $translated;
                }
            }
        }

        if ($property->isDirty()) {
            $property->save();
        }
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
     * Break text into pieces under MyMemory's per-request length limit,
     * preferring sentence boundaries so each chunk stays coherent.
     */
    private static function splitIntoChunks(string $text): array
    {
        if (mb_strlen($text) <= self::MAX_CHUNK_LENGTH) {
            return [$text];
        }

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
