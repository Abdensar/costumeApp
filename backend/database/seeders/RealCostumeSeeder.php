<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Costume;
use App\Models\Category;

class RealCostumeSeeder extends Seeder
{
    public function run()
    {
        $category = Category::first();
        if (! $category) {
            $category = Category::create(['name' => 'General', 'description' => 'General costumes']);
        }

        Costume::create([
            'category_id' => $category->id,
            'name' => 'Classic Pirate Costume',
            'description' => 'High-quality pirate costume with jacket, vest, and accessories. Perfect for events and parties.',
            'size' => 'M',
            'price_per_day' => 29.99,
            'images' => [
                'https://images.unsplash.com/photo-1516574187841-cb9cc2ca948b?w=1200&q=80'
            ],
            'available' => true,
        ]);
    }
}
