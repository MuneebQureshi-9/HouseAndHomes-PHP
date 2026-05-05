<?php
declare(strict_types=1);

require_once __DIR__ . '/lib/ddf.php';

header('Content-Type: application/json; charset=utf-8');

$out = ['ok' => false, 'time' => date('c')];

try {
    if (!ddf_has_credentials()) {
        throw new \Exception('DDF credentials missing. Set DDF_CLIENT_ID and DDF_CLIENT_SECRET.');
    }

    $token = get_ddf_token();
    $out['ok'] = true;
    $out['token_preview'] = substr($token, 0, 8) . '...';

    // Try a sample listings fetch
    $sample = ddf_get_featured_listings('Toronto', 3);
    $out['sample_count'] = is_array($sample) ? count($sample) : 0;
    $out['sample'] = array_map(function($p){
        return [
            'id' => $p['ListingId'] ?? $p['ListingKey'] ?? null,
            'address' => $p['UnparsedAddress'] ?? ($p['StreetNumber'] . ' ' . ($p['StreetName'] ?? '')),
            'price' => $p['ListPrice'] ?? null,
        ];
    }, array_slice($sample, 0, 3));

    $url = ddf_build_url('/odata/v1/Property', ['$top' => 1, '$count' => 'true']);
    $out['url_check'] = $url;
    $raw = ddf_fetch('/odata/v1/Property', ['$top' => 1, '$count' => 'true'], 10, 0);
    $out['raw_count'] = $raw['@odata.count'] ?? null;
    $out['raw_items'] = is_array($raw['value'] ?? null) ? count($raw['value']) : 0;

} catch (\Throwable $e) {
    http_response_code(500);
    $out['error'] = $e->getMessage();
}

echo json_encode($out, JSON_PRETTY_PRINT);
