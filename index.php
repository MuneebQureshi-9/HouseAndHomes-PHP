<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

render_header(
    'Find Homes in Canada',
    'Search Canada & GTA real estate with Muhammad Arshad. Expert buyer, seller & rental services. 15+ years experience. Free consultation.',
    '/'
);
?>

<!-- ====== HERO SECTION ====== -->
<section class="home-hero">
    <div class="site-container home-hero-inner">
        <div class="home-hero-copy reveal-el">
            <div class="home-hero-label">Established 2009 — Canada's Premier Real Estate</div>
            <h1>Luxury real estate,<br><em>handled</em> with calm precision.</h1>
            <p>Simple, sharp guidance for buyers, sellers, and investors across the GTA.</p>
            <div class="home-hero-actions">
                <a class="btn-primary" href="/contact/">Book Consultation →</a>
                <a class="btn-outline-gold" href="/listings/">View Listings</a>
            </div>
            <div class="home-hero-mini-meta">
                <span>Private service</span>
                <span>GTA focus</span>
                <span>Fast response</span>
            </div>
        </div>
        <div class="home-hero-media reveal-el">
            <div class="home-hero-frame">
                <div class="home-hero-frame-accent"></div>
                <img src="/assets/images/agent/Muhammad-Arshad.jpeg" alt="Muhammad Arshad — REALTOR® in Canada" loading="eager" fetchpriority="high">
                <div class="home-hero-caption">
                    <p class="hero-agent-name">Muhammad Arshad</p>
                    <p class="hero-agent-role">Licensed REALTOR® in Ontario</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ====== TRUST STATS ====== -->
<section class="trust-stats-section">
    <div class="dot-grid-overlay"></div>
    <div class="site-container">
        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-number" data-count="500" data-suffix="+">0</div>
                <div class="stat-line"></div>
                <div class="stat-label">Homes Sold</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-count="15" data-suffix="+">0</div>
                <div class="stat-line"></div>
                <div class="stat-label">Years in GTA</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-prefix="$" data-count="2" data-suffix="B+">0</div>
                <div class="stat-line"></div>
                <div class="stat-label">In Transactions</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-count="98" data-suffix="%">0</div>
                <div class="stat-line"></div>
                <div class="stat-label">Client Satisfaction</div>
            </div>
        </div>
    </div>
</section>

<!-- ====== HOW IT WORKS ====== -->
<section class="how-it-works-section">
    <div class="site-container">
        <div style="text-align:center; margin-bottom: 4rem;">
            <div class="section-label" style="justify-content:center;">Our Process</div>
            <h2 class="light-heading section-heading-lg">Simple. Transparent. Results-Driven.</h2>
        </div>
        <div class="steps-grid">
            <div class="step-connecting-line"></div>
            <?php
            $steps = [
                ['num' => '01', 'title' => 'Tell Us What You Need', 'desc' => 'Free consultation, zero pressure. We listen, understand your goals, and create a personalized plan to find your perfect property.'],
                ['num' => '02', 'title' => 'We Find Your Match', 'desc' => 'Curated properties, expert shortlisting, and exclusive off-market access. We bring you only the best options that match your criteria.'],
                ['num' => '03', 'title' => 'Close With Confidence', 'desc' => 'Expert negotiation, seamless closing, and post-sale support. We ensure your transaction is flawless from offer to keys in hand.'],
            ];
            foreach ($steps as $step):
            ?>
            <div class="step-card reveal-el">
                <span class="step-bg-number"><?php echo $step['num']; ?></span>
                <div class="step-icon-circle">
                    <span class="step-icon"><?php echo $step['num']; ?></span>
                </div>
                <h3 class="light-heading" style="margin:0 0 0.75rem;"><?php echo $step['title']; ?></h3>
                <p style="color: rgba(26,26,46,0.65); line-height:1.75;"><?php echo $step['desc']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ====== FEATURED AREAS ====== -->
