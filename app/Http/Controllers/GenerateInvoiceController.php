<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvoiceResource;
use App\Http\Services\FormatNumber;
use App\Http\Services\Invoice;
use App\Models\Business;
use App\Models\Customer;
use App\Models\Tax;
use App\Services\ProductSaleServices;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GenerateInvoiceController extends Controller
{


    public function index(Request $request)
    {

        $invoiceNumber = (new Invoice)->getUniqueNumber();
        $saleService = (new ProductSaleServices);
        $taxes = Tax::all();
        $totalTax = Tax::where('is_active',true)->sum('value');
        $response = collect($request->data);
        $subTotal = 0;
        foreach ($response as $res){
            $subTotal += $res['quantity'] * $res['selling_price'];
            $saleService->insert($res['id'],$res['quantity'],$invoiceNumber);
        }

        $formattedData = InvoiceResource::collection($response);
        $formattedArray = $formattedData->toArray($request);
        $pdf = Pdf::loadView('invoice_template',[
            'invoices' => $formattedArray,
            'taxes' => $taxes,
            'subT' => $subTotal,
            'business' => Business::all(),
            'customer' => Customer::findOrFail($request->customer_id),
            'subTotal' => currency_formatter($subTotal),
            'invoiceNumber' => $invoiceNumber,
            'grandTotal' =>  currency_formatter($subTotal + ($subTotal * ($totalTax / 100))),
            'date' => Carbon::now()->format('M d, Y')
            ]);
        return $pdf->stream('document.pdf');
    }
}
