<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';

$values = [
    ['title' => 'Integrity', 'description' => 'We operate with complete transparency. No hidden fees, no empty promises - just honest advice you can trust.', 'icon' => '🛡️'],
    ['title' => 'Expertise', 'description' => '15+ years of navigating the complex GTA market gives our clients a distinct competitive advantage.', 'icon' => '🎯'],
    ['title' => 'Community', 'description' => 'We do not just sell houses; we help build communities. We are deeply invested in the neighborhoods we serve.', 'icon' => '🤝'],
    ['title' => 'Results', 'description' => 'A proven track record of securing the best possible price and terms for our clients, in any market condition.', 'icon' => '🏆'],
];

render_header(
    'About Muhammad Arshad',
    'Learn about Muhammad Arshad, your trusted GTA REALTOR with over 15 years of experience in the Canada real estate market.',
    '/about/'
);
?>

<style>
.about-hero {
    position: relative;
    min-height: min(60vh, 500px);
    display: flex;
    align-items: center;
    background-image: linear-gradient(to right, rgba(5, 8, 14, 0.95), rgba(5, 8, 14, 0.8) 40%, transparent), url('/assets/images/agent/Muhammad-Arshad.jpeg');
    background-size: cover;
    background-position: center 20%;
    color: white;
}
.about-story {
    background: var(--navy);
    color: white;
    padding: 5rem 0;
}
.about-story-grid {
    display: grid;
    gap: 3rem;
    align-items: center;
}
@media (min-width: 900px) {
    .about-story-grid {
        grid-template-columns: 1fr 1fr;
        gap: 5rem;
    }
}
.about-story-image-wrap {
    position: relative;
    max-width: 400px;
    margin: 0 auto;
}
.about-story-image-wrap::before {
    content: "";
    position: absolute;
    top: -2rem; left: -2rem;
    width: 8rem; height: 8rem;
    background: rgba(201, 161, 74, 0.1);
    border-radius: 50%;
}
.about-story-image-wrap::after {
    content: "";
    position: absolute;
    bottom: -2rem; right: -2rem;
    width: 12rem; height: 12rem;
    background: rgba(11, 31, 58, 0.5);
    border-radius: 50%;
}
.about-story-image {
    position: relative;
    z-index: 1;
    border-radius: 8px;
    overflow: hidden;
    aspect-ratio: 4/5;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}
.about-story-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>

<!-- ====== HERO SECTION ====== -->
<section class="about-hero">
    <div class="site-container">
        <div style="max-width: 40rem;">
            <div class="section-label" style="border-color: rgba(255,255,255,0.3); color: white;">Meet Your Agent</div>
            <h1 class="h1 dark-heading" style="margin: 0.5rem 0; font-size: clamp(3.2rem, 7vw, 6rem); line-height: 0.98;">Muhammad<br><span style="color: var(--gold); font-style: italic;">Arshad Khan</span></h1>
            <p style="font-size: clamp(1.1rem, 2vw, 1.4rem); color: rgba(255,255,255,0.8); font-weight: 300; line-height: 1.6; margin-top: 1rem;">Your Trusted GTA REALTOR® for 15+ Years</p>
        </div>
    </div>
</section>

<!-- ====== STORY SECTION ====== -->
<section class="about-story">
    <div class="site-container about-story-grid">
        <div class="reveal-el">
            <div class="section-label">Our Story</div>
            <h2 class="dark-heading" style="font-size: clamp(2rem, 4vw, 3rem); line-height: 1.1; margin: 0 0 1.5rem;">A Realtor by Choice,<br><span style="color: var(--gold); font-style: italic;">Not by Chance</span></h2>
            <div style="color: rgba(255,255,255,0.8); font-size: 1.05rem; line-height: 1.8; display: grid; gap: 1rem;">
                <p>My journey in real estate began over a decade ago with a simple realization: buying or selling a home is often the most significant financial and emotional decision of a person's life. Yet, too often, the process feels transactional and stressful.</p>
                <p>I established Houses & Homes Toronto to change that. I believe that exceptional real estate service goes beyond just closing a deal; it's about providing strategic counsel, anticipating challenges before they arise, and acting as a steadfast advocate for my clients' best interests.</p>
                <p>Whether you're acquiring a luxury estate, seeking a high-yield pre-construction investment, or selling a cherished family home, my approach remains the same: meticulous preparation, aggressive marketing, and unyielding negotiation.</p>
            </div>
            
            <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; margin-top: 1.5rem;">
                <span style="padding: 0.5rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 4px; font-size: 0.85rem; font-weight: 600;">🏆 15+ Years Experience</span>
                <span style="padding: 0.5rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 4px; font-size: 0.85rem; font-weight: 600;">📍 Toronto & GTA Focus</span>
                <span style="padding: 0.5rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 4px; font-size: 0.85rem; font-weight: 600;">✅ Licensed REALTOR®</span>
                <span style="padding: 0.5rem 1rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 4px; font-size: 0.85rem; font-weight: 600;">🤝 200+ Families Served</span>
            </div>

            <div style="margin-top: 2rem;">
                <a class="btn-primary" href="/contact/">Book a Consultation</a>
            </div>
        </div>

        <div class="about-story-image-wrap reveal-el">
            <div class="about-story-image">
                <img src="/assets/images/agent/Muhammad-Arshad.jpeg" alt="Muhammad Arshad">
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

<!-- ====== VALUES SECTION ====== -->
<section class="content-shell" style="background: #0B111D;">
    <div class="site-container">
        <div style="text-align:center; margin-bottom: 3rem;">
            <div class="section-label" style="color: var(--gold); justify-content:center;">Core Values</div>
            <h2 class="dark-heading" style="font-size: clamp(2rem, 4vw, 3.4rem); margin: 0;">The Principles We Stand By</h2>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem;">
            <?php foreach ($values as $value): ?>
                <div class="reveal-el" style="padding: 2rem; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; transition: border-color 0.3s ease;">
                    <div style="width: 4rem; height: 4rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; margin-bottom: 1.5rem;"><?php echo $value['icon']; ?></div>
                    <h3 class="dark-heading" style="font-size: 1.5rem; margin: 0 0 1rem;"><?php echo htmlspecialchars($value['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p style="color: rgba(255,255,255,0.7); line-height: 1.75; margin: 0;"><?php echo htmlspecialchars($value['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
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

<?php render_footer(); ?>
