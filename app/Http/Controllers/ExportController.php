<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Sales;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Services\TransformResponse;
use App\Http\Resources\ProductCollection;
use App\Http\Services\Exports\SalesExport;
use App\Http\Services\Exports\UsersExport;
use App\Http\Resources\SalesExportResource;
use App\Http\Services\Exports\ProfitExport;
use App\Http\Resources\ProfitExportResource;
use App\Http\Services\Exports\CapitalExport;
use App\Http\Services\Exports\InStockExport;
use App\Http\Resources\CapitalExportResource;
use App\Http\Services\Exports\OutOfStockExport;
use App\Http\Services\Exports\VoidedTransactionExport;

class ExportController extends Controller
{
    public function salesExcel(Request $request)
    {
        if($request->has('from') && $request->has('to')){
            if($request->query('to') === '1970-01-01'){
                $startDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->endOfDay();
                $titleDate = $request->has('from') ? 'as of ' . $request->query('from') : '';
            }else{
                $startDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d',$request->query('to'))->endOfDay();
                $titleDate = $request->has('from') ? 'as of ' . $request->query('from') . ' to ' . $request->query('to') : '';
            }
        } 
        $sales = $request->has('from') && $request->has('to')
                ? (SalesExportResource::collection(Sales::where('remarks',config('remarks.complete'))->whereBetween('created_at',[$startDate,$endDate])->get()))->resolve()
                : (SalesExportResource::collection(Sales::where('remarks',config('remarks.complete'))->get()))->resolve();
       
        $export= new SalesExport(collect($sales),Sales::class, $titleDate, 'SALES');
        return Excel::download($export,'sales.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
    public function salesPdf(Request $request)
    {
         if($request->has('from') && $request->has('to')){
            if($request->query('to') === '1970-01-01'){
                $startDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->endOfDay();
                $titleDate = $request->has('from') ? 'as of ' . $request->query('from') : '';
            }else{
                $startDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d',$request->query('to'))->endOfDay();
                $titleDate = $request->has('from') ? 'as of ' . $request->query('from') . ' to ' . $request->query('to') : '';
            }
        } 
        $sales = $request->has('from') && $request->has('to')
                ? (SalesExportResource::collection(Sales::where('remarks',config('remarks.complete'))->whereBetween('created_at',[$startDate,$endDate])->get()))->resolve()
                : (SalesExportResource::collection(Sales::where('remarks',config('remarks.complete'))->get()))->resolve();
        $export= new SalesExport(collect($sales),Sales::class, $titleDate);
        return Excel::download($export,'sales.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }
    public function voidedItemExcel(Request $request)
    {
        if($request->has('from') && $request->has('to')){
            if($request->query('to') === '1970-01-01'){
                $startDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->endOfDay();
                $titleDate = $request->has('from') ? 'as of ' . $request->query('from') : '';
            }else{
                $startDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d',$request->query('to'))->endOfDay();
                $titleDate = $request->has('from') ? 'as of ' . $request->query('from') . ' to ' . $request->query('to') : '';
            }
        } 
        $sales = $request->has('from') && $request->has('to')
                ? (SalesExportResource::collection(Sales::where('remarks',config('remarks.voided_item'))->whereBetween('created_at',[$startDate,$endDate])->get()))->resolve()
                : (SalesExportResource::collection(Sales::where('remarks',config('remarks.voided_item'))->get()))->resolve();
       
        $export= new SalesExport(collect($sales),Sales::class, $titleDate, 'VOIDED ITEMS');
        return Excel::download($export,'voided_item.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
    public function voidedItemPdf(Request $request)
    {
         if($request->has('from') && $request->has('to')){
            if($request->query('to') === '1970-01-01'){
                $startDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->endOfDay();
                $titleDate = $request->has('from') ? 'as of ' . $request->query('from') : '';
            }else{
                $startDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d',$request->query('to'))->endOfDay();
                $titleDate = $request->has('from') ? 'as of ' . $request->query('from') . ' to ' . $request->query('to') : '';
            }
        } 
        $sales = $request->has('from') && $request->has('to')
                ? (SalesExportResource::collection(Sales::where('remarks',config('remarks.voided_item'))->whereBetween('created_at',[$startDate,$endDate])->get()))->resolve()
                : (SalesExportResource::collection(Sales::where('remarks',config('remarks.voided_item'))->get()))->resolve();
        $export= new SalesExport(collect($sales),Sales::class, $titleDate, 'VOIDED ITEMS' );
        return Excel::download($export,'voided_item.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }
    public function voidedTransactionExcel(Request $request)
    {
        if($request->has('from') && $request->has('to')){
            if($request->query('to') === '1970-01-01'){
                $startDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->endOfDay();
                $titleDate = $request->has('from') ? 'as of ' . $request->query('from') : '';
            }else{
                $startDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d',$request->query('to'))->endOfDay();
                $titleDate = $request->has('from') ? 'as of ' . $request->query('from') . ' to ' . $request->query('to') : '';
            }
        } 
        $sales = $request->has('from') && $request->has('to')
                ? collect((new TransformResponse)->transform(Sales::where('remarks',config('remarks.voided_transaction'))->whereBetween('created_at',[$startDate,$endDate])->get()))
                : collect((new TransformResponse)->transform(Sales::where('remarks',config('remarks.voided_transaction'))->get()));
       
        $export= new VoidedTransactionExport($sales,Sales::class, $titleDate, 'VOIDED TRANSACTIONS');
        return Excel::download($export,'voided_transaction.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
    public function voidedTransactionPdf(Request $request)
    {
         if($request->has('from') && $request->has('to')){
            if($request->query('to') === '1970-01-01'){
                $startDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->endOfDay();
                $titleDate = $request->has('from') ? 'as of ' . $request->query('from') : '';
            }else{
                $startDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d',$request->query('to'))->endOfDay();
                $titleDate = $request->has('from') ? 'as of ' . $request->query('from') . ' to ' . $request->query('to') : '';
            }
        } 
        $sales = $request->has('from') && $request->has('to')
                ? collect((new TransformResponse)->transform(Sales::where('remarks',config('remarks.voided_transaction'))->whereBetween('created_at',[$startDate,$endDate])->get()))
                : collect((new TransformResponse)->transform(Sales::where('remarks',config('remarks.voided_transaction'))->get()));
        $export= new VoidedTransactionExport($sales,Sales::class, $titleDate,'VOIDED TRANSACTIONS');
        return Excel::download($export,'profit.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    public function capitalExcel(Request $request)
    {
        if($request->has('from') && $request->has('to')){
            if($request->query('to') === '1970-01-01'){
                $startDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->endOfDay();
                $titleDate = $request->has('from') ? 'as of ' . $request->query('from') : '';
            }else{
                $startDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d',$request->query('to'))->endOfDay();
                $titleDate = $request->has('from') ? 'as of ' . $request->query('from') . ' to ' . $request->query('to') : '';
            }
        } 
        $capital = $request->has('from') && $request->has('to')
                ? (CapitalExportResource::collection(Product::with('quantities')->whereBetween('created_at',[$startDate,$endDate])->get()))->resolve()
                : (CapitalExportResource::collection(Product::with('quantities')->get()))->resolve();
       
        $export= new CapitalExport(collect($capital),Product::class);
        return Excel::download($export,'capital.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
    public function capitalPdf(Request $request)
    {
        if($request->has('from') && $request->has('to')){
            if($request->query('to') === '1970-01-01'){
                $startDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->endOfDay();
                $titleDate = $request->has('from') ? 'as of ' . $request->query('from') : '';
            }else{
                $startDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d',$request->query('to'))->endOfDay();
                $titleDate = $request->has('from') ? 'as of ' . $request->query('from') . ' to ' . $request->query('to') : '';
            }
        } 
        $capital = $request->has('from') && $request->has('to')
                ? (CapitalExportResource::collection(Product::with('quantities')->whereBetween('created_at',[$startDate,$endDate])->get()))->resolve()
                : (CapitalExportResource::collection(Product::with('quantities')->get()))->resolve();
       
        $export= new CapitalExport(collect($capital),Product::class);
        return Excel::download($export,'capital.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }
    public function profitExcel(Request $request)
    {
        if($request->has('from') && $request->has('to')){
            if($request->query('to') === '1970-01-01'){
                $startDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->endOfDay();
                $titleDate = $request->has('from') ? 'as of ' . $request->query('from') : '';
            }else{
                $startDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d',$request->query('to'))->endOfDay();
                $titleDate = $request->has('from') ? 'as of ' . $request->query('from') . ' to ' . $request->query('to') : '';
            }
        } 
        $sales = $request->has('from') && $request->has('to')
                ? (ProfitExportResource::collection(Sales::where('remarks',config('remarks.complete'))->whereBetween('created_at',[$startDate,$endDate])->get()))->resolve()
                : (ProfitExportResource::collection(Sales::where('remarks',config('remarks.complete'))->get()))->resolve();
       
        $export= new ProfitExport(collect($sales),Sales::class, $titleDate);
        return Excel::download($export,'profit.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
    public function profitPdf(Request $request)
    {
         if($request->has('from') && $request->has('to')){
            if($request->query('to') === '1970-01-01'){
                $startDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->endOfDay();
                $titleDate = $request->has('from') ? 'as of ' . $request->query('from') : '';
            }else{
                $startDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d',$request->query('to'))->endOfDay();
                $titleDate = $request->has('from') ? 'as of ' . $request->query('from') . ' to ' . $request->query('to') : '';
            }
        } 
        $sales = $request->has('from') && $request->has('to')
                ? (ProfitExportResource::collection(Sales::where('remarks',config('remarks.complete'))->whereBetween('created_at',[$startDate,$endDate])->get()))->resolve()
                : (ProfitExportResource::collection(Sales::where('remarks',config('remarks.complete'))->get()))->resolve();
        $export= new ProfitExport(collect($sales),Sales::class, $titleDate );
        return Excel::download($export,'profit.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }
    public function userExcel(Request $request)
    {
        if($request->has('from') && $request->has('to')){
            if($request->query('to') === '1970-01-01'){
                $startDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->endOfDay();
               
            }else{
                $startDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d',$request->query('to'))->endOfDay();
                
            }
        } 
        $users = $request->has('from') && $request->has('to')
                ? (UserResource::collection(User::whereBetween('created_at',[$startDate,$endDate])->get()))->resolve()
                : (UserResource::collection(User::all()))->resolve();
       
        $export= new UsersExport(collect($users),User::class);
        return Excel::download($export,'users.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
    public function userPdf(Request $request)
    {
         if($request->has('from') && $request->has('to')){
            if($request->query('to') === '1970-01-01'){
                $startDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->endOfDay();
            }else{
                $startDate = Carbon::createFromFormat('Y-m-d',$request->query('from'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d',$request->query('to'))->endOfDay();
            }
        } 
        $users = $request->has('from') && $request->has('to')
                ? (UserResource::collection(User::whereBetween('created_at',[$startDate,$endDate])->get()))->resolve()
                : (UserResource::collection(User::all()))->resolve();
        $export= new UsersExport(collect($users),User::class );
        return Excel::download($export,'users.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }
    public function outOfStockExcel(Request $request)
    {
        $product=Product::whereHas('quantities', function($result){
            $result->where('value',0);
        })->get();
        $products = new ProductCollection($product);
        $export= new OutOfStockExport(collect($products),Product::class);
        return Excel::download($export,'out_of_stock_products.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
    public function outOfStockPdf(Request $request)
    {
        $product=Product::select('id','product_name','category_id','unit_id')->whereHas('quantities', function($result){
            $result->where('value',0);
        })->get();
        $products = new ProductCollection($product);
        $export= new OutOfStockExport(collect($products),Product::class );
        return Excel::download($export,'out_of_stock_products.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }
    public function inStockExcel(Request $request)
    {
        $product=Product::select('id','product_name','category_id','unit_id')->with('quantities')->whereHas('quantities', function($result){
            $result->where('value','>',0);
        })->get();
        $products = new ProductCollection($product);
        $export= new InStockExport(collect($products),Product::class);
        return Excel::download($export,'in_stock_products.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
    public function inStockPdf(Request $request)
    {
        $product=Product::select('id','product_name','category_id','unit_id')->with('quantities')->whereHas('quantities', function($result){
            $result->where('value','>',0);
        })->get();
        $products = new ProductCollection($product);
        $export= new InStockExport(collect($products),Product::class );
        return Excel::download($export,'in_stock_products.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }
}
