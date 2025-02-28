<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $userId = User::first()->id;

        // Membuat 10 post dengan data acak
        foreach (range(1, 10) as $index) {
            Post::create([
                'title' => substr($faker->sentence(10), 0, 60),  // Membuat title dengan panjang maksimal 60 karakter
                'content' => $faker->paragraph(3),  // Membuat konten berupa 3 paragraf
                'status' => $faker->randomElement(['draft', 'published', 'scheduled']),  // Status acak
                'user_id' => $userId,  // Mengambil ID user acak antara 1 sampai 5 (misalnya kamu punya 5 user)
            ]);
    }
}
}
