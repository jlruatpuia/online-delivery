<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\number;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
//{"lat": 23.7429617000678, "lng": 92.71755570730552}
        for ($i = 0; $i < 10; $i++) {
            Customer::create([
                'local_id' => $faker->unique()->numberBetween(10, 100),
                'name' => $faker->name(),
                'address' => $faker->address(),
                'phone_no' => $faker->phoneNumber(),
                'map_location' => '{"lat":'.$faker->latitude.',"lng":'.$faker->longitude.'}',
            ]);
        }
    }
}
