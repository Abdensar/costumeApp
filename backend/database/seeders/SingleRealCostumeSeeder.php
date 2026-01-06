<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Costume;
use App\Models\Category;

class SingleRealCostumeSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Costume::truncate();
        DB::table('rentals')->truncate();
        DB::table('notifications')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ensure a category exists
        $category = Category::first();
        if (! $category) {
            $category = Category::create(['name' => 'Featured', 'description' => 'Featured costumes for mobile demo']);
        }

        // Create one real costume with external images
        Costume::create([
            'category_id' => $category->id,
            'name' => 'Deluxe Dragon Rider',
            'description' => 'Impressive dragon rider outfit with faux-leather armor and cape. Demo-ready and mobile-friendly image URLs.',
            'size' => 'L',
            'price_per_day' => 39.99,
            'images' => [
                'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=1200&q=80',
                'https://images.unsplash.com/photo-1503264116251-35a269479413?w=1200&q=80'
            ],
            'available' => true,
        ]);
    }
}
