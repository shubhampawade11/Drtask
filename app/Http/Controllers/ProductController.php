<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function index(Request $request)
    {

        $query = Product::with('category', 'subcategory');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhere('price', 'like', "%$search%")
                    ->orWhereHas('category', function ($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('subcategory', function ($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
            });
        }

        $products = $query->paginate(5);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();
        return view('products.create', compact('categories', 'subcategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required',
            'name' => 'required',
            'category' => 'required',
            'sub_category' => 'required',
            'price' => 'required|numeric',
            'status' => 'required|in:active,inactive',

        ]);
        $imagePath = $request->file('image')->store('images', 'public');

        $imageUrl = Storage::url($imagePath);


        $product = new Product();
        $product->image = $imageUrl;
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->category_id = $request->input('category');
        $product->subcategory_id = $request->input('sub_category');
        $product->status = $request->status;


        $product->save();

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }



    public function edit(Product $product)
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();
        return view('products.edit', compact('product', 'categories', 'subcategories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'sub_category' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);


        $imagePath = $request->file('image')->store('images', 'public');
        $imageUrl = Storage::url($imagePath);
        $product->update([
            'name' => $request->name,
            'category_id' => $request->category,
            'subcategory_id' => $request->sub_category,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imageUrl,
            $product->status = $request->status,

        ]);

        $product->save();

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }


    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();

        return response()->json(['success' => 'Product deleted successfully']);
    }
}
