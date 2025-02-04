<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceivedProduct extends Model
{
    use HasFactory;

    protected $guarded;

    public function products(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function suppliers(): BelongsTo
    {
        return  $this->belongsTo(Supplier::class);
    }
}
