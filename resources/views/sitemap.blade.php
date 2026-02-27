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

    {{-- Property Pages --}}
    @foreach($properties as $property)
    <url>
        <loc>{{ route('properties.show', $property) }}</loc>
        <lastmod>{{ $property->updated_at->toIso8601String() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
        @if($property->image)
        <image:image>
            <image:loc>{{ $property->image }}</image:loc>
            <image:title>{{ $property->title }}</image:title>
            <image:caption>{{ $property->title }} - {{ $property->bedrooms }} Zimmer in {{ $property->location }}</image:caption>
        </image:image>
        @endif
    </url>
    @endforeach

</urlset>
