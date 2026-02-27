@extends('layouts.app')

@section('title', 'Über uns | Kaymakci Real Estate GmbH')

@section('meta_description', 'Erfahren Sie mehr über Kaymakci Real Estate GmbH - Ihr zuverlässiger Immobilienmakler in Deutschland. Wir helfen Ihnen, Ihre Traumimmobilie zu finden.')

@section('meta_keywords', 'Kaymakci Real Estate GmbH, Über uns, Immobilienmakler Deutschland, Immobilienberatung')

@section('content')
    {{-- Hero --}}
    <section class="bg-blue-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-4">Über uns</h1>
            <p class="text-xl text-blue-100">Lernen Sie Kaymakci Real Estate GmbH kennen</p>
        </div>
    </section>

    {{-- Content --}}
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-xl shadow-md p-8">

            <div class="prose prose-lg max-w-none">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Willkommen bei Kaymakci Real Estate GmbH</h2>

                <p class="text-gray-600 leading-relaxed mb-6">
                    Kaymakci Real Estate GmbH ist Ihr vertrauenswürdiger Partner für alle Immobilienangelegenheiten in Deutschland.
                    Mit jahrelanger Erfahrung und tiefgreifendem Marktwissen unterstützen wir Sie dabei,
                    die perfekte Immobilie zu finden - sei es ein gemütliches Zuhause, eine lukrative Kapitalanlage
                    oder Ihr Traumhaus.
                </p>

                <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">Unsere Mission</h3>
                <p class="text-gray-600 leading-relaxed mb-6">
                    Wir glauben daran, dass jeder das Recht auf ein perfektes Zuhause hat.
                    Deshalb setzen wir alles daran, Ihnen nicht nur Immobilien zu zeigen,
                    sondern die richtige Immobilie für Ihre individuellen Bedürfnisse zu finden.
                </p>

                <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">Warum Kaymakci Real Estate GmbH?</h3>
                <ul class="list-none space-y-4 mb-6">
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-gray-600"><strong>Persönliche Beratung:</strong> Jeder Kunde ist einzigartig - und so behandeln wir Sie auch.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-gray-600"><strong>Marktkenntnis:</strong> Wir kennen den deutschen Immobilienmarkt in- und auswendig.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-gray-600"><strong>Transparenz:</strong> Keine versteckten Kosten, keine bösen Überraschungen.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-gray-600"><strong>Rundum-Service:</strong> Von der ersten Besichtigung bis zum Notartermin - wir sind für Sie da.</span>
                    </li>
                </ul>

                <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">Unsere Werte</h3>
                <p class="text-gray-600 leading-relaxed mb-6">
                    Vertrauen, Integrität und Kundenzufriedenheit stehen bei uns an erster Stelle.
                    Wir arbeiten stets in Ihrem besten Interesse und sorgen dafür,
                    dass der Kauf oder Verkauf Ihrer Immobilie zu einem positiven Erlebnis wird.
                </p>
            </div>

            {{-- CTA --}}
            <div class="mt-10 p-6 bg-blue-50 rounded-lg text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Bereit für den nächsten Schritt?</h3>
                <p class="text-gray-600 mb-4">Kontaktieren Sie uns noch heute für eine unverbindliche Beratung.</p>
                <a href="{{ route('pages.contact') }}"
                   class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition">
                    Kontakt aufnehmen
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>
@endsection
