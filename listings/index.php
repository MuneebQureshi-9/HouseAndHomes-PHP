<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../lib/ddf.php';

$cityProvinceOptions = [
    'Provinces' => ['Alberta', 'British Columbia', 'Manitoba', 'New Brunswick', 'Newfoundland and Labrador', 'Nova Scotia', 'Ontario', 'Prince Edward Island', 'Quebec', 'Saskatchewan', 'Northwest Territories', 'Nunavut', 'Yukon'],
    'Cities' => ['Abbotsford', 'Airdrie', 'Barrie', 'Brampton', 'Brandon', 'Burlington', 'Burnaby', 'Caledon', 'Calgary', 'Cambridge', 'Charlottetown', 'Chatham-Kent', 'Coquitlam', 'Delta', 'Drummondville', 'Edmonton', 'Fredericton', 'Gatineau', 'Guelph', 'Halifax', 'Hamilton', 'Iqaluit', 'Kamloops', 'Kanata', 'Kelowna', 'Kingston', 'Kitchener', 'Langley', 'Laval', 'Lethbridge', 'Longueuil', 'London', 'Markham', 'Medicine Hat', 'Milton', 'Mirabel', 'Mississauga', 'Moncton', 'Montreal', 'Nanaimo', 'New Westminster', 'Niagara Falls', 'North Bay', 'North Vancouver', 'Oakville', 'Oshawa', 'Ottawa', 'Peterborough', 'Pickering', 'Port Coquitlam', 'Prince George', 'Quebec City', 'Red Deer', 'Regina', 'Richmond', 'Richmond Hill', 'Saanich', 'Saskatoon', 'Sherbrooke', 'St. Catharines', 'St. John\'s', 'Surrey', 'Terrebonne', 'Thunder Bay', 'Toronto', 'Trois-Rivieres', 'Vancouver', 'Vaughan', 'Victoria', 'Waterloo', 'Welland', 'Whitehorse', 'Windsor', 'Winnipeg', 'Yellowknife'],
];

