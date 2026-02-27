@extends('layouts.app')

@section('title', 'Immobilien kaufen in Deutschland | Kaymakci Real Estate GmbH')

@section('meta_description', 'Entdecken Sie ' . $properties->total() . ' exklusive Immobilienangebote bei Kaymakci Real Estate GmbH. Häuser, Wohnungen, Villen und Penthäuser in ganz Deutschland. Jetzt Traumimmobilie finden!')

@section('meta_keywords', 'Immobilien kaufen, Haus kaufen Deutschland, Wohnung kaufen, Villa kaufen, Penthouse, Immobilienmakler, Kaymakci Real Estate GmbH')

@section('og_title', 'Immobilien kaufen in Deutschland | Kaymakci Real Estate GmbH')
@section('og_description', 'Entdecken Sie exklusive Immobilienangebote bei Kaymakci Real Estate GmbH. Häuser, Wohnungen und mehr in ganz Deutschland.')

@section('structured_data')
@php
$itemList = [
    '@context' => 'https://schema.org',
    '@type' => 'ItemList',
    'name' => 'Immobilienangebote bei Kaymakci Real Estate GmbH',
    'description' => 'Aktuelle Immobilienangebote in Deutschland',
    'numberOfItems' => $properties->total(),
    'itemListElement' => $properties->map(function($property, $index) use ($properties) {
        $item = [
            '@type' => 'ListItem',
            'position' => (($properties->currentPage() - 1) * $properties->perPage()) + $index + 1,
            'item' => [
                '@type' => 'RealEstateListing',
                'name' => $property->title,
                'description' => Str::limit($property->description, 160),
                'url' => route('properties.show', $property),
                'offers' => [
                    '@type' => 'Offer',
                    'price' => $property->price,
                    'priceCurrency' => 'EUR'
                ]
            ]
        ];
        if ($property->image) {
            $item['item']['image'] = $property->image;
        }
        return $item;
    })->toArray()
];

$breadcrumb = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Startseite',
            'item' => url('/')
        ]
    ]
];
@endphp
<script type="application/ld+json">{!! json_encode($itemList, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode($breadcrumb, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endsection

@section('content')
    {{-- Hero --}}
    <section class="bg-blue-600 text-white py-16" aria-labelledby="hero-title">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 id="hero-title" class="text-4xl font-bold mb-4">Immobilien kaufen bei Kaymakci Real Estate GmbH</h1>
            <p class="text-xl text-blue-100">Finden Sie gemeinsam mit uns Ihr Traumhaus in Deutschland</p>
        </div>
    </section>

    {{-- Angebotsliste --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" aria-labelledby="listings-title">
        <h2 id="listings-title" class="text-2xl font-bold text-gray-900 mb-8">Aktuelle Immobilienangebote ({{ $properties->total() }})</h2>

        @if($properties->isEmpty())
            <p class="text-gray-500 text-center py-12">Noch keine Angebote vorhanden.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($properties as $property)
                    <article class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition" itemscope itemtype="https://schema.org/RealEstateListing">
                        <a href="{{ route('properties.show', $property) }}" class="block" itemprop="url">
                            <div class="aspect-video bg-gray-200 overflow-hidden relative">
                                @if($property->first_image)
                                    <img src="{{ $property->first_image }}"
                                         alt="{{ $property->title }} - {{ $property->bedrooms }} Zimmer {{ $property->area }}m² in {{ $property->location }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                         loading="lazy"
                                         itemprop="image">
                                    @if($property->images->count() > 1)
                                        <span class="absolute bottom-2 right-2 bg-black/60 text-white text-xs px-2 py-1 rounded">
                                            {{ $property->images->count() }} Fotos
                                        </span>
                                    @endif
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400" aria-label="Kein Bild verfügbar">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 22V12h6v10"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-5">
                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition" itemprop="name">{{ $property->title }}</h3>
                                <p class="text-sm text-gray-500 mt-1" itemprop="address">{{ $property->location }}</p>
                                <p class="text-xl font-bold text-blue-600 mt-3" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                    <span itemprop="price" content="{{ $property->price }}">{{ number_format($property->price, 0, ',', '.') }}</span>
                                    <span itemprop="priceCurrency" content="EUR">€</span>
                                </p>
                                <div class="flex gap-4 mt-3 text-sm text-gray-500">
                                    <span><strong>{{ $property->bedrooms }}</strong> Zimmer</span>
                                    <span><strong>{{ $property->bathrooms }}</strong> Bad</span>
                                    <span><strong>{{ $property->area }}</strong> m²</span>
                                </div>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($properties->hasPages())
                <nav class="mt-12 flex justify-center" aria-label="Seitennavigation">
                    <div class="flex items-center gap-2">
                        {{-- Previous --}}
                        @if($properties->onFirstPage())
                            <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $properties->previousPageUrl() }}" class="px-4 py-2 text-gray-700 bg-white rounded-lg shadow hover:bg-blue-50 transition" aria-label="Vorherige Seite">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </a>
                        @endif

                        {{-- Page Numbers --}}
                        @foreach($properties->getUrlRange(1, $properties->lastPage()) as $page => $url)
                            @if($page == $properties->currentPage())
                                <span class="px-4 py-2 text-white bg-blue-600 rounded-lg font-medium" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-4 py-2 text-gray-700 bg-white rounded-lg shadow hover:bg-blue-50 transition">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if($properties->hasMorePages())
                            <a href="{{ $properties->nextPageUrl() }}" class="px-4 py-2 text-gray-700 bg-white rounded-lg shadow hover:bg-blue-50 transition" aria-label="Nächste Seite">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @else
                            <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                        @endif
                    </div>
                </nav>

                <p class="text-center text-sm text-gray-500 mt-4">
                    Seite {{ $properties->currentPage() }} von {{ $properties->lastPage() }} ({{ $properties->total() }} Angebote)
                </p>
            @endif
        @endif
    </section>

    {{-- SEO Text Section --}}
    <section class="bg-gray-100 py-12" aria-labelledby="seo-title">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 id="seo-title" class="text-2xl font-bold text-gray-900 mb-4">Ihr Immobilienmakler in Deutschland - Kaymakci Real Estate GmbH</h2>
            <div class="prose prose-gray max-w-none">
                <p class="text-gray-600 leading-relaxed">
                    Willkommen bei <strong>Kaymakci Real Estate GmbH</strong>, Ihrem zuverlässigen Partner für den Kauf von Immobilien in Deutschland.
                    Wir bieten Ihnen eine sorgfältig ausgewählte Auswahl an <strong>Häusern, Wohnungen, Villen und Penthäusern</strong>
                    in den besten Lagen Deutschlands - von München über Berlin bis Freiburg.
                </p>
                <p class="text-gray-600 leading-relaxed mt-4">
                    Unser erfahrenes Team begleitet Sie durch den gesamten Kaufprozess und hilft Ihnen,
                    die perfekte Immobilie zu finden, die Ihren Bedürfnissen und Ihrem Budget entspricht.
                    Kontaktieren Sie uns noch heute für eine persönliche Beratung.
                </p>
            </div>
        </div>
    </section>
@endsection
