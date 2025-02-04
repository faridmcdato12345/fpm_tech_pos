<?php

namespace App\Http\Resources;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'product_name' => $this->product_name,
            'sku' => $this->sku,
            'category' => new CategoryResource($this->whenLoaded('categories')),
            'unit' => new UnitResource($this->whenLoaded('units')),
            'brand' => new BrandResource($this->whenLoaded('brands')),
            'product_stock' => $this->when($this->relationLoaded('product'), ProductStockResource::collection($this->productStocks)),
            'reorder_level' => $this->reorder_level,
            'total_quantity' => $this->total_quantity,
            'createdAt' => Carbon::parse($this->created_at)->isoFormat('MMMM D, YYYY')
        ];
    }
}
