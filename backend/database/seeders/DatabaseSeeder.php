<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'name' => 'Earthbred Cashier',
            'email' => 'cashier@earthbred.com',
            'password' => bcrypt('password'),
            'role' => 'cashier',
        ]);

        \App\Models\User::create([
            'name' => 'Earthbred Manager',
            'email' => 'manager@earthbred.com',
            'password' => bcrypt('password'),
            'role' => 'manager',
        ]);

        \App\Models\User::create([
            'name' => 'Earthbred Owner',
            'email' => 'owner@earthbred.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        $this->call([
            InventorySeeder::class,
            OrderSampleSeeder::class,
        ]);
    }
}
