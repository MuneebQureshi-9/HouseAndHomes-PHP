<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';

render_header('Privacy Policy', 'Privacy policy for Houses & Homes Toronto.', '/privacy/');
?>

<section class="content-shell light">
    <div class="site-container page-section" style="max-width: 60rem;">
        <div class="section-label">Privacy Policy</div>
        <h1 class="light-heading" style="font-size: clamp(2.4rem, 5vw, 4rem); margin: 1rem 0;">Privacy Policy</h1>
        <div class="panel light" style="background:white; color:#1A1A2E; line-height:1.85; display:grid; gap:1rem;">
            <p>We collect only the information needed to respond to inquiries, provide real estate services, and improve the website experience.</p>
            <p>Contact form submissions may be used to follow up on your request and may be stored in our email system or CRM.</p>
            <p>We do not sell personal information. If you have questions about how your data is handled, contact us directly.</p>
        </div>
    </div>
</section>

<?php render_footer(); ?>
