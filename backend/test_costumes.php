<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$costumes = \App\Models\Costume::with(['sizes'])->get();

echo "Total Costumes: " . $costumes->count() . PHP_EOL;
echo str_repeat("=", 80) . PHP_EOL;

foreach ($costumes as $costume) {
    echo "ID: {$costume->id}" . PHP_EOL;
    echo "Name: {$costume->name}" . PHP_EOL;
    echo "Brand: {$costume->brand}" . PHP_EOL;
    echo "Price: {$costume->price_per_day} EUR/day" . PHP_EOL;
    echo "Image: {$costume->featured_image_url}" . PHP_EOL;
    echo "Sizes: " . $costume->sizes->pluck('size_label')->implode(', ') . PHP_EOL;
    echo str_repeat("-", 80) . PHP_EOL;
}
