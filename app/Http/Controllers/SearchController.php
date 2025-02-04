<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Quantity;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $results = Product::with(['productStocks' => function ($stockQuery) {
            $stockQuery->orderBy('created_at', 'asc') // Fetch the earliest stock
                ->limit(1); // Limit to the first record
            }])
                ->withCount(['productStocks as current_stock' => function ($stockQuery) {
                    $stockQuery->selectRaw("
                SUM(CASE
                    WHEN type IN ('add', 'restock', 'adjust', 'return') THEN quantity
                    ELSE -quantity
                END)
            ");
                }])
            ->where('product_name', 'like', '%' . $query . '%')
            ->get();
        return response()->json($results);
    }
    public function index(Request $request)
    {
        $query = $request->get('query');
        $results = Product::with('quantities')->where('id',$query)->get();
        return redirect()->back()->with('results',$results);
    }

    public function productSearch(Request $request)
    {
        $query = $request->input('query');
        $results = Product::where('product_name','LIKE',"%{$query}%")->get();
        return response()->json(ProductResource::collection($results));
    }
}
