<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function getBookedDates(Property $property)
    {
        $bookedDates = Booking::getBookedDates($property->id);

        return response()->json([
            'booked_dates' => $bookedDates,
        ]);
    }

    public function store(Request $request, Property $property)
    {
        $validated = $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'guests' => 'required|integer|min:1|max:20',
            'message' => 'nullable|string|max:1000',
        ]);

        // Check for overlapping bookings
        if (Booking::hasOverlap($property->id, $validated['check_in'], $validated['check_out'])) {
            return back()->with('error', 'Die gewählten Daten sind leider nicht mehr verfügbar. Bitte wählen Sie andere Daten.');
        }

        $booking = Booking::create([
            'property_id' => $property->id,
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'guests' => $validated['guests'],
            'message' => $validated['message'] ?? null,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Vielen Dank! Ihre Buchungsanfrage wurde erfolgreich gesendet. Wir werden uns in Kürze bei Ihnen melden.');
    }
}
