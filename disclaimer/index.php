<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';

render_header('Disclaimer', 'Website disclaimer for Houses & Homes Toronto.', '/disclaimer/');
?>

<section class="content-shell light">
    <div class="site-container page-section" style="max-width: 60rem;">
        <div class="section-label">Disclaimer</div>
        <h1 class="light-heading" style="font-size: clamp(2.4rem, 5vw, 4rem); margin: 1rem 0;">Disclaimer</h1>
        <div class="panel light" style="background:white; color:#1A1A2E; line-height:1.85; display:grid; gap:1rem;">
            <p>All information on this website is provided for general informational purposes only and may change without notice.</p>
            <p>Property details, pricing, availability, and market data should be independently verified before making any decisions.</p>
            <p>This website does not constitute legal, financial, or tax advice.</p>
        </div>
    </div>
</section>

<?php render_footer(); ?>
