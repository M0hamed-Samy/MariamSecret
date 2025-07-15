@extends('layouts.app')
@section('content')
    @php
        use Surfsidemedia\Shoppingcart\Facades\Cart as Cart;
    @endphp
    <style>
        .cart-total th,
        .cart-total td {
            color: green;
            font-weight: bold;
            font-size: 21px !important;
        }

        .text-danger {
            color: red !important;
        }
    </style>
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="shop-checkout container">
            <h2 class="page-title">{{ __('shop.cart') }}</h2>
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
                <a href="#" class="checkout-steps__item">
                    <span class="checkout-steps__item-number">03</span>
                    <span class="checkout-steps__item-title">
                        <span>{{ __('shop.step_3_title') }}</span>
                        <em>{{ __('shop.step_3_subtitle') }}</em>
                    </span>
                </a>
            </div>
            <form name="checkout-form" action="{{ route('cart.place_order') }}" method="POST">
                {{-- <form name="checkout-form" action="" method="POST"> --}}
                @csrf
                <div class="checkout-form">
                    <div class="billing-info__wrapper">
                        <div class="row">
                            <div class="col-6">
                                <h4>{{ __('shop.shipping_details') }}</h4>
                            </div>
                            <div class="col-6">
                                {{-- @if ($address)  
                            <a href="{{route('user.account.addresses')}}" class="btn btn-info btn-sm float-right">Change Address</a> 
                            <a href="{{route('user.account.address.edit',['address_id'=>$address->id])}}" class="btn btn-warning btn-sm float-right mr-3">Edit Address</a> 
                            @endif --}}
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-md-4">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" name="name"
                                        value="{{ Auth::user()->name }}">
                                    <label for="name">{{ __('shop.full_name') }}</label>
                                    <span class="text-danger">
                                        @error('name')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" name="phone"
                                        value="{{ Auth::user()->mobile ?? old('phone') }}">
                                    <label for="phone">{{ __('shop.phone_number') }}</label>
                                    <span class="text-danger">
                                        @error('phone')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                            </div>

                            <div class="col-md-4">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" name="zip" value="{{ old('zip') }}">
                                    <label for="zip">{{ __('shop.zip_code') }}</label>
                                    <span class="text-danger">
                                        @error('zip')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form mt-3 mb-3">
                                    <label for="state">{{ __('shop.state') }}</label>
                                    <select class="form-select" name="state" id="state">
                                        <option value="">{{ __('shop.lang') }}</option>
                                        @php
                                            $governorates = [
                                                'Cairo',
                                                'Alexandria',
                                                'Giza',
                                                'Qalyubia',
                                                'Sharqia',
                                                'Dakahlia',
                                                'Beheira',
                                                'Minya',
                                                'Sohag',
                                                'Faiyum',
                                                'Assiut',
                                                'Ismailia',
                                                'Aswan',
                                                'Beni Suef',
                                                'Qena',
                                                'Damietta',
                                                'Luxor',
                                                'Port Said',
                                                'Suez',
                                                'Kafr El Sheikh',
                                                'Monufia',
                                                'Red Sea',
                                                'New Valley',
                                                'Matrouh',
                                                'North Sinai',
                                                'South Sinai',
                                                'Gharbia',
                                            ];
                                        @endphp
                                        @foreach ($governorates as $gov)
                                            <option value="{{ $gov }}"
                                                {{ old('state') == $gov ? 'selected' : '' }}>
                                                {{ $gov }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <span class="text-danger">
                                        @error('state')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" name="city" value="{{ old('city') }}">
                                    <label for="city">{{ __('shop.city') }}</label>
                                    <span class="text-danger">
                                        @error('city')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" name="address" value="{{ old('address') }}">
                                    <label for="address">{{ __('shop.address') }}</label>
                                    <span class="text-danger">
                                        @error('address')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" name="locality"
                                        value="{{ old('locality') }}">
                                    <label for="locality">{{ __('shop.locality') }}</label>
                                    <span class="text-danger">
                                        @error('locality')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" name="landmark"
                                        value="{{ old('landmark') }}">
                                    <label for="landmark">{{ __('shop.landmark') }}</label>
                                    <span class="text-danger">
                                        @error('landmark')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="checkout__totals-wrapper">
                        <div class="sticky-content">
                            <div class="checkout__totals">
                                <h3>{{ __('shop.your_order') }}</h3>

                                <table class="checkout-cart-items">
                                    <thead>
                                        <tr>
                                            <th>{{ __('shop.product') }}</th>

                                            <th class="text-right">{{ __('shop.subtotal') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach (Cart::instance('cart')->content() as $item)
                                            <tr>
                                                <td>
                                                    {{ $item->name }} x {{ $item->qty }}
                                                </td>
                                                <td class="text-right">
                                                    {{ $item->subtotal }} EGP
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if (Session::has('discounts'))
                                    <table class="checkout-totals">
                                        <tbody>
                                            <tr>
                                                <th>{{ __('shop.subtotal') }}</th>
                                                <td class="text-right">{{ Cart::instance('cart')->subtotal() }} EGP</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('shop.discount') }} {{ Session('coupon')['code'] }}</th>
                                                <td class="text-right">- {{ Session('discounts')['discount'] }} EGP</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('shop.subtotal_after_discount') }}</th>
                                                <td class="text-right">{{ Session('discounts')['subtotal'] }} EGP</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('shop.shipping') }}</th>
                                                <td class="text-right" id="shipping-cost">0 EGP</td>
                                            </tr>

                                            <tr class="cart-total">
                                                <th>{{ __('shop.total') }}</th>
                                                <td class="text-right" id="total-price">
                                                    {{ Session('discounts')['total'] }} EGP
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @else
                                    <table class="checkout-totals">
                                        <tbody>
                                            <tr>
                                                <th>{{ __('shop.subtotal') }}</th>
                                                <td class="text-right">{{ Cart::instance('cart')->subtotal() }} EGP</td>
                                            </tr>

                                            <tr>
                                                <th>{{ __('shop.shipping') }}</th>
                                                <td class="text-right" id="shipping-cost">0 EGP</td>
                                            </tr>
                                            <tr class="cart-total">
                                                <th>{{ __('shop.total') }}</th>
                                                <td class="text-right" id="total-price">
                                                    {{ Cart::instance('cart')->total() }} EGP</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                            <div class="checkout__payment-methods">
                                {{-- <div class="form-check">
                                    <input class="form-check-input form-check-input_fill" type="radio" name="mode"
                                        value="card">
                                    <label class="form-check-label" for="mode1" id="mode1">
                                        Debit or Credit Card
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input form-check-input_fill" type="radio" name="mode"
                                        value="paypal">
                                    <label class="form-check-label" for="mode2"id="mode2">
                                        Paypal
                                    </label>
                                </div> --}}
                                <div class="form-check">
                                    <input class="form-check-input form-check-input_fill" type="radio" name="mode"
                                        value="cod" checked>
                                    <label class="form-check-label" for="mode3"id="mode3">
                                        {{ __('shop.cash_on_delivery') }}
                                    </label>
                                </div>
                                <div class="policy-text">
                                    {{ __('shop.privacy_notice') }} <a href="terms.html" target="_blank">
                                        {{ __('shop.privacy_policy') }}</a>.
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary"> {{ __('shop.place_order') }}</button>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="base-total"
                    value="{{ Session('discounts')['total'] ?? Cart::instance('cart')->total() }}">

                <input type="hidden" name="shipping_tax" id="shipping-tax">


            </form>
        </section>
    </main>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const shippingRates = {
            'Cairo': 50,
            'Giza': 45,
            'Alexandria': 40,
            'Qalyubia': 42,
            'Port Said': 38,
            'Suez': 35,
            'Dakahlia': 44,
            'Sharqia': 46,
            'Gharbia': 43,
            'Monufia': 41,
            'Beheira': 39,
            'Kafr El Sheikh': 37,
            'Faiyum': 36,
            'Beni Suef': 34,
            'Minya': 33,
            'Asyut': 32,
            'Sohag': 31,
            'Qena': 30,
            'Luxor': 29,
            'Aswan': 28,
            'Red Sea': 25,
            'New Valley': 20,
            'Matrouh': 22,
            'North Sinai': 23,
            'South Sinai': 21,
            'Damietta': 40,
            'Ismailia': 42
        };

        const stateSelect = document.getElementById('state');
        const shippingOutput = document.getElementById('shipping-cost');
        const shippingInput = document.getElementById('shipping-tax');
        const totalPriceEl = document.getElementById('total-price');
        const baseTotal = parseFloat(document.getElementById('base-total').value || 0);

        stateSelect.addEventListener('change', function() {
            const selectedState = this.value;
            const shippingCost = shippingRates[selectedState] || 0;

            // Update shipping display
            shippingOutput.textContent = shippingCost + ' EGP';

            // Update hidden input for backend
            shippingInput.value = shippingCost;

            // Calculate and display new total
            const newTotal = baseTotal + shippingCost;
            totalPriceEl.textContent = newTotal + ' EGP';
        });
    });
</script>
