<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';

$differentiators = [
    ['title' => 'Data-Driven Pricing', 'icon' => '🔎', 'desc' => 'We do not guess. We use deep market analytics to price your home for maximum return.'],
    ['title' => 'Premium Staging', 'icon' => '✅', 'desc' => 'Complementary staging consultation to ensure your home looks its absolute best.'],
    ['title' => 'Cinematic Media', 'icon' => '📷', 'desc' => '4K video tours, drone photography, and 3D matterport to capture buyers online.'],
    ['title' => 'Global Reach', 'icon' => '📣', 'desc' => 'Marketing that reaches beyond local buyers to international investors.'],
    ['title' => 'Fierce Negotiation', 'icon' => '🎯', 'desc' => 'Protecting your equity is our top priority during the negotiation process.'],
    ['title' => 'Record Breaking', 'icon' => '💰', 'desc' => 'Consistently achieving above-average list-to-sale price ratios.'],
];

render_header(
    'Sell Your Home in Canada',
    'Sell faster and for more money with Muhammad Arshad Khan. Expert pricing, staging, marketing, and negotiation strategies.',
    '/sellers/'
);
?>

<section class="content-shell">
    <div class="site-container grid-split">
        <div class="hero-image-frame" style="min-height: 520px;">
            <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1920&q=80" alt="Luxury home exterior">
            <div class="hero-overlay-dark"></div>
            <div class="hero-copy">
                <div>
                    <div class="section-label">Seller Services</div>
                    <h1 class="dark-heading" style="font-size: clamp(2.8rem, 6vw, 4.8rem); line-height: 1.02; margin: 0 0 1rem;">Sell Faster.<br><span style="color: var(--gold); font-style: italic;">Sell for More.</span></h1>
                    <p class="muted" style="font-size: 1.05rem; line-height: 1.85; max-width: 30rem;">Canada's market-savvy REALTOR® who gets results.</p>
                    <div style="margin-top: 1.5rem;"><a class="btn-primary" href="#valuation">Get Free Market Evaluation</a></div>
                </div>
            </div>
        </div>
        <div>
            <div class="section-label">The MAK Difference</div>
            <h2 class="dark-heading" style="font-size: clamp(2rem, 4vw, 3.4rem); margin: 0 0 1rem;">Why List With Us?</h2>
            <div class="service-grid" style="grid-template-columns: 1fr;">
                <?php foreach ($differentiators as $item): ?>
                    <div class="panel">
                        <div class="inline-icon-row" style="margin-bottom:0.75rem;"><span style="font-size:1.35rem; color: var(--gold);"><?php echo $item['icon']; ?></span><strong class="dark-heading"><?php echo htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?></strong></div>
                        <p class="muted" style="margin:0; line-height:1.75;"><?php echo htmlspecialchars($item['desc'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<section id="valuation" class="content-shell light">
    <div class="site-container page-section" style="text-align:center;">
        <h2 class="light-heading" style="font-size: clamp(2rem, 4vw, 3.4rem); margin: 0 0 1rem;">Get Free Market Evaluation</h2>
        <p style="color: rgba(26,26,46,0.75); max-width: 48rem; margin: 0 auto 1.5rem; line-height: 1.8;">Tell us about your property and we will prepare a pricing strategy designed to maximize your return.</p>
        <a class="btn-primary" href="/free-market-evaluation/">Request Valuation</a>
    </div>
</section>

<?php render_footer(); ?>
