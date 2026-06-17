<?php

/**
 * TZEL CAFÉ smoke test — run: php scripts/smoke-test.php
 */
$base = getenv('SMOKE_BASE_URL') ?: 'http://localhost:9000';
$api = rtrim($base, '/') . '/api';
$failures = 0;

function check(string $label, bool $ok, string $detail = ''): void
{
    global $failures;
    if ($ok) {
        echo "PASS  {$label}" . ($detail ? " — {$detail}" : '') . PHP_EOL;
    } else {
        echo "FAIL  {$label}" . ($detail ? " — {$detail}" : '') . PHP_EOL;
        $failures++;
    }
}

function request(string $method, string $url, ?array $json = null, array $headers = []): array
{
    $ch = curl_init($url);
    $opts = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HEADER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_TIMEOUT => 30,
    ];
    if ($json !== null) {
        $body = json_encode($json);
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Accept: application/json';
        $opts[CURLOPT_POSTFIELDS] = $body;
    }
    if ($headers) {
        $opts[CURLOPT_HTTPHEADER] = $headers;
    }
    curl_setopt_array($ch, $opts);
    $raw = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = (int) curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    curl_close($ch);

    return [
        'status' => $status,
        'headers' => substr((string) $raw, 0, $headerSize),
        'body' => substr((string) $raw, $headerSize),
    ];
}

echo "=== TZEL CAFÉ Smoke Tests ({$base}) ===" . PHP_EOL . PHP_EOL;

// Public API
$r = request('GET', "{$api}/categories");
$categories = json_decode($r['body'], true);
$catList = $categories['data'] ?? $categories;
check('GET /api/categories', $r['status'] === 200 && is_array($catList), 'count=' . count($catList));

$r = request('GET', "{$api}/menu-items");
$itemsPayload = json_decode($r['body'], true);
$items = $itemsPayload['data'] ?? $itemsPayload;
$itemCount = is_array($items) ? count($items) : 0;
check('GET /api/menu-items', $r['status'] === 200 && $itemCount >= 30, "count={$itemCount}");

$r = request('POST', "{$api}/chat", ['message' => 'Hello']);
$chat = json_decode($r['body'], true);
$reply = $chat['data']['reply'] ?? $chat['reply'] ?? null;
$chatOk = ($r['status'] === 200 && $reply) || ($r['status'] === 500 && str_contains($r['body'], 'not configured'));
check('POST /api/chat', $chatOk, $reply ? substr((string) $reply, 0, 60) : "status={$r['status']}");

// Auth API
$email = 'smoke_' . time() . '@example.com';
$password = 'SmokeTest@8498';
$r = request('POST', "{$api}/auth/register", [
    'name' => 'Smoke Tester',
    'email' => $email,
    'password' => $password,
    'password_confirmation' => $password,
]);
$reg = json_decode($r['body'], true);
$token = $reg['data']['token'] ?? $reg['token'] ?? null;
check('POST /api/auth/register', in_array($r['status'], [200, 201], true) && $token, $email);

$r = request('GET', "{$api}/me", null, ["Authorization: Bearer {$token}"]);
$mePayload = json_decode($r['body'], true);
$me = $mePayload['data'] ?? $mePayload;
check('GET /api/me', $r['status'] === 200 && ($me['email'] ?? '') === $email);

// Checkout initialize (needs menu item)
$firstItem = $items[0] ?? null;
if ($firstItem && $token) {
    $r = request('POST', "{$api}/checkout/initialize", [
        'orderType' => 'delivery',
        'items' => [['id' => (int) $firstItem['id'], 'quantity' => 1]],
        'dropoffLocation' => 'Nairobi CBD, Kenyatta Avenue',
    ], ["Authorization: Bearer {$token}"]);
    $checkoutPayload = json_decode($r['body'], true);
    $checkout = $checkoutPayload['data'] ?? $checkoutPayload;
    $hasPaystack = isset($checkout['authorizationUrl']) || isset($checkout['authorization_url']) || isset($checkout['reference']);
    $checkoutOk = ($r['status'] === 200 && $hasPaystack)
        || ($r['status'] === 500 && str_contains($r['body'], 'Paystack is not configured'));
    check('POST /api/checkout/initialize', $checkoutOk, $checkout['reference'] ?? ($r['status'] === 500 ? 'skipped (no Paystack)' : 'no ref'));
}

$r = request('GET', "{$api}/orders", null, ["Authorization: Bearer {$token}"]);
$ordersPayload = json_decode($r['body'], true);
$orders = $ordersPayload['data'] ?? $ordersPayload;
check('GET /api/orders', $r['status'] === 200 && is_array($orders));

// Admin web pages (session login)
$cookieFile = sys_get_temp_dir() . '/tzel_smoke_cookies.txt';
@unlink($cookieFile);

$ch = curl_init("{$base}/login");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_COOKIEJAR => $cookieFile,
    CURLOPT_COOKIEFILE => $cookieFile,
    CURLOPT_FOLLOWLOCATION => true,
]);
$html = curl_exec($ch);
curl_close($ch);
preg_match('/name="_token" value="([^"]+)"/', (string) $html, $m);
$csrf = $m[1] ?? '';

$ch = curl_init("{$base}/login");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        '_token' => $csrf,
        'email' => 'admin@tzelcafe.local',
        'password' => 'Admin@8498',
    ]),
    CURLOPT_COOKIEJAR => $cookieFile,
    CURLOPT_COOKIEFILE => $cookieFile,
    CURLOPT_FOLLOWLOCATION => true,
]);
curl_exec($ch);
curl_close($ch);

$adminPages = [
    '/admin' => 'Dashboard',
    '/admin/orders' => 'Orders',
    '/admin/categories' => 'Categories',
    '/admin/menu-items' => 'Menu Items',
    '/admin/promotions' => 'Promotions',
    '/admin/announcements' => 'Announcements',
    '/admin/users' => 'Users',
    '/admin/profile' => 'My Profile',
];

foreach ($adminPages as $path => $label) {
    $ch = curl_init($base . $path);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR => $cookieFile,
        CURLOPT_COOKIEFILE => $cookieFile,
        CURLOPT_FOLLOWLOCATION => false,
    ]);
    $body = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $hasSidebar = str_contains((string) $body, 'My Profile') && str_contains((string) $body, 'Announcements');
    check("GET {$path} ({$label})", $status === 200 && $hasSidebar, "status={$status}");
}

// Extra API + auth pages
$r = request('POST', "{$api}/contact", [
    'name' => 'Smoke Test',
    'email' => 'contact_' . time() . '@example.com',
    'message' => 'Smoke test contact message',
]);
check('POST /api/contact', in_array($r['status'], [200, 201], true));

$r = request('GET', "{$api}/promotions");
check('GET /api/promotions', $r['status'] === 200);

$r = request('POST', "{$api}/newsletter/subscribe", ['email' => 'news_' . time() . '@example.com']);
check('POST /api/newsletter/subscribe', $r['status'] === 200);

$r = request('GET', "{$base}/forgot-password");
check('GET /forgot-password', $r['status'] === 200 && str_contains($r['body'], 'verification code'));

$r = request('GET', "{$base}/reset-password-otp");
check('GET /reset-password-otp', $r['status'] === 200);

echo PHP_EOL;
if ($failures === 0) {
    echo "All smoke tests passed." . PHP_EOL;
    exit(0);
}

echo "{$failures} test(s) failed." . PHP_EOL;
exit(1);
