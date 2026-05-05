<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../lib/ddf.php';

try { $featuredListings = ddf_get_featured_listings('Toronto', 6); } catch (Exception $e) { $featuredListings = []; }

$steps = [
    ['title' => 'Consultation', 'icon' => '🤝', 'desc' => 'We sit down to understand your needs, lifestyle, and budget.'],
    ['title' => 'Pre-Approval', 'icon' => '🔎', 'desc' => 'We connect you with top mortgage brokers to secure your rate.'],
    ['title' => 'The Hunt', 'icon' => '📍', 'desc' => 'We curate a list of on and off-market properties that match your criteria.'],
    ['title' => 'Closing', 'icon' => '🔑', 'desc' => 'We negotiate fiercely on your behalf and handle all the paperwork.'],
];

render_header('Buy a Home in Toronto', 'Find your perfect home in Toronto. Expert buyer representation, neighborhood guides, and off-market access with Muhammad Arshad Khan.', '/buyers/');
?>

<!-- ====== HERO — Shared hero frame for consistent styling ====== -->
<section class="content-shell">
    <div class="site-container grid-split">
        <div class="hero-image-frame" style="min-height:420px;">
            <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1920&q=80" alt="Family in front of home">
            <div class="hero-overlay-dark"></div>
            <div class="hero-copy">
                <div>
                    <div class="section-label">Buyer Representation</div>
                    <h1 class="home-hero h1 dark-heading">Find Your Perfect Home<br><span style="color:var(--gold); font-style:italic;">in Toronto</span></h1>
                    <p class="muted" style="max-width:36rem; font-size:1.05rem; line-height:1.7;">Exclusive access to the best properties, unparalleled market insights, and stress-free purchasing.</p>
                    <div style="margin-top: 1.25rem;"><a class="btn-primary" href="/listings/">Browse Listings</a> <a class="btn-outline-gold" href="/contact/">Book a Buyer Strategy Session</a></div>
                </div>
            </div>
        </div>

        <div>
            <div class="section-label">Buyer Representation</div>
            <h2 class="dark-heading" style="font-size: clamp(2rem, 4vw, 3.2rem); margin: 0 0 1rem;">Your Expert Guide to Buying in Toronto</h2>
            <p class="muted" style="max-width:36rem;">We deliver curated listings, off-market access, and negotiation expertise to secure your ideal home.</p>
        </div>
    </div>
</section>

<!-- ====== THE PROCESS — White bg, 4 step cards ====== -->
<section style="padding:5rem 0; background:var(--bg-light); color:#1A1A2E;">
    <div class="site-container">
        <div style="text-align:center; margin-bottom:3rem;">
            <div class="section-label" style="justify-content:center; color:var(--gold);">The Process</div>
            <h2 style="font-size:clamp(2rem,4vw,3.2rem); color:#0B1F3A; margin:0.75rem 0 0;">Your Journey Home</h2>
        </div>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:1.5rem;">
            <?php foreach ($steps as $i => $step): ?>
            <div class="reveal-el" style="position:relative; padding:1.75rem; border:1px solid rgba(0,0,0,0.08); border-radius:8px; background:#F9F7F4;">
                <div style="position:absolute; top:1rem; right:1rem; font-size:3.5rem; font-weight:700; color:rgba(201,161,74,0.12); line-height:1;">0<?php echo $i + 1; ?></div>
                <div style="font-size:1.5rem; margin-bottom:1rem; position:relative; z-index:1; color:var(--gold);"><?php echo $step['icon']; ?></div>
                <h3 style="font-size:1.5rem; color:#0B1F3A; margin:0 0 0.5rem; position:relative; z-index:1;"><?php echo htmlspecialchars($step['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                <p style="color:rgba(26,26,46,0.75); margin:0; line-height:1.7; position:relative; z-index:1;"><?php echo htmlspecialchars($step['desc'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ====== FEATURED LISTINGS from DDF ====== -->
<?php if (!empty($featuredListings)): ?>
<section class="featured-listings-section">
    <div class="site-container">
        <div class="section-header-row">
            <div>
                <div class="section-label">Featured Listings</div>
                <h2 class="section-heading-lg">Recently Updated in Toronto</h2>
            </div>
            <a href="/listings/" class="section-link hide-mobile">View All Listings →</a>
        </div>
        <div class="property-grid">
            <?php foreach ($featuredListings as $prop):
                $media = $prop['Media'][0]['MediaURL'] ?? null;
                $address = $prop['UnparsedAddress'] ?? (($prop['StreetNumber'] ?? '') . ' ' . ($prop['StreetName'] ?? ''));
                $price = isset($prop['ListPrice']) ? '$' . number_format((float)$prop['ListPrice']) : 'Contact for price';
                $beds = $prop['BedroomsTotal'] ?? null;
                $baths = $prop['BathroomsTotalInteger'] ?? null;
                $link = '/listings/view.php?id=' . urlencode($prop['ListingKey'] ?? $prop['ListingId'] ?? '');
            ?>
            <a class="property-card reveal-el" href="<?php echo htmlspecialchars($link, ENT_QUOTES, 'UTF-8'); ?>">
                <?php if ($media): ?><div class="card-img"><img src="<?php echo htmlspecialchars($media, ENT_QUOTES, 'UTF-8'); ?>" alt="Property" loading="lazy"></div><?php endif; ?>
                <div class="card-body">
                    <div class="card-price"><?php echo $price; ?></div>
                    <div class="card-address"><?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?></div>
                    <div class="card-specs"><?php echo ($beds !== null ? $beds . ' bd' : '') . ($beds !== null && $baths !== null ? ' · ' : '') . ($baths !== null ? $baths . ' ba' : ''); ?></div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <div class="show-mobile"><a href="/listings/" class="section-link">View All Listings →</a></div>
    </div>
</section>
<?php endif; ?>

<!-- ====== FIRST-TIME HOME BUYER CTA — Navy bg ====== -->
<section style="padding:5rem 0; background:var(--navy); text-align:center;">
    <div class="site-container" style="max-width:52rem;">
        <h2 style="font-size:clamp(2rem,4vw,3.2rem); color:white; margin:0 0 1.5rem;">First-Time Home Buyer?</h2>
        <p style="font-size:1.2rem; color:rgba(255,255,255,0.8); margin:0 auto 2rem; max-width:40rem; line-height:1.7;">Navigating the Toronto market for the first time can be daunting. We offer specialized guidance for first-time buyers, helping you unlock tax rebates and incentives.</p>
        <a class="btn-primary" href="/contact/">Get Your Free FTHB Guide</a>
    </div>
</section>

<?php render_footer(); ?>
