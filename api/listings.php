<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=120');

require_once __DIR__ . '/../lib/ddf.php';

$limit = isset($_GET['limit']) ? max(1, min(50, (int)$_GET['limit'])) : 12;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

$filters = [
    'city'          => $_GET['city'] ?? null,
    'neighbourhood' => $_GET['neighbourhood'] ?? null,
    'minPrice'      => isset($_GET['minPrice']) ? (int)$_GET['minPrice'] : null,
    'maxPrice'      => isset($_GET['maxPrice']) ? (int)$_GET['maxPrice'] : null,
    'beds'          => isset($_GET['beds']) ? (int)$_GET['beds'] : null,
    'baths'         => isset($_GET['baths']) ? (int)$_GET['baths'] : null,
    'propertyType'  => $_GET['propertyType'] ?? null,
    'type'          => $_GET['type'] ?? null,
    'page'          => $page,
    'limit'         => $limit,
    'sortBy'        => $_GET['sortBy'] ?? null,
];

try {
    $data = ddf_get_listings($filters);
    $properties = $data['Properties'] ?? [];
    $pagination = $data['Pagination'] ?? ['CurrentPage' => $page, 'TotalRecords' => 0, 'TotalPages' => 1, 'RecordsPerPage' => $limit];

    // Slim down property data to reduce payload size
    $slim = [];
    foreach ($properties as $prop) {
        $media = $prop['Media'][0]['MediaURL'] ?? null;
        $photoCount = is_array($prop['Media'] ?? null) ? count($prop['Media']) : 0;

        // Format address
        $address = '';
        if (!empty($prop['UnparsedAddress'])) {
            $address = trim((string)$prop['UnparsedAddress']);
        } else {
            $parts = array_filter([$prop['StreetNumber'] ?? '', $prop['StreetName'] ?? '', $prop['StreetSuffix'] ?? '']);
            $address = trim(implode(' ', $parts));
        }
        $locParts = array_filter([trim((string)($prop['City'] ?? '')), trim((string)($prop['StateOrProvince'] ?? '')), trim((string)($prop['PostalCode'] ?? ''))]);
        $locStr = implode(', ', $locParts);
        if ($locStr !== '') $address .= $address !== '' ? ', ' . $locStr : $locStr;
        if ($address === '') $address = 'Address unavailable';

        // Format price
        $price = 'Contact for price';
        if (!empty($prop['ListPrice'])) {
            $price = '$' . number_format((float)$prop['ListPrice']);
        } elseif (!empty($prop['LeaseAmount'])) {
            $price = '$' . number_format((float)$prop['LeaseAmount']) . '/' . strtolower((string)($prop['LeaseAmountFrequency'] ?? 'mo'));
        }

        // Format type
        $type = 'Residential';
        if (($prop['CommonInterest'] ?? null) === 'Condo/Strata') $type = 'Condominium';
        elseif (!empty($prop['StructureType']) && is_array($prop['StructureType']) && in_array('Row / Townhouse', $prop['StructureType'], true)) $type = 'Townhouse';
        elseif (!empty($prop['PropertySubType'])) $type = strtoupper((string)$prop['PropertySubType']);

        // Format status
        $status = strtoupper(trim((string)($prop['StandardStatus'] ?? '')));
        if ($status === 'ACTIVE') $status = 'New';
        elseif ($status === '') $status = 'Listing';

        $slim[] = [
            'id'         => $prop['ListingKey'] ?? $prop['ListingId'] ?? '',
            'image'      => $media,
            'address'    => $address,
            'price'      => $price,
            'type'       => $type,
            'status'     => $status,
            'beds'       => $prop['BedroomsTotal'] ?? null,
            'baths'      => $prop['BathroomsTotalInteger'] ?? null,
            'storeys'    => $prop['Stories'] ?? null,
            'area'       => !empty($prop['BuildingAreaTotal']) ? trim((string)$prop['BuildingAreaTotal'] . ' ' . ($prop['BuildingAreaUnits'] ?? '')) : null,
            'photos'     => $photoCount,
            'source'     => $prop['OriginatingSystemName'] ?? 'MLS® System',
        ];
    }

    echo json_encode(['success' => true, 'properties' => $slim, 'pagination' => $pagination], JSON_UNESCAPED_SLASHES);
} catch (\Exception $e) {
    error_log('API listings error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'properties' => [], 'pagination' => ['CurrentPage' => $page, 'TotalRecords' => 0, 'TotalPages' => 1, 'RecordsPerPage' => $limit], 'error' => 'Failed to load listings']);
}
