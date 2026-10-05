@extends('admin.layouts.app')

@section('title', 'Immobilien')
@section('header', 'Immobilien verwalten')

@section('content')
    <div class="bg-ivory-50 rounded-2xl shadow-soft border border-ivory-200">
        <div class="px-4 sm:px-6 py-4 border-b border-ivory-200 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <h2 class="font-serif text-lg font-semibold text-ivory-900">Alle Immobilien ({{ $properties->total() }})</h2>
            <a href="{{ route('admin.properties.create') }}"
               class="bg-gold-600 text-ivory-50 px-4 py-2 rounded-lg hover:bg-gold-700 transition-colors flex items-center justify-center gap-2 font-medium shadow-soft">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Neue Immobilie
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-ivory-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-ivory-500 uppercase tracking-wider">Bild</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-ivory-500 uppercase tracking-wider">Titel</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-ivory-500 uppercase tracking-wider">Standort</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-ivory-500 uppercase tracking-wider">Preis pro Person / Nacht</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-ivory-500 uppercase tracking-wider">Details</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-ivory-500 uppercase tracking-wider">Aktionen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ivory-200">
                    @forelse($properties as $property)
                        <tr class="hover:bg-ivory-50/80 transition-colors">
                            <td class="px-6 py-4">
                                @if($property->first_image)
                                    <img src="{{ $property->first_image }}" alt="{{ $property->title }}" class="w-16 h-12 object-cover rounded-lg">
                                @else
                                    <div class="w-16 h-12 bg-ivory-200 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-ivory-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-ivory-900">{{ $property->title }}</div>
                                <div class="text-sm text-ivory-500">{{ $property->images->count() }} Bilder</div>
                            </td>
                            <td class="px-6 py-4 text-ivory-600">{{ $property->location }}</td>
                            <td class="px-6 py-4 font-semibold text-ivory-900">{{ number_format($property->price, 2, ',', '.') }} &euro; <span class="text-sm font-normal text-ivory-500">/ Person/Nacht</span></td>
                            <td class="px-6 py-4 text-sm text-ivory-600">
                                {{ $property->bedrooms }} Zi. &middot; {{ $property->bathrooms }} Bad &middot; {{ $property->area }} m&sup2;
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('properties.show', $property) }}" target="_blank"
                                       class="text-ivory-500 hover:text-gold-700 transition-colors" title="Ansehen">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.properties.edit', $property) }}"
                                       class="text-ivory-500 hover:text-gold-700 transition-colors" title="Bearbeiten">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.properties.destroy', $property) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Sind Sie sicher, dass Sie diese Immobilie löschen möchten?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-ivory-500 hover:text-red-600 transition-colors" title="Löschen">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-ivory-500">
                                Noch keine Immobilien vorhanden.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($properties->hasPages())
            <div class="px-6 py-4 border-t border-ivory-200">
                {{ $properties->links() }}
            </div>
        @endif
    </div>
@endsection
