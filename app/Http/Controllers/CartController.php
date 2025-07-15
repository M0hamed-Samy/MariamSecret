<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Surfsidemedia\Shoppingcart\Facades\Cart as Cart;

class CartController extends Controller
{
    // Cart
    public function index()
    {

        $items = Cart::instance('cart')->content();
        $cart = $items->count();

        $currentCartHash = $this->getCartHash();
        $lastCartHash = session()->get('cart_hash');

        // If the cart changed, remove the coupon
        if ($lastCartHash !== $currentCartHash) {
            session()->forget('coupon');
            session()->forget('discounts');
        }

        // Save the current cart hash
        session()->put('cart_hash', $currentCartHash);

        // Recalculate discounts if coupon exists
        if (session()->has('coupon')) {
            $this->calculateDiscounts();
        }

        return view('cart.index', compact('items', 'cart'));
    }

    private function getCartHash()
    {
        return md5(json_encode(Cart::instance('cart')->content()));
    }

    public function addToCart(Request $request)
    {
        Cart::instance('cart')->add($request->id, $request->name, $request->quantity, $request->price)->associate('App\Models\Product');
        session()->flash('success', 'Product is Added to Cart Successfully !');
        return redirect()->back();
    }

    public function increase_item_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }
    public function reduce_item_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }
    public function remove_item_from_cart($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return redirect()->back();
    }
    public function empty_cart()
    {
        Cart::instance('cart')->destroy();
        session()->forget('coupon');
        session()->forget('discounts');
        session()->forget('cart_hash');
        return redirect()->back();
    }

    // Coupons

    public function apply_coupon_code(Request $request)
    {

        $coupon_code = $request->coupon_code;
        if (isset($coupon_code)) {
            $coupon = Coupon::where('code', $coupon_code)->where('expiry_date', '>=', Carbon::today())->where('cart_value', '<=', Cart::instance('cart')->subtotal())->first();
            if (!$coupon) {
                return back()->with('error', 'Invalid coupon code!');
            }
            session()->put('coupon', [
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'cart_value' => $coupon->cart_value
            ]);
            $this->calculateDiscounts();
            return back()->with('status', 'Coupon code has been applied!');
        } else {
            return back()->with('error', 'Invalid coupon code!');
        }
    }

    public function calculateDiscounts()
    {
        $discount = 0;
        if (session()->has('coupon')) {
            if (session()->get('coupon')['type'] == 'fixed') {
                $discount = session()->get('coupon')['value'];
            } else {
                $discount = (Cart::instance('cart')->subtotal() * session()->get('coupon')['value']) / 100;
            }
            $subtotalAfterDiscount = Cart::instance('cart')->subtotal() - $discount;
            $taxAfterDiscount = ($subtotalAfterDiscount * config('cart.tax')) / 100;
            session()->put('discounts', [
                'discount' => number_format(floatval($discount), 2, '.', ''),
                'subtotal' => number_format(floatval(Cart::instance('cart')->subtotal() - $discount), 2, '.', ''),
                'tax' => number_format(floatval((($subtotalAfterDiscount * config('cart.tax')) / 100)), 2, '.', ''),
                'total' => number_format(floatval($subtotalAfterDiscount + $taxAfterDiscount), 2, '.', '')
            ]);
        }
    }
    public function remove_coupon_code()
    {
        session()->forget('coupon');
        session()->forget('discounts');
        session()->forget('cart_hash');
        return back()->with('status', 'Coupon has been removed!');
    }

    // Check-out
    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $user = User::select('name', 'mobile')->get();
        $address = Address::where('user_id', Auth::user()->id)
            ->where('isdefault', 1)
            ->first();
        return view('checkout.index', compact('address', 'user'));
    }

    public function place_order(Request $request)
    {
        $user_id = Auth::user()->id;
        $address = Address::where('user_id', $user_id)->where('isdefault', true)->first();

        $request->validate(
            [
                'name' => 'required|max:100',
                'phone' => ['required', 'regex:/^01[0-2,5]{1}[0-9]{8}$/'],
                'zip' => 'nullable|numeric|digits:5',
                'state' => 'required',
                'city' => 'required',
                'address' => 'required',
                'locality' => 'required',
                'landmark' => 'required'

            ],
            [
                'phone.regex' => 'Please enter a valid Egyptian phone number (e.g., 010xxxxxxxx).',
            ]
        );

        // 1. Get state
        $state = $request->state;

        // 2. Load shipping cost
        $shippingRates = config('state_taxes'); // or define inline
        $shipping = $shippingRates[$state] ?? 0;




        $address = new Address();
        $address->user_id = $user_id;
        $address->name = $request->name;
        $address->phone = $request->phone;
        $address->zip = $request->zip;
        $address->state = $request->state;
        $address->city = $request->city;
        $address->address = $request->address;
        $address->locality = $request->locality;
        $address->landmark = $request->landmark;
        $address->country = '';
        $address->isdefault = true;
        $address->save();

        $this->setAmountForCheckout();
        $order = new Order();
        $order->user_id = $user_id;
        $order->subtotal = Session::get('checkout')['subtotal'];
        $order->discount = Session::get('checkout')['discount'];
        $order->tax = $shipping;
        $order->total = Session::get('checkout')['total'] + $shipping;
        $order->name = $address->name;
        $order->phone = $address->phone;
        $order->locality = $address->locality;
        $order->address = $address->address;
        $order->city = $address->city;
        $order->state = $address->state;
        $order->country = $address->country;
        $order->landmark = $address->landmark;
        $order->zip = $address->zip;
        $order->save();

        foreach (Cart::instance('cart')->content() as $item) {
            $orderitem = new OrderItem();
            $orderitem->product_id = $item->id;
            $orderitem->order_id = $order->id;
            $orderitem->price = $item->price;
            $orderitem->quantity = $item->qty;
            $orderitem->save();
        }
        if ($request->mode == "card") {
            //
        } else if ($request->mode == "paypal") {
            //
        } else if ($request->mode == "cod") {
            $transaction = new Transaction();
            $transaction->user_id = $user_id;
            $transaction->order_id = $order->id;
            $transaction->mode = $request->mode;
            $transaction->status = "pending";
            $transaction->save();
        }



        Cart::instance('cart')->destroy();
        Session::forget('checkout');
        Session::forget('coupon');
        Session::forget('discounts');
        Session::put('order_id', $order->id);
        return redirect()->route('cart.confirmation');
    }
    public function setAmountForCheckout()
    {
        if (!Cart::instance('cart')->count() > 0) {
            Session::forget('checkout');
            return;
        }
        if (Session::has('coupon')) {
            Session::put('checkout', [
                'discount' =>  Session::get('discounts')['discount'],
                'subtotal' =>   Session::get('discounts')['subtotal'],
                'tax' =>   Session::get('discounts')['tax'],
                'total' =>   Session::get('discounts')['total']
            ]);
        } else {
            Session::put('checkout', [
                'discount' => 0,
                'subtotal' => Cart::instance('cart')->subtotal(),
                'tax' => Cart::instance('cart')->tax(),
                'total' => Cart::instance('cart')->total()
            ]);
        }
    }
    public function order_confirmation()
    {
        if (Session::has('order_id')) {

            $order = Order::findOrFail(Session::get('order_id'));
            return view('checkout.confirmation', compact('order'));
        }
        return redirect()->route('cart.index');
    }
}
