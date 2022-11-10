<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Post;
use App\Models\User;
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
        // User::factory(1)->create();

        // Post::factory(10)->create();

        User::factory()->create([
            'name' => 'Thae Thae',
            'email' => 'thaethae@gmail.com',
        ]);
    }
}
