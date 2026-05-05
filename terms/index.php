<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';

render_header('Terms', 'Terms of use for Houses & Homes Toronto.', '/terms/');
?>

<section class="content-shell light">
    <div class="site-container page-section" style="max-width: 60rem;">
        <div class="section-label">Terms</div>
        <h1 class="light-heading" style="font-size: clamp(2.4rem, 5vw, 4rem); margin: 1rem 0;">Terms of Use</h1>
        <div class="panel light" style="background:white; color:#1A1A2E; line-height:1.85; display:grid; gap:1rem;">
            <p>By using this website, you agree to use it only for lawful purposes and not to interfere with its operation or security.</p>
            <p>We reserve the right to update content, change services, or modify these terms at any time without prior notice.</p>
            <p>Any contact or valuation request submitted through this website is subject to follow-up and service availability.</p>
        </div>
    </div>
</section>

<?php render_footer(); ?>
