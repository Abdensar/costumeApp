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

$category = \App\Models\Category::find(2) ?: \App\Models\Category::first();

$c->name = 'Elegant Victorian Dress';
$c->description = 'Elegant Victorian-era dress with lace trims and accessories, suitable for weddings and period events.';
$c->category_id = $category->id;
$c->size = 'M';
$c->price_per_day = 22.00;
$c->images = ['/storage/costumes/dress1.svg', '/storage/costumes/dress2.svg'];
$c->available = true;
$c->save();

echo json_encode($c->toArray()) . PHP_EOL;
