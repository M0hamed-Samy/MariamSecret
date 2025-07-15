@extends('layouts.app')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>

        <section class="contact-us container">
            <div class="mw-930">
                <h2 class="page-title">{{ __('pages.terms_title') }}</h2>
            </div>
        </section>

        <div class="mb-5 pb-4"></div>

        <section class="container mw-930 lh-30">
            <h3 class="mb-4">{{ __('pages.intro_title') }}</h3>
            <p>{{ __('pages.intro_text') }}</p>

            <h3 class="mt-4 mb-3">{{ __('pages.usage_title') }}</h3>
            <ul>
                @foreach(__('pages.usage_list') as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>

            <h3 class="mt-4 mb-3">{{ __('pages.orders_title') }}</h3>
            <ul>
                @foreach(__('pages.orders_list') as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>

            <h3 class="mt-4 mb-3">{{ __('pages.shipping_title') }}</h3>
            <p>{{ __('pages.shipping_text') }}</p>

            <h3 class="mt-4 mb-3">{{ __('pages.returns_title') }}</h3>
            <ul>
                @foreach(__('pages.returns_list') as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>

            <h3 class="mt-4 mb-3">{{ __('pages.ip_title') }}</h3>
            <p>{{ __('pages.ip_text') }}</p>

            <h3 class="mt-4 mb-3">{{ __('pages.liability_title') }}</h3>
            <p>{{ __('pages.liability_text') }}</p>

            <h3 class="mt-4 mb-3">{{ __('pages.changes_title') }}</h3>
            <p>{{ __('pages.changes_text') }}</p>

            <h3 class="mt-4 mb-3">{{ __('pages.law_title') }}</h3>
            <p>{{ __('pages.law_text') }}</p>

            <h3 class="mt-4 mb-3">{{ __('pages.contact_title') }}</h3>
            <p>{!! __('pages.contact_text', ['email' => '<a href="mailto:mariamsecret10@gmail.com">mariamsecret10@gmail.com</a>']) !!}</p>
        </section>

        <div class="mb-5 pb-4"></div>
    </main>
@endsection
