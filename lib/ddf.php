<?php
declare(strict_types=1);

// Load .env.local from project root if environment variables are not already set
if (!function_exists('load_env_local')) {
    function load_env_local(): void
    {
        $root = realpath(__DIR__ . '/../../');
        if ($root === false) return;
        $envFile = $root . '/.env.local';
        if (!file_exists($envFile)) return;
        $lines = @file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!is_array($lines)) return;
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || strpos($line, '#') === 0) continue;
            if (strpos($line, '=') === false) continue;
            [$k, $v] = explode('=', $line, 2) + [1 => ''];
            $k = trim($k);
            $v = trim($v);
            if ($v === '') continue;
            // strip surrounding quotes
            if ((strpos($v, '"') === 0 && strrpos($v, '"') === strlen($v) - 1) || (strpos($v, "'") === 0 && strrpos($v, "'") === strlen($v) - 1)) {
                $v = substr($v, 1, -1);
            }
            if (getenv($k) === false) {
                putenv("$k=$v");
                $_ENV[$k] = $v;
                $_SERVER[$k] = $v;
            }
        }
    }
}

load_env_local();

// HTTP helper: prefer cURL, fallback to file_get_contents stream contexts
if (!function_exists('ddf_http_request')) {
    function ddf_http_request(string $method, string $url, array $headers = [], ?string $body = null, int $timeout = 10): array
    {
        // On Windows, prefer system curl.exe first because it already proved HTTPS works on this machine.
        $curlBinary = null;
        if (stripos(PHP_OS_FAMILY, 'Windows') !== false) {
            $where = @shell_exec('where curl.exe');
            if (is_string($where) && trim($where) !== '') {
                $first = preg_split('/\r?\n/', trim($where))[0] ?? '';
                if ($first !== '') {
                    $curlBinary = trim($first);
                }
            }
            if ($curlBinary === null && is_file('C:\Windows\System32\curl.exe')) {
                $curlBinary = 'C:\Windows\System32\curl.exe';
            }
        }

        if ($curlBinary !== null) {
            $command = [
                $curlBinary,
                '--silent',
                '--show-error',
                '--location',
                '--max-time', (string)$timeout,
                '--request', strtoupper($method),
            ];
            foreach ($headers as $header) {
                $command[] = '--header';
                $command[] = $header;
            }
            if ($body !== null && $body !== '') {
                $command[] = '--data-binary';
                $command[] = $body;
            }
            $command[] = $url;

            $descriptors = [
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ];
            $process = @proc_open($command, $descriptors, $pipes);
            if (is_resource($process)) {
                $stdout = stream_get_contents($pipes[1]);
                $stderr = stream_get_contents($pipes[2]);
                fclose($pipes[1]);
                fclose($pipes[2]);
                $exitCode = proc_close($process);
                if ($exitCode === 0) {
                    return ['code' => 200, 'body' => (string)$stdout];
                }
                $message = trim((string)$stderr) !== '' ? trim((string)$stderr) : trim((string)$stdout);
                return ['code' => $exitCode ?: 500, 'body' => $message];
            }
        }

        if (function_exists('curl_version')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            $hdrs = [];
            foreach ($headers as $h) $hdrs[] = $h;
            if (!empty($hdrs)) curl_setopt($ch, CURLOPT_HTTPHEADER, $hdrs);
            $method = strtoupper($method);
            if ($method === 'POST') {
                curl_setopt($ch, CURLOPT_POST, true);
                if ($body !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            } else {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
                if ($body !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            }
            $resp = curl_exec($ch);
            if ($resp !== false) {
                $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                return ['code' => $code, 'body' => $resp];
            }
            $err = curl_error($ch);
            curl_close($ch);
            // keep falling through; other transports may still work
        }

        // fallback to file_get_contents
        $opts = ['http' => ['method' => strtoupper($method), 'header' => implode("\r\n", $headers) . "\r\n", 'timeout' => $timeout, 'ignore_errors' => true]];
        if ($body !== null) $opts['http']['content'] = $body;
        $context = stream_context_create($opts);
        $resp = @file_get_contents($url, false, $context);
        if ($resp !== false) {
            $code = 200;
            if (isset($http_response_header) && is_array($http_response_header)) {
                foreach ($http_response_header as $h) {
                    if (preg_match('#HTTP/\d+\.\d+\s+(\d{3})#', $h, $m)) { $code = (int)$m[1]; break; }
                }
            }
            return ['code' => $code, 'body' => $resp];
        }

        // Fallback: raw socket request (supports HTTPS via ssl://)
        $err = error_get_last();
        $parsed = parse_url($url);
        if ($parsed === false || !isset($parsed['host'])) {
            throw new \RuntimeException('HTTP request failed: ' . ($err['message'] ?? 'invalid url'));
        }
        $scheme = $parsed['scheme'] ?? 'http';
        $host = $parsed['host'];
        $port = isset($parsed['port']) ? (int)$parsed['port'] : ($scheme === 'https' ? 443 : 80);
        $path = (isset($parsed['path']) ? $parsed['path'] : '/') . (isset($parsed['query']) ? '?' . $parsed['query'] : '');
        $remote = ($scheme === 'https' ? 'ssl://' : '') . $host . ':' . $port;
        $fp = @stream_socket_client($remote, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT);
        if (!$fp) {
            throw new \RuntimeException('Socket connect failed: ' . ($errstr ?: ($err['message'] ?? 'unknown')));
        }
        stream_set_timeout($fp, $timeout);
        $reqHeaders = [];
        $reqHeaders[] = strtoupper($method) . ' ' . $path . ' HTTP/1.1';
        $reqHeaders[] = 'Host: ' . $host;
        foreach ($headers as $h) { $reqHeaders[] = $h; }
        if ($body !== null) {
            $reqHeaders[] = 'Content-Length: ' . strlen($body);
        }
        $reqHeaders[] = 'Connection: close';
        $raw = implode("\r\n", $reqHeaders) . "\r\n\r\n" . ($body ?? '');
        fwrite($fp, $raw);
        $response = '';
        while (!feof($fp)) { $response .= fgets($fp, 8192); }
        fclose($fp);
        // split headers/body
        $parts = preg_split("/\r?\n\r?\n/", $response, 2);
        $headerText = $parts[0] ?? '';
        $bodyText = $parts[1] ?? '';
        $statusCode = 0;
        if (preg_match('#HTTP/\d+\.\d+\s+(\d{3})#', $headerText, $m)) { $statusCode = (int)$m[1]; }
        return ['code' => $statusCode ?: 200, 'body' => $bodyText];
    }
}

/**
 * Minimal DDF helper for PHP.
 * - get_ddf_token(): obtains and caches client_credentials token (file cache)
 * - ddf_fetch(): performs GET request to DDF OData endpoints with Bearer token
 * - helpers: escape_odata_value(), normalize_location_query()
 */

function ddf_has_credentials(): bool
{
    return (bool)(getenv('DDF_CLIENT_ID') && getenv('DDF_CLIENT_SECRET'));
}

function ddf_token_cache_path(): string
{
    $dir = __DIR__ . '/../tmp';
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    return $dir . '/ddf_token.json';
}

function get_ddf_token(): string
{
    $clientId = getenv('DDF_CLIENT_ID');
    $clientSecret = getenv('DDF_CLIENT_SECRET');
    $authUrl = getenv('DDF_AUTH_URL') ?: 'https://identity.crea.ca/connect/token';

    if (!$clientId || !$clientSecret) {
        throw new \RuntimeException('DDF_CLIENT_ID or DDF_CLIENT_SECRET is missing');
    }

    $cacheFile = ddf_token_cache_path();
    if (file_exists($cacheFile)) {
        $contents = @file_get_contents($cacheFile);
        if ($contents !== false) {
            $json = @json_decode($contents, true);
            if (isset($json['access_token'], $json['expires_at']) && time() < (int)$json['expires_at']) {
                return $json['access_token'];
            }
        }
    }

    $body = http_build_query([
        'grant_type' => 'client_credentials',
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'scope' => 'DDFApi_Read',
    ]);

    $opts = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => $body,
            'ignore_errors' => true,
            'timeout' => 10,
        ]
    ];

    try {
        $res = ddf_http_request('POST', $authUrl, ["Content-Type: application/x-www-form-urlencoded"], $body, 15);
    } catch (\Throwable $e) {
        throw new \RuntimeException('Failed to fetch DDF token: ' . $e->getMessage());
    }

    if ($res['code'] < 200 || $res['code'] >= 300) {
        throw new \RuntimeException('DDF auth error ' . $res['code'] . ': ' . substr($res['body'], 0, 1000));
    }

    $data = json_decode($res['body'], true);
    if (!isset($data['access_token'])) {
        throw new \RuntimeException('DDF token response missing access_token: ' . substr($res['body'], 0, 1000));
    }

    $expiresIn = isset($data['expires_in']) ? (int)$data['expires_in'] : 3600;
    $cacheSeconds = max(0, $expiresIn - 300);
    $expiresAt = time() + $cacheSeconds;

    $save = ['access_token' => $data['access_token'], 'expires_at' => $expiresAt];
    @file_put_contents($cacheFile, json_encode($save));

    return $data['access_token'];
}

