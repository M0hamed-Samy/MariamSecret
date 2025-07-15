@extends('layouts.app')
@section('content')
<main class="pt-90">
  <div class="mb-4 pb-4"></div>

  <section class="contact-us container">
    <div class="mw-930">
      <h2 class="page-title">{{ __('privacy.title') }}</h2>
    </div>
  </section>

  <div class="mb-5 pb-4"></div>

  <section class="container mw-930 lh-30">
    <h3 class="mb-4">{{ __('privacy.intro_title') }}</h3>
    <p>{{ __('privacy.intro_text') }}</p>

    <h3 class="mt-4 mb-3">{{ __('privacy.collect_title') }}</h3>
    <ul>
      @foreach(__('privacy.collect_list') as $item)
        <li>{!! $item !!}</li>
      @endforeach
    </ul>

    <h3 class="mt-4 mb-3">{{ __('privacy.use_title') }}</h3>
    <ul>
      @foreach(__('privacy.use_list') as $item)
        <li>{{ $item }}</li>
      @endforeach
    </ul>

    <h3 class="mt-4 mb-3">{{ __('privacy.share_title') }}</h3>
    <p>{{ __('privacy.share_text') }}</p>
    <ul>
      @foreach(__('privacy.share_list') as $item)
        <li>{{ $item }}</li>
      @endforeach
    </ul>

    <h3 class="mt-4 mb-3">{{ __('privacy.security_title') }}</h3>
    <p>{{ __('privacy.security_text') }}</p>

    <h3 class="mt-4 mb-3">{{ __('privacy.rights_title') }}</h3>
    <ul>
      @foreach(__('privacy.rights_list') as $item)
        <li>{{ $item }}</li>
      @endforeach
    </ul>

    <h3 class="mt-4 mb-3">{{ __('privacy.changes_title') }}</h3>
    <p>{{ __('privacy.changes_text') }}</p>

    <h3 class="mt-4 mb-3">{{ __('privacy.contact_title') }}</h3>
    <p>{!! __('privacy.contact_text', ['email' => '<a href="mailto:mariamsecret10@gmail.com">mariamsecret10@gmail.com</a>']) !!}</p>
  </section>

  <div class="mb-5 pb-4"></div>
</main>
@endsection
