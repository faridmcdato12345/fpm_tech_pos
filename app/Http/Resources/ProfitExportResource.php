<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfitExportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'product_name' => $this->products->product_name,
            'category' => $this->products->categories->name,
            'unit' => $this->products->units->name,
            'quantity' => $this->quantity,
            'purchased_price' => $this->products->purchased_price,
            'selling_price' => $this->products->selling_price,
            'profit' => $this->quantity * ($this->products->selling_price - $this->products->purchased_price),
        ];
    }
}
