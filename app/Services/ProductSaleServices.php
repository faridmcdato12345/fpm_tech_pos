<?php

namespace App\Services;


use App\Http\Services\Invoice;
use App\Models\ProductStock;
use App\Models\Sales;
use Illuminate\Support\Facades\DB;

class ProductSaleServices
{
    public function insert(int $productId,int $quantity,$invoiceNumber):void
    {

        DB::transaction(function() use ($productId, $quantity, $invoiceNumber) {
            $product = ProductStock::where('product_id', $productId)
                ->whereIn('type',['add','restock'])
                ->where('quantity', '>', 0)
                ->orderBy('expiration_date', 'asc')
                ->first();
            $ss = ProductStock::create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'purchased_price' => $product->purchased_price,
                'expiration_date' => $product->expiration_date,
                'selling_price' => $product->selling_price,
                'type' => config('type.reduce'),
            ]);
            Sales::create([
                'product_id' => $productId,
                'product_stock_id' => $product->id,
                'quantity' => $quantity,
                'invoice_number' => $invoiceNumber,
                'user_id' => auth()->user()->id,
                'remarks' => config('remarks.complete')
            ]);
        });

    }
}
