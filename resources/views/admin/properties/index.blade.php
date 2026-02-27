@extends('admin.layouts.app')

@section('title', 'Immobilien')
@section('header', 'Immobilien verwalten')

@section('content')
    <div class="bg-white rounded-xl shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Alle Immobilien ({{ $properties->total() }})</h2>
            <a href="{{ route('admin.properties.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Neue Immobilie
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bild</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titel</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Standort</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aktionen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($properties as $property)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                @if($property->first_image)
                                    <img src="{{ $property->first_image }}" alt="{{ $property->title }}" class="w-16 h-12 object-cover rounded">
                                @else
                                    <div class="w-16 h-12 bg-gray-200 rounded flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $property->title }}</div>
                                <div class="text-sm text-gray-500">{{ $property->images->count() }} Bilder</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $property->location }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-900">{{ number_format($property->price, 0, ',', '.') }} &euro;</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $property->bedrooms }} Zi. &middot; {{ $property->bathrooms }} Bad &middot; {{ $property->area }} m&sup2;
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('properties.show', $property) }}" target="_blank"
                                       class="text-gray-600 hover:text-blue-600 transition" title="Ansehen">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.properties.edit', $property) }}"
                                       class="text-gray-600 hover:text-blue-600 transition" title="Bearbeiten">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.properties.destroy', $property) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Sind Sie sicher, dass Sie diese Immobilie löschen möchten?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-600 hover:text-red-600 transition" title="Löschen">
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
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Noch keine Immobilien vorhanden.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($properties->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $properties->links() }}
            </div>
        @endif
    </div>
@endsection
