<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - Kaymakci Real Estate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white flex-shrink-0">
            <div class="p-4 border-b border-gray-800">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto bg-white rounded p-1">
                    <span class="font-bold">Admin Panel</span>
                </a>
            </div>
            <nav class="p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600' : 'hover:bg-gray-800' }} transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.properties.index') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('admin.properties.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }} transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Immobilien
                </a>
                <a href="{{ route('admin.bookings.index') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('admin.bookings.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }} transition relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Buchungen
                    @php
                        $pendingCount = \App\Models\Booking::where('status', 'pending')->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="absolute right-2 top-1/2 -translate-y-1/2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>
                <hr class="border-gray-800 my-4">
                <a href="{{ route('properties.index') }}" target="_blank"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-800 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Website ansehen
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center">
                <h1 class="text-xl font-semibold text-gray-800">@yield('header', 'Dashboard')</h1>
                <div class="flex items-center gap-4">
                    <span class="text-gray-600">{{ Auth::user()->name }}</span>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800 transition">
                            Abmelden
                        </button>
                    </form>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