function ddf_build_url(string $path, array $params = []): string
{
    $base = getenv('DDF_BASE_URL') ?: 'https://ddfapi.realtor.ca';
    $url = rtrim($base, '/') . '/' . ltrim($path, '/');
    if (!empty($params)) {
        $qs = http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        $url .= (strpos($url, '?') === false ? '?' : '&') . $qs;
    }
    return $url;
}

function ddf_cache_path(string $key): string
{
    $dir = __DIR__ . '/../tmp/ddf-cache';
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    return $dir . '/' . sha1($key) . '.json';
}

function ddf_fetch(string $path, array $params = [], int $timeout = 10, int $cacheSeconds = 0)
{
    if (!ddf_has_credentials()) {
        throw new \RuntimeException('DDF credentials missing');
    }

    $cacheKey = ddf_build_url($path, $params);
    $cacheFile = $cacheSeconds > 0 ? ddf_cache_path($cacheKey) : null;
    if ($cacheFile && file_exists($cacheFile)) {
        $cached = @json_decode((string)@file_get_contents($cacheFile), true);
        if (is_array($cached) && isset($cached['expires_at'], $cached['data']) && time() < (int)$cached['expires_at']) {
            return $cached['data'];
        }
    }

    $token = get_ddf_token();
    $url = ddf_build_url($path, $params);

    try {
        $res = ddf_http_request('GET', $url, ["Authorization: Bearer $token", 'Accept: application/json'], null, $timeout);
    } catch (\Throwable $e) {
        throw new \RuntimeException('DDF fetch failed: ' . $e->getMessage());
    }

    if ($res['code'] < 200 || $res['code'] >= 300) {
        throw new \RuntimeException('DDF API ' . $res['code'] . ': ' . substr($res['body'], 0, 2000));
    }

    $json = json_decode($res['body'], true);

    if ($cacheFile && is_array($json)) {
        @file_put_contents($cacheFile, json_encode([
            'expires_at' => time() + $cacheSeconds,
            'data' => $json,
        ]));
    }

    return $json;
}

