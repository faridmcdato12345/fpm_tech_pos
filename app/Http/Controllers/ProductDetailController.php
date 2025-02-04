<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProductDetailRequest;
use App\Models\Quantity;

class ProductDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductDetailRequest $request)
    {
        try {
            $totalQuantity = 0;
            DB::beginTransaction();
            auth()->user()->products()->attach(1,$request->validated());
            $quantities = DB::table('product_user')->select('product_id','quantity')->where('product_id',$request->product_id)->sum('quantity');
            Quantity::create([
                'product_id' => $request->product_id,
                'value' => $quantities
            ]);
            DB::commit();
            return redirect()->back()->with('toast', 'Success! Product has been created.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('toast', $e->getMessage());
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductDetail $productDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductDetail $productDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductDetail $productDetail)
    {
        $productDetail->update($request->all());
        return redirect()->back()->with('toast', 'Success! Product has been edited.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductDetail $productDetail)
    {
        //
    }
}
