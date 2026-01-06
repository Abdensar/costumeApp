<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$c = \App\Models\Costume::latest()->first();
if (! $c) {
    echo "No costume found\n";
    exit(1);
}

// Ensure 'Formal' category exists
$category = \App\Models\Category::firstOrCreate(
    ['name' => 'Formal'],
    ['description' => 'Formal menswear and suits']
);

$c->name = "Slim-Fit Men's Suit";
$c->description = "Slim-fit men's suit (blazer + trousers), modern cut, great for formal events and business wear.";
$c->category_id = $category->id;
$c->size = 'L';
$c->price_per_day = 49.99;
$c->images = ['/storage/costumes/suit1.svg', '/storage/costumes/suit2.svg'];
$c->available = true;
$c->save();

echo json_encode($c->toArray()) . PHP_EOL;
