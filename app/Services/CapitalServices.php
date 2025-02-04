<?php

namespace App\Services;

use App\Models\ProductStock;

class CapitalServices
{
    public function getTotalCapital()
    {
        return ProductStock::whereIn('type', ['add', 'restock', 'adjust'])
            ->selectRaw('SUM(COALESCE(quantity, 0) * CASE WHEN type = "reduce" THEN -1 ELSE 1 END * COALESCE(purchased_price, 0)) as total_investment')
            ->value('total_investment');
    }
}
