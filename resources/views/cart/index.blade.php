@extends('layouts.app')
@php
    use Surfsidemedia\Shoppingcart\Facades\Cart as Cart;
@endphp
@section('content')
    <style>
        .text-success {
            color: green !important;
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
                <a href="javascript:void(0)" class="checkout-steps__item active">
                    <span class="checkout-steps__item-number">01</span>
                    <span class="checkout-steps__item-title">
                        <span>{{ __('shop.step_1_title') }}</span>
                        <em>{{ __('shop.step_1_subtitle') }}</em>
                    </span>
                </a>
                <a href="javascript:void(0)" class="checkout-steps__item">
                    <span class="checkout-steps__item-number">02</span>
                    <span class="checkout-steps__item-title">
                        <span>{{ __('shop.step_2_title') }}</span>
                        <em>{{ __('shop.step_2_subtitle') }}</em>
                    </span>
                </a>
                <a href="javascript:void(0)" class="checkout-steps__item">
                    <span class="checkout-steps__item-number">03</span>
                    <span class="checkout-steps__item-title">
                        <span>{{ __('shop.step_3_title') }}</span>
                        <em>{{ __('shop.step_3_subtitle') }}</em>
                    </span>
                </a>
            </div>

            <div class="shopping-cart">
                @if ($items->count() > 0)
                    <div class="cart-table__wrapper">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>{{ __('shop.product') }}</th>
                                    <th></th>
                                    <th>{{ __('shop.price') }}</th>
                                    <th>{{ __('shop.quantity') }}</th>
                                    <th>{{ __('shop.subtotal') }}</th>
                                    <th></th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>
                                            <div class="shopping-cart__product-item">
                                                <img loading="lazy"
                                                    src="{{ asset('uploads/products/' . $item->model->image) }}"
                                                    width="120" height="120" alt="{{ $item->name }}" />
                                            </div>
                                        </td>
                                        <td>
                                            <div class="shopping-cart__product-item__detail">
                                                <h4>{{ $item->name }}</h4>
                                                @if (!empty($item->size))
                                                    <ul class="shopping-cart__product-item__options">
                                                        <li>Size: {{ $item->size }} ml</li>
                                                    </ul>
                                                @endif


                                            </div>
                                        </td>
                                        <td>
                                            <span class="shopping-cart__product-price">{{ $item->price }}</span>
                                        </td>
                                        <td>
                                            <div class="qty-control position-relative">
                                                <input type="number" name="quantity" value="{{ $item->qty }}"
                                                    min="1" class="qty-control__number text-center">
                                                <form method="post"
                                                    action="{{ route('cart.reduce.qty', ['rowId' => $item->rowId]) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="qty-control__reduce">-</div>
                                                </form>

                                                <form method="post"
                                                    action="{{ route('cart.increase.qty', ['rowId' => $item->rowId]) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="qty-control__increase">+</div>
                                                </form>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="shopping-cart__subtotal">{{ $item->subTotal() }} EGP</span>
                                        </td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('cart.remove', ['rowId' => $item->rowId]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <a href="javascript:void(0)" class="remove-cart">
                                                    <svg width="10" height="10" viewBox="0 0 10 10" fill="#767676"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M0.259435 8.85506L9.11449 0L10 0.885506L1.14494 9.74056L0.259435 8.85506Z" />


                                                        <path
                                                            d="M0.885506 0.0889838L9.74057 8.94404L8.85506 9.82955L0 0.97449L0.885506 0.0889838Z" />
                                                    </svg>
                                                </a>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach


                            </tbody>
                        </table>
                        <div class="cart-table-footer">
                            @if (!Session::has('coupon'))
                                <form action="{{ route('cart.coupon.apply') }}" method="post"
                                    class="position-relative bg-body">
                                    @csrf
                                    <input class="form-control" type="text" name="coupon_code" placeholder="{{ __('shop.coupon_code') }}"
                                        value="">
                                    <input class="btn-link fw-medium position-absolute top-0 end-0 h-100 px-4"
                                        type="submit" value="{{ __('shop.apply_coupon') }}">
                                </form>
                            @else
                                <form action="{{ route('cart.coupon.remove') }}" method="post"
                                    class="position-relative bg-body">
                                    @csrf
                                    @method('DELETE')
                                    <input class="form-control" type="text" name="coupon_code" placeholder="Coupon Code"
                                        value=" @if (Session::has('coupon')) {{ Session::get('coupon')['code'] }} Applied! @endif">
                                    <input class="btn-link fw-medium position-absolute top-0 end-0 h-100 px-4"
                                        type="submit" value="{{ __('shop.remove_coupon') }}">
                                </form>
                            @endif

                            <form class="position-relative bg-body" method="POST" action="{{ route('cart.empty') }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-light" type="submit">{{ __('shop.clear_cart') }}</button>
                            </form>
                        </div>
                        <div>
                            @if (Session::has('success'))
                                <p class="text-success">{{ Session::get('success') }}</p>
                            @elseif (Session::has('error'))
                                <p class="text-danger">{{ Session::get('error') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="shopping-cart__totals-wrapper">
                        <div class="sticky-content">
                            <div class="shopping-cart__totals">
                                <h3>{{ __('shop.cart_totals') }}</h3>
                                @if (Session::has('discounts'))
                                    <table class="cart-totals">
                                        <tbody>
                                            <tr>
                                                <th>{{ __('shop.subtotal') }}</th>
                                                <td class="text-right">{{ Cart::instance('cart')->subtotal() }} EGP</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('shop.discount') }} {{ Session('coupon')['code'] }}</th>
                                                <td class="text-right">-{{ Session('discounts')['discount'] }} EGP</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('shop.subtotal_after_discount') }}</th>
                                                <td class="text-right">{{ Session('discounts')['subtotal'] }} EGP</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('shop.shipping') }}</th>
                                                <td class="text-right">...</td>
                                            </tr>
                                            {{-- <tr>
                <th>{{ __('shop.vat') }}</th>
                <td class="text-right">{{ Session('discounts')['tax'] }} EGP</td>
            </tr> --}}
                                            <tr class="cart-total">
                                                <th>{{ __('shop.total') }}</th>
                                                <td class="text-right">{{ Session('discounts')['total'] }} EGP</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @else
                                    <table class="cart-totals">
                                        <tbody>
                                            <tr>
                                                <th>{{ __('shop.subtotal') }}</th>
                                                <td class="text-right">{{ Cart::instance('cart')->subtotal() }} EGP</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('shop.shipping') }}</th>
                                                <td class="text-right">...</td>
                                            </tr>
                                            {{-- <tr>
                <th>{{ __('shop.vat') }}</th>
                <td class="text-right">{{ Cart::instance('cart')->tax() }} EGP</td>
            </tr> --}}
                                            <tr class="cart-total">
                                                <th>{{ __('shop.total') }}</th>
                                                <td class="text-right">{{ Cart::instance('cart')->total() }} EGP</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif


                            </div>
                            <div class="mobile_fixed-btn_wrapper">
                                <div class="button-wrapper container">
                                    <a href="{{ route('cart.checkout') }}" class="btn btn-primary btn-checkout">{{ __('shop.proceed_to_checkout') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-12 text-center pt-5 bp-5">
                            <p>{{ __('shop.no_cart_items') }}</p>
                            <a href="{{ route('shop.index') }}" class="btn btn-info">{{ __('messages.shop_now') }}</a>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection
@push('scripts')
    <script>
        $(function() {
            $('.qty-control__increase').on("click", function() {
                $(this).closest('form').submit();
            });
            $('.qty-control__reduce').on("click", function() {
                $(this).closest('form').submit();
            });
            $('.remove-cart').on("click", function() {
                $(this).closest('form').submit();
            });
        })
    </script>
@endpush
