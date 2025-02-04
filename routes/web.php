<?php

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Sales;
use App\Models\ProductDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Services\PrintService;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;
use App\Http\Controllers\CostController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ProfitController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InStockController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AddStockController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscountController;

use App\Http\Controllers\QuantityController;
use App\Http\Controllers\SupplierController;
use App\Http\Resources\ProfitExportResource;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GetUserPinController;
use App\Http\Controllers\OutOfStockController;
use App\Http\Controllers\ReturnItemController;
use App\Http\Controllers\VoidedItemController;
use App\Http\Controllers\AdjustStockController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ManageStockController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\ReceivedProductController;
use App\Http\Controllers\VoidedTransactionController;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return Inertia::render('Auth/Login');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('/add_stock', AddStockController::class);
    Route::resource('/adjust_stock', AdjustStockController::class);
    Route::resource('/product', ProductController::class);
    Route::resource('/product_detail', ProductDetailController::class);
    Route::resource('/sale', SalesController::class);
    Route::get('/sales/dashboard',[SalesController::class,'dashboard'])->name('sales.dashboard');
    //Route::resource('/return_item', ReturnItemController::class)->only(['index','store','show','update','destroy']);
    Route::resource('/unit', UnitController::class);
    Route::resource('/category', CategoryController::class);
    Route::resource('/brand', BrandController::class);
    Route::resource('/received_product', ReceivedProductController::class)->only(['index','store','show','update','destroy']);
    Route::resource('/supplier', SupplierController::class)->only(['index','store','show','update','destroy']);
    Route::resource('/quantity', QuantityController::class)->only(['store','update']);
    Route::resource('/pos', PosController::class);
    Route::get('/voided_item',[VoidedItemController::class,'index'])->name('voided_item.index');
    Route::get('/voided_transaction',[VoidedTransactionController::class,'index'])->name('voided_transaction.index');

    Route::resource('/company',CompanyController::class)->except(['show']);
    Route::get('/search', [SearchController::class, 'search'])->name('product.search');
    Route::get('/search_product',[SearchController::class,'productSearch'])->name('search.product');
    Route::get('/get_product',[SearchController::class,'index']);
    Route::get('/discount', [DiscountController::class,'index']);
    Route::get('/get_transaction', [InvoiceController::class, 'show']);
    Route::patch('/void_item/{sales}', [InvoiceController::class, 'voidOne']);
    Route::get('/return_item/',[ReturnItemController::class, 'index'])->name('return.item.index');
    Route::patch('/return_item/{sales}',[InvoiceController::class, 'returnOne']);
    Route::patch('/void_invoice',[InvoiceController::class,'voidAll']);
    Route::post('/get/pin',GetUserPinController::class);
    Route::get('/costs', [CostController::class, 'getTotalCost']);
    Route::get('/profits',[ProfitController::class,'index']);
    Route::resource('/out_of_stock',OutOfStockController::class);
    Route::get('/in_stock',[InStockController::class,'index'])->name('in_stock.index');
    Route::resource('/users',UserController::class);
    Route::get('/dashboard/data',[DashboardController::class,'getData']);
    Route::get('/profit',[ProfileController::class,'index'])->name('profit.index');
    Route::get('/capital',[CostController::class, 'index'])->name('capital.index');
    Route::get('/profit',[ProfitController::class, 'index'])->name('profit.index');
    Route::get('/stock/manage', [ManageStockController::class,'index'])->name('stock.manage.index');

    Route::post('/generate-invoice', [\App\Http\Controllers\GenerateInvoiceController::class,'index'])->name('generate.invoice');
    Route::resource('/customer',CustomerController::class);
    Route::resource('/ai',AiController::class);
    Route::post('/ai/run-prediction',[AiController::class,'runPrediction'])->name('ai.run.prediction');

});

Route::resource('/business',BusinessController::class);
Route::get('/test', function(){
    $totalSales = 0;
    $sales = Sales::with(['product_details:selling_price'])->where('remarks',config('remarks.complete'))->get();
    return $sales;
});




Route::get('/test/printer', function(){
    (new PrintService)->usePrint("EPSON TM-U220");
});

Route::get('/test/address', function() {
    $json = Storage::get('address_lists.json');
    dd(json_decode($json, true));
});
Route::get('/test/path',function(){
    dd(base_path('app/ai_scripts/python/predict.py'));
});
require __DIR__.'/export.php';
require __DIR__.'/auth.php';
