@extends('admin.layouts.app')

@section('title', 'Neue Immobilie')
@section('header', 'Neue Immobilie hinzufügen')

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

            <form action="{{ route('admin.properties.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titel *</label>
                        <input type="text" id="title" name="title" required value="{{ old('title') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="z.B. Villa mit Seeblick">
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Beschreibung *</label>
                        <textarea id="description" name="description" rows="5" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Ausführliche Beschreibung der Immobilie...">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Preis pro Person / Nacht (EUR) *</label>
                        <input type="number" id="price" name="price" required value="{{ old('price') }}" min="0" step="0.01"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="z.B. 99,00">
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Standort *</label>
                        <input type="text" id="location" name="location" required value="{{ old('location') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="z.B. Mainzer Landstraße 50, 60329 Frankfurt am Main">
                        <p class="text-xs text-gray-500 mt-1">Die vollständige Adresse wird automatisch für die Karte verwendet.</p>
                    </div>

                    <div>
                        <label for="bedrooms" class="block text-sm font-medium text-gray-700 mb-1">Zimmer *</label>
                        <input type="number" id="bedrooms" name="bedrooms" required value="{{ old('bedrooms') }}" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="z.B. 4">
                    </div>

                    <div>
                        <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-1">Badezimmer *</label>
                        <input type="number" id="bathrooms" name="bathrooms" required value="{{ old('bathrooms') }}" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="z.B. 2">
                    </div>

                    <div>
                        <label for="area" class="block text-sm font-medium text-gray-700 mb-1">Fläche (m²) *</label>
                        <input type="number" id="area" name="area" required value="{{ old('area') }}" min="0" step="0.5"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="z.B. 150">
                    </div>

                    <div class="md:col-span-2">
                        <label for="images" class="block text-sm font-medium text-gray-700 mb-1">Bilder</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition cursor-pointer"
                             onclick="document.getElementById('images').click()">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-gray-600">Klicken Sie hier oder ziehen Sie Bilder hierher</p>
                            <p class="text-sm text-gray-400 mt-1">JPEG, PNG, WebP (max. 5MB pro Bild)</p>
                            <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden"
                                   onchange="previewImages(this)">
                        </div>
                        <p class="text-sm text-gray-500 mt-3">Ziehen Sie die Bilder in die gewünschte Reihenfolge. Das erste Bild wird als Titelbild verwendet.</p>
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
                        Immobilie erstellen
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let selectedImages = [];
        let draggedImageIndex = null;

        function previewImages(input) {
            const existingImages = new Set(selectedImages.map(image => `${image.name}-${image.size}-${image.lastModified}`));
            Array.from(input.files).forEach((image) => {
                const imageKey = `${image.name}-${image.size}-${image.lastModified}`;
                if (!existingImages.has(imageKey)) {
                    selectedImages.push(image);
                    existingImages.add(imageKey);
                }
            });
            syncImageInput();
            renderImagePreviews();
        }

        function moveImage(fromIndex, toIndex) {
            if (toIndex < 0 || toIndex >= selectedImages.length || fromIndex === toIndex) {
                return;
            }

            const [image] = selectedImages.splice(fromIndex, 1);
            selectedImages.splice(toIndex, 0, image);
            syncImageInput();
            renderImagePreviews();
        }

        function syncImageInput() {
            const dataTransfer = new DataTransfer();
            selectedImages.forEach((image) => dataTransfer.items.add(image));
            document.getElementById('images').files = dataTransfer.files;
        }

        function removeSelectedImage(index) {
            selectedImages.splice(index, 1);
            syncImageInput();
            renderImagePreviews();
        }

        function renderImagePreviews() {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = '';

            selectedImages.forEach((file, index) => {
                const card = document.createElement('div');
                card.className = 'relative rounded-lg border border-gray-200 bg-gray-50 p-2 cursor-move';
                card.draggable = true;

                card.addEventListener('dragstart', () => {
                    draggedImageIndex = index;
                    card.classList.add('opacity-50');
                });
                card.addEventListener('dragend', () => {
                    draggedImageIndex = null;
                    card.classList.remove('opacity-50');
                });
                card.addEventListener('dragover', (event) => event.preventDefault());
                card.addEventListener('drop', (event) => {
                    event.preventDefault();
                    if (draggedImageIndex !== null) {
                        moveImage(draggedImageIndex, index);
                    }
                });

                const image = document.createElement('img');
                image.src = URL.createObjectURL(file);
                image.alt = `Vorschau ${index + 1}`;
                image.className = 'w-full h-32 object-cover rounded-md';

                const badge = document.createElement('span');
                badge.className = 'absolute top-3 left-3 bg-blue-600 text-white text-xs font-semibold px-2 py-1 rounded';
                badge.textContent = index === 0 ? 'Titelbild' : `Bild ${index + 1}`;

                const controls = document.createElement('div');
                controls.className = 'flex gap-2 mt-2';

                const moveLeft = document.createElement('button');
                moveLeft.type = 'button';
                moveLeft.className = 'flex-1 px-2 py-1 text-sm border border-gray-300 rounded hover:bg-white disabled:opacity-40';
                moveLeft.textContent = '←';
                moveLeft.title = 'Nach links verschieben';
                moveLeft.disabled = index === 0;
                moveLeft.addEventListener('click', () => moveImage(index, index - 1));

                const moveRight = document.createElement('button');
                moveRight.type = 'button';
                moveRight.className = 'flex-1 px-2 py-1 text-sm border border-gray-300 rounded hover:bg-white disabled:opacity-40';
                moveRight.textContent = '→';
                moveRight.title = 'Nach rechts verschieben';
                moveRight.disabled = index === selectedImages.length - 1;
                moveRight.addEventListener('click', () => moveImage(index, index + 1));

                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'px-2 py-1 text-sm text-red-600 border border-red-200 rounded hover:bg-red-50';
                removeButton.textContent = 'Löschen';
                removeButton.title = 'Aus Auswahl entfernen';
                removeButton.addEventListener('click', () => removeSelectedImage(index));

                controls.append(moveLeft, moveRight, removeButton);
                card.append(image, badge, controls);
                preview.appendChild(card);
            });
        }
    </script>
@endsection
