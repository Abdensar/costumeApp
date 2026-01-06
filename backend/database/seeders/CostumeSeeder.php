<?php

namespace Database\Seeders;

use App\Models\Costume;
use App\Models\CostumeImage;
use App\Models\Size;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CostumeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, ensure categories exist
        $categoryCount = Category::count();
        if ($categoryCount == 0) {
            $this->command->info('No categories found. Creating default categories first...');
            $this->call(CategorySeeder::class);
        }

        // Get the first category ID
        $defaultCategory = Category::first();
        if (!$defaultCategory) {
            $this->command->error('Cannot seed costumes: No categories exist!');
            return;
        }
        $categoryId = $defaultCategory->id;

        // Clear existing costume data (in correct order for foreign keys)
        Size::query()->delete();
        CostumeImage::query()->delete();
        Costume::query()->delete();

        // Use images that exist in public/storage/costumes
        $costumes = [
            [
                'name' => 'Costume Slim-Fit Noir',
                'slug' => 'costume-slimfit-noir',
                'brand' => 'Maison Élégance',
                'description' => 'Un costume élégant et moderne parfait pour les occasions formelles. Coupe slim flatteuse avec finitions de qualité.',
                'category_id' => $categoryId,
                'price_per_day' => 49.99,
                'featured_image_url' => '/storage/costumes/costume-1.jpg',
                'available' => true,
                'is_active' => true,
                'sizes' => [
                    ['size_label' => 'S', 'quantity_available' => 2],
                    ['size_label' => 'M', 'quantity_available' => 5],
                    ['size_label' => 'L', 'quantity_available' => 3],
                    ['size_label' => 'XL', 'quantity_available' => 1],
                ],
            ],
            [
                'name' => 'Costume Trois Pièces Bleu Marine',
                'slug' => 'costume-trois-pieces-bleu-marine',
                'brand' => 'Hugo Boss',
                'description' => 'Costume trois pièces en laine bleu marine. Comprend veste, pantalon et gilet. Style classique intemporel.',
                'category_id' => $categoryId,
                'price_per_day' => 59.99,
                'featured_image_url' => '/storage/costumes/costume-2.jpg',
                'available' => true,
                'is_active' => true,
                'sizes' => [
                    ['size_label' => 'M', 'quantity_available' => 3],
                    ['size_label' => 'L', 'quantity_available' => 4],
                    ['size_label' => 'XL', 'quantity_available' => 2],
                ],
            ],
            [
                'name' => 'Costume Gris Clair Élégant',
                'slug' => 'costume-gris-clair',
                'brand' => 'Armani',
                'description' => 'Costume gris clair élégant, idéal pour les mariages et événements d\'été. Tissu léger et respirant.',
                'category_id' => $categoryId,
                'price_per_day' => 54.99,
                'featured_image_url' => '/storage/costumes/costume-3.jpg',
                'available' => true,
                'is_active' => true,
                'sizes' => [
                    ['size_label' => 'S', 'quantity_available' => 1],
                    ['size_label' => 'M', 'quantity_available' => 4],
                    ['size_label' => 'L', 'quantity_available' => 3],
                ],
            ],
            [
                'name' => 'Smoking Noir Classique',
                'slug' => 'smoking-noir-classique',
                'brand' => 'Tom Ford',
                'description' => 'Smoking noir classique avec revers en satin. Parfait pour les galas et événements de soirée.',
                'category_id' => $categoryId,
                'price_per_day' => 69.99,
                'featured_image_url' => '/storage/costumes/costume-5.jpg',
                'available' => true,
                'is_active' => true,
                'sizes' => [
                    ['size_label' => 'M', 'quantity_available' => 2],
                    ['size_label' => 'L', 'quantity_available' => 3],
                    ['size_label' => 'XL', 'quantity_available' => 2],
                ],
            ],
            [
                'name' => 'Costume Beige d\'Été',
                'slug' => 'costume-beige-ete',
                'brand' => 'Zara Man',
                'description' => 'Costume beige léger en lin, parfait pour les occasions estivales. Coupe moderne et confortable.',
                'category_id' => $categoryId,
                'price_per_day' => 44.99,
                'featured_image_url' => '/storage/costumes/costume-6.jpg',
                'available' => true,
                'is_active' => true,
                'sizes' => [
                    ['size_label' => 'S', 'quantity_available' => 3],
                    ['size_label' => 'M', 'quantity_available' => 5],
                    ['size_label' => 'L', 'quantity_available' => 2],
                ],
            ],
            [
                'name' => 'Costume Rayé Vintage',
                'slug' => 'costume-raye-vintage',
                'brand' => 'Ralph Lauren',
                'description' => 'Costume rayé inspiré des années 1920, avec veste croisée et pantalon large. Style gatsby.',
                'category_id' => $categoryId,
                'price_per_day' => 64.99,
                'featured_image_url' => '/storage/costumes/costume-7.jpg',
                'available' => true,
                'is_active' => true,
                'sizes' => [
                    ['size_label' => 'M', 'quantity_available' => 2],
                    ['size_label' => 'L', 'quantity_available' => 2],
                ],
            ],
            [
                'name' => 'Costume Charbon de Bois',
                'slug' => 'costume-charbon-de-bois',
                'brand' => 'Calvin Klein',
                'description' => 'Costume charbon de bois moderne avec finitions slim. Polyvalent pour bureau et événements.',
                'category_id' => $categoryId,
                'price_per_day' => 52.99,
                'featured_image_url' => '/storage/costumes/costume-8.jpg',
                'available' => true,
                'is_active' => true,
                'sizes' => [
                    ['size_label' => 'S', 'quantity_available' => 2],
                    ['size_label' => 'M', 'quantity_available' => 4],
                    ['size_label' => 'L', 'quantity_available' => 3],
                    ['size_label' => 'XL', 'quantity_available' => 1],
                ],
            ],
            [
                'name' => 'Costume Bordeaux Élégant',
                'slug' => 'costume-bordeaux-elegant',
                'brand' => 'Givenchy',
                'description' => 'Costume bordeaux audacieux pour se démarquer. Coupe moderne avec détails raffinés.',
                'category_id' => $categoryId,
                'price_per_day' => 57.99,
                'featured_image_url' => '/storage/costumes/costume-9.jpg',
                'available' => true,
                'is_active' => true,
                'sizes' => [
                    ['size_label' => 'M', 'quantity_available' => 3],
                    ['size_label' => 'L', 'quantity_available' => 2],
                ],
            ],
            [
                'name' => 'Costume Bleu Royal',
                'slug' => 'costume-bleu-royal',
                'brand' => 'Dolce & Gabbana',
                'description' => 'Costume bleu royal vibrant. Parfait pour les occasions spéciales et les mariages.',
                'category_id' => $categoryId,
                'price_per_day' => 74.99,
                'featured_image_url' => '/storage/costumes/costume-10.jpg',
                'available' => true,
                'is_active' => true,
                'sizes' => [
                    ['size_label' => 'S', 'quantity_available' => 1],
                    ['size_label' => 'M', 'quantity_available' => 3],
                    ['size_label' => 'L', 'quantity_available' => 2],
                ],
            ],
            [
                'name' => 'Costume Prince de Galles',
                'slug' => 'costume-prince-de-galles',
                'brand' => 'Burberry',
                'description' => 'Costume à carreaux Prince de Galles, un classique britannique revisité avec une coupe moderne.',
                'category_id' => $categoryId,
                'price_per_day' => 62.99,
                'featured_image_url' => '/storage/costumes/costume-11.jpg',
                'available' => true,
                'is_active' => true,
                'sizes' => [
                    ['size_label' => 'M', 'quantity_available' => 2],
                    ['size_label' => 'L', 'quantity_available' => 3],
                    ['size_label' => 'XL', 'quantity_available' => 1],
                ],
            ],
            [
                'name' => 'Costume Ivoire Mariage',
                'slug' => 'costume-ivoire-mariage',
                'brand' => 'Canali',
                'description' => 'Costume ivoire élégant spécialement conçu pour les mariages. Finitions luxueuses.',
                'category_id' => $categoryId,
                'price_per_day' => 89.99,
                'featured_image_url' => '/storage/costumes/costume-12.jpg',
                'available' => true,
                'is_active' => true,
                'sizes' => [
                    ['size_label' => 'S', 'quantity_available' => 2],
                    ['size_label' => 'M', 'quantity_available' => 4],
                    ['size_label' => 'L', 'quantity_available' => 3],
                ],
            ],
            [
                'name' => 'Costume Vert Forêt',
                'slug' => 'costume-vert-foret',
                'brand' => 'Paul Smith',
                'description' => 'Costume vert forêt original pour un look audacieux et moderne.',
                'category_id' => $categoryId,
                'price_per_day' => 58.99,
                'featured_image_url' => '/storage/costumes/costume-13.jpg',
                'available' => true,
                'is_active' => true,
                'sizes' => [
                    ['size_label' => 'M', 'quantity_available' => 2],
                    ['size_label' => 'L', 'quantity_available' => 2],
                ],
            ],
            [
                'name' => 'Costume Double Breasted Navy',
                'slug' => 'costume-double-breasted-navy',
                'brand' => 'Brooks Brothers',
                'description' => 'Costume croisé bleu marine classique. Style intemporel pour homme d\'affaires.',
                'category_id' => $categoryId,
                'price_per_day' => 67.99,
                'featured_image_url' => '/storage/costumes/costume-14.jpg',
                'available' => true,
                'is_active' => true,
                'sizes' => [
                    ['size_label' => 'M', 'quantity_available' => 3],
                    ['size_label' => 'L', 'quantity_available' => 4],
                    ['size_label' => 'XL', 'quantity_available' => 2],
                ],
            ],
            [
                'name' => 'Costume Noir Classique',
                'slug' => 'costume-noir-classique',
                'brand' => 'Boss',
                'description' => 'Le costume noir essentiel. Coupe parfaite pour toutes occasions formelles.',
                'category_id' => $categoryId,
                'price_per_day' => 55.99,
                'featured_image_url' => '/storage/costumes/costume-15.jpg',
                'available' => true,
                'is_active' => true,
                'sizes' => [
                    ['size_label' => 'S', 'quantity_available' => 3],
                    ['size_label' => 'M', 'quantity_available' => 6],
                    ['size_label' => 'L', 'quantity_available' => 4],
                    ['size_label' => 'XL', 'quantity_available' => 2],
                ],
            ],
            [
                'name' => 'Costume Camel Moderne',
                'slug' => 'costume-camel-moderne',
                'brand' => 'Massimo Dutti',
                'description' => 'Costume camel tendance pour un style décontracté chic. Idéal pour le printemps.',
                'category_id' => $categoryId,
                'price_per_day' => 48.99,
                'featured_image_url' => '/storage/costumes/costume-16.jpg',
                'available' => true,
                'is_active' => true,
                'sizes' => [
                    ['size_label' => 'S', 'quantity_available' => 2],
                    ['size_label' => 'M', 'quantity_available' => 3],
                    ['size_label' => 'L', 'quantity_available' => 2],
                ],
            ],
        ];

        $this->command->info('Seeding ' . count($costumes) . ' costumes...');

        foreach ($costumes as $costumeData) {
            $sizes = $costumeData['sizes'];
            unset($costumeData['sizes']);

            // Create costume
            $costume = Costume::create($costumeData);

            // Create sizes
            foreach ($sizes as $sizeData) {
                $sizeData['costume_id'] = $costume->id;
                Size::create($sizeData);
            }

            // Create image record
            CostumeImage::create([
                'costume_id' => $costume->id,
                'image_url' => $costumeData['featured_image_url'],
                'position' => 0,
            ]);
        }

        $this->command->info('✅ Costumes seeded successfully!');
    }
}

