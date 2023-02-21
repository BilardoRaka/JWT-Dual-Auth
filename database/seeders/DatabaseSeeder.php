<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Admin;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Admin::create([
            'email' => 'raka@gmail.com',
            'password' => bcrypt('password'),
        ]);

        Customer::create([
            'username' => 'Raka',
            'fullname' => 'Bilardo Raka Pamungkas',
            'email' => 'bilardo@gmail.com',
            'password' => bcrypt('password'),
        ]);
    }
}
