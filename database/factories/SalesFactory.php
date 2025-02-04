<?php

namespace Database\Factories;


use Carbon\Carbon;
use App\Models\ProductDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sales>
 */
class SalesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = Carbon::create(2024, 7, 15, 0, 0, 0); // Example start date
        $endDate = Carbon::create(2024, 7, 21, 23, 59, 59); // Example end date

         // Generate a random timestamp between start and end dates
        $randomTimestamp = mt_rand($startDate->timestamp, $endDate->timestamp);

        // Create a Carbon instance from the random timestamp
        $randomDate = Carbon::createFromTimestamp($randomTimestamp);
        return [
            'product_id' => ProductDetail::inRandomOrder()->first()->id,
            'quantity' => fake()->numberBetween(1,100),
            'invoice_number' => 'INV-' . fake()->unique()->numberBetween(100000, 999999),
            'remarks' => fake()->randomElement(['DAMAGE','COMPLETE','RETURN','EXPIRED','CHANGED','OTHERS','VOIDED ITEM','VOIDED TRANSACTION']),
            'created_at' => fake()->dateTimeBetween('2024-01-07', '2024-07-23'),
            'updated_at' => now(),
        ];
    }
}
