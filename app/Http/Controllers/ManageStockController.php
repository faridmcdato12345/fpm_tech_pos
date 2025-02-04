<?php

namespace App\Http\Controllers;

use App\Http\Resources\AddStockResource;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductStockResource;
use App\Models\AddStock;
use App\Models\ProductStock;

class ManageStockController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductStock::query();
        if($request->has('name')){
            $query->with('product')->where('product.product_name','like','%' . $request->query('name') . '%');
        }
        $limit = $request->has('query') ? $request->query('query'): 5;
        $products = $query->with('product','product.categories','product.brands','product.units')->where('type',config('type.restock'))->paginate(intval($limit))->withQueryString();
        return inertia('Stock/Manage/Index',[
            'user' => auth()->user(),
            'queryLimit' => intval($limit),
            'queryName' => $request->has('name') ? $request->query('name') : null,
            'products' => ProductStockResource::collection($products)
        ]);
    }
}
