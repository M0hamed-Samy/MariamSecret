<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Surfsidemedia\Shoppingcart\Facades\Cart as Cart;

class CartController extends Controller
{
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
}