render_header(
    'Exclusive Listings',
    'Discover Canada\'s finest properties for sale. From architectural masterpieces in the sky to timeless estates in prestigious enclaves.',
    '/listings/'
);
?>
    <style>
    /* Skeleton loading animation */
    @keyframes shimmer {
        0% { background-position: -400px 0; }
        100% { background-position: 400px 0; }
    }
    .skeleton-card {
        border-radius: 22px;
        overflow: hidden;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.06);
    }
    .skeleton-image {
        width: 100%; aspect-ratio: 16/10;
        background: linear-gradient(90deg, rgba(255,255,255,0.04) 25%, rgba(255,255,255,0.08) 50%, rgba(255,255,255,0.04) 75%);
        background-size: 800px 100%;
        animation: shimmer 1.5s infinite ease-in-out;
    }
    .skeleton-body { padding: 1.1rem 1.2rem 1.3rem; }
    .skeleton-line {
        height: 14px; border-radius: 6px; margin-bottom: 0.65rem;
        background: linear-gradient(90deg, rgba(255,255,255,0.06) 25%, rgba(255,255,255,0.1) 50%, rgba(255,255,255,0.06) 75%);
        background-size: 800px 100%;
        animation: shimmer 1.5s infinite ease-in-out;
    }
    .skeleton-line.w60 { width: 60%; }
    .skeleton-line.w80 { width: 80%; }
    .skeleton-line.w40 { width: 40%; height: 22px; }
    .skeleton-line.w50 { width: 50%; }

    .listings-grid { opacity: 1; transition: opacity 0.3s; }
    .listings-grid.is-loading { opacity: 0.5; pointer-events: none; }
    .listings-fade-in { animation: fadeInUp 0.35s ease both; }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
    </style>

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

                <form id="listingsFilterForm" method="get" action="/listings/" class="listings-filter-card">
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
                            <select id="city" name="city">
                                <option value="">All Cities / Provinces</option>
                                <?php $selectedCity = (string)($_GET['city'] ?? ''); ?>
                                <?php foreach ($cityProvinceOptions as $groupLabel => $groupOptions): ?>
                                    <optgroup label="<?php echo htmlspecialchars($groupLabel, ENT_QUOTES, 'UTF-8'); ?>">
                                        <?php foreach ($groupOptions as $option): ?>
                                            <option value="<?php echo htmlspecialchars($option, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $selectedCity === $option ? 'selected' : ''; ?>><?php echo htmlspecialchars($option, ENT_QUOTES, 'UTF-8'); ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
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

                <div class="listings-count-row" id="listingsCount">
                    <span style="color:rgba(255,255,255,0.4)">Loading listings...</span>
                </div>

                <div class="listings-grid" id="listingsGrid">
                    <!-- Skeleton cards shown while loading -->
                    <?php for ($i = 0; $i < 6; $i++): ?>
                    <div class="skeleton-card">
                        <div class="skeleton-image"></div>
                        <div class="skeleton-body">
                            <div class="skeleton-line w60"></div>
                            <div class="skeleton-line w40"></div>
                            <div class="skeleton-line w80"></div>
                            <div class="skeleton-line w50"></div>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>

                <div class="listings-pagination" id="listingsPagination"></div>
            </div>
        </section>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var grid = document.getElementById('listingsGrid');
    var countRow = document.getElementById('listingsCount');
    var paginationEl = document.getElementById('listingsPagination');
    var form = document.getElementById('listingsFilterForm');

    function getFiltersFromURL() {
        var params = new URLSearchParams(window.location.search);
        return params;
    }

    function escapeHtml(str) {
        if (!str) return '';
        var div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function renderCard(p, index) {
        var imgHtml = p.image
            ? '<img src="' + escapeHtml(p.image) + '" alt="' + escapeHtml(p.address) + '" loading="lazy">'
            : '<div class="listing-image-placeholder">No image</div>';

        var metaParts = [];
        if (p.beds !== null && p.beds !== '') metaParts.push('<span>🛏 ' + parseInt(p.beds) + ' Beds</span>');
        if (p.baths !== null && p.baths !== '') metaParts.push('<span>🛁 ' + parseInt(p.baths) + ' Baths</span>');
        if (p.storeys !== null && p.storeys !== '') metaParts.push('<span>🧱 ' + parseInt(p.storeys) + ' Storeys</span>');
        else if (p.area) metaParts.push('<span>◻ ' + escapeHtml(p.area) + '</span>');

        var delay = Math.min(index * 0.05, 0.6);

        return '<article class="property-card listing-card listings-fade-in" style="animation-delay:' + delay + 's">' +
            '<a class="listing-card-link" href="/listings/view.php?id=' + encodeURIComponent(p.id) + '" aria-label="View ' + escapeHtml(p.address) + '"></a>' +
            '<div class="listing-image-wrap">' + imgHtml +
            '<span class="listing-badge">' + escapeHtml(p.status) + '</span>' +
            '<button class="listing-save-btn" type="button" aria-label="Save property">♡</button></div>' +
            '<div class="listing-card-body">' +
            '<div class="listing-type">' + escapeHtml(p.type) + '</div>' +
            '<div class="listing-price">' + escapeHtml(p.price) + '</div>' +
            '<div class="listing-address" title="' + escapeHtml(p.address) + '">' + escapeHtml(p.address) + '</div>' +
            '<div class="listing-meta-row">' + metaParts.join('') + '</div>' +
            '<div class="listing-attribution">Listed by ' + escapeHtml(p.source) + (p.photos > 0 ? ' · ' + p.photos + ' Photos' : '') + '</div>' +
            '</div></article>';
    }

    function renderPagination(pag, currentParams) {
        if (!pag || pag.TotalPages <= 1) { paginationEl.innerHTML = ''; return; }

        var curr = parseInt(pag.CurrentPage);
        var total = parseInt(pag.TotalPages);
        var html = '';

        function pageUrl(p) {
            var params = new URLSearchParams(currentParams);
            params.set('page', p);
            return '?' + params.toString();
        }

        if (curr > 1) html += '<a class="pagination-btn" href="' + pageUrl(curr - 1) + '" aria-label="Previous page">‹</a>';

        var start = Math.max(1, curr - 2);
        var end = Math.min(total, start + 4);
        start = Math.max(1, end - 4);

        if (start > 1) {
            html += '<a class="pagination-page" href="' + pageUrl(1) + '">1</a>';
            if (start > 2) html += '<span class="pagination-ellipsis">...</span>';
        }

        for (var p = start; p <= end; p++) {
            html += '<a class="pagination-page' + (p === curr ? ' is-active' : '') + '" href="' + pageUrl(p) + '">' + p + '</a>';
        }

        if (end < total) {
            if (end < total - 1) html += '<span class="pagination-ellipsis">...</span>';
            html += '<a class="pagination-page" href="' + pageUrl(total) + '">' + total + '</a>';
        }

        if (curr < total) html += '<a class="pagination-btn" href="' + pageUrl(curr + 1) + '" aria-label="Next page">›</a>';

        paginationEl.innerHTML = html;
    }

    function fetchListings() {
        var params = getFiltersFromURL();
        var apiUrl = '/api/listings.php?' + params.toString();

        fetch(apiUrl)
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (!data.success || !data.properties || data.properties.length === 0) {
                    var cityFilter = params.get('city');
                    countRow.innerHTML = 'Showing <strong>0</strong> of <strong>0</strong> properties' + (cityFilter ? ' in ' + escapeHtml(cityFilter) : '');
                    grid.innerHTML = '<div class="listings-empty-state">No listings available for the selected filters.</div>';
                    paginationEl.innerHTML = '';
                    return;
                }

                var props = data.properties;
                var pag = data.pagination;
                var cityFilter = params.get('city');

                countRow.innerHTML = 'Showing <strong>' + props.length + '</strong> of <strong>' + (pag.TotalRecords || props.length) + '</strong> properties' + (cityFilter ? ' in ' + escapeHtml(cityFilter) : '');

                var html = '';
                for (var i = 0; i < props.length; i++) {
                    html += renderCard(props[i], i);
                }
                grid.innerHTML = html;

                renderPagination(pag, params);
            })
            .catch(function(err) {
                console.error('Listings fetch error:', err);
                countRow.innerHTML = 'Showing <strong>0</strong> of <strong>0</strong> properties';
                grid.innerHTML = '<div class="listings-empty-state">Unable to load listings. Please try again.</div>';
            });
    }

    // Intercept form submit to use AJAX instead of full page reload
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(form);
            var params = new URLSearchParams();
            formData.forEach(function(value, key) {
                if (value !== '') params.set(key, value);
            });
            // Update URL without reload
            var newUrl = '/listings/' + (params.toString() ? '?' + params.toString() : '');
            window.history.pushState({}, '', newUrl);
            // Show loading state
            grid.classList.add('is-loading');
            countRow.innerHTML = '<span style="color:rgba(255,255,255,0.4)">Searching...</span>';
            fetchListings();
            grid.classList.remove('is-loading');
        });
    }

    // Handle back/forward navigation
    window.addEventListener('popstate', function() {
        grid.innerHTML = '';
        for (var i = 0; i < 6; i++) {
            grid.innerHTML += '<div class="skeleton-card"><div class="skeleton-image"></div><div class="skeleton-body"><div class="skeleton-line w60"></div><div class="skeleton-line w40"></div><div class="skeleton-line w80"></div><div class="skeleton-line w50"></div></div></div>';
        }
        fetchListings();
    });

    // Initial load
    fetchListings();
});
</script>

<?php render_footer(); ?>
