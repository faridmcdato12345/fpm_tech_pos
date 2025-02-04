<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    use HasFactory;

    protected $guarded;

    public function logo(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => asset(Storage::url($value) ?? '')
        );
    }


}
