<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Costume;
use App\Models\CostumeImage;
use App\Models\Size;
use App\Models\Category;

class SimpleCostumesSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Size::truncate();
        CostumeImage::truncate();
        Costume::truncate();
        DB::table('rentals')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ensure at least one category exists
        $category = Category::first();
        if (! $category) {
            $category = Category::create(['name' => 'General', 'description' => 'General costumes']);
        }

        $items = [
            [
                'name' => 'Costume Slim-Fit Noir',
                'description' => "Un costume est un ensemble de vêtements coordonnés, généralement composé d’une veste et d’un pantalon, conçu pour être porté lors d’événements formels ou professionnels. Ce modèle slim-fit noir est élégant et moderne, idéal pour mariages et événements d’affaires.",
                'brand' => 'Maison Élégance',
                'price_per_day' => 49.99,
                'featured_image_url' => '/storage/costumes/costume-1.jpg',
                'size_variants' => [['label'=>'M','qty'=>3], ['label'=>'L','qty'=>2]],
            ],
            [
                'name' => 'Costume Bleu Marine',
                'description' => "Costume bleu marine coupe classique, parfait pour les rendez-vous professionnels et cérémonies. Composé d'une veste, d'un pantalon et d'un gilet en option.",
                'brand' => 'Atelier Marine',
                'price_per_day' => 44.00,
                'featured_image_url' => '/storage/costumes/costume-2.jpg',
                'size_variants' => [['label'=>'M','qty'=>4], ['label'=>'L','qty'=>3]],
            ],
            [
                'name' => 'Costume Gris Chiné',
                'description' => "Costume gris chiné, style professionnel et sobre. Conçu pour un usage formel et cérémoniel.",
                'brand' => 'TailorPro',
                'price_per_day' => 39.99,
                'featured_image_url' => '/storage/costumes/costume-3.jpg',
                'size_variants' => [['label'=>'L','qty'=>3], ['label'=>'XL','qty'=>2]],
            ],
            [
                'name' => 'Costume Coupe Moderne',
                'description' => "Coupe moderne et ajustée, recommandé pour événements formels et séances photo. Ensemble veste + pantalon.",
                'brand' => 'Moderne',
                'price_per_day' => 55.00,
                'featured_image_url' => '/storage/costumes/costume-5.jpg',
                'size_variants' => [['label'=>'M','qty'=>2], ['label'=>'L','qty'=>2]],
            ],
            [
                'name' => 'Costume Mariage Classique',
                'description' => "Costume de mariage traditionnel: tissu de haute qualité, coupe soignée — idéal pour cérémonies.",
                'brand' => 'Cérémonie',
                'price_per_day' => 65.00,
                'featured_image_url' => '/storage/costumes/costume-6.jpg',
                'size_variants' => [['label'=>'S','qty'=>2], ['label'=>'M','qty'=>2]],
            ],
            [
                'name' => 'Costume Noir 3 pièces',
                'description' => "Ensemble 3 pièces (veste, pantalon, gilet) pour un rendu très formel. Parfait pour réceptions et galas.",
                'brand' => 'Prestige',
                'price_per_day' => 72.00,
                'featured_image_url' => '/storage/costumes/costume-7.jpg',
                'size_variants' => [['label'=>'M','qty'=>2], ['label'=>'L','qty'=>1]],
            ],
            [
                'name' => 'Costume Business Slim',
                'description' => "Costume business slim pour réunions et présentations. Tissu respirant, coupe professionnelle.",
                'brand' => 'OfficeWear',
                'price_per_day' => 45.00,
                'featured_image_url' => '/storage/costumes/costume-8.jpg',
                'size_variants' => [['label'=>'M','qty'=>3], ['label'=>'L','qty'=>2]],
            ],
            [
                'name' => 'Costume Cérémonie Bleu Clair',
                'description' => "Costume bleu clair, élégant pour mariages de jour et événements d'été.",
                'brand' => 'Été',
                'price_per_day' => 50.00,
                'featured_image_url' => '/storage/costumes/costume-9.jpg',
                'size_variants' => [['label'=>'M','qty'=>2], ['label'=>'L','qty'=>2]],
            ],
            [
                'name' => 'Costume Tweed Vintage',
                'description' => "Costume en tweed au style vintage — idéal pour cérémonies à thème et photos rétro.",
                'brand' => 'Vintage Atelier',
                'price_per_day' => 55.00,
                'featured_image_url' => '/storage/costumes/costume-10.jpg',
                'size_variants' => [['label'=>'S','qty'=>3], ['label'=>'M','qty'=>2]],
            ],
            [
                'name' => 'Costume Noir Cintré',
                'description' => "Noir cintré pour une silhouette nette — recommandé pour cocktails et dîners.",
                'brand' => 'Couturier',
                'price_per_day' => 48.00,
                'featured_image_url' => '/storage/costumes/costume-11.jpg',
                'size_variants' => [['label'=>'S','qty'=>3], ['label'=>'M','qty'=>2]],
            ],
            [
                'name' => 'Costume Beige Élégant',
                'description' => "Costume beige élégant, parfait pour cérémonies en journée et événements estivaux.",
                'brand' => 'Soleil',
                'price_per_day' => 42.00,
                'featured_image_url' => '/storage/costumes/costume-12.jpg',
                'size_variants' => [['label'=>'M','qty'=>2], ['label'=>'L','qty'=>1]],
            ],
            [
                'name' => 'Costume Anthracite',
                'description' => "Tissu anthracite pour une allure sobre et moderne — polyvalent pour occasions formelles.",
                'brand' => 'UrbanTailor',
                'price_per_day' => 46.00,
                'featured_image_url' => '/storage/costumes/costume-13.jpg',
                'size_variants' => [['label'=>'S','qty'=>2], ['label'=>'M','qty'=>3]],
            ],
            [
                'name' => 'Costume Gris Clair',
                'description' => "Gris clair, style contemporain, agréable pour événements professionnels et cérémonies.",
                'brand' => 'LightLine',
                'price_per_day' => 40.00,
                'featured_image_url' => '/storage/costumes/costume-14.jpg',
                'size_variants' => [['label'=>'M','qty'=>2], ['label'=>'L','qty'=>2]],
            ],
            [
                'name' => 'Costume 3 pièces Bleu Nuit',
                'description' => "Ensemble 3 pièces bleu nuit — très formel, recommandé pour galas et mariages.",
                'brand' => 'Nocturne',
                'price_per_day' => 75.00,
                'featured_image_url' => '/storage/costumes/costume-15.jpg',
                'size_variants' => [['label'=>'L','qty'=>2], ['label'=>'XL','qty'=>1]],
            ],
            [
                'name' => 'Costume Marron Chocolat',
                'description' => "Costume marron chocolat, tissu premium — excellent pour mariages automne/hiver et événements chics.",
                'brand' => 'Automne',
                'price_per_day' => 52.00,
                'featured_image_url' => '/storage/costumes/costume-16.jpg',
                'size_variants' => [['label'=>'M','qty'=>3], ['label'=>'L','qty'=>2]],
            ],
        ];

        foreach ($items as $it) {
            $cost = Costume::create([
                'name' => $it['name'],
                'slug' => 
                    
                    strtolower(str_replace(' ', '-', preg_replace('/[^A-Za-z0-9 ]/', '', $it['name']))),
                'description' => $it['description'],
                'category_id' => $category->id,
                'brand' => $it['brand'] ?? null,
                'price_per_day' => $it['price_per_day'],
                'featured_image_url' => $it['featured_image_url'] ?? ($it['images'][0] ?? null),
                'is_active' => true,
                'images' => $it['images'] ?? null,
                'available' => true,
            ]);

            // create size variants
            if (!empty($it['size_variants'])) {
                foreach ($it['size_variants'] as $sv) {
                    Size::create([
                        'costume_id' => $cost->id,
                        'size_label' => $sv['label'],
                        'quantity_available' => $sv['qty'],
                    ]);
                }
            }

            // create costume_images rows for featured image
            if (!empty($it['featured_image_url'])) {
                CostumeImage::create([
                    'costume_id' => $cost->id,
                    'image_url' => $it['featured_image_url'],
                    'position' => 0,
                ]);
            }
        }
    }
}
