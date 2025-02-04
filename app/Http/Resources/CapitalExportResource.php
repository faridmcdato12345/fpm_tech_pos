<?php

namespace App\Http\Resources;

use App\Models\Quantity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CapitalExportResource extends JsonResource
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
            'product' => $this->product_name,
            'category' => $this->categories->name,
            'unit' => $this->units->name,
            'price' => $this->purchased_price,
            'quantity' => $this->quantities ? $this->quantities->value : 0,
            'extended' => $this->purchased_price *  ($this->quantities ? $this->quantities->value : 0)
        ];
    }
}
