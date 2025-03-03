<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PostSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 50) as $index) { // Loop untuk membuat 10 post
            DB::table('posts')->insert([
                'title' => $faker->sentence(6), // Maksimal 6 kata
                'content' => $faker->paragraph(4), // 4 kalimat
                'user_id' => 1, // Pastikan ini sesuai dengan user yang ada
                'status' => $faker->randomElement(['draft', 'published', 'scheduled']), // Random status
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    }
}
}
