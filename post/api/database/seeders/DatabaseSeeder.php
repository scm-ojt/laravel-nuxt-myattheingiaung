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
        User::factory()->create([
            'name' => 'Thae Thae',
            'email' => 'thaethae@gmail.com',
        ]);

        Post::factory()->create([
            'title' => 'Post One',
            'image' => 'uploadPhoto/one.jpg',
        ]);

        Post::factory()->create([
            'title' => 'Post Two',
            'image' => 'uploadPhoto/two.jpg',
        ]);

        Post::factory()->create([
            'title' => 'Post Three',
            'image' => 'uploadPhoto/three.jpg',
        ]);

        Post::factory()->create([
            'title' => 'Post Four',
            'image' => 'uploadPhoto/four.jfif',
        ]);

        Post::factory()->create([
            'title' => 'Post Five',
            'image' => 'uploadPhoto/five.jfif',
        ]);

        Post::factory()->create([
            'title' => 'Post Six',
            'image' => 'uploadPhoto/six.jfif',
        ]);
    }
}
