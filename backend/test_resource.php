<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $costume = \App\Models\Costume::with(['images', 'sizes'])->find(1);
    
    if (!$costume) {
        echo "Costume not found\n";
        exit(1);
    }
    
    echo "Costume loaded\n";
    echo "Images count: " . $costume->images->count() . "\n";
    echo "Sizes count: " . $costume->sizes->count() . "\n";
    
    $resource = new \App\Http\Resources\CostumeResource($costume);
    $array = $resource->toArray(request());
    
    echo json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo $e->getTraceAsString();
}
