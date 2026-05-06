<?php
/**
 * DDF Credential Tester
 * Upload this to your server root and open in browser.
 * DELETE THIS FILE AFTER TESTING!
 */

header('Content-Type: text/html; charset=utf-8');
echo '<html><head><title>DDF Test</title><style>body{background:#0a0a0f;color:#fff;font-family:monospace;padding:2rem;line-height:1.8}
.ok{color:#4ade80}.fail{color:#f87171}.warn{color:#fbbf24}h2{color:#d4a843;margin-top:2rem}</style></head><body>';
echo '<h1>🔍 DDF Credential Test</h1>';

// Step 1: Check .env.local
echo '<h2>Step 1: Environment File</h2>';
$envPaths = [
    __DIR__ . '/.env.local',
    __DIR__ . '/.env',
    dirname(__DIR__) . '/.env.local',
    dirname(__DIR__) . '/.env',
];

$envFound = null;
foreach ($envPaths as $path) {
    if (file_exists($path)) {
        $envFound = $path;
        echo "<p class='ok'>✅ Found: " . basename($path) . " at " . dirname($path) . "</p>";
        break;
    }
}

if (!$envFound) {
    echo "<p class='fail'>❌ .env.local NOT FOUND anywhere! Upload it to: " . __DIR__ . "</p>";
    echo "<p class='warn'>⚠️ Yeh file server pe nahi hai — isi liye listings nahi aa rahi.</p>";
    echo '</body></html>';
    exit;
}

// Load env
$lines = file($envFound, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    $line = trim($line);
    if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) continue;
    [$k, $v] = explode('=', $line, 2);
    $k = trim($k);
    $v = trim($v);
    if ($v !== '' && getenv($k) === false) {
        if (($v[0] === '"' && substr($v, -1) === '"') || ($v[0] === "'" && substr($v, -1) === "'")) {
            $v = substr($v, 1, -1);
        }
        putenv("$k=$v");
    }
}

// Step 2: Check credentials
echo '<h2>Step 2: Credentials</h2>';
$clientId = getenv('DDF_CLIENT_ID');
$clientSecret = getenv('DDF_CLIENT_SECRET');
$authUrl = getenv('DDF_AUTH_URL') ?: 'https://identity.crea.ca/connect/token';

if ($clientId) {
    echo "<p class='ok'>✅ DDF_CLIENT_ID: " . substr($clientId, 0, 8) . "..." . "</p>";
} else {
    echo "<p class='fail'>❌ DDF_CLIENT_ID not set</p>";
}

if ($clientSecret) {
    echo "<p class='ok'>✅ DDF_CLIENT_SECRET: " . substr($clientSecret, 0, 8) . "..." . "</p>";
} else {
    echo "<p class='fail'>❌ DDF_CLIENT_SECRET not set</p>";
}

if (!$clientId || !$clientSecret) {
    echo "<p class='fail'>❌ Credentials missing — cannot test API.</p>";
    echo '</body></html>';
    exit;
}

// Step 3: Test token
echo '<h2>Step 3: Token Request (identity.crea.ca)</h2>';

$body = http_build_query([
    'grant_type' => 'client_credentials',
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'scope' => 'DDFApi_Read',
]);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $authUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErr = curl_error($ch);
curl_close($ch);

if ($curlErr) {
    echo "<p class='fail'>❌ cURL Error: $curlErr</p>";
    echo "<p class='warn'>⚠️ Server pe cURL ya SSL issue hai.</p>";
    echo '</body></html>';
    exit;
}

echo "<p>HTTP Status: <strong>$httpCode</strong></p>";

if ($httpCode === 200) {
    $tokenData = json_decode($response, true);
    if (isset($tokenData['access_token'])) {
        $token = $tokenData['access_token'];
        echo "<p class='ok'>✅ TOKEN MILA! Credentials VALID hain!</p>";
        echo "<p class='ok'>Token: " . substr($token, 0, 30) . "...</p>";
    } else {
        echo "<p class='fail'>❌ 200 aaya but token nahi mila.</p>";
        echo "<pre>" . htmlspecialchars(substr($response, 0, 500)) . "</pre>";
        echo '</body></html>';
        exit;
    }
} else {
    echo "<p class='fail'>❌ Token request FAILED (HTTP $httpCode)</p>";
    echo "<p class='fail'>Response: </p><pre>" . htmlspecialchars(substr($response, 0, 1000)) . "</pre>";
    
    if ($httpCode === 400 || $httpCode === 401) {
        echo "<p class='warn'>⚠️ <strong>MATLAB:</strong> Credentials INVALID ya EXPIRED hain.</p>";
        echo "<p class='warn'>👉 Admin se naye credentials generate karwao.</p>";
    }
    echo '</body></html>';
    exit;
}

// Step 4: Test API call
echo '<h2>Step 4: Property API Test (ddfapi.realtor.ca)</h2>';

$apiUrl = 'https://ddfapi.realtor.ca/odata/v1/Property?$top=1&$count=true';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token", 'Accept: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
$apiResponse = curl_exec($ch);
$apiCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$apiErr = curl_error($ch);
curl_close($ch);

if ($apiErr) {
    echo "<p class='fail'>❌ API cURL Error: $apiErr</p>";
    echo '</body></html>';
    exit;
}

echo "<p>API HTTP Status: <strong>$apiCode</strong></p>";

if ($apiCode === 200) {
    $apiData = json_decode($apiResponse, true);
    $count = $apiData['@odata.count'] ?? 'unknown';
    $props = $apiData['value'] ?? [];
    
    echo "<p class='ok'>✅ API KAAM KAR RAHA HAI!</p>";
    echo "<p class='ok'>Total listings available: <strong>$count</strong></p>";
    
    if (!empty($props[0])) {
        $p = $props[0];
        echo "<p class='ok'>Sample: " . htmlspecialchars(($p['City'] ?? '') . ' - $' . number_format((float)($p['ListPrice'] ?? 0))) . "</p>";
    }
    
    echo "<h2 style='color:#4ade80'>🎉 SAB KUCH KAAM KAR RAHA HAI!</h2>";
    echo "<p>Agar live site pe listings nahi dikh rahi, toh issue yeh hai:</p>";
    echo "<ul>";
    echo "<li>Server pe <code>.env.local</code> file upload nahi hui</li>";
    echo "<li>Ya PHP code mein bug tha (jo ab fix ho chuka hai)</li>";
    echo "</ul>";
} elseif ($apiCode === 403) {
    echo "<p class='fail'>❌ API 403 Forbidden</p>";
    echo "<p class='warn'>⚠️ Token valid hai BUT Data Feed authorized nahi hai.</p>";
    echo "<p class='warn'>👉 Admin se bolo: DDF Dashboard pe feed ACTIVATE karo.</p>";
} else {
    echo "<p class='fail'>❌ API returned HTTP $apiCode</p>";
    echo "<pre>" . htmlspecialchars(substr($apiResponse, 0, 1000)) . "</pre>";
}

echo '<hr style="border-color:#333;margin:2rem 0">';
echo '<p class="warn">⚠️ IS FILE KO TEST KE BAAD DELETE KAR DENA — credentials expose ho sakti hain!</p>';
echo '</body></html>';
