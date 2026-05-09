<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';

$textTestimonials = [
    ['quote' => 'Working with Muhammad Arshad was the best decision we made. He did not just list our home; he created a comprehensive marketing strategy that brought in multiple offers within the first week. His negotiation skills are unmatched, securing us a price far above what we initially expected.', 'client' => 'Sarah & David M.', 'transaction' => 'Sold in Oakville'],
    ['quote' => 'As first-time homebuyers in a very competitive Canada market, we were incredibly overwhelmed. Muhammad was patient, highly educational, and never pressured us. When we finally found the one, his strategic advice won us the bidding war. We could not be happier.', 'client' => 'James T.', 'transaction' => 'Bought in Downtown Toronto'],
    ['quote' => 'I have worked with several agents over the years for my investment properties, but Muhammad Arshad operates on a completely different level. His data-driven approach and deep understanding of pre-construction ROI have been invaluable to my portfolio growth.', 'client' => 'Robert K.', 'transaction' => 'Investor, Pre-Construction'],
    ['quote' => 'Selling a luxury estate requires a specific network and marketing finesse. Muhammad and his team delivered flawlessly. From the cinematic video tours to the international reach, they positioned our property perfectly to attract the right buyer.', 'client' => 'The Henderson Family', 'transaction' => 'Sold in The Bridle Path'],
    ['quote' => 'Relocating from the US was stressful, but having Muhammad Arshad as our guide made the transition seamless. He understands the nuances of the various GTA neighborhoods and found us a spectacular home in Mississauga that completely fits our family lifestyle.', 'client' => 'Elena & Marcus V.', 'transaction' => 'Bought in Mississauga'],
    ['quote' => 'Integrity, transparency, and relentless work ethic. That is what you get with Muhammad. He told us exactly what we needed to do to prep our house for sale, brought in his staging team, and the results spoke for themselves. Highly recommended.', 'client' => 'Priya S.', 'transaction' => 'Sold in Brampton'],
];

$videos = [
    ['title' => 'Selling for Record Price in Richmond Hill', 'client' => 'The Chen Family', 'thumbnail' => 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?w=800&q=80'],
    ['title' => 'Finding the Perfect Downtown Condo', 'client' => 'Michael R.', 'thumbnail' => '/assets/images/agent/Muhammad-Arshad.jpeg'],
    ['title' => 'A Seamless Out-of-Province Relocation', 'client' => 'Amanda & Greg', 'thumbnail' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800&q=80'],
    ['title' => 'Strategic Investment Portfolio Growth', 'client' => 'Dr. S. Patel', 'thumbnail' => 'https://images.unsplash.com/photo-1556910103-1c02745a872f?w=800&q=80'],
];

render_header(
    'Client Testimonials',
    'Read success stories and watch video testimonials from clients who trusted Muhammad Arshad with their GTA real estate transactions.',
    '/testimonials/'
);
?>

<section class="content-shell light">
    <div class="site-container page-section" style="text-align:center; max-width: 56rem;">
        <div class="section-label" style="justify-content:center;">Client Experiences</div>
        <h1 class="light-heading" style="font-size: clamp(2.8rem, 6vw, 4.8rem); margin: 1rem 0 1rem;">Client Success Stories</h1>
        <p style="color: rgba(26,26,46,0.72); font-size: 1.15rem; line-height: 1.85;">Over $1B in GTA real estate sold, but our greatest achievement is the trust of our clients.</p>
    </div>
</section>

<section class="content-shell">
    <div class="site-container">
        <div class="testimonial-grid">
            <?php foreach ($textTestimonials as $item): ?>
                <div class="testimonial-card">
                    <div style="color: var(--gold); font-size: 1.8rem; margin-bottom: 1rem;">“</div>
                    <p class="muted" style="line-height: 1.8; font-size: 0.98rem;">&quot;<?php echo htmlspecialchars($item['quote'], ENT_QUOTES, 'UTF-8'); ?>&quot;</p>
                    <div style="margin-top: 1rem; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 1rem;">
                        <strong class="dark-heading"><?php echo htmlspecialchars($item['client'], ENT_QUOTES, 'UTF-8'); ?></strong>
                        <div style="color: var(--gold); text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.12em; font-weight: 700;"><?php echo htmlspecialchars($item['transaction'], ENT_QUOTES, 'UTF-8'); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="content-shell light">
    <div class="site-container page-section">
        <div style="text-align:center; margin-bottom: 2rem; max-width: 48rem; margin-left:auto; margin-right:auto;">
            <h2 class="light-heading" style="font-size: clamp(2rem, 4vw, 3.4rem); margin: 0 0 0.75rem;">Hear Directly From Our Clients</h2>
            <p style="color: rgba(26,26,46,0.72); line-height: 1.8;">We believe the best way to understand the value we provide is to hear it directly from the families and investors we have helped succeed.</p>
        </div>
        <div class="video-grid">
            <?php foreach ($videos as $video): ?>
                <div class="video-card panel light" style="padding: 0; overflow:hidden;">
                    <div class="video-thumb">
                        <img src="<?php echo htmlspecialchars($video['thumbnail'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($video['client'], ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="play-badge"><span>▶</span></div>
                    </div>
                    <div style="padding: 1.25rem; background:white;">
                        <h3 class="light-heading" style="margin-bottom: 0.4rem;"><?php echo htmlspecialchars($video['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <div style="color: var(--gold); font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; font-size: 0.75rem;"><?php echo htmlspecialchars($video['client'], ENT_QUOTES, 'UTF-8'); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php render_footer(); ?>
