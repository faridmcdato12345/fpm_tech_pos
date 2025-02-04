<?php

namespace App\Http\Controllers;

use App\Models\AdjustStock;
use App\Models\ProductStock;
use App\Services\PerProductServices;
use Illuminate\Http\Request;
use App\Models\ProductDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\AdjustStockResource;
use App\Http\Requests\StoreProductStockRequest;
use App\Models\Product;

class AdjustStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AdjustStock::query();
        if($request->has('name')){
            $query->with(['products','users'])->where('products.product_name','like','%' . $request->query('name') . '%');
        }
        $limit = $request->has('query') ? $request->query('query'): 5;
        $products = $query->with(['products','users'],'products.categories','products.brands','products.units')->paginate(intval($limit))->withQueryString();
        return inertia('Stock/Adjust/Index',[
            'user' => auth()->user(),
            'queryLimit' => intval($limit),
            'queryName' => $request->has('name') ? $request->query('name') : null,
            'products' => AdjustStockResource::collection($products)
        ]);
    }

    private function insertAdjudestStockToTable(int $productId, int $toQuantity): void
    {
        $productQuantity = (new PerProductServices)->getQuantity($productId);
        try{
            auth()->user()->adjuststocks()->create([
                'user_id' => auth()->user()->id,
                'product_id' => $productId,
                'to_quantity' => $productQuantity + $toQuantity,
                'from_quantity' => $productQuantity
            ]);

        }catch(\Exception $e){
            throw $e;
        }
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
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'to_quantity' => 'required|integer|min:0',
        ]);
        try {
            DB::beginTransaction();
            $product = Product::findOrFail($request->product_id);
            $productStock = ProductStock::where('product_id',$request->product_id)->orderBy('created_at','desc')->first();
            $quantity = (int) $request->to_quantity - $product->total_quantity;
            $this->insertAdjudestStockToTable($request->product_id,$quantity);
            $product->productStocks()->create([
                'type' => config('type.adjust'),
                'quantity' => $quantity,
                'selling_price' => $productStock->selling_price,
                'purchased_price' => $productStock->purchased_price,
                'expiration_date' => $productStock->expiration_date,
            ]);
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Something went wrong. Please try again later.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AdjustStock $adjustStock)
    {
        $stock = AdjustStock::with('products')->findOrFail($adjustStock->id);

        return response()->json(new AdjustStockResource($stock));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdjustStock $adjustStock)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdjustStock $adjustStock)
    {
        try {
            DB::beginTransaction();
            $item = ProductDetail::where('product_id',$request['id'])->first();
            $item->quantity = $request['to_quantity'];
            $item->save();
            $adjustStock->update(['to_quantity' => $request['to_quantity']]);
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdjustStock $adjustStock)
    {
        try {
            DB::beginTransaction();
            $item = ProductDetail::where('product_id',$adjustStock->product_id)->first();
            $item->quantity = $adjustStock->from_quantity;
            $item->save();
            $adjustStock->delete();
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage());
        }
    }
}
