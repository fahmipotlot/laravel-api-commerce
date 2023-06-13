<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Transaction::with('product')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out',
            'qty' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);
        if ($request->type == 'out') {
            if ($request->qty > $product->stock) {
                return response()->json(['message' => 'Product Stock Not Enough, stock is '.$product->stock.'!'], 422);
            }

            $product->stock = $product->stock - $request->qty;
            $product->save();
        } else {
            $product->stock = $product->stock + $request->qty;
            $product->save();
        }

        $user = Transaction::create([
            'product_id' => $request->product_id,
            'type' => $request->type,
            'qty' => $request->qty,
        ]);

        return response()->json(['message' => 'Transaction created!'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
