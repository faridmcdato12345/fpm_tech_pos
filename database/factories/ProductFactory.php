<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\=Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => DB::table('categories')->inRandomOrder()->first()->id,
            'unit_id' => DB::table('units')->inRandomOrder()->first()->id,
            'brand_id' => DB::table('brands')->inRandomOrder()->first()->id,
            'product_name' => fake()->word(),
            'is_prescription' => fake()->numberBetween(0,1),
            'user_id' => DB::table('users')->inRandomOrder()->first()->id,
        ];
    }
}
