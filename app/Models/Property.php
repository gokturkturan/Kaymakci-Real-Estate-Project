<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_en',
        'description',
        'description_en',
        'price',
        'location',
        'bedrooms',
        'bathrooms',
        'area',
        'image',
        'latitude',
        'longitude',
        'king_size_bed_count',
        'single_bed_count',
        'has_parking',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'has_parking' => 'boolean',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class)->orderBy('order');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(PropertyVideo::class)->orderBy('order');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Property $property) {
            if (empty($property->slug)) {
                $property->slug = static::generateUniqueSlug($property->title, $property->location);
            }
        });
    }

    public static function generateUniqueSlug(string $title, string $location, ?int $excludeId = null): string
    {
        $base = Str::slug($title . ' ' . $location);
        $slug = $base;
        $suffix = 2;

        while (
            static::where('slug', $slug)
                ->when($excludeId, fn ($query) => $query->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = $base . '-' . $suffix++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Resolve by slug; fall back to legacy numeric IDs (old /immobilie/{id} links)
     * with a 301 redirect to the current slug URL so indexed links keep working.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $field ??= $this->getRouteKeyName();

        $property = $this->where($field, $value)->first();

        if ($property) {
            return $property;
        }

        if (ctype_digit((string) $value) && ($legacy = $this->find($value))) {
            $route = request()->route();
            $parameters = array_merge($route->parameters(), [$route->parameterNames()[0] => $legacy->{$field}]);

            throw new HttpResponseException(redirect()->route($route->getName(), $parameters, 301));
        }

        return null;
    }

    public function getFirstImageAttribute(): ?string
    {
        return $this->images->first()?->url ?? $this->image;
    }

    protected function localizedTitle(): Attribute
    {
        return Attribute::get(
            fn () => app()->getLocale() === 'en' && filled($this->title_en) ? $this->title_en : $this->title
        );
    }

    protected function localizedDescription(): Attribute
    {
        return Attribute::get(
            fn () => app()->getLocale() === 'en' && filled($this->description_en) ? $this->description_en : $this->description
        );
    }

    public function getMediaAttribute()
    {
        return $this->images
            ->map(fn ($image) => ['type' => 'image', 'url' => $image->url, 'order' => $image->order])
            ->concat($this->videos->map(fn ($video) => ['type' => 'video', 'url' => $video->url, 'order' => $video->order]))
            ->sortBy('order')
            ->values();
    }
}
