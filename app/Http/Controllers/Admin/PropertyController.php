<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
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
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $property->update($validated);

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
}