<section class="featured-areas-section">
    <div class="site-container">
        <div class="section-header-row">
            <div>
                <div class="section-label">Areas We Serve</div>
                <h2 class="dark-heading section-heading-lg">Expert Local Knowledge Across the GTA</h2>
            </div>
            <a class="section-link hide-mobile" href="/neighbourhoods/">Explore All Areas →</a>
        </div>
        <div class="areas-grid">
            <?php
            $areas = [
                ['name' => 'Toronto', 'listings' => '320+', 'img' => 'https://images.unsplash.com/photo-1517935706615-2717063c2225?w=800&q=80', 'slug' => 'toronto'],
                ['name' => 'Mississauga', 'listings' => '180+', 'img' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&q=80', 'slug' => 'mississauga'],
                ['name' => 'Brampton', 'listings' => '150+', 'img' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&q=80', 'slug' => 'brampton'],
                ['name' => 'Oakville', 'listings' => '95+', 'img' => 'https://images.unsplash.com/photo-1600607686527-6fb886090705?w=800&q=80', 'slug' => 'oakville'],
                ['name' => 'Milton', 'listings' => '75+', 'img' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&q=80', 'slug' => 'milton'],
                ['name' => 'Greater Toronto Area', 'listings' => '500+', 'img' => 'https://images.unsplash.com/photo-1582407947304-fd86f028f716?w=800&q=80', 'slug' => 'gta'],
            ];
            foreach ($areas as $area):
            ?>
            <a class="area-tile reveal-el" href="/listings/?city=<?php echo urlencode($area['slug']); ?>">
                <img src="<?php echo htmlspecialchars($area['img'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($area['name'], ENT_QUOTES, 'UTF-8'); ?>" loading="lazy">
                <div class="area-overlay"></div>
                <div class="area-content">
                    <h3><?php echo htmlspecialchars($area['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p><?php echo $area['listings']; ?> Active Listings</p>
                </div>
                <div class="area-gold-line"></div>
            </a>
            <?php endforeach; ?>
        </div>
        <a class="section-link show-mobile" href="/neighbourhoods/">Explore All Areas →</a>
    </div>
</section>

<!-- ====== TESTIMONIALS ====== -->
<section class="home-testimonials-section">
    <div class="site-container">
        <div style="text-align:center; margin-bottom: 3rem;">
            <div class="section-label" style="justify-content:center;">Client Stories</div>
            <h2 class="dark-heading section-heading-lg">What Our Clients Say</h2>
        </div>
        <div class="testimonial-grid">
            <?php
            $reviews = [
                ['text' => "Muhammad Arshad's understanding of the Canada market is unparalleled. He didn't just find us a condo; he secured an off-market penthouse that perfectly matched our demanding lifestyle.", 'author' => 'Sarah & James T.', 'area' => 'Yorkville', 'type' => 'Buyer', 'initials' => 'SJ'],
                ['text' => 'Selling a legacy estate requires absolute discretion and a global network. Muhammad Arshad and his team delivered on both fronts, setting a neighborhood record in just 14 days.', 'author' => 'Dr. Robert H.', 'area' => 'Bridle Path', 'type' => 'Seller', 'initials' => 'RH'],
                ['text' => "We were navigating a complex relocation from London. Muhammad Arshad's white-glove concierge service made the transition seamless. We couldn't be happier with our Forest Hill home.", 'author' => 'Emma W.', 'area' => 'Forest Hill', 'type' => 'Buyer', 'initials' => 'EW'],
            ];
            foreach ($reviews as $review):
            ?>
            <div class="testimonial-card reveal-el">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text"><?php echo htmlspecialchars($review['text'], ENT_QUOTES, 'UTF-8'); ?></p>
                <div class="testimonial-divider"></div>
                <div class="testimonial-author">
                    <div class="author-avatar-circle"><?php echo $review['initials']; ?></div>
                    <div>
                        <p class="author-name"><?php echo htmlspecialchars($review['author'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="author-detail"><?php echo $review['area']; ?> · <?php echo $review['type']; ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center; margin-top: 2rem;" class="reveal-el">
            <span class="testimonial-stars" style="display:inline;">★★★★★</span>
            <span class="muted" style="font-size: 0.9rem;"> 4.9 on Google Reviews · </span>
            <a href="/testimonials/" style="color: var(--gold); font-weight: 700; font-size: 0.9rem;">See All 200+ Reviews →</a>
        </div>
    </div>
</section>

<!-- ====== VALUATION CTA ====== -->
<section class="valuation-cta-section">
    <div class="valuation-bg"></div>
    <div class="valuation-overlay"></div>
    <div class="site-container valuation-inner">
        <h2 class="dark-heading" style="font-size: clamp(2.25rem, 5vw, 4rem); text-align:center; margin: 0 0 0.5rem;">What Is Your Home Worth?</h2>
        <p style="text-align:center; color: rgba(255,255,255,0.7); font-style: italic; font-size: clamp(1.1rem, 2vw, 1.5rem); margin: 0 0 2.5rem;">Free, no-obligation market evaluation. Results in 48 hours.</p>
        <form id="valuationForm" class="valuation-form-row">
            <input type="text" name="name" placeholder="Full Name" required class="valuation-input">
            <input type="email" name="email" placeholder="Email Address" required class="valuation-input">
            <input type="tel" name="phone" placeholder="Phone Number" class="valuation-input">
            <input type="text" name="address" placeholder="Property Address" required class="valuation-input">
            <button type="submit" class="btn-primary valuation-btn">Get Valuation →</button>
        </form>
        <div id="valuationMsg" style="text-align:center; margin-top:1rem;"></div>
        <div style="text-align:center; margin-top:1.5rem; color: rgba(201,161,74,0.7); font-size: 0.85rem;">🔒 100% Free · No Commitment · Muhammad Arshad Responds Within 48 Hours</div>
    </div>
</section>

<!-- ====== ADD-ON SERVICES ====== -->
<section class="addon-services-section">
    <div class="site-container">
        <div style="text-align:center; margin-bottom: 3rem;">
            <div class="section-label" style="justify-content:center;">More Than Real Estate</div>
            <h2 class="light-heading section-heading-lg">Every Service You Need</h2>
            <p style="color: rgba(26,26,46,0.65); max-width: 40rem; margin: 1rem auto 0; line-height: 1.85;">We provide an end-to-end ecosystem of services, ensuring every aspect of your real estate journey is handled with excellence.</p>
        </div>
        <div class="addon-grid">
            <?php
            $addons = [
                ['icon' => '🏛️', 'title' => 'Bespoke Mortgage Solutions', 'desc' => 'Exclusive partnerships with private wealth managers and specialized lenders for tailored financing that suits your lifestyle.'],
                ['icon' => '🔍', 'title' => 'Home Inspection Services', 'desc' => 'Pre-listing and buyer inspections with certified professionals, ensuring your investment is protected from day one.'],
                ['icon' => '🛋️', 'title' => 'Strategic Home Staging', 'desc' => 'Our design partners transform your property with curated furnishings to maximize emotional appeal and final sale price.'],
                ['icon' => '🏢', 'title' => 'Property Management', 'desc' => 'Full-service management for your investment properties, from tenant screening to maintenance coordination.'],
            ];
            foreach ($addons as $addon):
            ?>
            <div class="addon-card reveal-el">
                <div class="addon-gold-line"></div>
                <div class="addon-icon"><?php echo $addon['icon']; ?></div>
                <h3 class="light-heading" style="font-size: 1.15rem; margin: 0 0 0.75rem;"><?php echo $addon['title']; ?></h3>
                <p style="color: rgba(26,26,46,0.65); line-height: 1.75; font-size: 0.95rem;"><?php echo $addon['desc']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ====== PRE-CONSTRUCTION SPOTLIGHT ====== -->
<section class="precon-spotlight-section">
    <div class="dot-grid-overlay"></div>
    <div class="site-container" style="position:relative; z-index:1;">
        <div style="margin-bottom: 3rem;">
            <div class="section-label">Pre-Construction</div>
            <h2 class="dark-heading section-heading-lg">Get In Before The Price Goes Up</h2>
            <p class="muted" style="max-width: 40rem; margin-top: 0.75rem;">VIP access to Canada's newest condo projects before public launch.</p>
        </div>
        <div class="precon-card reveal-el">
            <div class="precon-card-img">
                <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&q=80" alt="Pre-construction project">
                <div class="precon-img-overlay"></div>
            </div>
            <div class="precon-card-content">
                <span class="precon-badge">Now Selling</span>
                <h3 class="dark-heading" style="font-size: clamp(1.5rem, 3vw, 2.25rem); margin: 0 0 1rem;">The Residences at King West</h3>
                <div class="precon-meta">
                    <span>📍 King West, Toronto</span>
                    <span>💰 Starting from $685,000</span>
                    <span>📅 Occupancy 2027</span>
                </div>
                <p class="muted" style="line-height: 1.75; margin-bottom: 2rem; max-width: 36rem;">An exclusive collection of 42 residences in the heart of King West. Premium finishes, private terraces, and direct subway access. Platinum VIP pricing available for a limited time.</p>
                <div style="display:flex; flex-wrap:wrap; gap: 1rem;">
                    <a class="btn-primary" href="/pre-construction/">Get VIP Floor Plans →</a>
                    <a class="btn-outline-gold" href="/contact/">Register for VIP Access</a>
                </div>
            </div>
        </div>
        <div style="text-align:center; margin-top: 2.5rem;">
            <a href="/pre-construction/" class="muted" style="font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em;">View All Pre-Construction Projects →</a>
        </div>
    </div>
</section>

<!-- ====== BLOG PREVIEW ====== -->
<section class="blog-preview-section">
    <div class="site-container">
        <div class="section-header-row">
            <div>
                <div class="section-label">Market Intelligence</div>
                <h2 class="dark-heading section-heading-lg">Stay Ahead of the Market</h2>
            </div>
            <a class="section-link hide-mobile" href="/blog/">Visit Our Blog →</a>
        </div>
        <?php
        $articles = [
            ['title' => 'Toronto Real Estate Market Forecast 2026: Trends & Predictions', 'excerpt' => "Explore expert analysis on Toronto's housing market for 2026. From interest rate shifts to inventory levels, discover what buyers and sellers need to know.", 'date' => 'JAN 15, 2026', 'cat' => 'Market Outlook', 'img' => 'https://images.unsplash.com/photo-1514924013411-cbf25faa35bb?w=800&q=80', 'featured' => true],
            ['title' => 'How to Invest in Toronto Pre-Construction Condos for Maximum ROI', 'excerpt' => 'Master the art of pre-construction investing. Learn how to identify Tier-1 developers and high-growth transit-oriented communities.', 'date' => 'JAN 10, 2026', 'cat' => 'Investment', 'img' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&q=80', 'featured' => false],
            ['title' => 'Luxury Home Selling Guide: Get Top Dollar for Your Toronto Property', 'excerpt' => 'Selling a high-end estate requires more than just a listing. Discover advanced staging and global marketing strategies.', 'date' => 'JAN 05, 2026', 'cat' => 'Selling', 'img' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&q=80', 'featured' => false],
        ];
        ?>
        <div class="blog-layout">
            <?php foreach ($articles as $index => $article): ?>
            <?php if ($article['featured']): ?>
            <a class="blog-featured reveal-el" href="/blog/">
                <div class="blog-featured-img">
                    <img src="<?php echo htmlspecialchars($article['img'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?>" loading="lazy">
                    <div class="blog-img-overlay"></div>
                    <div class="blog-featured-content">
                        <span class="blog-cat-badge"><?php echo $article['cat']; ?></span>
                        <h3><?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p><?php echo htmlspecialchars($article['excerpt'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <span class="blog-date"><?php echo $article['date']; ?></span>
                    </div>
                </div>
            </a>
            <?php endif; ?>
            <?php endforeach; ?>
            <div class="blog-secondary">
                <?php foreach ($articles as $article): ?>
                <?php if (!$article['featured']): ?>
                <a class="blog-side-item reveal-el" href="/blog/">
                    <div class="blog-side-img"><img src="<?php echo htmlspecialchars($article['img'], ENT_QUOTES, 'UTF-8'); ?>" alt="" loading="lazy"></div>
                    <div>
                        <span class="blog-side-cat"><?php echo $article['cat']; ?></span>
                        <h3 class="blog-side-title"><?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <span class="blog-date"><?php echo $article['date']; ?></span>
                    </div>
                </a>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <a class="section-link show-mobile" href="/blog/">Visit Our Blog →</a>
    </div>
</section>

<?php render_footer(); ?>
