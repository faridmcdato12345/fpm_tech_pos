<?php

namespace App\Http\Services;

use Illuminate\Database\Eloquent\Collection;

class TransformResponse
{
    protected $collection;
 
    public function transform(Collection $collection): array
    {
        $result = [];
        $totalSellingPrice = 0;
        $totalQuantity = 0;
        $temp = '';
        for($i = 0; $i < count($collection); $i++){
            if($collection[$i]->invoice_number === $temp){
                $totalSellingPrice = $result[$i-1]['total_selling_price'] + $collection[$i]->products->selling_price;
                $totalQuantity = $result[$i-1]['total_quantity'] + $collection[$i]->quantity;
                $result[$i-1]['total_quantity'] = $totalQuantity;
                $result[$i-1]['total_selling_price'] = $totalSellingPrice;
            }else{
                $result[] = [
                    'invoice_number' => $collection[$i]->invoice_number,
                    'total_quantity' => $collection[$i]->quantity,
                    'total_selling_price' => $collection[$i]->products->selling_price
                ];
            }
            $temp = $collection[$i]->invoice_number;
        }
        return $result;
    }
}
