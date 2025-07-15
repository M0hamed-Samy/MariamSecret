@extends('layouts.app')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="shop-checkout container">
            
            <h2 class="page-title">{{ __('shop.order_received') }}</h2>
            <div class="checkout-steps">
                <a href="{{ route('cart.index') }}" class="checkout-steps__item active">
                    <span class="checkout-steps__item-number">01</span>
                    <span class="checkout-steps__item-title">
                        <span>{{ __('shop.step_1_title') }}</span>
                        <em>{{ __('shop.step_1_subtitle') }}</em>
                    </span>
                </a>
                <a href="{{ route('cart.checkout') }}" class="checkout-steps__item active">
                    <span class="checkout-steps__item-number">02</span>
                    <span class="checkout-steps__item-title">
                        <span>{{ __('shop.step_2_title') }}</span>
                        <em>{{ __('shop.step_2_subtitle') }}</em>
                    </span>
                </a>
                {{-- <a href="{{route('cart.confirmation')}}" class="checkout-steps__item"> --}}
                <a href="#" class="checkout-steps__item active">
                    <span class="checkout-steps__item-number">03</span>
                    <span class="checkout-steps__item-title">
                        <span>{{ __('shop.step_3_title') }}</span>
                        <em>{{ __('shop.step_3_subtitle') }}</em>
                    </span>
                </a>
            </div>
            <div class="order-complete">
                <div class="order-complete__message">
                    <svg width="80" height="80" viewBox="0 0 80 80" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <circle cx="40" cy="40" r="40" fill="#B9A16B" />
                        <path
                            d="M52.9743 35.7612C52.9743 35.3426 52.8069 34.9241 52.5056 34.6228L50.2288 32.346C49.9275 32.0446 49.5089 31.8772 49.0904 31.8772C48.6719 31.8772 48.2533 32.0446 47.952 32.346L36.9699 43.3449L32.048 38.4062C31.7467 38.1049 31.3281 37.9375 30.9096 37.9375C30.4911 37.9375 30.0725 38.1049 29.7712 38.4062L27.4944 40.683C27.1931 40.9844 27.0257 41.4029 27.0257 41.8214C27.0257 42.24 27.1931 42.6585 27.4944 42.9598L33.5547 49.0201L35.8315 51.2969C36.1328 51.5982 36.5513 51.7656 36.9699 51.7656C37.3884 51.7656 37.8069 51.5982 38.1083 51.2969L40.385 49.0201L52.5056 36.8996C52.8069 36.5982 52.9743 36.1797 52.9743 35.7612Z"
                            fill="white" />
                    </svg>
                    <h3>{{ __('shop.order_completed') }}</h3>
                    <p>{{ __('shop.thank_you_order_received') }}</p>

                </div>
                <div class="order-info">
                    <div class="order-info__item">
                        <label>{{ __('shop.order_number') }}</label>
                        <span>{{ $order->id }}</span>
                    </div>
                    <div class="order-info__item">
                        <label>{{ __('shop.order_date') }}</label>
                        <span>{{ $order->created_at }}</span>
                    </div>
                    <div class="order-info__item">
                        <label>{{ __('shop.order_total') }}</label>
                        <span>{{ $order->total }} EGP</span>
                    </div>
                    <div class="order-info__item">
                        <label>{{ __('shop.payment_method') }}</label>
                        <span>{{ __('shop.cash_on_delivery') }}</span>
                    </div>
                </div>
                <div class="checkout__totals-wrapper">
                    <div class="checkout__totals">
                        <h3>{{ __('shop.order_details') }}</h3>
                        <table class="checkout-cart-items">
                            <thead>
                                <tr>
                                    <th>{{ __('shop.product') }}</th>
                                    <th>{{ __('shop.subtotal') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            {{ $item->product->name }} x {{ $item->quantity }}
                                        </td>
                                        <td class="text-right">
                                            {{ $item->price }} EGP
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <table class="checkout-totals">
                            <tbody>
                                <tr>
                                    <th>{{ __('shop.subtotal') }}</th>
                                    <td class="text-right">{{ $order->subtotal }} EGP</td>
                                </tr>
                                <tr>
                                    <th>{{ __('shop.discount') }}</th>
                                    <td class="text-right">{{ $order->discount }} EGP</td>
                                </tr>
                                <tr>
                                    <th>{{ __('shop.shipping') }}</th>
                                    <td class="text-right">{{ $order->tax }} EGP</td>
                                </tr>
                                <tr class="cart-total">
                                    <th>{{ __('shop.total') }}</th>
                                    <td class="text-right">{{ $order->total  }} EGP</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </section>
    </main>
@endsection
