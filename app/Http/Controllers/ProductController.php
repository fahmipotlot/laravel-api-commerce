<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::with('category')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'name' => 'required|unique:products|max:255',
            'description' => 'required|max:255',
            'stock' => 'required|integer|min:1',
            'image' => 'required|image',
            'category_id' => 'required|exists:categories,id'
        ]);

        if ($request->hasFile('image')) {
            if ($request->has('image')) {
                $image_data = request()->file('image');
                $image_ext  = request()->file('image')->getClientOriginalExtension();
                $image_name = md5(time()).".".$image_ext;
                $image_path = 'images/product';

                $uploaded = Storage::disk('public')->putFileAs($image_path, $image_data, $image_name, ['visibility' => 'public']);
            }
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => isset($uploaded) ? $uploaded : 'bc',
            'category_id' => $request->category_id,
            'stock' => $request->stock
        ]);

        return response()->json(['message' => 'Product created!'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Product::with('category')->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $this->validate(request(), [
            'name' => 'required|max:255|unique:products,name,'. $id .'',
            'description' => 'required|max:255',
            'stock' => 'required|integer|min:1',
            'image' => 'nullable|image',
            'category_id' => 'required|exists:categories,id'
        ]);

        if ($request->hasFile('image')) {
            // delete data
            if (Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // upload data
            if ($request->has('image')) {
                $image_data = request()->file('image');
                $image_ext  = request()->file('image')->getClientOriginalExtension();
                $image_name = md5(time()).".".$image_ext;
                $image_path = 'images/product';

                $uploaded = Storage::disk('public')->putFileAs($image_path, $image_data, $image_name, ['visibility' => 'public']);

                $save = $product->update([
                    'image' => $uploaded,
                    'name' => $request->name,
                    'description' => $request->description,
                    'category_id' => $request->category_id,
                    'stock' => $request->stock
                ]);
            }
        } else {
            $save = $product->update([                
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'stock' => $request->stock
            ]);
        }

        return response()->json(['message' => 'Product updated!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        if (Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return response()->json(['message' => 'Product deleted!'], 200);
    }
}
