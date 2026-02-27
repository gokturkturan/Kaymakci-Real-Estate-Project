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
    ];

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class)->orderBy('order');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getFirstImageAttribute(): ?string
    {
        return $this->images->first()?->url ?? $this->image;
    }
}
