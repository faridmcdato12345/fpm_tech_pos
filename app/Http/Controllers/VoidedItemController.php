<?php

namespace App\Http\Controllers;

use App\Http\Services\FormatNumber;
use App\Models\Sales;
use Illuminate\Http\Request;

class VoidedItemController extends Controller
{
    public function index(Request $request, FormatNumber $format)
    {
        $query = Sales::query();
        $queryPayload = $request->query('name');
        if($request->has('name')){
            $query->whereHas('products', function ($q) use ($queryPayload){
                return $q->where('product_name','like','%' . $queryPayload . '%');
            });
        }
        $limit = $request->has('query') ? $request->query('query'): 5;
        $sales = $query->where('remarks',config('remarks.voided_item'))->paginate(intval($limit));
        
        $augmentedSales = $sales->getCollection()->map(function ($sales) use ($format){
            return [
                'id' => $sales->id,
                'invoice_number' => $sales->invoice_number,
                'product_name' => $sales->products->product_name,
                'quantity' => $sales->quantity,
                'price' => $format->toCurrency($sales->products->selling_price),
                'extended' => $format->toCurrency($sales->quantity * $sales->products->selling_price)
            ];
        });
         
        $sales->setCollection($augmentedSales);
        return inertia('Sale/Voided',[
            'queryLimit' => intval($limit),
            'queryName' => $request->has('name') ? $request->query('name') : null,
            'sales' => $sales
        ]);
    }
}
