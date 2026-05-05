<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';

$buyerSteps = ['Initial Consultation', 'Mortgage Pre-Approval', 'Property Search', 'Offer & Negotiation', 'Closing'];
$sellerServices = ['Strategic Pricing', 'Professional Staging', 'High-End Photography', 'Aggressive Marketing', 'Expert Negotiation', 'Seamless Closing'];
$faqs = [
    ['q' => 'How much does it cost to use a Realtor to buy a home?', 'a' => 'In Ontario, the buyer agent commission is almost always paid by the seller. This means our expert representation, negotiation, and guidance are completely free to you as a buyer.'],
    ['q' => 'What is the best time of year to sell a home in Toronto?', 'a' => 'Spring and Fall are traditionally the most active seasons, bringing the most buyers. However, serious buyers look year-round. The best time depends heavily on your specific neighborhood and property type.'],
    ['q' => 'Do I really need to stage my home before selling?', 'a' => 'Yes. Staged homes consistently sell faster and for a higher price. It helps potential buyers visualize themselves in the space and highlights the propertys best features while minimizing flaws.'],
    ['q' => 'What is a pre-construction condo and should I invest?', 'a' => 'Pre-construction means buying a property before it is built. It offers potential for strong appreciation by the time of completion and flexible deposit structures, making it an excellent investment vehicle when guided by an expert.'],
];

render_header(
    'Real Estate Services',
    'Expert real estate services in Canada and the GTA. Buying, selling, renting, and pre-construction investments with Muhammad Arshad Khan.',
    '/services/'
);
?>

<section class="content-shell">
    <div class="site-container">
        <div class="page-hero-center" style="padding: 1rem 0 2rem;">
            <div class="section-label">Our Services</div>
            <h1 class="dark-heading" style="font-size: clamp(2.5rem, 5vw, 4.5rem); margin: 1rem 0 0;">Expert Real Estate Services in Canada & GTA</h1>
        </div>

        <div class="grid-split page-section">
            <div>
                <div class="section-label">For Buyers</div>
                <h2 class="dark-heading" style="font-size: clamp(2rem, 4vw, 3.5rem);">Buying a Home?</h2>
                <p class="muted" style="font-size: 1.05rem; line-height: 1.85;">Finding the perfect home in the GTA's competitive market requires more than just browsing listings. It requires strategic planning, neighborhood expertise, and aggressive negotiation. We guide you through every step, ensuring you do not overpay or miss out on your dream home.</p>
                <h3 class="dark-heading" style="margin-top: 1.5rem;">The Buying Process</h3>
                <ul class="service-list">
                    <?php foreach ($buyerSteps as $index => $step): ?>
                        <li class="panel"><div class="inline-icon-row"><span style="color: var(--gold);">●</span><span>Step <?php echo $index + 1; ?>: <?php echo htmlspecialchars($step, ENT_QUOTES, 'UTF-8'); ?></span></div></li>
                    <?php endforeach; ?>
                </ul>
                <div style="margin-top: 1.25rem;"><a class="btn-primary" href="/listings/">Start Your Home Search</a></div>
            </div>
            <div class="hero-image-frame"><img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800&q=80" alt="Happy buyers"></div>
        </div>
    </div>
</section>

<section class="content-shell light">
    <div class="site-container">
        <div class="grid-split page-section">
            <div class="hero-image-frame"><img src="https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?w=800&q=80" alt="Staged home for sale"></div>
            <div>
                <div class="section-label">For Sellers</div>
                <h2 class="light-heading" style="font-size: clamp(2rem, 4vw, 3.5rem);">Selling Your Home?</h2>
                <p style="color: rgba(26,26,46,0.75); font-size: 1.05rem; line-height: 1.85;">We do not just list homes; we launch them. Our comprehensive marketing strategy is designed to maximize your property's exposure to qualified buyers, creating urgency and driving the highest possible sale price.</p>
                <div class="service-grid" style="margin-top: 1.25rem;">
                    <?php foreach ($sellerServices as $service): ?>
                        <div class="panel light"><div class="inline-icon-row"><span style="color: var(--gold);">◌</span><span><?php echo htmlspecialchars($service, ENT_QUOTES, 'UTF-8'); ?></span></div></div>
                    <?php endforeach; ?>
                </div>
                <div style="margin-top: 1.25rem;"><a class="btn-primary" href="/free-market-evaluation/">Get Free Market Evaluation</a></div>
            </div>
        </div>
    </div>
</section>

<section class="content-shell">
    <div class="site-container">
        <div class="grid-split page-section">
            <div>
                <div class="section-label">For Renters & Landlords</div>
                <h2 class="dark-heading" style="font-size: clamp(2rem, 4vw, 3.5rem);">Renting in the GTA?</h2>
                <p class="muted" style="font-size: 1.05rem; line-height: 1.85;">Whether you're a professional looking for the perfect downtown condo, or a landlord seeking reliable, AAA tenants, we manage the entire leasing process to ensure a smooth, secure transaction.</p>
                <div class="service-grid" style="grid-template-columns: 1fr; margin-top: 1rem;">
                    <div class="panel">
                        <h3 class="dark-heading">For Tenants</h3>
                        <p class="muted">We help you find the right property, prepare a winning application, and negotiate lease terms in a highly competitive rental market.</p>
                    </div>
                    <div class="panel">
                        <h3 class="dark-heading">For Landlords</h3>
                        <p class="muted">Comprehensive tenant screening, professional marketing, and lease preparation to protect your investment and ensure peace of mind.</p>
                    </div>
                </div>
                <div style="margin-top: 1.25rem;"><a class="btn-primary" href="/contact/">Contact Us About Rentals</a></div>
            </div>
            <div class="hero-image-frame"><img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&q=80" alt="Luxury rental apartment"></div>
        </div>
    </div>
</section>

<section class="content-shell light">
    <div class="site-container">
        <div style="text-align:center; margin-bottom: 2rem;">
            <div class="section-label" style="justify-content:center;">FAQ</div>
            <h2 class="light-heading" style="font-size: clamp(2rem, 4vw, 3.4rem);">Frequently Asked Questions</h2>
        </div>
        <div class="faq-list">
            <?php foreach ($faqs as $faq): ?>
                <details class="faq-item light panel" style="background:white; border-color: rgba(75,85,99,0.16);">
                    <summary style="color:#0B1F3A;"><?php echo htmlspecialchars($faq['q'], ENT_QUOTES, 'UTF-8'); ?></summary>
                    <div class="faq-answer" style="color: rgba(26,26,46,0.75);"><?php echo htmlspecialchars($faq['a'], ENT_QUOTES, 'UTF-8'); ?></div>
                </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php render_footer(); ?>
