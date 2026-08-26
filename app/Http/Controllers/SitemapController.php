<?php

namespace App\Http\Controllers;

use App\Models\Property;

class SitemapController extends Controller
{
    public function index()
    {
        $properties = Property::with('images')->latest()->get();

        return response()->view('sitemap', compact('properties'))
            ->header('Content-Type', 'application/xml');
    }
}