function escape_odata_value(string $value): string
{
    return str_replace("'", "''", $value);
}

function normalize_location_query(string $value): array
{
    $normalized = trim(strtolower($value));
    if ($normalized === '') return [];

    $aliases = [
        'on' => 'Ontario', 'ontario' => 'Ontario',
        'bc' => 'British Columbia', 'british columbia' => 'British Columbia',
        'ab' => 'Alberta', 'alberta' => 'Alberta',
        'sk' => 'Saskatchewan', 'saskatchewan' => 'Saskatchewan',
        'mb' => 'Manitoba', 'manitoba' => 'Manitoba',
        'ns' => 'Nova Scotia', 'nova scotia' => 'Nova Scotia',
        'nb' => 'New Brunswick', 'new brunswick' => 'New Brunswick',
        'nl' => 'Newfoundland and Labrador', 'newfoundland and labrador' => 'Newfoundland and Labrador',
        'pe' => 'Prince Edward Island', 'prince edward island' => 'Prince Edward Island',
        'qc' => 'Quebec', 'quebec' => 'Quebec',
        'yt' => 'Yukon', 'yukon' => 'Yukon',
        'nt' => 'Northwest Territories', 'northwest territories' => 'Northwest Territories',
        'nu' => 'Nunavut', 'nunavut' => 'Nunavut',
    ];

    if (isset($aliases[$normalized])) {
        return ['province' => $aliases[$normalized]];
    }

    return ['city' => trim($value)];
}

