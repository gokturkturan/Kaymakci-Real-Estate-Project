@extends('admin.layouts.app')

@section('title', 'Immobilie bearbeiten')
@section('header', 'Immobilie bearbeiten')

@section('content')
    <div class="max-w-4xl">
        <div class="bg-white rounded-xl shadow-sm p-6">
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.properties.update', $property) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titel *</label>
                        <input type="text" id="title" name="title" required value="{{ old('title', $property->title) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="z.B. Villa mit Seeblick">
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Beschreibung *</label>
                        <textarea id="description" name="description" rows="5" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Ausführliche Beschreibung der Immobilie...">{{ old('description', $property->description) }}</textarea>
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Preis (EUR) *</label>
                        <input type="number" id="price" name="price" required value="{{ old('price', $property->price) }}" min="0" step="1000"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="z.B. 450000">
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Standort *</label>
                        <input type="text" id="location" name="location" required value="{{ old('location', $property->location) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="z.B. Frankfurt am Main">
                    </div>

                    <div>
                        <label for="bedrooms" class="block text-sm font-medium text-gray-700 mb-1">Zimmer *</label>
                        <input type="number" id="bedrooms" name="bedrooms" required value="{{ old('bedrooms', $property->bedrooms) }}" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="z.B. 4">
                    </div>

                    <div>
                        <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-1">Badezimmer *</label>
                        <input type="number" id="bathrooms" name="bathrooms" required value="{{ old('bathrooms', $property->bathrooms) }}" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="z.B. 2">
                    </div>

                    <div>
                        <label for="area" class="block text-sm font-medium text-gray-700 mb-1">Fläche (m²) *</label>
                        <input type="number" id="area" name="area" required value="{{ old('area', $property->area) }}" min="0" step="0.5"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="z.B. 150">
                    </div>

                    <!-- Existing Images -->
                    @if($property->images->count() > 0)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vorhandene Bilder ({{ $property->images->count() }})</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                                @foreach($property->images as $image)
                                    <div class="image-card relative rounded-lg overflow-hidden shadow-sm border border-gray-200 aspect-square cursor-pointer">
                                        <img src="{{ $image->url }}" alt="Bild {{ $loop->iteration }}" class="w-full h-full object-cover">
                                        <div class="image-overlay absolute inset-0 flex items-center justify-center bg-black/50">
                                            <button type="button"
                                                    onclick="deleteImage({{ $image->id }}, '{{ csrf_token() }}')"
                                                    class="bg-red-600 text-white p-3 rounded-full hover:bg-red-700 hover:scale-110 transition-all duration-200 shadow-xl">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <span class="absolute bottom-2 left-2 bg-black/60 text-white text-xs font-medium px-2 py-1 rounded z-10">
                                            {{ $loop->iteration }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <style>
                            .image-card .image-overlay {
                                opacity: 0;
                                transition: opacity 0.2s ease;
                            }
                            .image-card:hover .image-overlay {
                                opacity: 1;
                            }
                        </style>
                    @endif

                    <!-- Add New Images -->
                    <div class="md:col-span-2">
                        <label for="images" class="block text-sm font-medium text-gray-700 mb-1">Neue Bilder hinzufügen</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition cursor-pointer"
                             onclick="document.getElementById('images').click()">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <p class="text-gray-600">Klicken Sie hier oder ziehen Sie Bilder hierher</p>
                            <p class="text-sm text-gray-400 mt-1">JPEG, PNG, WebP (max. 5MB pro Bild)</p>
                            <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden"
                                   onchange="previewImages(this)">
                        </div>
                        <div id="image-preview" class="grid grid-cols-4 gap-4 mt-4"></div>
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-4 border-t">
                    <a href="{{ route('admin.properties.index') }}"
                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Abbrechen
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Änderungen speichern
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImages(input) {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = '';

            if (input.files) {
                Array.from(input.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg">
                            <span class="absolute bottom-1 right-1 bg-green-500 text-white text-xs px-2 py-1 rounded">Neu</span>
                        `;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }
        }

        function deleteImage(imageId, token) {
            if (!confirm('Bild wirklich löschen?')) {
                return;
            }

            fetch('/admin/images/' + imageId, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('Fehler beim Löschen des Bildes.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Fehler beim Löschen des Bildes.');
            });
        }
    </script>
@endsection
