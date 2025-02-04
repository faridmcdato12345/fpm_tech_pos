<?php

namespace Database\Factories;

use App\Models\ProductDetail;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\=ProductDetail>
 */
class ProductDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate the current date
        $currentDate = now();
        
        // Add 5 weeks to the current date
        $datePlusFiveWeeks = $currentDate->addWeeks(5);
        return [
            'barcode' => fake()->ean13(),
            'product_id' => DB::table('products')->inRandomOrder()->first()->id,
            'quantity' => fake()->numberBetween(1,200),
            'purchased_price' => fake()->randomFloat(2,5,250),
            'selling_price' => 1,
            'reorder_level' => fake()->numberBetween(5,10),
            'expiration_date' => $datePlusFiveWeeks->format('Y-m-d')
        ];
    }
    /**
     * Configure the model factory
     */
    public function configure(): static
    {
        return $this->afterMaking(function(ProductDetail $product){
            $sellingPrice = $product->purchased_price * 0.50;
            $product->selling_price = $product->purchased_price + $sellingPrice;
            $product->reorder_level = round($product->quantity / 2);
        })->afterCreating(function(ProductDetail $product){
            $sellingPrice = $product->purchased_price * 0.50;
            $product->selling_price = $product->purchased_price + $sellingPrice;
            $product->reorder_level = round($product->quantity / 2);
            $product->save();
        });
    }
}
