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

    try {
        $response = $kernel->handle($request);
    } catch (\Throwable $e) {
        return ['status' => 500, 'content' => null, 'error' => $e->getMessage()];
    }

    $content = $response->getContent();
    $status = $response->getStatusCode();

    $decoded = null;
    if ($content) {
        $decoded = @json_decode($content, true);
    }

    return ['status' => $status, 'content' => $decoded, 'raw' => $content];
}

$summary = [
    'created' => [],
    'responses' => []
];

try {
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
        $summary['created']['admin_user'] = $admin->id;
    } else {
        $summary['created']['admin_user'] = $admin->id;
    }

    // Login admin
    $res = callApi('POST', '/api/login', ['email' => $adminEmail, 'password' => 'password123']);
    $summary['responses']['admin_login'] = ['status' => $res['status']];
    $adminToken = null;
    if (is_array($res['content'])) {
        $adminToken = Arr::get($res['content'], 'token') ?: Arr::get($res['content'], 'data.token') ?: Arr::get($res['content'], 'access_token');
    }
    if (! $adminToken) {
        $summary['error'] = 'Admin login failed';
        echo json_encode($summary, JSON_PRETTY_PRINT);
        exit(1);
    }
    $summary['created']['admin_token'] = $adminToken;
    $authHeader = ['Authorization' => 'Bearer ' . $adminToken];

    // Create categories
    $categories = [
        ['name' => 'Formal', 'description' => 'Formal menswear'],
        ['name' => 'Superheroes', 'description' => 'Superhero costumes'],
        ['name' => 'Historical', 'description' => 'Period costumes'],
    ];
    $createdCategories = [];
    foreach ($categories as $cat) {
        $r = callApi('POST', '/api/categories', $cat, $authHeader);
        $summary['responses']['create_category_' . $cat['name']] = ['status' => $r['status']];
        if (is_array($r['content']) && isset($r['content']['data']['id'])) {
            $createdCategories[] = $r['content']['data'];
        }
    }
    $summary['created']['categories'] = array_column($createdCategories, 'id');

    // Create costume
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
    $summary['responses']['create_costume'] = ['status' => $r['status']];
    $costumeId = null;
    if (is_array($r['content']) && isset($r['content']['data']['id'])) {
        $costumeId = $r['content']['data']['id'];
        $summary['created']['costume'] = $costumeId;
    }

    // Add images (by URL)
    $imageIds = [];
    if ($costumeId) {
        $img1 = callApi('POST', "/api/costumes/{$costumeId}/images", ['image_url' => '/storage/costumes/suit1.svg', 'position' => 0], $authHeader);
        $img2 = callApi('POST', "/api/costumes/{$costumeId}/images", ['image_url' => '/storage/costumes/suit2.svg', 'position' => 1], $authHeader);
        $summary['responses']['add_image_1'] = ['status' => $img1['status']];
        $summary['responses']['add_image_2'] = ['status' => $img2['status']];
        if (is_array($img1['content']) && isset($img1['content']['data']['id'])) { $imageIds[] = $img1['content']['data']['id']; }
        if (is_array($img2['content']) && isset($img2['content']['data']['id'])) { $imageIds[] = $img2['content']['data']['id']; }
    }
    $summary['created']['images'] = $imageIds;

    // Add sizes
    $sizeIds = [];
    if ($costumeId) {
        $s1 = callApi('POST', "/api/costumes/{$costumeId}/sizes", ['size_label' => 'M', 'quantity_available' => 3], $authHeader);
        $s2 = callApi('POST', "/api/costumes/{$costumeId}/sizes", ['size_label' => 'L', 'quantity_available' => 2], $authHeader);
        $summary['responses']['add_size_1'] = ['status' => $s1['status']];
        $summary['responses']['add_size_2'] = ['status' => $s2['status']];
        if (is_array($s1['content']) && isset($s1['content']['data']['id'])) { $sizeIds[] = $s1['content']['data']['id']; }
        if (is_array($s2['content']) && isset($s2['content']['data']['id'])) { $sizeIds[] = $s2['content']['data']['id']; }
    }
    $summary['created']['sizes'] = $sizeIds;

    // Create client and login
    $clientEmail = 'client@example.com';
    $client = User::where('email', $clientEmail)->first();
    if (! $client) {
        $resClient = callApi('POST', '/api/register', ['name' => 'Client', 'email' => $clientEmail, 'password' => 'password', 'password_confirmation' => 'password']);
        $summary['responses']['register_client'] = ['status' => $resClient['status']];
    }
    $resLogin = callApi('POST', '/api/login', ['email' => $clientEmail, 'password' => 'password']);
    $summary['responses']['client_login'] = ['status' => $resLogin['status']];
    $clientToken = null;
    if (is_array($resLogin['content'])) {
        $clientToken = Arr::get($resLogin['content'], 'token') ?: Arr::get($resLogin['content'], 'data.token') ?: Arr::get($resLogin['content'], 'access_token');
    }
    if ($clientToken) { $summary['created']['client_token'] = $clientToken; }

    // Create rental
    if ($costumeId && count($sizeIds) > 0 && $clientToken) {
        $clientAuth = ['Authorization' => 'Bearer ' . $clientToken];
        $rentalPayload = [
            'costume_id' => $costumeId,
            'size_id' => $sizeIds[0],
            'start_date' => date('Y-m-d', strtotime('+7 days')),
            'end_date' => date('Y-m-d', strtotime('+9 days')),
        ];
        $rr = callApi('POST', '/api/rentals', $rentalPayload, $clientAuth);
        $summary['responses']['create_rental'] = ['status' => $rr['status']];
        if (is_array($rr['content']) && isset($rr['content']['data']['id'])) {
            $summary['created']['rental'] = $rr['content']['data']['id'];
        }
    }

} catch (\Throwable $e) {
    $summary['error'] = $e->getMessage();
}

// Output concise JSON summary
header('Content-Type: application/json');
echo json_encode($summary, JSON_PRETTY_PRINT);

