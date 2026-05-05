<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../lib/ddf.php';

$limit = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 12;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$filters = [
    'city' => $_GET['city'] ?? null,
    'neighbourhood' => $_GET['neighbourhood'] ?? null,
    'minPrice' => isset($_GET['minPrice']) ? (int)$_GET['minPrice'] : null,
    'maxPrice' => isset($_GET['maxPrice']) ? (int)$_GET['maxPrice'] : null,
    'beds' => isset($_GET['beds']) ? (int)$_GET['beds'] : null,
    'baths' => isset($_GET['baths']) ? (int)$_GET['baths'] : null,
    'propertyType' => $_GET['propertyType'] ?? null,
    'type' => $_GET['type'] ?? null,
    'page' => $page,
    'limit' => $limit,
    'sortBy' => $_GET['sortBy'] ?? null,
];

render_header(
    'Exclusive Listings',
    'Discover Canada\'s finest properties for sale. From architectural masterpieces in the sky to timeless estates in prestigious enclaves.',
    '/listings/'
);

if (!function_exists('format_listing_type')) {
    function format_listing_type(array $prop): string
    {
        if (($prop['CommonInterest'] ?? null) === 'Condo/Strata') {
            return 'Condominium';
        }
        if (!empty($prop['StructureType']) && is_array($prop['StructureType']) && in_array('Row / Townhouse', $prop['StructureType'], true)) {
            return 'Townhouse';
        }
        if (!empty($prop['PropertySubType'])) {
            return strtoupper((string)$prop['PropertySubType']);
        }
        return 'Residential';
    }
}

if (!function_exists('format_listing_status')) {
    function format_listing_status(array $prop): string
    {
        $status = strtoupper(trim((string)($prop['StandardStatus'] ?? '')));
        if ($status === 'ACTIVE') return 'New';
        if ($status !== '') return $status;
        return 'Listing';
    }
}

if (!function_exists('format_listing_price')) {
    function format_listing_price(array $prop): string
    {
        if (!empty($prop['ListPrice'])) {
            return '$' . number_format((float)$prop['ListPrice']);
        }
        if (!empty($prop['LeaseAmount'])) {
            return '$' . number_format((float)$prop['LeaseAmount']) . '/' . strtolower((string)($prop['LeaseAmountFrequency'] ?? 'mo'));
        }
        return 'Contact for price';
    }
}

if (!function_exists('format_listing_address')) {
    function format_listing_address(array $prop): string
    {
        $address = '';
        if (!empty($prop['UnparsedAddress'])) {
            $address = trim((string)$prop['UnparsedAddress']);
        } else {
            $parts = array_filter([
                $prop['StreetNumber'] ?? '',
                $prop['StreetName'] ?? '',
                $prop['StreetSuffix'] ?? '',
            ]);
            $address = trim(implode(' ', $parts));
        }

        $locationParts = array_filter([
            trim((string)($prop['City'] ?? '')),
            trim((string)($prop['StateOrProvince'] ?? '')),
            trim((string)($prop['PostalCode'] ?? '')),
        ]);
        
        $locationStr = implode(', ', $locationParts);
        
        if ($locationStr !== '') {
            $address .= $address !== '' ? ', ' . $locationStr : $locationStr;
        }

        return $address !== '' ? $address : 'Address unavailable';
    }
}

