<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdjustStockResource extends JsonResource
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
            'product' => new ProductResource($this->whenLoaded('products')),
            'from_quantity' => $this->from_quantity,
            'adjustedBy' => $this->whenLoaded('users'),
            'to_quantity' => $this->to_quantity,
            'createdAt' => Carbon::parse($this->created_at)->isoFormat('MMMM D YYYY')
        ];
    }
}
