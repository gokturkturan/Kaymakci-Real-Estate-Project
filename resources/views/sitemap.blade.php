<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">

    {{-- Homepage --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toIso8601String() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    {{-- Static Pages --}}
    <url>
        <loc>{{ route('pages.about') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>{{ route('pages.contact') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>

    {{-- Property Pages --}}
    @foreach($properties as $property)
    <url>
        <loc>{{ route('properties.show', $property) }}</loc>
        <lastmod>{{ $property->updated_at->toIso8601String() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
        @foreach($property->images as $image)
        <image:image>
            <image:loc>{{ url($image->url) }}</image:loc>
            <image:title>{{ $property->title }}</image:title>
            <image:caption>{{ $property->title }} - {{ $property->bedrooms }} Zimmer in {{ $property->location }}</image:caption>
        </image:image>
        @endforeach
    </url>
    @endforeach

</urlset>
