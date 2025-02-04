<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;
use App\Http\Services\FormatNumber;
use App\Http\Services\TransformResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class VoidedTransactionController extends Controller
{
    public function index(Request $request, FormatNumber $format){
        $query = Sales::query();
        $queryPayload = $request->query('name');
        if($request->has('name')){
            $query->whereHas('products', function ($q) use ($queryPayload){
                return $q->where('product_name','like','%' . $queryPayload . '%');
            });
        }
        $limit = $request->has('query') ? $request->query('query'): 5;
        $sales = $query->where('remarks',config('remarks.voided_transaction'))->get();
        $salesGrouped = collect((new TransformResponse)->transform($sales))->paginate(intval($limit));
        $augmentedSales = $salesGrouped->getCollection()->map(function ($sales) use ($format){
            return [
                'invoice_number' => $sales['invoice_number'],
                'total_quantity' => $sales['total_quantity'],
                'total_selling_price' => $format->toCurrency($sales['total_selling_price']),
            ];
        });
        $salesGrouped->setCollection($augmentedSales);
        return inertia('Sale/VoidedTransaction',[
            'queryLimit' => intval($limit),
            'queryName' => $request->has('name') ? $request->query('name') : null,
            'sales' => $salesGrouped
        ]);
    }
}
