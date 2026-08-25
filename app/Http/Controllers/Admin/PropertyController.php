<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyImage;
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
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $coordinates = $this->geocodeAddress($validated['location']);
        if ($coordinates) {
            $validated = array_merge($validated, $coordinates);
        }

        $property = Property::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('properties', 'public');
                PropertyImage::create([
                    'property_id' => $property->id,
                    'url' => '/storage/' . $path,
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.properties.index')
            ->with('success', 'Immobilie wurde erfolgreich erstellt.');
    }

    public function edit(Property $property)
    {
        $property->load('images');
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
            'image_order' => 'nullable|array',
            'image_order.*' => 'integer',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($property->location !== $validated['location'] || $property->latitude === null || $property->longitude === null) {
            $coordinates = $this->geocodeAddress($validated['location']);
            $validated = array_merge($validated, $coordinates ?? ['latitude' => null, 'longitude' => null]);
        }

        $property->update($validated);

        if (!empty($validated['image_order'])) {
            $orderedIds = collect($validated['image_order'])
                ->map(fn($imageId) => (int) $imageId)
                ->unique()
                ->values();
            $existingImages = $property->images()->get()->keyBy('id');
            $order = 0;

            foreach ($orderedIds as $imageId) {
                if ($existingImages->has($imageId)) {
                    $existingImages[$imageId]->update(['order' => $order++]);
                    $existingImages->forget($imageId);
                }
            }

            foreach ($existingImages as $image) {
                $image->update(['order' => $order++]);
            }
        }

        if ($request->hasFile('images')) {
            $maxOrder = $property->images()->max('order') ?? -1;
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('properties', 'public');
                PropertyImage::create([
                    'property_id' => $property->id,
                    'url' => '/storage/' . $path,
                    'order' => $maxOrder + $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.properties.index')
            ->with('success', 'Immobilie wurde erfolgreich aktualisiert.');
    }

    public function destroy(Property $property)
    {
        // Delete associated images from storage
        foreach ($property->images as $image) {
            $path = str_replace('/storage/', '', $image->url);
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
