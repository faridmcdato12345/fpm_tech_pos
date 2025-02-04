<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductCollection;

class InStockController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        if($request->has('name')){
            $query->where('product_name','like','%' . $request->query('name') . '%');
        }
        $limit = $request->has('query') ? $request->query('query'): 5;
        $products = $query->whereHas('quantities', function($result){
            $result->where('value','>', 0);
        })->orderBy('id','desc')->paginate(intval($limit));
        
        return inertia('Product/InStock',[
            'user' => auth()->user(),
            'queryLimit' => intval($limit),
            'queryName' => $request->has('name') ? $request->query('name') : null,
            'products' => new ProductCollection($products)
        ]);
    }
}
