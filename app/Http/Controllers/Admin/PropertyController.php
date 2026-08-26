<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\PropertyVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::with('images')->latest()->paginate(10);
        return view('admin.properties.index', compact('properties'));
    }

    public function create()
    {
        return view('admin.properties.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'area' => 'required|numeric|min:0',
            'king_size_bed_count' => 'required|integer|min:0',
            'single_bed_count' => 'required|integer|min:0',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            'videos.*' => 'file|mimes:mp4,mov,webm|max:102400',
            'media_type_order' => 'nullable|array',
            'media_type_order.*' => 'in:image,video',
        ]);

        $validated['has_parking'] = $request->boolean('has_parking');

        $coordinates = $this->geocodeAddress($validated['location']);
        if ($coordinates) {
            $validated = array_merge($validated, $coordinates);
        }

        $property = Property::create($validated);

        $this->storeNewMedia($request, $property, 0);

        return redirect()->route('admin.properties.index')
            ->with('success', 'Immobilie wurde erfolgreich erstellt.');
    }

    public function edit(Property $property)
    {
        $property->load('images', 'videos');
        return view('admin.properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'area' => 'required|numeric|min:0',
            'king_size_bed_count' => 'required|integer|min:0',
            'single_bed_count' => 'required|integer|min:0',
            'media_order' => 'nullable|array',
            'media_order.*' => 'string',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            'videos.*' => 'file|mimes:mp4,mov,webm|max:102400',
            'media_type_order' => 'nullable|array',
            'media_type_order.*' => 'in:image,video',
        ]);

        $validated['has_parking'] = $request->boolean('has_parking');

        if ($property->location !== $validated['location'] || $property->latitude === null || $property->longitude === null) {
            $coordinates = $this->geocodeAddress($validated['location']);
            $validated = array_merge($validated, $coordinates ?? ['latitude' => null, 'longitude' => null]);
        }

        $property->update($validated);

        $nextOrder = 0;

        if (!empty($validated['media_order'])) {
            $existingImages = $property->images()->get()->keyBy('id');
            $existingVideos = $property->videos()->get()->keyBy('id');

            foreach (collect($validated['media_order'])->unique() as $token) {
                [$type, $id] = array_pad(explode('-', $token, 2), 2, null);
                $id = (int) $id;

                if ($type === 'image' && $existingImages->has($id)) {
                    $existingImages[$id]->update(['order' => $nextOrder++]);
                    $existingImages->forget($id);
                } elseif ($type === 'video' && $existingVideos->has($id)) {
                    $existingVideos[$id]->update(['order' => $nextOrder++]);
                    $existingVideos->forget($id);
                }
            }

            foreach ($existingImages as $image) {
                $image->update(['order' => $nextOrder++]);
            }

            foreach ($existingVideos as $video) {
                $video->update(['order' => $nextOrder++]);
            }
        } else {
            $nextOrder = max($property->images()->max('order') ?? -1, $property->videos()->max('order') ?? -1) + 1;
        }

        $this->storeNewMedia($request, $property, $nextOrder);

        return redirect()->route('admin.properties.index')
            ->with('success', 'Immobilie wurde erfolgreich aktualisiert.');
    }

    private function storeNewMedia(Request $request, Property $property, int $order): void
    {
        $images = $request->file('images', []);
        $videos = $request->file('videos', []);
        $typeOrder = $request->input('media_type_order', []);

        if (empty($typeOrder)) {
            $typeOrder = array_merge(array_fill(0, count($images), 'image'), array_fill(0, count($videos), 'video'));
        }

        $imageIndex = 0;
        $videoIndex = 0;

        foreach ($typeOrder as $type) {
            if ($type === 'image' && isset($images[$imageIndex])) {
                $path = $images[$imageIndex]->store('properties', 'public');
                PropertyImage::create([
                    'property_id' => $property->id,
                    'url' => '/storage/' . $path,
                    'order' => $order++,
                ]);
                $imageIndex++;
            } elseif ($type === 'video' && isset($videos[$videoIndex])) {
                $path = $videos[$videoIndex]->store('property-videos', 'public');
                PropertyVideo::create([
                    'property_id' => $property->id,
                    'url' => '/storage/' . $path,
                    'order' => $order++,
                ]);
                $videoIndex++;
            }
        }
    }

    public function destroy(Property $property)
    {
        // Delete associated images from storage
        foreach ($property->images as $image) {
            $path = str_replace('/storage/', '', $image->url);
            Storage::disk('public')->delete($path);
        }

        // Delete associated videos from storage
        foreach ($property->videos as $video) {
            $path = str_replace('/storage/', '', $video->url);
            Storage::disk('public')->delete($path);
        }

        $property->delete();

        return redirect()->route('admin.properties.index')
            ->with('success', 'Immobilie wurde erfolgreich gelöscht.');
    }

    public function deleteImage(PropertyImage $image)
    {
        $path = str_replace('/storage/', '', $image->url);
        Storage::disk('public')->delete($path);
        $image->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Bild wurde erfolgreich gelöscht.');
    }

    public function deleteVideo(PropertyVideo $video)
    {
        $path = str_replace('/storage/', '', $video->url);
        Storage::disk('public')->delete($path);
        $video->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Video wurde erfolgreich gelöscht.');
    }

    private function geocodeAddress(string $address): ?array
    {
        try {
            $response = Http::withoutVerifying()
                ->acceptJson()
                ->withUserAgent(config('app.name') . ' property geocoder')
                ->timeout(8)
                ->get('https://nominatim.openstreetmap.org/search', [
                    'q' => $address,
                    'format' => 'jsonv2',
                    'limit' => 1,
                ]);

            $result = $response->json()[0] ?? null;
            if (!$response->successful() || !isset($result['lat'], $result['lon'])) {
                return null;
            }

            return [
                'latitude' => $result['lat'],
                'longitude' => $result['lon'],
            ];
        } catch (\Throwable) {
            return null;
        }
    }
}
