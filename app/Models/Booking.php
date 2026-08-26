<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Booking extends Model
{
    protected $fillable = [
        'property_id',
        'check_in',
        'check_out',
        'name',
        'email',
        'phone',
        'guests',
        'price_per_person_per_night',
        'total_price',
        'message',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'price_per_person_per_night' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Ausstehend',
            'approved' => 'Bestätigt',
            'rejected' => 'Abgelehnt',
            'cancelled' => 'Storniert',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }

    public function getNightsAttribute(): int
    {
        return $this->check_in->diffInDays($this->check_out);
    }

    public function getDisplayPricePerPersonPerNightAttribute(): float
    {
        return (float) ($this->price_per_person_per_night ?? $this->property?->price ?? 0);
    }

    public function getDisplayTotalPriceAttribute(): float
    {
        return (float) ($this->total_price ?? ($this->nights * $this->guests * $this->display_price_per_person_per_night));
    }

    /**
     * Get all booked date ranges for a property (only bookings confirmed by an admin)
     */
    public static function getBookedDates(int $propertyId): array
    {
        $bookings = self::where('property_id', $propertyId)
            ->where('status', 'approved')
            ->where('check_out', '>=', now()->toDateString())
            ->get(['check_in', 'check_out']);

        $bookedDates = [];
        foreach ($bookings as $booking) {
            $current = $booking->check_in->copy();
            while ($current < $booking->check_out) {
                $bookedDates[] = $current->format('Y-m-d');
                $current->addDay();
            }
        }

        return array_unique($bookedDates);
    }

    /**
     * Check if dates overlap with existing bookings
     */
    public static function hasOverlap(int $propertyId, string $checkIn, string $checkOut, ?int $excludeId = null): bool
    {
        $query = self::where('property_id', $propertyId)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($q) use ($checkIn, $checkOut) {
                $q->where(function ($q2) use ($checkIn, $checkOut) {
                    // New booking starts during existing booking
                    $q2->where('check_in', '<=', $checkIn)
                       ->where('check_out', '>', $checkIn);
                })->orWhere(function ($q2) use ($checkIn, $checkOut) {
                    // New booking ends during existing booking
                    $q2->where('check_in', '<', $checkOut)
                       ->where('check_out', '>=', $checkOut);
                })->orWhere(function ($q2) use ($checkIn, $checkOut) {
                    // New booking contains existing booking
                    $q2->where('check_in', '>=', $checkIn)
                       ->where('check_out', '<=', $checkOut);
                });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