function ddf_get_listings(array $filters = []): array
{
    if (!ddf_has_credentials()) {
        $limit = $filters['limit'] ?? 12;
        $page = $filters['page'] ?? 1;
        return ['Properties' => [], 'Pagination' => ['CurrentPage' => $page, 'TotalRecords' => 0, 'TotalPages' => 1, 'RecordsPerPage' => $limit]];
    }

    $odata = [];

    // Status / type filter (sale vs lease)
    if (!empty($filters['type']) && $filters['type'] !== 'all') {
        $t = strtolower((string)$filters['type']);
        if ($t === 'sale') {
            $odata[] = "StandardStatus eq 'Active'";
            $odata[] = "ListPrice gt 0";
        } elseif ($t === 'lease') {
            $odata[] = "StandardStatus eq 'Active'";
            $odata[] = "LeaseAmount gt 0";
        }
    }

    if (!empty($filters['city']) && $filters['city'] !== 'all') {
        $loc = normalize_location_query((string)$filters['city']);
        if (!empty($loc['province'])) $odata[] = "StateOrProvince eq '" . escape_odata_value($loc['province']) . "'";
        elseif (!empty($loc['city'])) $odata[] = "City eq '" . escape_odata_value($loc['city']) . "'";
    }
    if (!empty($filters['neighbourhood']) && $filters['neighbourhood'] !== 'all') {
        $odata[] = "SubdivisionName eq '" . escape_odata_value($filters['neighbourhood']) . "'";
    }

    $priceField = $isLease ? 'LeaseAmount' : 'ListPrice';
    if (!empty($filters['minPrice'])) $odata[] = "$priceField ge " . (int)$filters['minPrice'];
    if (!empty($filters['maxPrice'])) $odata[] = "$priceField le " . (int)$filters['maxPrice'];

    if (!empty($filters['beds'])) $odata[] = "BedroomsTotal ge " . (int)$filters['beds'];
    if (!empty($filters['baths'])) $odata[] = "BathroomsTotalInteger ge " . (int)$filters['baths'];
    if (!empty($filters['propertyType']) && $filters['propertyType'] !== 'all') {
        $pt = $filters['propertyType'];
        if ($pt === 'Condominium') $odata[] = "CommonInterest eq 'Condo/Strata'";
        elseif ($pt === 'Townhouse') $odata[] = "StructureType/any(s: s eq 'Row / Townhouse')";
        else $odata[] = "PropertySubType eq '" . escape_odata_value((string)$pt) . "'";
    }

    $limit = $filters['limit'] ?? 12;
    $page = $filters['page'] ?? 1;
    $skip = ($page - 1) * $limit;

    $orderBy = 'ModificationTimestamp desc';
    $isLease = (!empty($filters['type']) && strtolower((string)$filters['type']) === 'lease');

    if (!empty($filters['sortBy'])) {
        if ($filters['sortBy'] === 'price_asc') {
            $orderBy = $isLease ? 'LeaseAmount asc' : 'ListPrice asc';
        } elseif ($filters['sortBy'] === 'price_desc') {
            $orderBy = $isLease ? 'LeaseAmount desc' : 'ListPrice desc';
        } elseif ($filters['sortBy'] === 'newest') {
            $orderBy = 'ModificationTimestamp desc';
        } elseif ($filters['sortBy'] === 'oldest') {
            $orderBy = 'ModificationTimestamp asc';
        }
    }

    $params = ['$top' => $limit, '$skip' => $skip, '$orderby' => $orderBy, '$count' => 'true'];
    if (!empty($odata)) $params['$filter'] = implode(' and ', $odata);

    try {
        $data = ddf_fetch('/odata/v1/Property', $params, 10, 300);
    } catch (\Exception $e) {
        error_log('DDF listings fetch error: ' . $e->getMessage());
        return ['Properties' => [], 'Pagination' => ['CurrentPage' => $page, 'TotalRecords' => 0, 'TotalPages' => 1, 'RecordsPerPage' => $limit]];
    }

    $properties = $data['value'] ?? [];
    $total = $data['@odata.count'] ?? count($properties);

    return ['Properties' => $properties, 'Pagination' => ['CurrentPage' => $page, 'TotalRecords' => $total, 'TotalPages' => $total > 0 ? (int)ceil($total / $limit) : 1, 'RecordsPerPage' => $limit]];
}

