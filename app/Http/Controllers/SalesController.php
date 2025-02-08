<?php

namespace App\Http\Controllers;

use App\Http\Resources\SaleResource;
use App\Http\Services\FormatNumber;
use App\Http\Services\Invoice;
use App\Http\Services\WeeklyEarning;
use App\Models\Customer;
use App\Models\ProductStock;
use App\Models\Sales;
use App\Models\User;
use App\Services\ProductSaleServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Sales $sales, Request $request, FormatNumber $format)
    {
        $query = Sales::query();
        //dd($query);
        $queryPayload = $request->query('name');
        if($request->has('name')){
            $query->whereHas('products', function ($q) use ($queryPayload){
                return $q->where('product_name','like','%' . $queryPayload . '%');
            });
        }
        $limit = $request->has('query') ? $request->query('query'): 5;

        //$sales = $query->where('remarks',config('remarks.complete'))->paginate(intval($limit));

        $sales = $query->join('product_stocks', 'sales.product_stock_id', '=', 'product_stocks.id')
            ->where('remarks', config('remarks.complete'))
            ->select(
                'sales.invoice_number','sales.created_at','sales.remarks','sales.user_id','sales.id',
                DB::raw('SUM(product_stocks.selling_price * sales.quantity) as grand_total'),

            )
            ->groupBy('sales.invoice_number')
            ->orderBy('sales.created_at','desc')
            ->paginate(intval($limit));
        $sales->getCollection()->transform(function ($sale) use ($format) {
            $sale->products = Sales::with('productStocks.product')->where('invoice_number',$sale->invoice_number)->get();
            $sale->createdAt = Carbon::parse($sale->created_at)->format('M d, Y');
            $sale->grand_total = $format->toCurrency($sale->grand_total);
            $sale->biller = User::where('id',$sale->user_id)->first();
            return $sale;
        });
//        $augmentedSales = $sales->getCollection()->map(function ($sales) use ($format){
//            return [
//                'id' => $sales->id,
//                'invoice_number' => $sales->invoice_number,
//                'product_name' => $sales->products->product_name,
//                'quantity' => $sales->quantity,
//                'price' => $format->toCurrency($sales->productStocks?->selling_price),
//                'extended' => $format->toCurrency($sales->quantity * $sales->productStocks?->selling_price)
//            ];
//        });
//        $sales->setCollection($augmentedSales);
        return inertia('Sale/Index',[
            'queryLimit' => intval($limit),
            'queryName' => $request->has('name') ? $request->query('name') : null,
            'sales' => $sales,
            'customers' => Customer::all(),
            'addressList' => json_decode(Storage::get('address_lists.json'), true)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $items = collect($request->input('fields'));
        $invoiceNumber = (new Invoice)->getUniqueNumber();
        $saleService = (new ProductSaleServices);
        try {
            DB::beginTransaction();
            foreach ($items as $item) {

                $saleService->insert($item['id'],$item['quantity'],$invoiceNumber);
            }
            DB::commit();
            return redirect()->back()->with('toast','Transaction complete');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sales $sales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sales $sales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sales $sales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sales $sales)
    {
        //
    }

    public function dashboard(Request $request): Response
    {

        $now = Carbon::now('Asia/Manila');

        $selectedDate = $request->input('date', $now->toDateString()); // Default to today if no date is selected
        dd($selectedDate);
        // Convert the selected date to Carbon instance
        $selectedDateCarbon = Carbon::parse($selectedDate)->startOfDay();

        $totalSalesPerMonth = Sales::select(
            DB::raw('strftime("%Y", sales.created_at) as year'),
            DB::raw('strftime("%m", sales.created_at) as month'),
            DB::raw('SUM(sales.quantity * product_stocks.selling_price) as total_sales')
        )
        ->join('product_stocks', 'sales.product_stock_id', '=', 'product_stocks.id')
        ->groupBy('year', 'month')
        ->orderBy('year', 'asc') // Order by year in ascending order
        ->orderBy('month', 'asc') // Order by month in ascending order
        ->get();
        $totalSalesAnalytics = $totalSalesPerMonth->pluck('total_sales')->toArray();
        $months = DB::table('sales')
                    ->selectRaw('strftime("%m",created_at) as month,strftime("%Y", created_at) as year')
                    ->distinct()
                    ->orderBy('month', 'asc')
                    ->get()
                    ->map(function ($item) {
                        $item->month_name = date('F', mktime(0, 0, 0, $item->month, 10)); // Convert month number to month name
                        return $item;
                    });
        $topSellers = Sales::with('products')->select('product_id', DB::raw('SUM(quantity) as total_quantity_sold'))
                    ->whereDate('created_at', $selectedDateCarbon)
                    ->groupBy('product_id')
                    ->orderBy('total_quantity_sold', 'desc')
                    ->take(5)
                    ->get();
        $recentSales = Sales::with('products')
                    ->whereDate('created_at', $selectedDateCarbon)
                    ->orderBy('created_at', 'asc')
                    ->take(5)
                    ->get();
        $totalSales = Sales::count();
        $numberSalesToday = Sales::whereDate('created_at',$now)->orderBy('created_at','desc')->count();
        return Inertia::render('Sale/Dashboard',[
            'user_name' => auth()->user()->fullname,
            'months' => $months,
            'topSellers' => $topSellers,
            'recentSales' => $recentSales,
            'percentage' => round((new WeeklyEarning)->getEarnings()['percentage_change'],2),
            'weekEarnings' => (new FormatNumber)->toCurrency((new WeeklyEarning)->getEarnings()['current_week_earnings']),
            'numberSalesToday' => $numberSalesToday,
            'totalSalesNumber' => $totalSales,
            'totalSalesAnalytics' => $totalSalesAnalytics,
        ]);
    }


}
