<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Simulate API response for GET /api/costumes
$costumes = \App\Models\Costume::with(['sizes', 'category', 'costumeImages'])
    ->where('is_active', true)
    ->get();

$response = [];

foreach ($costumes as $costume) {    
    $response[] = [
        'id' => $costume->id,
        'name' => $costume->name,
        'slug' => $costume->slug,
        'description' => $costume->description,
        'brand' => $costume->brand,
        'price_per_day' => (float) $costume->price_per_day,
        'featured_image_url' => $costume->featured_image_url,
        'is_active' => $costume->is_active,
        'available' => $costume->available,
        'category' => $costume->category ? [
            'id' => $costume->category->id,
            'name' => $costume->category->name,
        ] : null,
        'sizes' => $costume->sizes->map(function($size) {
            return [
                'id' => $size->id,
                'size_label' => $size->size_label,
                'quantity_available' => $size->quantity_available,
            ];
        }),
        'images' => $costume->costumeImages->map(function($img) {
            return [
                'id' => $img->id,
                'image_url' => $img->image_url,
                'position' => $img->position,
            ];
        }),
    ];
}

echo json_encode([
    'success' => true,
    'data' => $response,
    'total' => count($response),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
