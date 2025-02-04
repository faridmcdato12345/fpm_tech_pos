<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdjustStock extends Model
{
    use HasFactory;

    protected $guarded;


    public function users(): BelongsTo
    {
        return $this-> belongsTo(User::class,'user_id','id','users');
    }
    public function products(): BelongsTo
    {
        return $this->belongsTo(Product::class,'product_id','id','products');
    }
}
