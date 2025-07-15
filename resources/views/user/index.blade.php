@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
   <section class="my-account container">
    <h2 class="page-title">{{ __('shop.my_account') }}</h2>
    <div class="row">
        <div class="col-lg-3">
            @include('user.account-nav')
        </div>
        <div class="col-lg-9">
            <div class="page-content my-account__dashboard">
                <p>{{ __('shop.hello_user', ['name' => Auth::user()->name]) }}</p>
                <p>
                    {!! __('shop.account_intro', [
                        'orders_link' => '<a class="unerline-link" href="' . route('user.orders') . '">' . __('shop.recent_orders') . '</a>',
                        'edit_link' => '<a class="unerline-link" href="' . route('account.edit') . '">' . __('shop.edit_account') . '</a>',
                    ]) !!}
                </p>
            </div>
        </div>
    </div>
</section>

  </main>
@endsection