function ddf_get_property_by_id(string $id)
{
    if (!ddf_has_credentials()) return null;
    try {
        $data = ddf_fetch("/odata/v1/Property('" . rawurlencode($id) . "')", [], 10, 900);
        return $data;
    } catch (\Exception $e) {
        return null;
    }
}

function ddf_get_featured_listings(string $city, int $top = 3): array
{
    if (!ddf_has_credentials()) return [];
    try {
        $data = ddf_fetch('/odata/v1/Property', ['$filter' => "City eq '" . escape_odata_value($city) . "'", '$top' => $top, '$orderby' => 'ModificationTimestamp desc'], 10, 600);
        return $data['value'] ?? [];
    } catch (\Exception $e) {
        return [];
    }
}

function ddf_get_open_houses(?string $propertyId = null): array
{
    if (!ddf_has_credentials()) return [];
    $params = [];
    if ($propertyId) $params['$filter'] = "PropertyId eq '" . escape_odata_value($propertyId) . "'";
    try {
        $data = ddf_fetch('/odata/v1/OpenHouse', $params, 10, 300);
        return $data['value'] ?? [];
    } catch (\Exception $e) {
        return [];
    }
}

function ddf_get_by_neighbourhood(string $neighbourhood, string $city, string $excludeId, int $limit): array
{
    if (!ddf_has_credentials()) return [];
    if ($neighbourhood === '' || $city === '') return [];

    $filter = [
        "SubdivisionName eq '" . escape_odata_value($neighbourhood) . "'",
        "City eq '" . escape_odata_value($city) . "'",
        "ListingKey ne '" . escape_odata_value($excludeId) . "'",
    ];

    try {
        $data = ddf_fetch('/odata/v1/Property', [
            '$filter' => implode(' and ', $filter),
            '$top' => $limit,
            '$orderby' => 'ModificationTimestamp desc',
        ], 10, 900);
        return $data['value'] ?? [];
    } catch (\Exception $e) {
        return [];
    }
}

function ddf_get_agent_info()
{
    if (!ddf_has_credentials()) return null;
    try {
        $data = ddf_fetch('/odata/v1/Member', ['$top' => 1], 10, 3600);
        return $data['value'][0] ?? null;
    } catch (\Exception $e) {
        return null;
    }
}

function ddf_get_city_listing_count(string $city): int
{
    if (!ddf_has_credentials()) return 0;
    try {
        $data = ddf_fetch('/odata/v1/Property/$count', ['$filter' => "City eq '" . escape_odata_value($city) . "'"], 10, 1800);
        return (int)$data;
    } catch (\Exception $e) {
        return 0;
    }
}
