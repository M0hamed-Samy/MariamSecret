@extends('layouts.app')
@section('content')
    <style>
        .table> :not(caption)>tr>th {
            padding: 0.625rem 1.5rem .625rem !important;
            background-color: #6a6e51 !important;
        }

        .table>tr>td {
            padding: 0.625rem 1.5rem .625rem !important;
        }

        .table-bordered> :not(caption)>tr>th,
        .table-bordered> :not(caption)>tr>td {
            border-width: 1px 1px;
            border-color: #6a6e51;
        }

        .badge.bg-success {
            background-color: #28a745 !important;
            /* Bootstrap green */
            color: #fff !important;
        }

        .badge.bg-danger {
            background-color: #dc3545 !important;
            /* Bootstrap red */
            color: #fff !important;
        }

        .badge.bg-warning {
            background-color: #ffc107 !important;
            /* Bootstrap yellow */
            color: #212529 !important;
            /* Dark text for contrast */
        }


        .table> :not(caption)>tr>td {
            padding: .8rem 1rem !important;
        }
    </style>
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">{{ __('messages.orders') }}</h2>
            <div class="row">
                <div class="col-lg-2">
                    @include('user.account-nav')
                </div>
                <div class="col-lg-10">
                    <div class="wg-table table-all-user">
                        <div class="table-responsive">
                            @if ($orders->count() == 0)
                                <div class="row">
                                    <div class="col-md-12 text-center pt-5 bp-5">
                                        <p>No item found in your cart</p>
                                        <a href="{{ route('shop.index') }}" class="btn btn-info">Shop Now</a>
                                    </div>
                                </div>
                            @else
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 80px">OrderNo</th>
                                            <th>{{ __('messages.name') }}</th>
                                            <th class="text-center">Phone</th>
                                            <th class="text-center">Subtotal</th>
                                            <th class="text-center">Tax</th>
                                            <th class="text-center">Total</th>

                                            <th class="text-center">Status</th>
                                            <th class="text-center">Order Date</th>
                                            <th class="text-center">Items</th>
                                            <th class="text-center">Delivered On</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td class="text-center">{{ $order->id }}</td>
                                                <td class="text-center">{{ $order->name }}</td>
                                                <td class="text-center">{{ $order->phone }}</td>
                                                <td class="text-center">{{ $order->subtotal }} EGP</td>
                                                <td class="text-center">{{ $order->tax }} EGP</td>
                                                <td class="text-center">{{ $order->total }} EGP</td>
                                                <td>
                                                    @if ($order->status == 'delivered')
                                                        <span class="badge bg-success">Delivered</span>
                                                    @elseif ($order->status == 'canceled')
                                                        <span class="badge bg-danger">Canceled</span>
                                                    @else
                                                        <span class="badge bg-warning">Ordered</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $order->created_at }}</td>
                                                <td class="text-center"> {{ $order->orderItems->count() }}</td>
                                                <td class="text-center">{{ $order->delivered_date }}</td>
                                                <td class="text-center">
                                                    <a
                                                        href="{{ route('user.order.details', ['order_id' => $order->id]) }}">
                                                        <div class="list-icon-function view-icon">
                                                            <div class="item eye">
                                                                <i class="fa fa-eye"></i>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
