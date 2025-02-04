<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\User;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductStock;
use App\Models\ProductDetail;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        $brands = Brand::all();
        $units = Unit::all();
        $users = User::all();

        // Create 100 random products
        foreach (range(1, 50) as $index) {
            // Create a product
            $productName = fake()->unique()->word . ' ' . fake()->randomElement(['500mg', '250mg', '100mg']);
             // Generate SKU from product name
            $sku = strtoupper(str_replace(' ', '-', $productName)) . '-' . fake()->unique()->numberBetween(100, 999);
            $product = Product::create([
                'user_id' => $users->random()->id,
                'unit_id' => $units->random()->id,
                'category_id' => $categories->random()->id,
                'brand_id' => $brands->random()->id,
                'product_name' => $productName,
                'is_prescription' => fake()->boolean(20), // 20% chance of being prescription-only
                'sku' => $sku,
                'reorder_level' => fake()->numberBetween(10, 30),
                'description' => fake()->sentence,
            ]);
            // Create product details
            $initialQuantity = fake()->numberBetween(50, 500);
            $purchasePrice = fake()->randomFloat(2, 5, 50);
            $sellingPrice = fake()->randomFloat(2, $purchasePrice + 1, $purchasePrice + 20);
            // Add initial stock
            ProductStock::create([
                'product_id' => $product->id,
                'quantity' => $initialQuantity,
                'purchased_price' => $purchasePrice,
                'selling_price' =>  $sellingPrice,
                'expiration_date' => fake()->dateTimeBetween('+1 month', '+1 year'),
                'type' => 'add',
            ]);
        }
    }
}
