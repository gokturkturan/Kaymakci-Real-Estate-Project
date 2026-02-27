@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center gap-4">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_properties'] }}</p>
                    <p class="text-gray-600">Immobilien</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <a href="{{ route('admin.properties.create') }}" class="flex items-center gap-4 group">
                <div class="bg-green-100 p-3 rounded-lg group-hover:bg-green-200 transition">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-lg font-semibold text-gray-900 group-hover:text-green-600 transition">Neue Immobilie</p>
                    <p class="text-gray-600">Hinzufügen</p>
                </div>
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <a href="{{ route('properties.index') }}" target="_blank" class="flex items-center gap-4 group">
                <div class="bg-purple-100 p-3 rounded-lg group-hover:bg-purple-200 transition">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </div>
                <div>
                    <p class="text-lg font-semibold text-gray-900 group-hover:text-purple-600 transition">Website</p>
                    <p class="text-gray-600">Ansehen</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Properties -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Neueste Immobilien</h2>
            <a href="{{ route('admin.properties.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                Alle anzeigen
            </a>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($stats['recent_properties'] as $property)
                <div class="px-6 py-4 flex items-center gap-4">
                    @if($property->first_image)
                        <img src="{{ $property->first_image }}" alt="{{ $property->title }}" class="w-16 h-16 object-cover rounded-lg">
                    @else
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900">{{ $property->title }}</h3>
                        <p class="text-sm text-gray-600">{{ $property->location }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">{{ number_format($property->price, 0, ',', '.') }} &euro;</p>
                        <p class="text-sm text-gray-500">{{ $property->created_at->diffForHumans() }}</p>
                    </div>
                    <a href="{{ route('admin.properties.edit', $property) }}" class="text-blue-600 hover:text-blue-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    Noch keine Immobilien vorhanden.
                    <a href="{{ route('admin.properties.create') }}" class="text-blue-600 hover:underline">Erste Immobilie hinzufügen</a>
                </div>
            @endforelse
        </div>
    </div>
@endsection
