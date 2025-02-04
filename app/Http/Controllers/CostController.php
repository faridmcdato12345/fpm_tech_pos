<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Quantity;
use Illuminate\Http\Request;
use App\Http\Services\FormatNumber;

class CostController extends Controller
{
    public function index(Request $request,FormatNumber $format)
    {
        $query = Product::query();
        $queryPayload = $request->query('name');
        if($request->has('name')){
            $query->whereHas('products', function ($q) use ($queryPayload){
                return $q->where('product_name','like','%' . $queryPayload . '%');
            });
        }
        $limit = $request->has('query') ? $request->query('query'): 5;
        $products = $query->paginate(intval($limit))->withQueryString();
        $augmentedSales = $products->getCollection()->map(function ($product) use ($format){
            return [
                'id' => $product->id,
                'product_name' => $product->product_name,
                'category' => $product->categories->name,
                'unit' => $product->units->name,
                'quantity' => $product->quantity,
                'purchased_price' => $format->toCurrency($product->purchased_price),
                'extended' => $format->toCurrency($product->quantity * $product->purchased_price)
            ];
        });
        $products->setCollection($augmentedSales);
        
        return inertia('Capital/Index',[
            'queryLimit' => intval($limit),
            'queryName' => $request->has('name') ? $request->query('name') : null,
            'capitals' => $products
        ]);
    }
}
