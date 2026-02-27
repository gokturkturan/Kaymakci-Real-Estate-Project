<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- Primary Meta Tags --}}
    <title>@yield('title', 'Kaymakci Real Estate GmbH - Ihr Immobilienmakler in Deutschland')</title>
    <meta name="description" content="@yield('meta_description', 'Kaymakci Real Estate GmbH - Ihr zuverlässiger Partner für Immobilien in Deutschland. Finden Sie Häuser, Wohnungen, Villen und mehr.')">
    <meta name="keywords" content="@yield('meta_keywords', 'Immobilien, Haus kaufen, Wohnung kaufen, Immobilienmakler, Deutschland, Villa, Penthouse, Kaymakci Real Estate GmbH')">
    <meta name="author" content="Kaymakci Real Estate GmbH">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', 'Kaymakci Real Estate GmbH - Ihr Immobilienmakler in Deutschland')">
    <meta property="og:description" content="@yield('og_description', 'Kaymakci Real Estate GmbH - Ihr zuverlässiger Partner für Immobilien in Deutschland.')">
    <meta property="og:image" content="@yield('og_image', asset('images/logo.png'))">
    <meta property="og:locale" content="de_DE">
    <meta property="og:site_name" content="Kaymakci Real Estate GmbH">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('twitter_title', 'Kaymakci Real Estate GmbH - Ihr Immobilienmakler in Deutschland')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Kaymakci Real Estate GmbH - Ihr zuverlässiger Partner für Immobilien in Deutschland.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/logo.png'))">

    {{-- Geo Tags --}}
    <meta name="geo.region" content="DE">
    <meta name="geo.placename" content="Deutschland">

    {{-- Structured Data --}}
    @yield('structured_data')

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <header class="bg-white shadow-sm" role="banner">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('properties.index') }}" class="flex items-center gap-3" aria-label="Kaymakci Real Estate GmbH - Startseite">
                    <img src="{{ asset('images/logo.png') }}" alt="Kaymakci Real Estate Logo" class="h-16 w-auto">
                    <span class="text-xl font-bold text-gray-900">Kaymakci Real Estate</span>
                </a>
                <nav class="flex gap-6 text-sm font-medium text-gray-600" role="navigation" aria-label="Hauptnavigation">
                    <a href="{{ route('properties.index') }}" class="hover:text-blue-600 transition">Angebote</a>
                    <a href="{{ route('pages.about') }}" class="hover:text-blue-600 transition">Über uns</a>
                    <a href="{{ route('pages.contact') }}" class="hover:text-blue-600 transition">Kontakt</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="flex-1" role="main">
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-gray-400 py-10 mt-12" role="contentinfo">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="bg-white rounded-2xl p-3 inline-block mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Kaymakci Real Estate Logo" class="h-14 w-auto">
            </div>
            <p class="text-sm text-gray-300">Zuverlässige Immobilienberatung</p>

            {{-- Social & Contact Links --}}
            <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-8">
                {{-- Instagram --}}
                <a href="https://www.instagram.com/kaymakci_realestate" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 text-gray-400 hover:text-pink-500 transition">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                    <span class="text-sm">@kaymakci_realestate</span>
                </a>

                {{-- Email --}}
                <a href="mailto:ali@kaymakci-real-estate.de"
                   class="inline-flex items-center gap-2 text-gray-400 hover:text-blue-400 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm">ali@kaymakci-real-estate.de</span>
                </a>
            </div>

            <p class="text-xs mt-4 text-gray-500">&copy; {{ date('Y') }} Kaymakci Real Estate GmbH. Alle Rechte vorbehalten.</p>
        </div>
    </footer>

    {{-- Organization Schema --}}
    @php
    $organization = [
        '@context' => 'https://schema.org',
        '@type' => 'RealEstateAgent',
        'name' => 'Kaymakci Real Estate GmbH',
        'description' => 'Ihr zuverlässiger Partner für Immobilien in Deutschland',
        'url' => url('/'),
        'logo' => asset('images/logo.png'),
        'address' => [
            '@type' => 'PostalAddress',
            'addressCountry' => 'DE'
        ],
        'areaServed' => [
            '@type' => 'Country',
            'name' => 'Deutschland'
        ]
    ];
    @endphp
    <script type="application/ld+json">{!! json_encode($organization, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>

</body>
</html>
