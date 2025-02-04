<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductStockRequest;
use App\Models\AddStock;
use Illuminate\Http\Request;
use App\Models\ProductDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\AddStockResource;
use App\Models\Product;
use App\Models\ProductStock;

class AddStockController extends Controller
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
    public function store(StoreProductStockRequest $request)
    {   
        try {
            ProductStock::create(array_merge($request->validated(),['type' => config('type.restock')]));
            return redirect()->back();
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AddStock $addStock)
    {
        $stock = AddStock::with('products')->findOrFail($addStock->id);

        return response()->json(new AddStockResource($stock));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AddStock $addStock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AddStock $addStock)
    {
        try {
            DB::beginTransaction();
            $item = ProductDetail::where('product_id',$request['id'])->first();
            if($addStock->quantity > $request['quantity']){
                $item->quantity -= ($addStock->quantity - $request['quantity']);
            }else{
                $item->quantity += ($request['quantity'] - $addStock->quantity);
            }
            $item->save();
            $addStock->update(['quantity' => $request['quantity']]);
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AddStock $addStock)
    {
        try {
            DB::beginTransaction();
            $item = ProductDetail::where('product_id',$addStock->product_id)->first();
            $item->quantity -= $addStock->quantity;
            $item->save();
            $addStock->delete();
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage());
        }
    }
}
