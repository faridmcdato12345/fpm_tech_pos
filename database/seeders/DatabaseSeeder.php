<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\ProductDetail;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CustomerSeeder::class,
            AdminUserSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            UnitSeeder::class,
            ProductSeeder::class,
            ProductDetailSeeder::class,
            SaleSeeder::class,
        ]);
    }
}
