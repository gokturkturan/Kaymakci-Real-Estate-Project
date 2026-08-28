@extends('layouts.app')

@section('title', __('about.meta_title'))

@section('meta_description', __('about.meta_description'))

@section('meta_keywords', __('about.meta_keywords'))

@section('content')
    {{-- Hero --}}
    <section class="bg-blue-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-4">{{ __('about.hero_title') }}</h1>
            <p class="text-xl text-blue-100">{{ __('about.hero_subtitle') }}</p>
        </div>
    </section>

    {{-- Content --}}
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-xl shadow-md p-8">

            <div class="prose prose-lg max-w-none">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('about.heading') }}</h2>

                <p class="text-gray-600 leading-relaxed mb-6">
                    {{ __('about.paragraph_1') }}
                </p>

                <p class="text-gray-600 leading-relaxed mb-6">
                    {{ __('about.paragraph_2') }}
                </p>

                <p class="text-gray-600 leading-relaxed mb-6">
                    {{ __('about.paragraph_3') }}
                </p>

                <p class="text-gray-600 leading-relaxed mb-6">
                    <strong>{{ __('about.paragraph_4') }}</strong>
                </p>
            </div>

            {{-- CTA --}}
            <div class="mt-10 p-6 bg-blue-50 rounded-lg text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('about.cta_heading') }}</h3>
                <p class="text-gray-600 mb-4">{{ __('about.cta_text') }}</p>
                <a href="{{ route('pages.contact') }}"
                   class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition">
                    {{ __('about.cta_button') }}
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>
@endsection
