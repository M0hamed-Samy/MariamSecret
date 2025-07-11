@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-primary text-white text-center fw-semibold fs-5">
                {{ __('Verify Your Email Address') }}
            </div>

            <div class="card-body text-center py-4">
                @if (session('resent'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <p class="mb-3 text-secondary">
                    {{ __('Before proceeding, please check your email for a verification link.') }}
                </p>

                <p class="text-secondary">
                    {{ __('If you did not receive the email') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline text-primary text-decoration-underline fw-semibold">
                            {{ __('click here to request another') }}
                        </button>.
                    </form>
                </p>
            </div>
        </div>
    </div>
</div>

</div>
@endsection
