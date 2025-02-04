<?php

namespace App\Http\Services;

use App\Models\Sales;

class Invoice {

    
    public function getUniqueNumber()
    {
        $randomNumber = rand(1000000000, 9999999999);
        $invoiceNumber = Sales::where('invoice_number',$randomNumber)->first();
        if($invoiceNumber){
            return $this->getUniqueNumber();
        }
        return $randomNumber;
    }
    public function getInvoice($invoice)
    {
        $sales = Sales::with('products')
        ->where('invoice_number',$invoice)
        ->where('remarks',config('remarks.complete'))
        ->get();
        if($sales->isEmpty()){
            return [];
        }
        return $sales;
    }
}