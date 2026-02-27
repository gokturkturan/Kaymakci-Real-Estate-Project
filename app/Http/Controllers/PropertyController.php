<?php

namespace App\Http\Controllers;

use App\Models\Property;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::with('images')->latest()->paginate(6);
        return view('properties.index', compact('properties'));
    }

    public function show(Property $property)
    {
        $property->load('images');
        return view('properties.show', compact('property'));
    }
}
