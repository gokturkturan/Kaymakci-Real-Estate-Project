@extends('layouts.app')

@section('title', __('contact.meta_title'))

@section('meta_description', __('contact.meta_description'))

@section('meta_keywords', __('contact.meta_keywords'))

@section('content')
    {{-- Hero --}}
    <section class="bg-blue-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-4">{{ __('contact.hero_title') }}</h1>
            <p class="text-xl text-blue-100">{{ __('contact.hero_subtitle') }}</p>
        </div>
    </section>

    {{-- Content --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            {{-- Contact Info --}}
            <div class="bg-white rounded-xl shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('contact.info_heading') }}</h2>

                <div class="space-y-6">
                    {{-- Address --}}
                    <div class="flex items-start gap-4">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ __('contact.address_label') }}</h3>
                            <p class="text-gray-600 mt-1">
                                Kaymakci Real Estate GmbH<br>
                                Riedhofweg 23<br>
                                60596 Frankfurt am Main<br>
                                {{ __('contact.country') }}
                            </p>
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="flex items-start gap-4">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ __('contact.phone_label') }}</h3>
                            <p class="text-gray-600 mt-1">
                                <a href="tel:+4917624821040" class="hover:text-blue-600 transition block">{{ __('contact.phone_mobile_prefix') }} 0176 / 248 21 040</a>
                                <a href="tel:+496926094750" class="hover:text-blue-600 transition block">{{ __('contact.phone_tel_prefix') }} 069 / 260 94 750</a>
                                <span class="block">{{ __('contact.fax_prefix') }} 069 / 260 94 755</span>
                            </p>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="flex items-start gap-4">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ __('contact.email_label') }}</h3>
                            <p class="text-gray-600 mt-1">
                                <a href="mailto:ali@kaymakci-real-estate.de" class="hover:text-blue-600 transition">ali@kaymakci-real-estate.de</a>
                            </p>
                        </div>
                    </div>

                    {{-- Hours --}}
                    <div class="flex items-start gap-4">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ __('contact.hours_label') }}</h3>
                            <p class="text-gray-600 mt-1">
                                {{ __('contact.hours_weekdays') }}<br>
                                {{ __('contact.hours_saturday') }}<br>
                                {{ __('contact.hours_sunday') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact Form --}}
            <div class="bg-white rounded-xl shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('contact.form_heading') }}</h2>

                <form action="{{ route('pages.contact.send') }}" method="POST" class="space-y-6">
                    @csrf

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('contact.name_label') }}</label>
                        <input type="text" id="name" name="name" required value="{{ old('name') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               placeholder="{{ __('contact.name_placeholder') }}">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('contact.email_field_label') }}</label>
                        <input type="email" id="email" name="email" required value="{{ old('email') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               placeholder="{{ __('contact.email_placeholder') }}">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('contact.phone_field_label') }}</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               placeholder="{{ __('contact.phone_placeholder') }}">
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">{{ __('contact.subject_label') }}</label>
                        <select id="subject" name="subject" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">{{ __('contact.subject_placeholder') }}</option>
                            <option value="kaufen" {{ old('subject') == 'kaufen' ? 'selected' : '' }}>{{ __('contact.subject_buy') }}</option>
                            <option value="verkaufen" {{ old('subject') == 'verkaufen' ? 'selected' : '' }}>{{ __('contact.subject_sell') }}</option>
                            <option value="besichtigung" {{ old('subject') == 'besichtigung' ? 'selected' : '' }}>{{ __('contact.subject_viewing') }}</option>
                            <option value="beratung" {{ old('subject') == 'beratung' ? 'selected' : '' }}>{{ __('contact.subject_consulting') }}</option>
                            <option value="sonstiges" {{ old('subject') == 'sonstiges' ? 'selected' : '' }}>{{ __('contact.subject_other') }}</option>
                        </select>
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">{{ __('contact.message_label') }}</label>
                        <textarea id="message" name="message" rows="5" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-none"
                                  placeholder="{{ __('contact.message_placeholder') }}">{{ old('message') }}</textarea>
                    </div>

                    <div class="flex items-start gap-2">
                        <input type="checkbox" id="privacy" name="privacy" required
                               class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="privacy" class="text-sm text-gray-600">
                            {{ __('contact.privacy_text') }}
                        </label>
                    </div>

                    <button type="submit"
                            class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition flex items-center justify-center gap-2">
                        {{ __('contact.submit') }}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
