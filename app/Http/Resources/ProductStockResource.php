<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductStockResource extends JsonResource
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
            'product_id' => $this->product_id,
            'product' => $this->when($this->relationLoaded('product'), new ProductResource($this->product)),
            'barcode' => $this->barcode,
            'quantity' => $this->quantity,
            'purchased_price' => $this->purchased_price,
            'selling_price' => $this->selling_price,
            'createdAt' => Carbon::parse($this->created_at)->isoFormat('MMMM D, YYYY'),
            'type' => $this->type
        ];
    }
}
