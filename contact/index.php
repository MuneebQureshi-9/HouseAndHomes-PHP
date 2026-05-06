<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';

$formData = [
    'name' => '',
    'email' => '',
    'phone' => '',
    'subject' => '',
    'message' => '',
];

render_header(
    'Contact',
    'Whether you are buying, selling, or investing, reach out and we will craft a strategy tailored to your goals.',
    '/contact/'
);
?>

<section class="content-shell">
    <div class="site-container page-section" style="text-align:center; max-width: 56rem;">
        <div class="section-label" style="justify-content:center;">Get in Touch</div>
        <h1 class="dark-heading" style="font-size: clamp(2.8rem, 6vw, 4.8rem); margin: 1rem 0 1rem;">Let&apos;s Find Your <span style="color: var(--gold);">Perfect Home</span></h1>
        <p class="muted" style="font-size: 1.05rem; line-height: 1.85;">Whether you&apos;re buying, selling, or investing - reach out and we&apos;ll craft a strategy tailored to your goals.</p>
        <div style="display:flex; flex-wrap:wrap; justify-content:center; gap: 1rem; margin-top: 1.5rem;">
            <a class="btn-primary" href="tel:4168261777">(416) 826-1777</a>
            <a class="btn-outline-gold" href="mailto:info@houseandhomesintoronto.com">info@houseandhomesintoronto.com</a>
        </div>
    </div>
</section>

<section class="content-shell">
    <div class="site-container grid-split" style="align-items: flex-start;">
        <div class="contact-card">
            <div class="inline-icon-row" style="margin-bottom: 1rem;"><span style="color: var(--gold);">☎</span><h3 class="dark-heading">Phone</h3></div>
            <a href="tel:4168261777" style="color: rgba(255,255,255,0.72);">(416) 826-1777</a>
            <div class="inline-icon-row" style="margin: 1.5rem 0 1rem;"><span style="color: var(--gold);">✉</span><h3 class="dark-heading">Email</h3></div>
            <a href="mailto:info@houseandhomesintoronto.com" style="color: rgba(255,255,255,0.72); word-break: break-all;">info@houseandhomesintoronto.com</a>
            <div class="inline-icon-row" style="margin: 1.5rem 0 1rem;"><span style="color: var(--gold);">📍</span><h3 class="dark-heading">Office</h3></div>
            <p style="color: rgba(255,255,255,0.72); line-height: 1.7;">115 Matheson Ave Office<br><span style="color: rgba(255,255,255,0.45); font-size: 0.95rem;">Toronto, Ontario, Canada</span></p>
            <div class="inline-icon-row" style="margin: 1.5rem 0 1rem;"><span style="color: var(--gold);">🕒</span><h3 class="dark-heading">Hours</h3></div>
            <p style="color: rgba(255,255,255,0.72); line-height: 1.7;">Mon - Fri: 9am - 7pm<br><span style="color: rgba(255,255,255,0.45); font-size: 0.95rem;">Sat: 10am - 5pm | Sun: By Appointment</span></p>
        </div>

        <div>
            <div class="panel" style="background:#111827; border-color: rgba(255,255,255,0.08);">
                <h2 class="dark-heading" style="margin-top:0;">Send a Message</h2>
                <p class="form-note">We typically respond within a few hours on business days.</p>

                    <div id="formMessage"></div>
                    <form id="contactForm" method="post" class="form-grid" action="/api/contact.php">
                        <div class="form-field">
                            <label for="name">Full Name *</label>
                            <input id="name" name="name" type="text" required value="<?php echo htmlspecialchars($formData['name'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="Your full name">
                        </div>
                        <div class="form-field">
                            <label for="email">Email *</label>
                            <input id="email" name="email" type="email" required value="<?php echo htmlspecialchars($formData['email'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="your@email.com">
                        </div>
                        <div class="form-field">
                            <label for="phone">Phone</label>
                            <input id="phone" name="phone" type="tel" value="<?php echo htmlspecialchars($formData['phone'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="(416) 000-0000">
                        </div>
                        <div class="form-field">
                            <label for="subject">I&apos;m looking to</label>
                            <select id="subject" name="subject">
                                <?php $subject = $formData['subject']; ?>
                                <option value="" <?php echo $subject === '' ? 'selected' : ''; ?>>Select an option</option>
                                <option value="buy" <?php echo $subject === 'buy' ? 'selected' : ''; ?>>Buy a Property</option>
                                <option value="sell" <?php echo $subject === 'sell' ? 'selected' : ''; ?>>Sell My Home</option>
                                <option value="invest" <?php echo $subject === 'invest' ? 'selected' : ''; ?>>Invest in Real Estate</option>
                                <option value="pre-con" <?php echo $subject === 'pre-con' ? 'selected' : ''; ?>>Pre-Construction Condo</option>
                                <option value="valuation" <?php echo $subject === 'valuation' ? 'selected' : ''; ?>>Free Market Valuation</option>
                                <option value="other" <?php echo $subject === 'other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        <div class="form-field" style="grid-column: 1 / -1;">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" required placeholder="Tell us about your real estate goals, preferred neighborhoods, budget range, or any questions you have..."><?php echo htmlspecialchars($formData['message'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                        <!-- Honeypot anti-bot field - hidden from real users -->
                        <div style="position:absolute;left:-9999px;opacity:0;height:0;overflow:hidden;" aria-hidden="true">
                            <input type="text" name="website" tabindex="-1" autocomplete="off">
                        </div>
                        <div style="grid-column: 1 / -1;">
                            <button class="btn-primary" type="submit">Send Message</button>
                        </div>
                    </form>
            </div>

            <div class="panel" style="margin-top: 1.5rem; text-align:center; background: #111827; border-color: rgba(255,255,255,0.08); padding: 3rem 1.5rem;">
                <a href="https://maps.google.com/?q=115+Matheson+Ave+Toronto" target="_blank" rel="noopener noreferrer" class="muted" style="display:inline-flex; flex-direction:column; gap:0.5rem; align-items:center;">
                    <span style="font-size: 1.5rem; color: var(--gold);">📍</span>
                    <strong style="color: white; font-size: 1.1rem;">115 Matheson Ave Office, Toronto</strong>
                    <span style="font-size: 0.9rem; color: rgba(255,255,255,0.4);">Click to open in Google Maps</span>
                </a>
            </div>
        </div>
    </div>
</section>

<?php render_footer(); ?>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const form = document.getElementById('contactForm');
    const msgBox = document.getElementById('formMessage');
    if (!form) return;
    form.addEventListener('submit', async function(e){
        e.preventDefault();
        msgBox.innerHTML = '';
        const data = new FormData(form);
        try {
            const res = await fetch(form.action, { method: 'POST', body: data });
            const json = await res.json();
            if (res.ok && json.success) {
                msgBox.innerHTML = '<div class="success-box"><h3 class="dark-heading">Message Sent!</h3><p class="muted">Thank you — we will be in touch shortly.</p></div>';
                form.reset();
            } else {
                msgBox.innerHTML = '<div class="error-box">' + (json.message || 'Failed to send message') + '</div>';
            }
        } catch (err) {
            msgBox.innerHTML = '<div class="error-box">Unable to submit — network error.</div>';
        }
    });
});
</script>
