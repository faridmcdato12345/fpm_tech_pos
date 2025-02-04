<?php

namespace App\Http\Controllers;

use App\Models\User;
use NumberFormatter;
use App\Models\Sales;
use App\Models\Quantity;
use Illuminate\Http\Request;
use App\Models\ProductDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Services\FormatNumber;
use App\Models\Product;
use App\Models\ProductStock;
use App\Services\CapitalServices;

class DashboardController extends Controller
{
    public function getData(User $user, Sales $sales, FormatNumber $format, ProductStock $productStock)
    {
        try {
            $outOfStocks = Quantity::where('value',0)->count();
            $products = Quantity::all();
            $inStocks = ProductStock::selectRaw("SUM(CASE WHEN type IN ('add','restock','adjust','return','voided') THEN quantity ELSE -quantity END) as current_stock")
                        ->value('current_stock');
            $users = $user->count();
            $returnItems = $sales->where('remarks',config('remarks.return'))->count();
            $voidedItems = $sales->where('remarks',config('remarks.voided_item'))->count();
            $sale = Sales::where('remarks',config('remarks.voided_transaction'))->get();
            $group = $sale->groupBy('invoice_number');
            $voidedTransaction = $group->count();
            $products = Product::with('productStocks')->get();
            $totalCosts = (new CapitalServices)->getTotalCapital();
            $totalSales = 0;
            $totalProfit = 0;
            $sales = Sales::with('productStocks')->where('remarks',config('remarks.complete'))->get();
            foreach ($sales as $sale) {
                $totalSales += $sale->productStocks?->selling_price * $sale->quantity;
                $totalProfit += ($sale->productStocks?->selling_price - $sale->productStocks?->purchased_price) * $sale->quantity;
            }

            $items = Sales::with('productStocks')->where('remarks',config('remarks.complete'))->get();
            foreach ($items as $item) {

            }
            $data = [
                ['value' => $format->toCurrency($totalCosts), 'label' => 'Capital', 'description' => 'Total Capital','icon' => 'capital', 'link' => route('capital.index') ],
                ['value' => $format->toCurrency($totalSales), 'label' => 'Sales', 'description' => 'Total Sales', 'icon' => 'sales','link' => route('sale.index')],
                ['value' => $format->toCurrency($totalProfit), 'label' => 'Profit', 'description' => 'Total Profit', 'icon' => 'profit', 'link' => route('profit.index')],
                ['value' => $users, 'label' => 'Users', 'description' => 'Total Number of Users','icon' => 'users','link' => route('users.index')],
                ['value' => $outOfStocks, 'label' => 'Out Of Stock', 'description' => 'Total Out of Stock Products','icon' => 'out_of_stock','link' => route('out_of_stock.index')],
                ['value' => number_format($inStocks), 'label' => 'In Stocks', 'description' => 'Total Number of In Stock Products', 'icon' => 'in_stock', 'link' => route('in_stock.index')],
                ['value' => $returnItems, 'label' => 'Returned Items', 'description' => 'Total Number of Returned Items','icon' => 'return_item'],
                ['value' => $voidedItems, 'label' => 'Voided Items', 'description' => 'Total Number of Voided Items','icon' => 'void_item', 'link' => route('voided_item.index')],
                ['value' => $voidedTransaction, 'label' => 'Voided Transactions', 'description' => 'Total Number of Voided Transactions', 'icon' => 'void_transaction', 'link' => route('voided_transaction.index')],
            ];
            //dd($data);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
