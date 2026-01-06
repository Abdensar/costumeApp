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

$c->images = ['/storage/costumes/dragon1.svg', '/storage/costumes/dragon2.svg'];
$c->save();

echo json_encode($c->toArray()) . PHP_EOL;
