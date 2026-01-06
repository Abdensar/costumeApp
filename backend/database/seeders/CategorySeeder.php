<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Costumes Formels',
                'description' => 'Costumes élégants pour occasions formelles et professionnelles',
            ],
            [
                'name' => 'Smokings',
                'description' => 'Smokings de luxe pour événements de soirée',
            ],
            [
                'name' => 'Costumes Décontractés',
                'description' => 'Costumes confortables pour occasions semi-formelles',
            ],
            [
                'name' => 'Costumes de Mariage',
                'description' => 'Costumes spécialement conçus pour les mariages',
            ],
            [
                'name' => 'Costumes d\'Été',
                'description' => 'Costumes légers en lin et coton pour l\'été',
            ],
            [
                'name' => 'Costumes Vintage',
                'description' => 'Costumes rétro inspirés des décennies passées',
            ],
        ];

        $created = 0;
        foreach ($categories as $categoryData) {
            $category = Category::firstOrCreate(
                ['name' => $categoryData['name']],
                ['description' => $categoryData['description']]
            );
            if ($category->wasRecentlyCreated) {
                $created++;
            }
        }

        $this->command->info('✅ Categories: ' . $created . ' created, ' . (count($categories) - $created) . ' already existed');
    }
}
