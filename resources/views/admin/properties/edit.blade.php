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
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Preis pro Person / Nacht (EUR) *</label>
                        <input type="number" id="price" name="price" required value="{{ old('price', $property->price) }}" min="0" step="0.01"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="z.B. 99,00">
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Standort *</label>
                        <input type="text" id="location" name="location" required value="{{ old('location', $property->location) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="z.B. Mainzer Landstraße 50, 60329 Frankfurt am Main">
                        <p class="text-xs text-gray-500 mt-1">Adres değişirse harita konumu otomatik güncellenir.</p>
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
                            <p class="text-sm text-gray-500 mb-3">Ziehen Sie die Bilder in die gewünschte Reihenfolge. Das erste Bild wird als Titelbild verwendet.</p>
                            <div id="existing-image-order"></div>
                            <div id="existing-images" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                                @foreach($property->images as $image)
                                    <div class="existing-image-card relative rounded-lg overflow-hidden shadow-sm border border-gray-200 bg-gray-50 p-2 cursor-move"
                                         data-image-id="{{ $image->id }}" draggable="true"
                                         ondragstart="startExistingImageDrag(event, this)" ondragend="endExistingImageDrag(this)" ondragover="event.preventDefault()" ondrop="dropExistingImage(event, this)">
                                        <div class="relative aspect-square">
                                            <img src="{{ $image->url }}" alt="Bild {{ $loop->iteration }}" class="w-full h-full object-cover rounded-md">
                                            <span class="image-position absolute top-2 left-2 bg-blue-600 text-white text-xs font-semibold px-2 py-1 rounded">{{ $loop->first ? 'Titelbild' : 'Bild ' . $loop->iteration }}</span>
                                        </div>
                                        <div class="flex gap-2 mt-2">
                                            <button type="button" onclick="moveExistingImage(this.closest('.existing-image-card'), -1)" class="flex-1 px-2 py-1 text-sm border border-gray-300 rounded hover:bg-white" title="Nach links verschieben">←</button>
                                            <button type="button" onclick="moveExistingImage(this.closest('.existing-image-card'), 1)" class="flex-1 px-2 py-1 text-sm border border-gray-300 rounded hover:bg-white" title="Nach rechts verschieben">→</button>
                                            <button type="button" onclick="deleteImage({{ $image->id }}, '{{ csrf_token() }}')" class="px-2 py-1 text-sm text-red-600 border border-red-200 rounded hover:bg-red-50" title="Bild löschen">Löschen</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
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
                        <div id="image-preview" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4"></div>
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
        let selectedImages = [];
        let draggedExistingImage = null;

        function syncExistingImageOrder() {
            const orderContainer = document.getElementById('existing-image-order');
            const imageCards = document.querySelectorAll('#existing-images .existing-image-card');
            if (!orderContainer) return;

            orderContainer.innerHTML = '';
            imageCards.forEach((card, index) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'image_order[]';
                input.value = card.dataset.imageId;
                orderContainer.appendChild(input);
                card.querySelector('.image-position').textContent = index === 0 ? 'Titelbild' : `Bild ${index + 1}`;
            });
        }

        function startExistingImageDrag(event, card) {
            draggedExistingImage = card;
            event.dataTransfer.effectAllowed = 'move';
            card.classList.add('opacity-50');
        }

        function dropExistingImage(event, targetCard) {
            event.preventDefault();
            if (draggedExistingImage && draggedExistingImage !== targetCard) {
                const cards = Array.from(targetCard.parentNode.children);
                const sourceIndex = cards.indexOf(draggedExistingImage);
                const targetIndex = cards.indexOf(targetCard);

                targetCard.parentNode.insertBefore(
                    draggedExistingImage,
                    sourceIndex < targetIndex ? targetCard.nextElementSibling : targetCard
                );
                syncExistingImageOrder();
            }
            if (draggedExistingImage) draggedExistingImage.classList.remove('opacity-50');
            draggedExistingImage = null;
        }

        function endExistingImageDrag(card) {
            card.classList.remove('opacity-50');
            draggedExistingImage = null;
        }

        function moveExistingImage(card, direction) {
            const sibling = direction < 0 ? card.previousElementSibling : card.nextElementSibling;
            if (!sibling) return;

            card.parentNode.insertBefore(card, direction < 0 ? sibling : sibling.nextElementSibling);
            syncExistingImageOrder();
        }

        function previewImages(input) {
            const existingImages = new Set(selectedImages.map(image => `${image.name}-${image.size}-${image.lastModified}`));
            Array.from(input.files).forEach((image) => {
                const imageKey = `${image.name}-${image.size}-${image.lastModified}`;
                if (!existingImages.has(imageKey)) {
                    selectedImages.push(image);
                    existingImages.add(imageKey);
                }
            });
            syncSelectedImageInput();
            renderImagePreviews();
        }

        function removeSelectedImage(index) {
            selectedImages.splice(index, 1);
            syncSelectedImageInput();
            renderImagePreviews();
        }

        function syncSelectedImageInput() {
            const dataTransfer = new DataTransfer();
            selectedImages.forEach((image) => dataTransfer.items.add(image));
            document.getElementById('images').files = dataTransfer.files;
        }

        function renderImagePreviews() {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = '';

            selectedImages.forEach((file, index) => {
                const card = document.createElement('div');
                card.className = 'relative rounded-lg border border-gray-200 bg-gray-50 p-2';

                const image = document.createElement('img');
                image.src = URL.createObjectURL(file);
                image.alt = `Neue Bildvorschau ${index + 1}`;
                image.className = 'w-full h-32 object-cover rounded-md';

                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'w-full mt-2 px-2 py-1 text-sm text-red-600 border border-red-200 rounded hover:bg-red-50';
                removeButton.textContent = 'Aus Auswahl entfernen';
                removeButton.addEventListener('click', () => removeSelectedImage(index));

                card.append(image, removeButton);
                preview.appendChild(card);
            });
        }

        document.addEventListener('DOMContentLoaded', syncExistingImageOrder);

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
