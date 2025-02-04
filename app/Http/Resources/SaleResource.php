<?php

namespace App\Http\Resources;

use App\Http\Services\FormatNumber;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $format = (new FormatNumber);
        return [
            'id' => $this->id,
            'products' => $this->products,
            'invoice_number' => $this->invoice_number,
            'remarks' => $this->remarks,
            'biller' => User::where('id',$this->user_id)->first(),
            'grandTotal' => $format->toCurrency($this->grand_total),
            'createdAt' => Carbon::parse($this->created_at)->format('M d, Y')
        ];
    }
}
