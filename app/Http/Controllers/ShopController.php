<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $order = $request->query('order') ? $request->query('order') : -1;
        $o_coloumn = "";
        $o_order = "";
        $f_brands = $request->query('brands');
        $f_categories = $request->query('categories');
        switch ($order) {
            case 1:
                $o_coloumn = "created_at";
                $o_order = "DESC";
                break;
            case 2:
                $o_coloumn = "created_at";
                $o_order = "ASC";
                break;
            case 3:
                $o_coloumn = "sale_price";
                $o_order = "ASC";
                break;
            case 4:
                $o_coloumn = "sale_price";
                $o_order = "DESC";
                break;
            default:
                $o_coloumn = "id";
                $o_order = "DESC";
        }

        $size = $request->query('size') ? $request->query('size') : 12;

        $products = Product::where(function ($query) use ($f_brands) {
            if (!empty($f_brands)) {
                $query->whereIn('brand_id', explode(',', $f_brands));
            }
        })->where(function ($query) use ($f_categories) {
            if (!empty($f_categories)) {
                $query->whereIn('category_id', explode(',', $f_categories));
            }
        })
            ->orderBy($o_coloumn, $o_order)->paginate($size);
        $categories = Category::orderBy("name", "ASC")->get();
        $brands = Brand::orderBy("name", "ASC")->get();

        return view('shop.index', compact("products", "size", "order", "brands", "categories", "f_brands", "f_categories"));
    }

    public function showProductDetails($product_slug)
    {
        

        $product = Product::where('slug', $product_slug)->first();
        $rproducts = Product::where('slug', '<>', $product_slug)->get()->take(8);
        return view('shop.show-details', compact('product', 'rproducts'));
    }
}
