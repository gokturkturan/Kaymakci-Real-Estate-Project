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
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Über Kaymakci Real Estate GmbH</h2>

                <p class="text-gray-600 leading-relaxed mb-6">
                    Die Kaymakci Real Estate GmbH ist Ihr zuverlässiger Partner im Bereich der möblierten Vermietung.
                    Wir haben uns darauf spezialisiert, komfortabel ausgestattete Immobilien für Menschen anzubieten,
                    die eine flexible und unkomplizierte Wohnlösung suchen.
                </p>

                <p class="text-gray-600 leading-relaxed mb-6">
                    Unser Anspruch ist es, unseren Mietern ein Zuhause auf Zeit zu bieten – modern, komfortabel und bezugsfertig.
                    Dabei legen wir großen Wert auf eine professionelle Betreuung, gepflegte Immobilien und einen reibungslosen Ablauf
                    von der Anfrage bis zur Vermietung.
                </p>

                <p class="text-gray-600 leading-relaxed mb-6">
                    Mit unserem Service richten wir uns sowohl an Geschäftsreisende und Berufstätige als auch an Personen,
                    die für einen bestimmten Zeitraum eine möblierte Wohnung benötigen.
                </p>

                <p class="text-gray-600 leading-relaxed mb-6">
                    <strong>Kaymakci Real Estate GmbH – komfortabel wohnen, flexibel bleiben.</strong>
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
