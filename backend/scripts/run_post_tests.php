<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Arr;
use App\Models\User;

function callApi($method, $uri, $data = [], $headers = [])
{
    global $kernel;

    $server = [];
    foreach ($headers as $k => $v) {
        $server['HTTP_' . strtoupper(str_replace('-', '_', $k))] = $v;
    }

    $request = HttpRequest::create($uri, $method, $data, [], [], $server);
    $response = $kernel->handle($request);
    $content = $response->getContent();
    $status = $response->getStatusCode();

    $decoded = null;
    if ($content) {
        $decoded = @json_decode($content, true);
    }

    return ['status' => $status, 'content' => $decoded, 'raw' => $content];
}

echo "Starting POST tests...\n";

// Ensure admin exists
$adminEmail = 'admin@costumeapp.com';
$admin = User::where('email', $adminEmail)->first();
if (! $admin) {
    $admin = User::create([
        'name' => 'Admin',
        'email' => $adminEmail,
        'password' => bcrypt('password123'),
        'role' => 'admin'
    ]);
    echo "Created admin user: {$adminEmail}\n";
} else {
    echo "Admin user exists: {$adminEmail}\n";
}

// Login admin to get token
$res = callApi('POST', '/api/login', ['email' => $adminEmail, 'password' => 'password123']);
$adminToken = null;
if (is_array($res['content'])) {
    $adminToken = Arr::get($res['content'], 'token') ?: Arr::get($res['content'], 'data.token');
}
if (! $adminToken) {
    echo "Admin login failed: ";
    var_dump($res);
    exit(1);
}

echo "Admin token obtained.\n";
$authHeader = ['Authorization' => 'Bearer ' . $adminToken];

// Create sample categories
$categories = [
    ['name' => 'Formal', 'description' => 'Formal menswear'],
    ['name' => 'Superheroes', 'description' => 'Superhero costumes'],
    ['name' => 'Historical', 'description' => 'Period costumes'],
];
$createdCategories = [];
foreach ($categories as $cat) {
    $r = callApi('POST', '/api/categories', $cat, $authHeader);
    echo "Create category {$cat['name']} -> status {$r['status']}\n";
    $createdCategories[] = $r['content']['data'] ?? $r['content'];
}

// Create a costume (admin)
$costumePayload = [
    'name' => "Slim-Fit Tuxedo",
    'slug' => 'slim-fit-tuxedo',
    'description' => 'Slim-fit tuxedo with satin lapel, perfect for formal events.',
    'category_id' => $createdCategories[0]['id'] ?? 1,
    'brand' => 'Mango',
    'price_per_day' => 59.99,
    'featured_image_url' => '/storage/costumes/suit1.svg',
    'is_active' => true,
];
$r = callApi('POST', '/api/costumes', $costumePayload, $authHeader);
echo "Create costume -> status {$r['status']}\n";
$costume = $r['content']['data'] ?? $r['content'];
$costumeId = $costume['id'] ?? null;

// Add two images by URL
$image1 = ['image_url' => '/storage/costumes/suit1.svg', 'position' => 0];
$image2 = ['image_url' => '/storage/costumes/suit2.svg', 'position' => 1];
if ($costumeId) {
    $ri1 = callApi('POST', "/api/costumes/{$costumeId}/images", $image1, $authHeader);
    $ri2 = callApi('POST', "/api/costumes/{$costumeId}/images", $image2, $authHeader);
    echo "Add images -> statuses: {$ri1['status']}, {$ri2['status']}\n";
}

// Add sizes
$s1 = ['size_label' => 'M', 'quantity_available' => 3];
$s2 = ['size_label' => 'L', 'quantity_available' => 2];
if ($costumeId) {
    $rs1 = callApi('POST', "/api/costumes/{$costumeId}/sizes", $s1, $authHeader);
    $rs2 = callApi('POST', "/api/costumes/{$costumeId}/sizes", $s2, $authHeader);
    echo "Add sizes -> statuses: {$rs1['status']}, {$rs2['status']}\n";
    $sizeId = $rs1['content']['data']['id'] ?? ($rs1['content']['id'] ?? null);
} else {
    $sizeId = null;
}

// Create test client user and login
$clientEmail = 'client@example.com';
$client = User::where('email', $clientEmail)->first();
if (! $client) {
    $resClient = callApi('POST', '/api/register', ['name' => 'Client', 'email' => $clientEmail, 'password' => 'password', 'password_confirmation' => 'password']);
    echo "Register client -> status {$resClient['status']}\n";
}
$resLogin = callApi('POST', '/api/login', ['email' => $clientEmail, 'password' => 'password']);
$clientToken = Arr::get($resLogin['content'], 'token') ?: Arr::get($resLogin['content'], 'data.token');
if (! $clientToken) {
    echo "Client login failed: "; var_dump($resLogin); exit(1);
}

$clientAuth = ['Authorization' => 'Bearer ' . $clientToken];

// Create a rental by client
if ($costumeId && $sizeId) {
    $rentalPayload = [
        'costume_id' => $costumeId,
        'size_id' => $sizeId,
        'start_date' => date('Y-m-d', strtotime('+7 days')),
        'end_date' => date('Y-m-d', strtotime('+9 days')),
    ];
    $rr = callApi('POST', '/api/rentals', $rentalPayload, $clientAuth);
    echo "Create rental -> status {$rr['status']}\n";
    echo "Rental response: \n";
    print_r($rr['content']);
}

echo "POST tests completed.\n";

