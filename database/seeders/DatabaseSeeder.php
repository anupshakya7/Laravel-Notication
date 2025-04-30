<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
<<<<<<< HEAD
        // \App\Models\User::factory(2)->create();
=======
        // \App\Models\User::factory(10)->create();
>>>>>>> 45317f6ee1f3cafab5591d94cd41d61a217a2f64

        Product::factory(50)->create();

        $this->call([
            UserSeeder::class
        ]);
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            UserSeeder::class
        ]);
    }
}
