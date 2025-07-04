<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class WhishListController extends Controller
{

    public function index()
    {
        $items = Cart::instance('wishlist')->content();
        return view('wishlist.index', compact('items'));
    }
    public function add(Request $request)
    {

        Cart::instance('wishlist')->add($request->id, $request->name, $request->quantity, $request->price)->associate('App\Models\Product');
        return redirect()->back();
    }
    public function remove_item_from_wishlist($rowId)
    {
        Cart::instance('wishlist')->remove($rowId);
        return redirect()->back();
    }
    public function empty_wishlist()
    {
        Cart::instance('wishlist')->destroy();
        return redirect()->back();
    }
    public function move_to_cart($rowId)
    {
        $item = Cart::instance('wishlist')->get($rowId);
        Cart::instance('wishlist')->remove($rowId);
        Cart::instance('cart')->add($item->id, $item->name, 1, $item->price)->associate('App\Models\Product');
        return redirect()->back();
    }
}
