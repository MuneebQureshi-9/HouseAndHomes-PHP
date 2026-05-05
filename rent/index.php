<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';

// Featured rental listings (from DDF when available)
require_once __DIR__ . '/../lib/ddf.php';
try {
    $featuredRentals = ddf_get_featured_listings('Toronto', 4);
} catch (Exception $e) {
    $featuredRentals = [];
}

$rentals = [
    ['title' => 'Fully Verified Listings', 'icon' => '🏠', 'desc' => 'Premium condos, homes, and apartments available for lease across Toronto & the GTA.'],
    ['title' => 'MLS® Powered', 'icon' => '🛡️', 'desc' => 'Updated daily with latest leases and supported by expert tenant representation.'],
    ['title' => 'Updated Daily', 'icon' => '🕒', 'desc' => 'Stay ahead of the market with fresh rental opportunities and off-market options.'],
];

render_header(
    'Rentals & Leases in Toronto GTA',
    'Browse premium rental properties and leases in Toronto and the GTA. Find your perfect home to rent with Houses & Homes Toronto.',
    '/rent/'
);
?>

<section class="content-shell">
    <div class="site-container">
        <div class="page-hero-center" style="padding: 1rem 0 2rem;">
            <div class="section-label">Rental Listings</div>
            <h1 class="dark-heading" style="font-size: clamp(2.5rem, 5vw, 4.5rem); margin: 1rem 0 1rem;">Find Your Perfect <span style="color: var(--gold); font-style: italic;">Rental</span></h1>
            <p style="color: rgba(255,255,255,0.72); max-width: 50rem; margin: 0 auto; line-height: 1.8;">Browse premium condos, homes, and apartments available for lease across Toronto and the GTA.</p>
        </div>

        <div class="service-grid" style="margin-top: 2rem;">
            <?php foreach ($rentals as $item): ?>
                <div class="panel">
                    <div class="inline-icon-row" style="margin-bottom:0.75rem;"><span style="font-size:1.35rem; color: var(--gold);"><?php echo $item['icon']; ?></span><strong class="dark-heading"><?php echo htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?></strong></div>
                    <p class="muted" style="margin:0; line-height:1.75;"><?php echo htmlspecialchars($item['desc'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="panel" style="margin-top: 2rem; text-align:center;">
            <h2 class="dark-heading" style="margin-top:0;">Not finding the right rental?</h2>
            <p class="muted" style="max-width: 48rem; margin: 0 auto 1.25rem; line-height:1.8;">Tell us your requirements and we will match you with off-market options and new listings before they hit MLS®.</p>
            <a class="btn-primary" href="/contact/">Contact Us About Rentals</a>
        </div>
    </div>
</section>

<?php render_footer(); ?>

<?php if (!empty($featuredRentals)): ?>
<section class="content-shell">
    <div class="site-container">
        <div class="section-label">Featured Rentals</div>
        <h2 class="dark-heading">Available Rentals in Toronto</h2>
        <div class="card-grid">
            <?php foreach ($featuredRentals as $prop):
                $media = $prop['Media'][0]['MediaURL'] ?? null;
                $address = $prop['UnparsedAddress'] ?? (($prop['StreetNumber'] ?? '') . ' ' . ($prop['StreetName'] ?? ''));
                $price = isset($prop['ListPrice']) ? '$' . number_format($prop['ListPrice']) : 'Contact for price';
                $beds = $prop['BedroomsTotal'] ?? null;
                $baths = $prop['BathroomsTotalInteger'] ?? null;
                $link = '/listings/?id=' . ($prop['ListingId'] ?? $prop['ListingKey'] ?? '');
            ?>
                <a class="property-card" href="<?php echo htmlspecialchars($link, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php if ($media): ?>
                        <div class="property-media"><img src="<?php echo htmlspecialchars($media, ENT_QUOTES, 'UTF-8'); ?>" alt="Property image"></div>
                    <?php endif; ?>
                    <div class="property-info">
                        <div class="property-price"><?php echo $price; ?></div>
                        <div class="property-address"><?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="property-meta"><?php echo ($beds !== null ? $beds . ' bd' : '') . ($beds !== null && $baths !== null ? ' • ' : '') . ($baths !== null ? $baths . ' ba' : ''); ?></div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
