<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['total_quantity'];

    protected $with = ['categories','units','users','brands','productDetails','productStocks'];

    public function productStocks(): HasMany
    {
        return $this->hasMany(ProductStock::class);
    }

    public function units(): BelongsTo
    {
        return $this->belongsTo(Unit::class,'unit_id');
    }

    public function categories(): BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function receivedProducts(): HasMany
    {
        return $this->hasMany(ReceivedProduct::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sales::class);
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function quantities(): HasOne
    {
        return $this->hasOne(Quantity::class);
    }
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
    public function productDetails(): HasOne
    {
        return $this->hasOne(ProductDetail::class);
    }
    public function brands(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
    public function addstocks(): HasMany
    {
        return $this->hasMany(AddStock::class);
    }
    public function adjuststocks(): HasMany
    {
        return $this->hasMany(AdjustStock::class);
    }

    public function getTotalQuantityAttribute()
    {
        return ProductStock::where('product_id',$this->id)->selectRaw("SUM(CASE WHEN type IN ('add','restock','adjust') THEN quantity ELSE -quantity END) as current_stock")
                        ->value('current_stock');
    }
}
