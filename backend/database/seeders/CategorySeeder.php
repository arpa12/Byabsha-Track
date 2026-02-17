<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Mobile & Accessories', 'description' => 'Mobile phones and related accessories'],
            ['name' => 'Computers & Laptops', 'description' => 'Desktop computers, laptops, and accessories'],
            ['name' => 'Audio & Video', 'description' => 'Audio systems, speakers, and video equipment'],
            ['name' => 'Smart Watches', 'description' => 'Smart watches and fitness trackers'],
            ['name' => 'Cameras', 'description' => 'Digital cameras and photography equipment'],
            ['name' => 'Gaming', 'description' => 'Gaming consoles and accessories'],
            ['name' => 'Networking', 'description' => 'Routers, switches, and networking equipment'],
            ['name' => 'Storage Devices', 'description' => 'Hard drives, SSDs, and memory cards'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'is_active' => true,
            ]);
        }
    }
}
