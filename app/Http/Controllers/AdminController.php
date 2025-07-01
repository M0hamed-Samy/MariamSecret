<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $brands = \App\Models\Brand::orderBy('id', 'desc')->paginate(10);
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
    

}
