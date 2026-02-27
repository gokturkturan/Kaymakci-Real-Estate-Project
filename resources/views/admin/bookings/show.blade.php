@extends('admin.layouts.app')

@section('title', 'Buchungsdetails')
@section('header', 'Buchungsdetails')

@section('content')
    <div class="max-w-3xl">
        <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-6">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Zurück zur Übersicht
        </a>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            {{-- Status Header --}}
            @php
                $statusColors = [
                    'pending' => 'bg-yellow-500',
                    'approved' => 'bg-green-500',
                    'rejected' => 'bg-red-500',
                    'cancelled' => 'bg-gray-500',
                ];
            @endphp
            <div class="{{ $statusColors[$booking->status] }} text-white px-6 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm opacity-90">Status</p>
                        <p class="text-xl font-bold">{{ $booking->status_label }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm opacity-90">Erstellt am</p>
                        <p class="font-medium">{{ $booking->created_at->format('d.m.Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-6">
                {{-- Booking Details --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-sm text-blue-600 font-medium mb-1">Zeitraum</p>
                        <p class="text-xl font-bold text-blue-900">{{ $booking->check_in->format('d.m.Y') }} - {{ $booking->check_out->format('d.m.Y') }}</p>
                        <p class="text-lg text-blue-700">{{ $booking->nights }} Nächte</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 font-medium mb-1">Immobilie</p>
                        <a href="{{ route('admin.properties.edit', $booking->property) }}"
                           class="text-lg font-bold text-gray-900 hover:text-blue-600 transition">
                            {{ $booking->property->title }}
                        </a>
                        <p class="text-gray-600">{{ $booking->property->location }}</p>
                    </div>
                </div>

                {{-- Guests Info --}}
                <div class="bg-purple-50 rounded-lg p-4">
                    <p class="text-sm text-purple-600 font-medium mb-1">Anzahl Gäste</p>
                    <p class="text-2xl font-bold text-purple-900">{{ $booking->guests }}</p>
                </div>

                {{-- Customer Details --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Kundendaten</h3>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="font-medium text-gray-900">{{ $booking->name }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <a href="mailto:{{ $booking->email }}" class="text-blue-600 hover:text-blue-800">
                                {{ $booking->email }}
                            </a>
                        </div>
                        @if($booking->phone)
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <a href="tel:{{ $booking->phone }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $booking->phone }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Message --}}
                @if($booking->message)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Nachricht des Kunden</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-700 whitespace-pre-line">{{ $booking->message }}</p>
                        </div>
                    </div>
                @endif

                {{-- Admin Notes --}}
                @if($booking->admin_notes)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Admin-Notizen</h3>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-gray-700 whitespace-pre-line">{{ $booking->admin_notes }}</p>
                        </div>
                    </div>
                @endif

                {{-- Actions --}}
                @if($booking->status === 'pending')
                    <div class="border-t pt-6 flex flex-wrap gap-3">
                        <form action="{{ route('admin.bookings.approve', $booking) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Buchung bestätigen
                            </button>
                        </form>

                        <form action="{{ route('admin.bookings.reject', $booking) }}" method="POST"
                              onsubmit="return confirm('Buchung wirklich ablehnen?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Buchung ablehnen
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
