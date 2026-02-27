@extends('admin.layouts.app')

@section('title', 'Buchungen verwalten')
@section('header', 'Buchungen')

@section('content')
    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <p class="text-2xl font-bold text-yellow-700">{{ $stats['pending'] }}</p>
            <p class="text-sm text-yellow-600">Ausstehend</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <p class="text-2xl font-bold text-green-700">{{ $stats['approved'] }}</p>
            <p class="text-sm text-green-600">Bestätigt</p>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <p class="text-2xl font-bold text-red-700">{{ $stats['rejected'] }}</p>
            <p class="text-sm text-red-600">Abgelehnt</p>
        </div>
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
            <p class="text-2xl font-bold text-gray-700">{{ $stats['total'] }}</p>
            <p class="text-sm text-gray-600">Gesamt</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Alle</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Ausstehend</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Bestätigt</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Abgelehnt</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Storniert</option>
                </select>
            </div>
            <div>
                <label for="from_date" class="block text-sm font-medium text-gray-700 mb-1">Check-in ab</label>
                <input type="date" name="from_date" id="from_date" value="{{ request('from_date') }}"
                       class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="to_date" class="block text-sm font-medium text-gray-700 mb-1">Check-out bis</label>
                <input type="date" name="to_date" id="to_date" value="{{ request('to_date') }}"
                       class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition">
                Filtern
            </button>
            @if(request()->hasAny(['status', 'from_date', 'to_date']))
                <a href="{{ route('admin.bookings.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition">
                    Filter zurücksetzen
                </a>
            @endif
        </form>
    </div>

    {{-- Bookings List --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if($bookings->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Zeitraum</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Immobilie</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kunde</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gäste</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($bookings as $booking)
                            <tr class="hover:bg-gray-50 {{ $booking->status === 'pending' ? 'bg-yellow-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="font-medium text-gray-900">{{ $booking->check_in->format('d.m.Y') }} - {{ $booking->check_out->format('d.m.Y') }}</p>
                                    <p class="text-sm text-gray-500">{{ $booking->nights }} Nächte</p>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.properties.edit', $booking->property) }}"
                                       class="text-blue-600 hover:text-blue-800 font-medium">
                                        {{ Str::limit($booking->property->title, 30) }}
                                    </a>
                                    <p class="text-sm text-gray-500">{{ $booking->property->location }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-900">{{ $booking->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $booking->email }}</p>
                                    @if($booking->phone)
                                        <p class="text-sm text-gray-500">{{ $booking->phone }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-gray-900">{{ $booking->guests }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'approved' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'cancelled' => 'bg-gray-100 text-gray-800',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$booking->status] }}">
                                        {{ $booking->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($booking->status === 'pending')
                                            <form action="{{ route('admin.bookings.approve', $booking) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition"
                                                        title="Bestätigen">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </button>
                                            </form>
                                            <button type="button"
                                                    onclick="showRejectModal({{ $booking->id }})"
                                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                                    title="Ablehnen">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        @endif
                                        <a href="{{ route('admin.bookings.show', $booking) }}"
                                           class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg transition"
                                           title="Details">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Buchung wirklich löschen?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                                    title="Löschen">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t">
                {{ $bookings->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-gray-500">Keine Buchungen gefunden.</p>
            </div>
        @endif
    </div>

    {{-- Reject Modal --}}
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Buchung ablehnen</h3>
            <form id="rejectForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-1">
                        Grund der Ablehnung (optional)
                    </label>
                    <textarea name="admin_notes" id="admin_notes" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                              placeholder="z.B. Die Immobilie ist in diesem Zeitraum nicht verfügbar..."></textarea>
                    <p class="text-xs text-gray-500 mt-1">Diese Nachricht wird dem Kunden per E-Mail mitgeteilt.</p>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="hideRejectModal()"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Abbrechen
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Ablehnen
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showRejectModal(bookingId) {
            const modal = document.getElementById('rejectModal');
            const form = document.getElementById('rejectForm');
            form.action = `/admin/bookings/${bookingId}/reject`;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function hideRejectModal() {
            const modal = document.getElementById('rejectModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideRejectModal();
            }
        });
    </script>
@endsection
