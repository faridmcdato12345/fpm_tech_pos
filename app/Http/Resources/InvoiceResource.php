<?php

namespace App\Http\Resources;

use App\Http\Services\FormatNumber;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'],
            'product' => new ProductResource($this['id']),
            'name' => $this['name'],
            'quantity' => $this['quantity'],
            'selling_price' => currency_formatter($this['selling_price']),
            'total' => currency_formatter($this['quantity'] * $this['selling_price'])
        ];
    }
}
