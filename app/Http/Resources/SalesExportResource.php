<?php

namespace App\Http\Resources;

use App\Http\Services\FormatNumber;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesExportResource extends JsonResource
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
            'quantity' => $this->quantity,
            'price' => $this->products->selling_price,
            'extended' => $this->quantity * $this->products->selling_price,
        ];
    }
}
