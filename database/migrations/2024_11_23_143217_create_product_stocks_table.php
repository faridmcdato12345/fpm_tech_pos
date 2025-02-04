<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('barcode')->nullable()->unique();
            $table->bigInteger('quantity');
            $table->decimal('purchased_price',8,2);
            $table->decimal('selling_price',8,2)->nullable();
            $table->date('expiration_date')->nullable();
            $table->enum('type', ['add', 'reduce','adjust','return','damage','expired','restock','voided'])->default('add');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stocks');
    }
};
