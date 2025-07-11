<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Slide;
use Illuminate\Http\Request;

class HomeController extends Controller
{


    public function index()
    {
        $slides = Slide::where('status', 1)->get()->take(3);
        $categories = Category::orderBy('name')->get();
        
        $big_sale_products = Product::whereNotNull('sale_price')
        ->whereColumn('sale_price', '<>', 'regular_price')
        ->whereRaw('sale_price <= regular_price * 0.7')
        ->inRandomOrder()
        ->take(8)
        ->get();
        
        $big_sale_ids = $big_sale_products->pluck('id');
        $sale_products = Product::whereNotNull('sale_price')
            ->where('sale_price', '<>', '')
            ->whereColumn('sale_price', '<>', 'regular_price')
            ->whereNotIn('id', $big_sale_ids) // Exclude already taken
            ->inRandomOrder()
            ->take(8)
            ->get();
        $featured_products = Product::where('featured', true)
            ->inRandomOrder()
            ->get();
        return view('index', compact('slides', 'categories', 'sale_products', 'big_sale_products', 'featured_products'));
    }

    public function contact()
    {
        return view('about.contact');
    }

    public function contact_store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email',
            'phone' => ['required', 'regex:/^01[0125][0-9]{8}$/'],
            'comment' => 'required'

        ], [
            'phone.regex' => 'The mobile number must be a valid Egyptian number (e.g., 010xxxxxxxx).',
        ]);
        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->comment = $request->comment;
        $contact->save();
        return redirect()->back()->with('success', 'Your message has been sent successfully');
    }
}
