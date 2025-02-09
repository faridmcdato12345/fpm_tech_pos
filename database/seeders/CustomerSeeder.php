<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'name' => 'sample customer',
            'region' => '10',
            'province' => 'sample province',
            'municipality' => 'sample municipality',
            'barangay' => 'sample barangay',
            'company' => 'xyz company',
            'email' => 'sample@email.com',
        ]);
    }
}
