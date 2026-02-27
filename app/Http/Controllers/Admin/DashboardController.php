<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_properties' => Property::count(),
            'recent_properties' => Property::latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