try {
    $data = ddf_get_listings($filters);
    $properties = $data['Properties'] ?? [];
    $pagination = $data['Pagination'] ?? null;
} catch (Exception $e) {
    $properties = [];
    $pagination = null;
    $fetchError = $e->getMessage();
}
?>
    <div class="listings-page-shell">
        <section class="listings-hero">
            <div class="listings-hero-overlay"></div>
            <div class="site-container listings-hero-inner">
                <div class="section-label listings-hero-label">Our Portfolio</div>
                <h1 class="listings-hero-title">Exclusive Listings</h1>
                <p class="listings-hero-copy">Discover Canada's finest properties for sale. From architectural masterpieces in the sky to timeless estates in prestigious enclaves.</p>
            </div>
        </section>

        <section class="listings-content">
            <div class="site-container">
                <div class="listings-disclaimer">
                    <div class="listings-disclaimer-badge">
                        <svg width="36" height="20" viewBox="0 0 36 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <rect width="36" height="20" rx="3" fill="#C41230"></rect>
                            <text x="18" y="14" text-anchor="middle" fill="white" font-size="10" font-weight="700" font-family="Arial, sans-serif">MLS®</text>
                        </svg>
                        <span>CREA Member</span>
                    </div>
                    <div class="listings-disclaimer-copy">
                        <p>The trademarks MLS®, Multiple Listing Service® and the associated logos are owned by The Canadian Real Estate Association (CREA) and identify the quality of services provided by real estate professionals who are members of CREA.</p>
                        <p>Listing data is deemed reliable but not guaranteed accurate by CREA.</p>
                    </div>
                </div>

                <form method="get" action="/listings/" class="listings-filter-card">
                    <div class="listings-filter-grid">
                        <div class="listings-field">
                            <label for="type">Status</label>
                            <select id="type" name="type">
                                <option value="">All</option>
                                <option value="sale" <?php echo (($_GET['type'] ?? '') === 'sale') ? 'selected' : ''; ?>>For Sale</option>
                                <option value="lease" <?php echo (($_GET['type'] ?? '') === 'lease') ? 'selected' : ''; ?>>For Lease</option>
                            </select>
                        </div>

                        <div class="listings-field">
                            <label for="city">City / Province</label>
                            <input id="city" name="city" type="text" placeholder="e.g. Toronto or Ontario" value="<?php echo htmlspecialchars((string)($_GET['city'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="listings-field">
                            <label for="propertyType">Property Type</label>
                            <select id="propertyType" name="propertyType">
                                <option value="">All Types</option>
                                <option value="Condominium" <?php echo (($_GET['propertyType'] ?? '') === 'Condominium') ? 'selected' : ''; ?>>Condominium</option>
                                <option value="Townhouse" <?php echo (($_GET['propertyType'] ?? '') === 'Townhouse') ? 'selected' : ''; ?>>Townhouse</option>
                                <option value="Detached" <?php echo (($_GET['propertyType'] ?? '') === 'Detached') ? 'selected' : ''; ?>>Detached</option>
                                <option value="Semi-Detached" <?php echo (($_GET['propertyType'] ?? '') === 'Semi-Detached') ? 'selected' : ''; ?>>Semi-Detached</option>
                            </select>
                        </div>

                        <div class="listings-field">
                            <label for="minPrice">Min Price</label>
                            <select id="minPrice" name="minPrice">
                                <option value="">Any</option>
                                <option value="100000" <?php echo (($_GET['minPrice'] ?? '') === '100000') ? 'selected' : ''; ?>>$100,000</option>
                                <option value="200000" <?php echo (($_GET['minPrice'] ?? '') === '200000') ? 'selected' : ''; ?>>$200,000</option>
                                <option value="300000" <?php echo (($_GET['minPrice'] ?? '') === '300000') ? 'selected' : ''; ?>>$300,000</option>
                                <option value="400000" <?php echo (($_GET['minPrice'] ?? '') === '400000') ? 'selected' : ''; ?>>$400,000</option>
                                <option value="500000" <?php echo (($_GET['minPrice'] ?? '') === '500000') ? 'selected' : ''; ?>>$500,000</option>
                                <option value="600000" <?php echo (($_GET['minPrice'] ?? '') === '600000') ? 'selected' : ''; ?>>$600,000</option>
                                <option value="750000" <?php echo (($_GET['minPrice'] ?? '') === '750000') ? 'selected' : ''; ?>>$750,000</option>
                                <option value="1000000" <?php echo (($_GET['minPrice'] ?? '') === '1000000') ? 'selected' : ''; ?>>$1,000,000</option>
                                <option value="1500000" <?php echo (($_GET['minPrice'] ?? '') === '1500000') ? 'selected' : ''; ?>>$1,500,000</option>
                                <option value="2000000" <?php echo (($_GET['minPrice'] ?? '') === '2000000') ? 'selected' : ''; ?>>$2,000,000</option>
                                <option value="3000000" <?php echo (($_GET['minPrice'] ?? '') === '3000000') ? 'selected' : ''; ?>>$3,000,000</option>
                                <option value="5000000" <?php echo (($_GET['minPrice'] ?? '') === '5000000') ? 'selected' : ''; ?>>$5,000,000</option>
                            </select>
                        </div>

                        <div class="listings-field">
                            <label for="maxPrice">Max Price</label>
                            <select id="maxPrice" name="maxPrice">
                                <option value="">Any</option>
                                <option value="300000" <?php echo (($_GET['maxPrice'] ?? '') === '300000') ? 'selected' : ''; ?>>$300,000</option>
                                <option value="400000" <?php echo (($_GET['maxPrice'] ?? '') === '400000') ? 'selected' : ''; ?>>$400,000</option>
                                <option value="500000" <?php echo (($_GET['maxPrice'] ?? '') === '500000') ? 'selected' : ''; ?>>$500,000</option>
                                <option value="600000" <?php echo (($_GET['maxPrice'] ?? '') === '600000') ? 'selected' : ''; ?>>$600,000</option>
                                <option value="750000" <?php echo (($_GET['maxPrice'] ?? '') === '750000') ? 'selected' : ''; ?>>$750,000</option>
                                <option value="1000000" <?php echo (($_GET['maxPrice'] ?? '') === '1000000') ? 'selected' : ''; ?>>$1,000,000</option>
                                <option value="1500000" <?php echo (($_GET['maxPrice'] ?? '') === '1500000') ? 'selected' : ''; ?>>$1,500,000</option>
                                <option value="2000000" <?php echo (($_GET['maxPrice'] ?? '') === '2000000') ? 'selected' : ''; ?>>$2,000,000</option>
                                <option value="3000000" <?php echo (($_GET['maxPrice'] ?? '') === '3000000') ? 'selected' : ''; ?>>$3,000,000</option>
                                <option value="5000000" <?php echo (($_GET['maxPrice'] ?? '') === '5000000') ? 'selected' : ''; ?>>$5,000,000</option>
                                <option value="10000000" <?php echo (($_GET['maxPrice'] ?? '') === '10000000') ? 'selected' : ''; ?>>$10,000,000+</option>
                            </select>
                        </div>

                        <div class="listings-field">
                            <label for="beds">Bedrooms</label>
                            <select id="beds" name="beds">
                                <option value="">Any</option>
                                <option value="1" <?php echo (($_GET['beds'] ?? '') === '1') ? 'selected' : ''; ?>>1+</option>
                                <option value="2" <?php echo (($_GET['beds'] ?? '') === '2') ? 'selected' : ''; ?>>2+</option>
                                <option value="3" <?php echo (($_GET['beds'] ?? '') === '3') ? 'selected' : ''; ?>>3+</option>
                                <option value="4" <?php echo (($_GET['beds'] ?? '') === '4') ? 'selected' : ''; ?>>4+</option>
                                <option value="5" <?php echo (($_GET['beds'] ?? '') === '5') ? 'selected' : ''; ?>>5+</option>
                            </select>
                        </div>

                        <div class="listings-field">
                            <label for="baths">Bathrooms</label>
                            <select id="baths" name="baths">
                                <option value="">Any</option>
                                <option value="1" <?php echo (($_GET['baths'] ?? '') === '1') ? 'selected' : ''; ?>>1+</option>
                                <option value="2" <?php echo (($_GET['baths'] ?? '') === '2') ? 'selected' : ''; ?>>2+</option>
                                <option value="3" <?php echo (($_GET['baths'] ?? '') === '3') ? 'selected' : ''; ?>>3+</option>
                                <option value="4" <?php echo (($_GET['baths'] ?? '') === '4') ? 'selected' : ''; ?>>4+</option>
                            </select>
                        </div>

                        <div class="listings-field">
                            <label for="sortBy">Sort By</label>
                            <select id="sortBy" name="sortBy">
                                <option value="newest" <?php echo (($_GET['sortBy'] ?? '') === 'newest' || ($_GET['sortBy'] ?? '') === '') ? 'selected' : ''; ?>>Newest First</option>
                                <option value="oldest" <?php echo (($_GET['sortBy'] ?? '') === 'oldest') ? 'selected' : ''; ?>>Oldest First</option>
                                <option value="price_asc" <?php echo (($_GET['sortBy'] ?? '') === 'price_asc') ? 'selected' : ''; ?>>Price: Low to High</option>
                                <option value="price_desc" <?php echo (($_GET['sortBy'] ?? '') === 'price_desc') ? 'selected' : ''; ?>>Price: High to Low</option>
                            </select>
                        </div>
                    </div>

                    <div class="listings-filter-actions">
                        <a class="listings-clear-link" href="/listings/">Clear Filters</a>
                        <button class="btn-primary listings-search-btn" type="submit">Search Properties</button>
                    </div>
                </form>

                <div class="listings-count-row">
                    Showing <strong><?php echo (int)count($properties); ?></strong> of <strong><?php echo (int)($pagination['TotalRecords'] ?? count($properties)); ?></strong> properties<?php echo !empty($filters['city']) ? ' in ' . htmlspecialchars((string)$filters['city'], ENT_QUOTES, 'UTF-8') : ''; ?>
                </div>

                <?php if (!empty($fetchError)): ?>
                    <div class="listings-empty-state">Error loading listings: <?php echo htmlspecialchars($fetchError, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php elseif (empty($properties)): ?>
                    <div class="listings-empty-state">No listings available for the selected filters.</div>
                <?php else: ?>
                    <div class="listings-grid">
                        <?php foreach ($properties as $prop):
                            $media = $prop['Media'][0]['MediaURL'] ?? null;
                            $link = '/listings/view.php?id=' . urlencode($prop['ListingKey'] ?? $prop['ListingId'] ?? '');
                            $beds = $prop['BedroomsTotal'] ?? null;
                            $baths = $prop['BathroomsTotalInteger'] ?? null;
                            $storeys = $prop['Stories'] ?? null;
                            $photoCount = is_array($prop['Media'] ?? null) ? count($prop['Media']) : 0;
                        ?>
                            <article class="property-card listing-card">
                                <a class="listing-card-link" href="<?php echo htmlspecialchars($link, ENT_QUOTES, 'UTF-8'); ?>" aria-label="View details for <?php echo htmlspecialchars(format_listing_address($prop), ENT_QUOTES, 'UTF-8'); ?>"></a>
                                <div class="listing-image-wrap">
                                    <?php if ($media): ?>
                                        <img src="<?php echo htmlspecialchars($media, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars(format_listing_address($prop), ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php else: ?>
                                        <div class="listing-image-placeholder">No image</div>
                                    <?php endif; ?>
                                    <span class="listing-badge"><?php echo htmlspecialchars(format_listing_status($prop), ENT_QUOTES, 'UTF-8'); ?></span>
                                    <button class="listing-save-btn" type="button" aria-label="Save property">♡</button>
                                </div>
                                <div class="listing-card-body">
                                    <div class="listing-type"><?php echo htmlspecialchars(format_listing_type($prop), ENT_QUOTES, 'UTF-8'); ?></div>
                                    <div class="listing-price"><?php echo htmlspecialchars(format_listing_price($prop), ENT_QUOTES, 'UTF-8'); ?></div>
                                    <div class="listing-address" title="<?php echo htmlspecialchars(format_listing_address($prop), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars(format_listing_address($prop), ENT_QUOTES, 'UTF-8'); ?></div>
                                    <div class="listing-meta-row">
                                        <?php if ($beds !== null && $beds !== ''): ?><span>🛏 <?php echo (int)$beds; ?> Beds</span><?php endif; ?>
                                        <?php if ($baths !== null && $baths !== ''): ?><span>🛁 <?php echo (int)$baths; ?> Baths</span><?php endif; ?>
                                        <?php if ($storeys !== null && $storeys !== ''): ?><span>🧱 <?php echo (int)$storeys; ?> Storeys</span><?php elseif (!empty($prop['BuildingAreaTotal'])): ?><span>◻ <?php echo htmlspecialchars((string)$prop['BuildingAreaTotal'] . ' ' . ($prop['BuildingAreaUnits'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
                                    </div>
                                    <div class="listing-attribution">Listed by <?php echo htmlspecialchars((string)($prop['OriginatingSystemName'] ?? 'MLS® System'), ENT_QUOTES, 'UTF-8'); ?><?php if ($photoCount > 0): ?> · <?php echo (int)$photoCount; ?> Photos<?php endif; ?></div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <?php if ($pagination && $pagination['TotalPages'] > 1): ?>
                        <div class="listings-pagination">
                            <?php if ((int)$pagination['CurrentPage'] > 1): ?>
                                <a class="pagination-btn" href="?<?php echo htmlspecialchars(http_build_query(array_merge($_GET, ['page' => (int)$pagination['CurrentPage'] - 1])), ENT_QUOTES, 'UTF-8'); ?>" aria-label="Previous page">‹</a>
                            <?php endif; ?>

                            <?php
                            $currentPage = (int)$pagination['CurrentPage'];
                            $totalPages = (int)$pagination['TotalPages'];
                            $start = max(1, $currentPage - 2);
                            $end = min($totalPages, $start + 4);
                            $start = max(1, $end - 4);
                            if ($start > 1):
                            ?>
                                <a class="pagination-page" href="?<?php echo htmlspecialchars(http_build_query(array_merge($_GET, ['page' => 1])), ENT_QUOTES, 'UTF-8'); ?>">1</a>
                                <?php if ($start > 2): ?><span class="pagination-ellipsis">...</span><?php endif; ?>
                            <?php endif; ?>

                            <?php for ($p = $start; $p <= $end; $p++): ?>
                                <a class="pagination-page<?php echo $p === $currentPage ? ' is-active' : ''; ?>" href="?<?php echo htmlspecialchars(http_build_query(array_merge($_GET, ['page' => $p])), ENT_QUOTES, 'UTF-8'); ?>"><?php echo $p; ?></a>
                            <?php endfor; ?>

                            <?php if ($end < $totalPages): ?>
                                <?php if ($end < $totalPages - 1): ?><span class="pagination-ellipsis">...</span><?php endif; ?>
                                <a class="pagination-page" href="?<?php echo htmlspecialchars(http_build_query(array_merge($_GET, ['page' => $totalPages])), ENT_QUOTES, 'UTF-8'); ?>"><?php echo $totalPages; ?></a>
                            <?php endif; ?>

                            <?php if ((int)$pagination['CurrentPage'] < $totalPages): ?>
                                <a class="pagination-btn" href="?<?php echo htmlspecialchars(http_build_query(array_merge($_GET, ['page' => (int)$pagination['CurrentPage'] + 1])), ENT_QUOTES, 'UTF-8'); ?>" aria-label="Next page">›</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </section>
    </div>
<?php render_footer(); ?>
