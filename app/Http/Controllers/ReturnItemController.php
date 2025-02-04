<?php

namespace App\Http\Controllers;

use App\Http\Services\FormatNumber;
use App\Models\ReturnItem;
use App\Models\Sales;
use Illuminate\Http\Request;

class ReturnItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Sales $sales, Request $request, FormatNumber $format)
    {
        $query = Sales::query();
        $queryPayload = $request->query('name');
        if($request->has('name')){
            $query->whereHas('products', function ($q) use ($queryPayload){
                return $q->where('product_name','like','%' . $queryPayload . '%');
            });
        }
        $limit = $request->has('query') ? $request->query('query'): 5;
        $sales = $query->where('remarks',config('remarks.return'))->paginate(intval($limit));

        $augmentedSales = $sales->getCollection()->map(function ($sales) use ($format){
            return [
                'id' => $sales->id,
                'invoice_number' => $sales->invoice_number,
                'product_name' => $sales->products->product_name,
                'quantity' => $sales->quantity,
                'price' => $format->toCurrency($sales->productStocks?->selling_price),
                'extended' => $format->toCurrency($sales->quantity * $sales->productStocks?->selling_price)
            ];
        });

        $sales->setCollection($augmentedSales);
        return inertia('Sale/ReturnItem/Index',[
            'queryLimit' => intval($limit),
            'queryName' => $request->has('name') ? $request->query('name') : null,
            'sales' => $sales
        ]);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ReturnItem $returnItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReturnItem $returnItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReturnItem $returnItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReturnItem $returnItem)
    {
        //
    }
}
