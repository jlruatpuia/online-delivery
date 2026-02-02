<?php

namespace Database\Seeders;

use App\Models\Delivery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class DeliverySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        foreach (range(1, 100) as $index) {
            $serial = str_pad($faker->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT);
            Delivery::create([
                'invoice_no' => 'SI' . date('Ym') . $serial,
                'sales_date' => $faker->dateTimeBetween('2026-01-18', '2026-01-21'),
                'amount' => $faker->numberBetween(50, 5000),
                'payment_type' => $faker->randomElement(['prepaid', 'cod']),
                'customer_id' => $faker->numberBetween(3, 6),
                'deliveryboy_id' => $faker->numberBetween(2, 3),
                'status' => 'pending',
            ]);
        }
    }
}
