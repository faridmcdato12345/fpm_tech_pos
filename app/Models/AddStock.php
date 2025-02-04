<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AddStock extends Model
{
    use HasFactory;

    protected $guarded;

    public function products(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id','id','products');
    }
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
