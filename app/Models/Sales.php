<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Sales extends Model
{
    use HasFactory;

    protected $guarded = [];


//    public function returnedItem(): HasMany
//    {
//        return $this->hasMany(ReturnItem::class);
//    }

    public function products(): BelongsTo
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product_details(): BelongsTo
    {
        return $this->belongsTo(ProductDetail::class,'product_id','id','product_details');
    }
    public function productStocks(): BelongsTo
    {
        return $this->belongsTo(ProductStock::class,'product_stock_id','id','product_stocks');
    }

    public function scopeTotalPayment(Builder $query): Builder
    {
        $query->select(DB::raw('SUM(paid) as total_paid'))->where('remarks',config('remarks.complete'));
    }
    public function groupedProductStocks()
    {
        return $this->hasMany(ProductStock::class, 'id', 'product_stock_id')
            ->select('product_stocks.*'); // Grouping by a specific column
    }
}
