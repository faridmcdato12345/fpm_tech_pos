<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;
use App\Http\Services\FormatNumber;

class ProfitController extends Controller
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
        $sales = $query->where('remarks',config('remarks.complete'))->paginate(intval($limit))->withQueryString();
        $augmentedSales = $sales->getCollection()->map(function ($sales) use ($format){
            return [
                'id' => $sales->id,
                'invoice_number' => $sales->invoice_number,
                'product_name' => $sales->products->product_name,
                'quantity' => $sales->quantity,
                'purchased_price' => $format->toCurrency($sales->products->purchased_price),
                'selling_price' => $format->toCurrency($sales->products->selling_price),
                'profit' => $format->toCurrency($sales->quantity * ($sales->products->selling_price - $sales->products->purchased_price))
            ];
        });
        $sales->setCollection($augmentedSales);
        return inertia('Profit/Index',[
            'queryLimit' => intval($limit),
            'queryName' => $request->has('name') ? $request->query('name') : null,
            'profits' => $sales
        ]);
    }
}
