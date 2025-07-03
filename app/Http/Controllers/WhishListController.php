<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class WhishListController extends Controller
{

    public function index(){
        $items = Cart::instance('wishlist')->content();
        return view('wishlist.index',compact('items'));
    }
    public function add(Request $request)
    {

        Cart::instance('wishlist')->add($request->id, $request->name, $request->quantity, $request->price)->associate('App\Models\Product');
        return redirect()->back();
    }
}
