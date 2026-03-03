<?php

namespace Modules\Shop\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Shop\Models\Shop;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shops = [
            ['name' => 'Malaysia Electronics'],
            ['name' => 'Japan Electronics'],
            ['name' => 'Mousumi Electronics'],
        ];

        foreach ($shops as $shop) {
            Shop::create($shop);
        }
    }
}
