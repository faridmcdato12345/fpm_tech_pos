<?php

namespace App\Services;

use App\Models\ProductStock;

class PerProductServices
{
    public function getQuantity(int $productId): int
    {
        return ProductStock::where('product_id',$productId)
            ->selectRaw("SUM(CASE WHEN type IN ('add','restock','adjust') THEN quantity ELSE -quantity END) as current_stock")
            ->value('current_stock');
    }
}
