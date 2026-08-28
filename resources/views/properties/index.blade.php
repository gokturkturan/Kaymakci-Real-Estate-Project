@extends('layouts.app')

@section('title', __('home.meta_title'))

@section('meta_description', __('home.meta_description', ['count' => $properties->total()]))

@section('meta_keywords', __('home.meta_keywords'))

@section('og_title', __('home.og_title'))
@section('og_description', __('home.og_description'))

@section('structured_data')
@php
$itemList = [
    '@context' => 'https://schema.org',
    '@type' => 'ItemList',
    'name' => __('home.schema_item_list_name'),
    'description' => __('home.schema_item_list_description'),
    'numberOfItems' => $properties->total(),
    'itemListElement' => $properties->map(function($property, $index) use ($properties) {
        $item = [
            '@type' => 'ListItem',
            'position' => (($properties->currentPage() - 1) * $properties->perPage()) + $index + 1,
            'item' => [
                '@type' => 'RealEstateListing',
                'name' => $property->localized_title,
                'description' => Str::limit($property->localized_description, 160),
                'url' => route('properties.show', $property),
                'offers' => [
                    '@type' => 'Offer',
                    'price' => $property->price,
                    'priceCurrency' => 'EUR',
                    'priceSpecification' => [
                        '@type' => 'UnitPriceSpecification',
                        'price' => $property->price,
                        'priceCurrency' => 'EUR',
                        'unitText' => __('home.schema_unit_text')
                    ]
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
            'name' => __('home.schema_home'),
            'item' => url('/')
        ]
    ]
];
@endphp
<script type="application/ld+json">{!! json_encode($itemList, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode($breadcrumb, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endsection

@section('content')
    @php
        $decimalSep = app()->getLocale() === 'de' ? ',' : '.';
        $thousandSep = app()->getLocale() === 'de' ? '.' : ',';
    @endphp
    {{-- Hero --}}
    <section class="bg-blue-600 text-white py-16" aria-labelledby="hero-title">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 id="hero-title" class="text-4xl font-bold mb-4">{{ __('home.hero_title') }}</h1>
            <p class="text-xl text-blue-100">{{ __('home.hero_subtitle') }}</p>
        </div>
    </section>

    {{-- Filter --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-10">
        <form method="GET" action="{{ route('properties.index') }}" class="bg-white rounded-xl shadow-md p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @php $selectedLocations = (array) request('location', []); @endphp
            <div class="relative" x-data="{ open: false, selected: {{ Illuminate\Support\Js::from(array_values($selectedLocations)) }} }" @click.outside="open = false">
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('home.filter_location_label') }}</label>
                <button type="button" @click="open = !open"
                        class="w-full flex items-center justify-between gap-2 px-3 py-2 border border-gray-300 rounded-lg bg-white text-left focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <span class="truncate text-gray-700" x-text="selected.length ? selected.length + ' {{ __('home.filter_location_selected_suffix') }}' : '{{ __('home.filter_location_all') }}'"></span>
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open" x-transition style="display: none;"
                     class="absolute z-20 mt-1 w-full max-h-60 overflow-y-auto bg-white border border-gray-300 rounded-lg shadow-lg">
                    @forelse($locations as $location)
                        <label class="flex items-center gap-2 px-3 py-2 hover:bg-gray-50 cursor-pointer text-sm text-gray-700">
                            <input type="checkbox" name="location[]" value="{{ $location }}" x-model="selected"
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                            {{ $location }}
                        </label>
                    @empty
                        <p class="px-3 py-2 text-sm text-gray-400">{{ __('home.filter_location_empty') }}</p>
                    @endforelse
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('home.filter_price_label') }}</label>
                <div class="flex gap-2">
                    <input type="number" name="price_min" value="{{ request('price_min') }}" placeholder="{{ __('home.filter_from') }}" min="0"
                           class="w-1/2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <input type="number" name="price_max" value="{{ request('price_max') }}" placeholder="{{ __('home.filter_to') }}" min="0"
                           class="w-1/2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div>
                <label for="bedrooms" class="block text-sm font-medium text-gray-700 mb-1">{{ __('home.filter_bedrooms_label') }}</label>
                <select id="bedrooms" name="bedrooms" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">{{ __('home.filter_any') }}</option>
                    @for($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}" {{ (string) request('bedrooms') === (string) $i ? 'selected' : '' }}>{{ $i }}+</option>
                    @endfor
                </select>
            </div>

            <div>
                <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-1">{{ __('home.filter_bathrooms_label') }}</label>
                <select id="bathrooms" name="bathrooms" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">{{ __('home.filter_any') }}</option>
                    @for($i = 1; $i <= 4; $i++)
                        <option value="{{ $i }}" {{ (string) request('bathrooms') === (string) $i ? 'selected' : '' }}>{{ $i }}+</option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('home.filter_area_label') }}</label>
                <div class="flex gap-2">
                    <input type="number" name="area_min" value="{{ request('area_min') }}" placeholder="{{ __('home.filter_from') }}" min="0"
                           class="w-1/2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <input type="number" name="area_max" value="{{ request('area_max') }}" placeholder="{{ __('home.filter_to') }}" min="0"
                           class="w-1/2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="flex items-end">
                <label class="flex items-center gap-2 cursor-pointer select-none py-2">
                    <input type="checkbox" name="parking" value="1" {{ request('parking') ? 'checked' : '' }}
                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-700">{{ __('home.filter_parking_only') }}</span>
                </label>
            </div>

            <div class="lg:col-span-2 flex items-end justify-end gap-3">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    {{ __('home.filter_submit') }}
                </button>
                @if(request()->anyFilled(['location', 'price_min', 'price_max', 'bedrooms', 'bathrooms', 'area_min', 'area_max', 'parking']))
                    <a href="{{ route('properties.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        {{ __('home.filter_reset') }}
                    </a>
                @endif
            </div>
        </form>
    </section>

    {{-- Angebotsliste --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" aria-labelledby="listings-title">
        <h2 id="listings-title" class="text-2xl font-bold text-gray-900 mb-8">{{ __('home.listings_heading', ['count' => $properties->total()]) }}</h2>

        @if($properties->isEmpty())
            <p class="text-gray-500 text-center py-12">{{ __('home.listings_empty') }}</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($properties as $property)
                    <article class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition" itemscope itemtype="https://schema.org/RealEstateListing">
                        <a href="{{ route('properties.show', $property) }}" class="block" itemprop="url">
                            <div class="aspect-video bg-gray-200 overflow-hidden relative">
                                @if($property->first_image)
                                    <img src="{{ $property->first_image }}"
                                         alt="{{ __('home.card_image_alt', ['title' => $property->localized_title, 'bedrooms' => $property->bedrooms, 'area' => $property->area, 'location' => $property->location]) }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                         loading="lazy"
                                         itemprop="image">
                                    @if($property->images->count() > 1)
                                        <span class="absolute bottom-2 right-2 bg-black/60 text-white text-xs px-2 py-1 rounded">
                                            {{ __('home.card_photos_count', ['count' => $property->images->count()]) }}
                                        </span>
                                    @endif
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400" aria-label="{{ __('home.card_no_image') }}">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 22V12h6v10"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-5">
                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition" itemprop="name">{{ $property->localized_title }}</h3>
                                <p class="text-sm text-gray-500 mt-1" itemprop="address">{{ $property->location }}</p>
                                <p class="text-xl font-bold text-blue-600 mt-3" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                    <span itemprop="price" content="{{ $property->price }}">{{ number_format($property->price, 2, $decimalSep, $thousandSep) }}</span>
                                    <span itemprop="priceCurrency" content="EUR">€</span>
                                    <span class="text-sm font-medium text-gray-500">{{ __('home.card_per_person_night') }}</span>
                                </p>
                                <div class="flex gap-4 mt-3 text-sm text-gray-500">
                                    <span><strong>{{ $property->bedrooms }}</strong> {{ __('home.card_rooms') }}</span>
                                    <span><strong>{{ $property->bathrooms }}</strong> {{ __('home.card_bath') }}</span>
                                    <span><strong>{{ $property->area }}</strong> m²</span>
                                </div>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($properties->hasPages())
                <nav class="mt-12 flex justify-center" aria-label="{{ __('home.pagination_aria') }}">
                    <div class="flex items-center gap-2">
                        {{-- Previous --}}
                        @if($properties->onFirstPage())
                            <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $properties->previousPageUrl() }}" class="px-4 py-2 text-gray-700 bg-white rounded-lg shadow hover:bg-blue-50 transition" aria-label="{{ __('home.pagination_prev') }}">
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
                            <a href="{{ $properties->nextPageUrl() }}" class="px-4 py-2 text-gray-700 bg-white rounded-lg shadow hover:bg-blue-50 transition" aria-label="{{ __('home.pagination_next') }}">
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
                    {{ __('home.pagination_summary', ['current' => $properties->currentPage(), 'last' => $properties->lastPage(), 'total' => $properties->total()]) }}
                </p>
            @endif
        @endif
    </section>

    {{-- SEO Text Section --}}
    <section class="bg-gray-100 py-12" aria-labelledby="seo-title">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 id="seo-title" class="text-2xl font-bold text-gray-900 mb-4">{{ __('home.seo_heading') }}</h2>
            <div class="prose prose-gray max-w-none">
                <p class="text-gray-600 leading-relaxed">
                    {!! __('home.seo_paragraph_1') !!}
                </p>
                <p class="text-gray-600 leading-relaxed mt-4">
                    {{ __('home.seo_paragraph_2') }}
                </p>
            </div>
        </div>
    </section>
@endsection
