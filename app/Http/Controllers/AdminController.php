<?php

namespace App\Http\Controllers;


use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }
    //              Brands
    public function brands()
    {
        $brands = Brand::orderBy('id', 'desc')->paginate(10);
        return view('admin.brands.index', compact('brands'));
    }
    public function createBrand()
    {
        return view('admin.brands.create');
    }


    public function storeBrand(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $image = $request->file('image');
        $file_extention = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extention;
        $this->GenerateBrandThumbnailsImage($image, $file_name);
        $brand->image = $file_name;
        $brand->save();
        return redirect()->route('admin.brands.index')->with('status', 'Record has been added successfully !');;
    }
    public function GenerateBrandThumbnailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/brands');
        $img = Image::read($image->path());
        $img->cover(124, 124, "top");
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);
    }

    public function editBrand($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brands.edit', compact('brand'));
    }

    public function updateBrand(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:brands,slug,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $brand->name = $request->name;
        $brand->slug = $request->slug;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            $oldPath = public_path('uploads/brands/' . $brand->image);
            if (file_exists($oldPath) && is_file($oldPath)) {
                unlink($oldPath);
            }

            // Save new image
            $image = $request->file('image');
            $fileName = Carbon::now()->timestamp . '.' . $image->getClientOriginalExtension();
            $this->GenerateBrandThumbnailsImagetwo($image, $fileName);
            $brand->image = $fileName;
        }

        $brand->save();

        return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully.');
    }

    public function GenerateBrandThumbnailsImagetwo($image, $imageName)
    {
        $destinationPath = public_path('uploads/brands');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $img = Image::read($image->getRealPath());
        $img->resize(124, 124)->save($destinationPath . '/' . $imageName);
    }
    public function destroyBrand($id)
    {
        $brand = Brand::findOrFail($id);

        // Optionally delete image file
        $imagePath = public_path('uploads/brands/' . $brand->image);
        if (file_exists($imagePath) && is_file($imagePath)) {
            unlink($imagePath);
        }

        $brand->delete();

        return redirect()->route('admin.brands.index')->with('success', 'Brand deleted successfully.');
    }

    //              Categories

    public function categories()
    {
        $categories = Category::orderBy('id', 'desc')->paginate(10);
        return view('admin.category.index', compact('categories'));
    }
    public function createCategory()
    {
        return view('admin.category.create');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);
        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $image = $request->file('image');
        $file_extention = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extention;
        $this->GenerateCategoryThumbnailsImage($image, $file_name);
        $category->image = $file_name;
        $category->save();
        return redirect()->route('admin.category.index')->with('status', 'Record has been added successfully !');
    }

    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category.edit', compact('category'));
    }

    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $category->name = $request->name;
        $category->slug = $request->slug;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            $oldPath = public_path('uploads/categories/' . $category->image);
            if (file_exists($oldPath) && is_file($oldPath)) {
                unlink($oldPath);
            }

            // Save new image
            $image = $request->file('image');
            $fileName = Carbon::now()->timestamp . '.' . $image->getClientOriginalExtension();
            $this->GenerateCategoryThumbnailsImagetwo($image, $fileName);
            $category->image = $fileName;
        }

        $category->save();

        return redirect()->route('admin.category.index')->with('success', 'Brand updated successfully.');
    }

    public function destroyCategory($id)
    {
        $category = Category::findOrFail($id);

        // Optionally delete image file
        $imagePath = public_path('uploads/categories/' . $category->image);
        if (file_exists($imagePath) && is_file($imagePath)) {
            unlink($imagePath);
        }

        $category->delete();

        return redirect()->route('admin.category.index')->with('success', 'Category deleted successfully.');
    }

    public function GenerateCategoryThumbnailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/categories');
        $img = Image::read($image->path());
        $img->cover(124, 124, "top");
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);
    }

    public function GenerateCategoryThumbnailsImagetwo($image, $imageName)
    {
        $destinationPath = public_path('uploads/categories');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $img = Image::read($image->getRealPath());
        $img->resize(124, 124)->save($destinationPath . '/' . $imageName);
    }


    //              Products

    public function products()
    {
        $products = Product::orderBy('created_at', "DESC")->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function createProduct()
    {
        $categories = Category::Select('id', 'name')->orderBy('name')->get();
        $brands = Brand::Select('id', 'name')->orderBy('name')->get();



        return view("admin.products.create", compact('categories', 'brands'));
    }


    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug',
            'category_id' => 'required',
            'brand_id' => 'required',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg|max:2048'
        ]);
        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $current_timestamp = Carbon::now()->timestamp;
        if ($request->hasFile('image')) {
            if (File::exists(public_path('uploads/products') . '/' . $product->image)) {
                File::delete(public_path('uploads/products') . '/' . $product->image);
            }
            if (File::exists(public_path('uploads/products/thumbnails') . '/' . $product->image)) {
                File::delete(public_path('uploads/products/thumbnails') . '/' . $product->image);
            }

            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnailImage($image, $imageName);
            $product->image = $imageName;
        }
        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;
        if ($request->hasFile('images')) {
            $oldGImages = explode(",", $product->images);
            foreach ($oldGImages as $gimage) {
                if (File::exists(public_path('uploads/products') . '/' . trim($gimage))) {
                    File::delete(public_path('uploads/products') . '/' . trim($gimage));
                }
                if (File::exists(public_path('uploads/products/thumbails') . '/' . trim($gimage))) {
                    File::delete(public_path('uploads/products/thumbails') . '/' . trim($gimage));
                }
            }
            $allowedfileExtension = ['jpg', 'png', 'jpeg'];
            $files = $request->file('images');
            foreach ($files as $file) {
                $gextension = $file->getClientOriginalExtension();
                $check = in_array($gextension, $allowedfileExtension);
                if ($check) {
                    $gfilename = $current_timestamp . "-" . $counter . "." . $gextension;
                    $this->GenerateProductThumbnailImage($file, $gfilename);
                    array_push($gallery_arr, $gfilename);
                    $counter = $counter + 1;
                }
            }
            $gallery_images = implode(',', $gallery_arr);
        }
        $product->images = $gallery_images;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->save();
        return redirect()->route('admin.products.index')->with('status', 'Record has been added successfully !');
    }

    public function editProduct($id)
    {
        $product = Product::find($id);
        $categories = Category::Select('id', 'name')->orderBy('name')->get();
        $brands = Brand::Select('id', 'name')->orderBy('name')->get();



        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function updateProduct(Request $request)
    {
        $request->validate([
            'name'             => 'required',
            'slug'             => 'required|unique:products,slug,' . $request->id,
            'category_id'      => 'required',
            'brand_id'         => 'required',
            'short_description' => 'required',
            'description'      => 'required',
            'regular_price'    => 'required',
            'sale_price'       => 'required',
            'SKU'              => 'required',
            'stock_status'     => 'required',
            'featured'         => 'required',
            'quantity'         => 'required',
            'image'            => 'nullable|mimes:png,jpg,jpeg|max:2048',
            'images.*'         => 'nullable|mimes:png,jpg,jpeg'
        ]);

        $product = Product::find($request->id);
        $product->name              = $request->name;
        $product->slug              = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description       = $request->description;
        $product->regular_price     = $request->regular_price;
        $product->sale_price        = $request->sale_price;
        $product->SKU               = $request->SKU;
        $product->stock_status      = $request->stock_status;
        $product->featured          = $request->featured;
        $product->quantity          = $request->quantity;
        $current_timestamp          = Carbon::now()->timestamp;

        // ——— Main Image ———
        if ($request->hasFile('image')) {
            // delete old main image and thumbnail
            if ($product->image) {
                File::delete(public_path('uploads/products/' . $product->image));
                File::delete(public_path('uploads/products/thumbnails/' . $product->image));
            }

            $image     = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnailImage($image, $imageName);
            $product->image = $imageName;
        }

        // ——— Gallery Images ———
        $gallery_arr  = [];
        $gallery_images = '';
        $counter       = 1;

        if ($request->hasFile('images')) {
            // delete old gallery images and thumbnails
            if (!empty($product->images)) {
                $oldGImages = explode(',', $product->images);
                foreach ($oldGImages as $gimage) {
                    $gimage = trim($gimage);
                    File::delete(public_path("uploads/products/{$gimage}"));
                    File::delete(public_path("uploads/products/thumbnails/{$gimage}"));
                }
            }

            $files = $request->file('images');
            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();
                $filename  = $current_timestamp . '-' . $counter . '.' . $extension;
                $this->GenerateProductThumbnailImage($file, $filename);
                $gallery_arr[] = $filename;
                $counter++;
            }
            $gallery_images = implode(',', $gallery_arr);
        }

        $product->images      = $gallery_images;
        $product->category_id = $request->category_id;
        $product->brand_id    = $request->brand_id;
        $product->save();

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Record has been updated successfully !');
    }
    public function destroyProduct($id)
    {
        $product = Product::find($id);
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function generateProductThumbnailImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/products/thumbnails');

        // Ensure directory exists
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        $img = Image::read($image->path());
        $img->cover(124, 124, 'top');
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);

        // Save original image
        $image->move(public_path('uploads/products'), $imageName);
    }

    //              Coupons
    public function coupons()
    {
        $coupons = Coupon::orderBy('expiry_date', "DESC")->paginate(12);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function add_coupon()
    {
        return view("admin.coupons.create");
    }

    public function add_coupon_store(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'type' => 'required',
            'value' => 'required|numeric',
            'cart_value' => 'required|numeric',
            'expiry_date' => 'required|date'
        ]);
        $coupon = new Coupon();
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->save();
        return redirect()->route("admin.coupons.index")->with('status', 'Record has been added successfully !');
    }

    public function edit_coupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupons.edit', compact('coupon'));
    }
    public function update_coupon(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'type' => 'required',
            'value' => 'required|numeric',
            'cart_value' => 'required|numeric',
            'expiry_date' => 'required|date'
        ]);
        $coupon = Coupon::find($request->id);
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->save();
        return redirect()->route('admin.coupons.index')->with('status', 'Record has been updated successfully !');
    }
     public function destroy_coupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Product deleted successfully.');
    }

}
