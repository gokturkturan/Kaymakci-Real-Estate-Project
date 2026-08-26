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

                    <div>
                        <label for="king_size_bed_count" class="block text-sm font-medium text-gray-700 mb-1">King Size Betten</label>
                        <input type="number" id="king_size_bed_count" name="king_size_bed_count" required value="{{ old('king_size_bed_count', 0) }}" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="z.B. 1">
                    </div>

                    <div>
                        <label for="single_bed_count" class="block text-sm font-medium text-gray-700 mb-1">Einzelbetten</label>
                        <input type="number" id="single_bed_count" name="single_bed_count" required value="{{ old('single_bed_count', 0) }}" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="z.B. 2">
                    </div>

                    <div class="flex items-center">
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <input type="checkbox" id="has_parking" name="has_parking" value="1" {{ old('has_parking') ? 'checked' : '' }}
                                   class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-700">Parkplatz vorhanden</span>
                        </label>
                    </div>

                    <div class="md:col-span-2">
                        <label for="media-input" class="block text-sm font-medium text-gray-700 mb-1">Bilder &amp; Videos</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition cursor-pointer"
                             onclick="document.getElementById('media-input').click()">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-gray-600">Klicken Sie hier oder ziehen Sie Bilder/Videos hierher</p>
                            <p class="text-sm text-gray-400 mt-1">JPEG, PNG, WebP (max. 5MB) · MP4, MOV, WebM (max. 100MB)</p>
                            <input type="file" id="media-input" multiple accept="image/*,video/mp4,video/quicktime,video/webm" class="hidden"
                                   onchange="handleMediaInput(this)">
                            <input type="file" id="images" name="images[]" multiple class="hidden">
                            <input type="file" id="videos" name="videos[]" multiple class="hidden">
                            <div id="media-type-order"></div>
                        </div>
                        <p class="text-sm text-gray-500 mt-3">Ziehen Sie die Medien in die gewünschte Reihenfolge. Das erste Bild wird als Titelbild verwendet.</p>
                        <div id="media-preview" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4"></div>
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
        let selectedMedia = []; // { type: 'image'|'video', file: File }
        let draggedMediaIndex = null;

        function handleMediaInput(input) {
            const existingKeys = new Set(selectedMedia.map(item => `${item.file.name}-${item.file.size}-${item.file.lastModified}`));
            Array.from(input.files).forEach((file) => {
                const key = `${file.name}-${file.size}-${file.lastModified}`;
                if (!existingKeys.has(key)) {
                    selectedMedia.push({ type: file.type.startsWith('video/') ? 'video' : 'image', file });
                    existingKeys.add(key);
                }
            });
            input.value = '';
            syncMediaInputs();
            renderMediaPreviews();
        }

        function moveMedia(fromIndex, toIndex) {
            if (toIndex < 0 || toIndex >= selectedMedia.length || fromIndex === toIndex) {
                return;
            }

            const [item] = selectedMedia.splice(fromIndex, 1);
            selectedMedia.splice(toIndex, 0, item);
            syncMediaInputs();
            renderMediaPreviews();
        }

        function removeSelectedMedia(index) {
            selectedMedia.splice(index, 1);
            syncMediaInputs();
            renderMediaPreviews();
        }

        function syncMediaInputs() {
            const imageTransfer = new DataTransfer();
            const videoTransfer = new DataTransfer();

            selectedMedia.forEach((item) => {
                if (item.type === 'image') {
                    imageTransfer.items.add(item.file);
                } else {
                    videoTransfer.items.add(item.file);
                }
            });

            document.getElementById('images').files = imageTransfer.files;
            document.getElementById('videos').files = videoTransfer.files;

            const orderContainer = document.getElementById('media-type-order');
            orderContainer.innerHTML = '';
            selectedMedia.forEach((item) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'media_type_order[]';
                input.value = item.type;
                orderContainer.appendChild(input);
            });
        }

        function renderMediaPreviews() {
            const preview = document.getElementById('media-preview');
            preview.innerHTML = '';
            const firstImageIndex = selectedMedia.findIndex(item => item.type === 'image');

            selectedMedia.forEach((item, index) => {
                const card = document.createElement('div');
                card.className = 'relative rounded-lg border border-gray-200 bg-gray-50 p-2 cursor-move';
                card.draggable = true;

                card.addEventListener('dragstart', () => {
                    draggedMediaIndex = index;
                    card.classList.add('opacity-50');
                });
                card.addEventListener('dragend', () => {
                    draggedMediaIndex = null;
                    card.classList.remove('opacity-50');
                });
                card.addEventListener('dragover', (event) => event.preventDefault());
                card.addEventListener('drop', (event) => {
                    event.preventDefault();
                    if (draggedMediaIndex !== null) {
                        moveMedia(draggedMediaIndex, index);
                    }
                });

                let mediaEl;
                if (item.type === 'image') {
                    mediaEl = document.createElement('img');
                    mediaEl.src = URL.createObjectURL(item.file);
                    mediaEl.alt = `Vorschau ${index + 1}`;
                    mediaEl.className = 'w-full h-32 object-cover rounded-md';
                } else {
                    mediaEl = document.createElement('video');
                    mediaEl.src = URL.createObjectURL(item.file);
                    mediaEl.className = 'w-full h-32 object-cover rounded-md';
                    mediaEl.muted = true;
                    mediaEl.controls = true;
                }

                const badge = document.createElement('span');
                badge.className = 'absolute top-3 left-3 bg-blue-600 text-white text-xs font-semibold px-2 py-1 rounded';
                badge.textContent = item.type === 'video' ? 'Video' : (index === firstImageIndex ? 'Titelbild' : 'Bild');

                const controls = document.createElement('div');
                controls.className = 'flex gap-2 mt-2';

                const moveLeft = document.createElement('button');
                moveLeft.type = 'button';
                moveLeft.className = 'flex-1 px-2 py-1 text-sm border border-gray-300 rounded hover:bg-white disabled:opacity-40';
                moveLeft.textContent = '←';
                moveLeft.title = 'Nach links verschieben';
                moveLeft.disabled = index === 0;
                moveLeft.addEventListener('click', () => moveMedia(index, index - 1));

                const moveRight = document.createElement('button');
                moveRight.type = 'button';
                moveRight.className = 'flex-1 px-2 py-1 text-sm border border-gray-300 rounded hover:bg-white disabled:opacity-40';
                moveRight.textContent = '→';
                moveRight.title = 'Nach rechts verschieben';
                moveRight.disabled = index === selectedMedia.length - 1;
                moveRight.addEventListener('click', () => moveMedia(index, index + 1));

                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'px-2 py-1 text-sm text-red-600 border border-red-200 rounded hover:bg-red-50';
                removeButton.textContent = 'Löschen';
                removeButton.title = 'Aus Auswahl entfernen';
                removeButton.addEventListener('click', () => removeSelectedMedia(index));

                controls.append(moveLeft, moveRight, removeButton);
                card.append(mediaEl, badge, controls);
                preview.appendChild(card);
            });
        }
    </script>
@endsection
