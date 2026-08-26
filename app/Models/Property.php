<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
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

    public function getFirstImageAttribute(): ?string
    {
        return $this->images->first()?->url ?? $this->image;
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
