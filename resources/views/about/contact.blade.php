@extends('layouts.app')
@section('content')
    <style>
        .text-danger {
            color: red !important;
        }
    </style>
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="contact-us container">
            <div class="mw-930">
                <h2 class="page-title">{{ __('messages.contact_us') }}</h2>
            </div>
        </section>

        <hr class="mt-2 text-secondary " />
        <div class="mb-4 pb-4"></div>

        <section class="contact-us container">
            <div class="mw-930">
                <div class="contact-us__form">
                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ Session::get('success') }}
                        </div>
                    @endif
                    <form name="contact-us-form" class="needs-validation" novalidate=""
                        action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <h3 class="mb-5">{{ __('shop.contact_us') }}</h3>

                        <div class="form-floating my-4">
                            <input type="text" class="form-control" name="name" placeholder="{{ __('shop.name') }} *"
                                value="{{ old('name') }}" required="">
                            <label for="contact_us_name">{{ __('shop.name') }} *</label>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating my-4">
                            <input type="text" class="form-control" name="phone" placeholder="{{ __('shop.phone') }} *"
                                value="{{ old('phone') }}" required="">
                            <label for="contact_us_phone">{{ __('shop.phone') }} *</label>
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating my-4">
                            <input type="email" class="form-control" name="email"
                                placeholder="{{ __('shop.email_address') }} *" value="{{ old('email') }}" required="">
                            <label for="contact_us_email">{{ __('shop.email_address') }} *</label>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="my-4">
                            <textarea class="form-control form-control_gray" name="comment" placeholder="{{ __('shop.your_message') }}"
                                cols="30" rows="8" required=""></textarea>
                            @error('comment')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="my-4">
                            <button type="submit" class="btn btn-primary">{{ __('shop.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
@endsection
