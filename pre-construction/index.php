<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';

$explainers = [
    ['title' => 'What is it?', 'icon' => '🏢', 'desc' => 'Buying a property before or during its construction phase, typically requiring deposits over an extended period rather than a lump sum.'],
    ['title' => 'The Benefits', 'icon' => '📈', 'desc' => 'Secure today\'s pricing for tomorrow\'s real estate. Customize finishes, benefit from extended deposit structures, and enjoy potential appreciation before move-in.'],
    ['title' => 'Risks to Know', 'icon' => '⚠️', 'desc' => 'Construction delays, changing market conditions upon closing, and builder cancellations. We guide you towards reputable developers to mitigate these risks.'],
];

$projects = [
    ['name' => 'The Avery', 'dev' => 'Cresford Developments', 'loc' => 'Downtown Core', 'price' => '750k', 'occ' => '2027', 'img' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&q=80', 'status' => 'Now Selling', 'beds' => '1-3', 'type' => 'Condo'],
    ['name' => 'Lumina Towers', 'dev' => 'Tridel', 'loc' => 'North York', 'price' => '620k', 'occ' => '2026', 'img' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800&q=80', 'status' => 'Coming Soon', 'beds' => '1-2', 'type' => 'Condo'],
    ['name' => 'Harbour Edge', 'dev' => 'Pinnacle', 'loc' => 'Waterfront', 'price' => '890k', 'occ' => '2028', 'img' => 'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?w=800&q=80', 'status' => 'VIP Registration', 'beds' => '2-4', 'type' => 'Condo + Town'],
    ['name' => 'Oakline Residences', 'dev' => 'Mattamy Homes', 'loc' => 'Milton', 'price' => '680k', 'occ' => '2027', 'img' => 'https://images.unsplash.com/photo-1460317442991-0ec209397118?w=800&q=80', 'status' => 'Now Selling', 'beds' => '2-4', 'type' => 'Townhomes'],
    ['name' => 'East Port District', 'dev' => 'Great Gulf', 'loc' => 'Pickering', 'price' => '710k', 'occ' => '2028', 'img' => 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?w=800&q=80', 'status' => 'VIP Registration', 'beds' => '1-3', 'type' => 'Condo'],
    ['name' => 'Queensway Collection', 'dev' => 'Menkes', 'loc' => 'Etobicoke', 'price' => '770k', 'occ' => '2029', 'img' => 'https://images.unsplash.com/photo-1494526585095-c41746248156?w=800&q=80', 'status' => 'Coming Soon', 'beds' => '1-3', 'type' => 'Condo'],
];

$roadmap = [
    ['step' => '01', 'title' => 'Strategy Call', 'desc' => 'Define your budget, timeline, and target neighborhoods before you register.'],
    ['step' => '02', 'title' => 'Project Shortlist', 'desc' => 'Get a curated list of high-potential developments matching your criteria.'],
    ['step' => '03', 'title' => 'VIP Allocation', 'desc' => 'Receive first-round pricing, worksheets, and preferred unit selection guidance.'],
    ['step' => '04', 'title' => 'Secure & Close', 'desc' => 'Offer review, deposit structure planning, and closing support end-to-end.'],
];

render_header(
    'Pre-Construction Condos',
    'VIP access to Canada\'s newest pre-construction condo projects. Get floor plans and pricing before the public launch.',
    '/pre-construction/'
);
?>

<!-- ====== HERO ====== -->
<section class="content-shell">
    <div class="site-container grid-split">
        <div class="hero-image-frame" style="min-height:420px;">
            <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1920&q=80" alt="Modern pre-construction development">
            <div class="hero-overlay-dark"></div>
            <div class="hero-copy">
                <div>
                    <div class="section-label">Pre-Construction Advisory</div>
                    <h1 class="home-hero h1 dark-heading">Handpicked Condos<br><span style="color:var(--gold); font-style:italic;">for Smart Buyers</span></h1>
                    <p class="muted" style="max-width:36rem; font-size:1.05rem; line-height:1.7;">Get platinum launch access, real deposit planning, and unbiased guidance on projects with stronger builder track records.</p>
                    <div style="margin-top: 1.25rem;">
                        <a class="btn-primary" href="#vip-access">Get VIP Access</a>
                        <a class="btn-outline-gold" href="/contact/">Book Strategy Call</a>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="section-label">Investing in Pre-Con</div>
            <h2 class="dark-heading" style="font-size: clamp(2rem, 4vw, 3.2rem); margin: 0 0 1rem;">Secure Today's Price for Tomorrow's Market</h2>
            <p class="muted" style="max-width:36rem;">From first worksheet to final allocation, we keep the process clear and strategic. We help you understand unit mix, assignment flexibility, carrying costs, and builder credibility before you commit.</p>
        </div>
    </div>
</section>

<!-- ====== WHAT YOU NEED TO KNOW ====== -->
<section style="padding:5rem 0; background:var(--bg-light); color:#1A1A2E;">
    <div class="site-container">
        <div style="text-align:center; margin-bottom:3rem;">
            <div class="section-label" style="justify-content:center; color:var(--gold);">Pre-Construction Basics</div>
            <h2 style="font-size:clamp(2rem,4vw,3.2rem); color:#0B1F3A; margin:0.75rem 0 0;">What You Need to Know</h2>
        </div>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:1.5rem;">
            <?php foreach ($explainers as $i => $step): ?>
            <div class="reveal-el" style="position:relative; padding:1.75rem; border:1px solid rgba(0,0,0,0.08); border-radius:8px; background:#F9F7F4;">
                <div style="font-size:1.5rem; margin-bottom:1rem; position:relative; z-index:1; color:var(--gold);"><?php echo $step['icon']; ?></div>
                <h3 style="font-size:1.5rem; color:#0B1F3A; margin:0 0 0.5rem; position:relative; z-index:1;"><?php echo htmlspecialchars($step['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                <p style="color:rgba(26,26,46,0.75); margin:0; line-height:1.7; position:relative; z-index:1;"><?php echo htmlspecialchars($step['desc'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ====== FEATURED PROJECTS ====== -->
<?php if (!empty($projects)): ?>
<section class="featured-listings-section" style="background:var(--navy);">
    <div class="site-container">
        <div class="section-header-row">
            <div>
                <div class="section-label">Featured Developments</div>
                <h2 class="section-heading-lg" style="color:white;">Active Projects</h2>
            </div>
            <a href="#vip-access" class="section-link hide-mobile" style="color:var(--gold);">Get VIP Access →</a>
        </div>
        <div class="property-grid">
            <?php foreach ($projects as $project): ?>
            <a class="property-card reveal-el" href="#vip-access" style="background:#0B111D; border:1px solid rgba(255,255,255,0.08);">
                <div class="card-img" style="position:relative;">
                    <img src="<?php echo htmlspecialchars($project['img'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($project['name'], ENT_QUOTES, 'UTF-8'); ?>" loading="lazy">
                    <div style="position:absolute; top:0.75rem; left:0.75rem; background:rgba(10,14,23,0.9); border:1px solid rgba(201,161,74,0.55); color:var(--gold-light); padding:0.25rem 0.6rem; border-radius:999px; font-size:0.7rem; font-weight:bold; text-transform:uppercase;">
                        <?php echo htmlspecialchars($project['status'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-price" style="color:white;">Starting from $<?php echo htmlspecialchars($project['price'], ENT_QUOTES, 'UTF-8'); ?></div>
                    <div class="card-address" style="color:rgba(255,255,255,0.8);"><?php echo htmlspecialchars($project['name'], ENT_QUOTES, 'UTF-8'); ?> • <?php echo htmlspecialchars($project['loc'], ENT_QUOTES, 'UTF-8'); ?></div>
                    <div class="card-specs" style="color:rgba(255,255,255,0.5);">By <?php echo htmlspecialchars($project['dev'], ENT_QUOTES, 'UTF-8'); ?> • <?php echo htmlspecialchars($project['beds'], ENT_QUOTES, 'UTF-8'); ?> Bed <?php echo htmlspecialchars($project['type'], ENT_QUOTES, 'UTF-8'); ?></div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <div class="show-mobile"><a href="#vip-access" class="section-link" style="color:var(--gold);">Get VIP Access →</a></div>
    </div>
</section>
<?php endif; ?>

<!-- ====== ROADMAP ====== -->
<section style="padding:5rem 0; background:white; color:#1A1A2E;">
    <div class="site-container">
        <div style="text-align:center; margin-bottom:3rem;">
            <div class="section-label" style="justify-content:center; color:var(--gold);">How We Work</div>
            <h2 style="font-size:clamp(2rem,4vw,3.2rem); color:#0B1F3A; margin:0.75rem 0 0;">Simple 4-Step Buying Roadmap</h2>
        </div>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:1.5rem;">
            <?php foreach ($roadmap as $step): ?>
            <div class="reveal-el" style="position:relative; padding:1.75rem; border:1px solid rgba(0,0,0,0.08); border-radius:8px; background:#F9F7F4;">
                <div style="position:absolute; top:1rem; right:1rem; font-size:3.5rem; font-weight:700; color:rgba(201,161,74,0.12); line-height:1;"><?php echo htmlspecialchars($step['step'], ENT_QUOTES, 'UTF-8'); ?></div>
                <h3 style="font-size:1.5rem; color:#0B1F3A; margin:0 0 0.5rem; position:relative; z-index:1;"><?php echo htmlspecialchars($step['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                <p style="color:rgba(26,26,46,0.75); margin:0; line-height:1.7; position:relative; z-index:1;"><?php echo htmlspecialchars($step['desc'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ====== VIP ACCESS CTA ====== -->
<section id="vip-access" style="padding:5rem 0; background:var(--navy); text-align:center;">
    <div class="site-container" style="max-width:52rem;">
        <h2 style="font-size:clamp(2rem,4vw,3.2rem); color:white; margin:0 0 1.5rem;">Get VIP Access Before Public Launch</h2>
        <p style="font-size:1.2rem; color:rgba(255,255,255,0.8); margin:0 auto 2rem; max-width:40rem; line-height:1.7;">Register to receive launch worksheets, floor plans, and first-release pricing ahead of public openings. We will also share honest project comparisons so you can pick with confidence.</p>
        <div style="display:flex; justify-content:center; gap:1rem; flex-wrap:wrap;">
            <a class="btn-primary" href="/contact/">Request VIP Access</a>
            <a class="btn-outline-gold" href="tel:+14168261777">Call 416-826-1777</a>
        </div>
    </div>
</section>

<?php render_footer(); ?>
