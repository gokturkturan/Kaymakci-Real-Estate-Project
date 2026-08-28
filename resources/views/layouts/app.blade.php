<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- Primary Meta Tags --}}
    <title>@yield('title', __('layout.meta_default_title'))</title>
    <meta name="description" content="@yield('meta_description', __('layout.meta_default_description'))">
    <meta name="keywords" content="@yield('meta_keywords', __('layout.meta_default_keywords'))">
    <meta name="author" content="Kaymakci Real Estate GmbH">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', __('layout.og_default_title'))">
    <meta property="og:description" content="@yield('og_description', __('layout.og_default_description'))">
    <meta property="og:image" content="@yield('og_image', asset('images/logo.png'))">
    <meta property="og:locale" content="{{ app()->getLocale() === 'de' ? 'de_DE' : 'en_US' }}">
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
                <a href="{{ route('properties.index') }}" class="flex items-center gap-3" aria-label="{{ __('layout.home_aria') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Kaymakci Real Estate Logo" class="h-16 w-auto">
                    <span class="text-xl font-bold text-gray-900">Kaymakci Real Estate</span>
                </a>
                <div class="flex items-center gap-6">
                    <nav class="flex gap-6 text-sm font-medium text-gray-600" role="navigation" aria-label="{{ __('layout.main_nav_aria') }}">
                        <a href="{{ route('properties.index') }}" class="hover:text-blue-600 transition">{{ __('layout.nav_properties') }}</a>
                        <a href="{{ route('pages.about') }}" class="hover:text-blue-600 transition">{{ __('layout.nav_about') }}</a>
                        <a href="{{ route('pages.contact') }}" class="hover:text-blue-600 transition">{{ __('layout.nav_contact') }}</a>
                    </nav>
                    <div class="flex items-center gap-1 text-sm font-medium border-l border-gray-200 pl-6" role="navigation" aria-label="Sprachauswahl / Language selection">
                        <a href="{{ route('locale.switch', 'de') }}"
                           class="px-2 py-1 rounded transition {{ app()->getLocale() === 'de' ? 'text-blue-600 font-bold' : 'text-gray-400 hover:text-blue-600' }}"
                           @if(app()->getLocale() === 'de') aria-current="true" @endif>DE</a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('locale.switch', 'en') }}"
                           class="px-2 py-1 rounded transition {{ app()->getLocale() === 'en' ? 'text-blue-600 font-bold' : 'text-gray-400 hover:text-blue-600' }}"
                           @if(app()->getLocale() === 'en') aria-current="true" @endif>EN</a>
                    </div>
                </div>
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
            <p class="text-sm text-gray-300">{{ __('layout.footer_tagline') }}</p>

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

            <p class="text-xs mt-4 text-gray-500">&copy; {{ date('Y') }} Kaymakci Real Estate GmbH. {{ __('layout.footer_rights') }}</p>
        </div>
    </footer>

    {{-- Organization Schema --}}
    @php
    $organization = [
        '@context' => 'https://schema.org',
        '@type' => 'RealEstateAgent',
        'name' => 'Kaymakci Real Estate GmbH',
        'description' => __('layout.org_description'),
        'url' => url('/'),
        'logo' => asset('images/logo.png'),
        'image' => asset('images/logo.png'),
        'telephone' => '+49 176 24821040',
        'email' => 'ali@kaymakci-real-estate.de',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => 'Riedhofweg 23',
            'postalCode' => '60596',
            'addressLocality' => 'Frankfurt am Main',
            'addressCountry' => 'DE'
        ],
        'openingHoursSpecification' => [
            '@type' => 'OpeningHoursSpecification',
            'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            'opens' => '09:00',
            'closes' => '18:00'
        ],
        'areaServed' => [
            '@type' => 'Country',
            'name' => __('layout.org_area_served')
        ]
    ];
    @endphp
    <script type="application/ld+json">{!! json_encode($organization, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>

</body>
</html>
