<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\ProductStock;
use App\Models\Quantity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Services\Invoice as ServicesInvoice;
use App\Models\Sales;

class InvoiceController extends Controller
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
    public function store(StoreSaleRequest $request)
    {
        try{
            DB::beginTransaction();
            foreach ($request->validated() as $item) {
                Sales::create($request->validated());
                DB::table('quantities')->where('product_id',$request->id)->decrement([
                    'value',$request->quantity
                ]);
            }
            DB::commit();

        }catch(\Exception $e){
            return redirect()->back()->with('toast', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $query = $request->input('query');
        return response()->json((new ServicesInvoice)->getInvoice($query));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }

    public function voidOne(Sales $sales, Request $request)
    {
        try{
            DB::beginTransaction();
            $result = $sales->update(['remarks' => $request->remarks]);
            if($result){
                DB::table('quantities')->where('product_id',$sales->product_id)->increment('value',$sales->quantity);
            }
            DB::commit();
            return response()->json($result);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json($e->getMessage());
        }

    }


    public function returnOne(Sales $sales, Request $request)
    {
        try {
            DB::beginTransaction();
            $result = $sales->update(
                ['remarks' => $request->remarks],
                ['quantity' => $request->quantity]
            );
            if($result){
                $productStockData = array_replace($sales->productStocks->toArray(), [
                    'type' => config('type.return'),
                    'quantity' => $request->quantity
                ]);
                unset($productStockData['id']);
                ProductStock::create($productStockData);
            }
            DB::commit();
            return response()->json($result);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage());
        }
    }

    public function voidAll(Request $request)
    {
        try {
            DB::beginTransaction();
            $sales = Sales::where('invoice_number',$request->invoice_number)
                            ->where('remarks',config('remarks.complete'))
                            ->get();
            if($sales){
                foreach ($sales as $sale) {
                    $productStockData = array_replace($sale->productStocks->toArray(), [
                        'type' => config('type.voided'),
                        'quantity' => $sale->quantity
                    ]);
                    unset($productStockData['id']);
                    ProductStock::create($productStockData);
                    $sale->update(['remarks' => $request->remarks]);
                }
            }
            DB::commit();
            return response()->json(true);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage());
        }
    }
}
