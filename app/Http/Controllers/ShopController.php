<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::orderBy('created_at', 'DESC')->paginate(12);
        return view('shop.index', compact("products"));
    }

    public function showProductDetails($product_slug){

        $product = Product::where('slug',$product_slug)->first();
        $rproducts= Product::where('slug','<>',$product_slug)->get()->take(8);
        return view('shop.show-details',compact('product','rproducts'));

    }
}
