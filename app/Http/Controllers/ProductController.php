<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\Quantity;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\ProductDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UnitResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Product $products, Request $request)
    {
        $query = Product::query();
        if($request->has('name')){
            $query->where('product_name','like','%' . $request->query('name') . '%');
        }
        $limit = $request->has('query') ? $request->query('query'): 5;
        $products = $query->paginate(intval($limit))->withQueryString();
        return Inertia::render('Product/Index',[
            'user' => auth()->user(),
            'queryLimit' => intval($limit),
            'queryName' => $request->has('name') ? $request->query('name') : null,
            'products' => ProductResource::collection($products)
        ]);
    }
    public function create(Request $request)
    {
        return inertia('Product/Create',[
                'product' => $request->has('id') ? Product::with('users')->find($request->query('id')) : null,
                'categories' => Category::all(),
                'units' => Unit::all(),
                'brands' => Brand::all()
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $product = Product::create([
                    'product_name' => $request->product_name,
                    'is_prescription' => $request->is_prescription,
                    'unit_id' => $request->unit_id,
                    'category_id' => $request->category_id,
                    'brand_id' => $request->brand_id,
                    'user_id' => auth()->user()->id,
                    'sku' => strtoupper(str_replace(' ', '-', $request->product_name)),
                    'description' => $request->description,
                    'reorder_level' => $request->reorder_level,
                ]);
                ProductStock::create([
                    'product_id' => $product->id,
                    'quantity' => $request->quantity,
                    'purchased_price' => $request->purchased_price,
                    'selling_price' =>  $request->selling_price,
                    'expiration_date' => $request->expiration_date,
                    'type' => 'add',
                ]);
            }, 5);
            
            return back();
        } catch (\Exception $e) {
            return redirect()->back()->with('toast', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }
    public function edit(Product $product)
    {
        return inertia('Product/Edit',[
            'product' => $product,
            'productDetail' => Quantity::where('product_id',$product->id)->first(),
            'categories' => Category::all(),
            'units' => Unit::all()
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            $product->update($request->validated());
            return redirect()->back()->with('product_id',$product->id);
        } catch (\Exception $e) {
            return redirect()->back()->with('toast', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->back()->with('toast','Product has been deleted.');
    }
}
