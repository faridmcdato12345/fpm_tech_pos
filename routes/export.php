<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;


Route::middleware('auth')->group(function () {
    Route::get('/capital/pdf', [ExportController::class,'capitalPdf'])->name('capital.pdf');
    Route::get('/capital/excel', [ExportController::class,'capitalExcel'])->name('capital.excel');
    Route::get('/sales/pdf', [ExportController::class,'salesPdf'])->name('sales.pdf');
    Route::get('/sales/excel', [ExportController::class,'salesExcel'])->name('sales.excel');
    Route::get('/profit/pdf', [ExportController::class,'profitPdf'])->name('profit.pdf');
    Route::get('/profit/excel', [ExportController::class,'profitExcel'])->name('profit.excel');
    Route::get('/user/pdf', [ExportController::class,'userPdf'])->name('users.pdf');
    Route::get('/user/excel', [ExportController::class,'userExcel'])->name('users.excel');
    Route::get('/outofstock/pdf', [ExportController::class,'outOfStockPdf'])->name('out_of_stock.pdf');
    Route::get('/outofstock/excel', [ExportController::class,'outOfStockExcel'])->name('out_of_stock.excel');
    Route::get('/instock/pdf', [ExportController::class,'inStockPdf'])->name('in_stock.pdf');
    Route::get('/instock/excel', [ExportController::class,'inStockExcel'])->name('in_stock.excel');
    Route::get('/voided_item/pdf', [ExportController::class,'voidedItemPdf'])->name('voided_item.pdf');
    Route::get('/voided_item/excel', [ExportController::class,'voidedItemExcel'])->name('voided_item.excel');
    Route::get('/voided_transaction/pdf', [ExportController::class,'voidedTransactionPdf'])->name('voided_transaction.pdf');
    Route::get('/voided_transaction/excel', [ExportController::class,'voidedTransactionExcel'])->name('voided_transaction.excel');
});
