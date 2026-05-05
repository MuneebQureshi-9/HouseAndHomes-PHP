<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';

$formData = [
    'fullName' => '',
    'email' => '',
    'phone' => '',
    'postalCode' => '',
    'address' => '',
    'beds' => '',
    'baths' => '',
    'type' => 'Detached',
    'notes' => '',
];
$status = 'idle';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($formData as $key => $value) {
        $formData[$key] = trim((string)($_POST[$key] ?? $value));
    }

    if ($formData['fullName'] === '' || $formData['email'] === '' || $formData['phone'] === '' || $formData['postalCode'] === '' || $formData['address'] === '') {
        $status = 'error';
    } else {
        require_once __DIR__ . '/../lib/mailer.php';
        $to = 'houseandhomesintoronto@gmail.com';
        $subject = 'New Valuation Request';
        $body = implode("\n", [
            'Name: ' . $formData['fullName'],
            'Email: ' . $formData['email'],
            'Phone: ' . $formData['phone'],
            'Postal Code: ' . $formData['postalCode'],
            'Address: ' . $formData['address'],
            'Bedrooms: ' . $formData['beds'],
            'Bathrooms: ' . $formData['baths'],
            'Property Type: ' . $formData['type'],
            'Notes: ' . $formData['notes'],
        ]);
        
        $sent = send_site_mail($to, $subject, $body, $formData['email'], $formData['fullName']);
        if ($sent === true) {
            $status = 'success';
            $formData = [
                'fullName' => '', 'email' => '', 'phone' => '', 'postalCode' => '', 'address' => '', 'beds' => '', 'baths' => '', 'type' => 'Detached', 'notes' => '',
            ];
        } else {
            $status = 'error';
            $errorMessage = is_string($sent) ? $sent : 'Unknown error occurred.';
        }
    }
}

render_header(
    'Free Market Evaluation',
    'Get a free, no-obligation home valuation from a certified GTA REALTOR. Find out what your home is worth in today\'s market.',
    '/free-market-evaluation/'
);
?>

<section class="content-shell light">
    <div class="site-container page-section" style="text-align:center; max-width: 56rem;">
        <div class="section-label" style="justify-content:center;">Free Market Evaluation</div>
        <h1 class="light-heading" style="font-size: clamp(2.8rem, 6vw, 4.8rem); margin: 1rem 0 1rem;">What Is Your Home Worth?</h1>
        <p style="color: rgba(26,26,46,0.72); font-size: 1.15rem; line-height: 1.85;">Get a free, no-obligation home valuation from a certified GTA REALTOR®.</p>
    </div>
</section>

<section class="content-shell">
    <div class="site-container grid-split">
        <div class="panel light" style="background:white;">
            <?php if ($status === 'success'): ?>
                <div class="success-box">
                    <h2 class="light-heading">Request Received</h2>
                    <p style="color: rgba(26,26,46,0.75); line-height:1.8;">Thank you for providing your property details. MAK will review the information and get back to you within 48 hours with a comprehensive valuation report.</p>
                    <a class="btn-primary" href="/free-market-evaluation/">Submit Another Property</a>
                </div>
            <?php else: ?>
                <h2 class="light-heading" style="margin-top: 0;">Property Details</h2>
                <?php if ($status === 'error'): ?>
                    <div class="error-box" style="margin-bottom: 1rem;">
                        <?php echo isset($errorMessage) ? "Failed to send message: " . htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') : "Please fill in the required property and contact fields."; ?>
                    </div>
                <?php endif; ?>
                <form method="post" class="form-grid" style="color:#1A1A2E;">
                    <div class="form-field">
                        <label for="fullName" style="color:#4B5563;">Full Name *</label>
                        <input id="fullName" name="fullName" value="<?php echo htmlspecialchars($formData['fullName'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="Jane Doe" required>
                    </div>
                    <div class="form-field">
                        <label for="email" style="color:#4B5563;">Email *</label>
                        <input id="email" name="email" type="email" value="<?php echo htmlspecialchars($formData['email'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="jane@example.com" required>
                    </div>
                    <div class="form-field">
                        <label for="phone" style="color:#4B5563;">Phone *</label>
                        <input id="phone" name="phone" type="tel" value="<?php echo htmlspecialchars($formData['phone'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="(416) 555-0123" required>
                    </div>
                    <div class="form-field">
                        <label for="postalCode" style="color:#4B5563;">Postal Code *</label>
                        <input id="postalCode" name="postalCode" value="<?php echo htmlspecialchars($formData['postalCode'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="M4W 1A1" required>
                    </div>
                    <div class="form-field" style="grid-column: 1 / -1;">
                        <label for="address" style="color:#4B5563;">Property Address *</label>
                        <input id="address" name="address" value="<?php echo htmlspecialchars($formData['address'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="123 Luxury Ave, Toronto" required>
                    </div>
                    <div class="form-field">
                        <label for="beds" style="color:#4B5563;">Bedrooms</label>
                        <input id="beds" name="beds" type="number" value="<?php echo htmlspecialchars($formData['beds'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="3">
                    </div>
                    <div class="form-field">
                        <label for="baths" style="color:#4B5563;">Bathrooms</label>
                        <input id="baths" name="baths" type="number" value="<?php echo htmlspecialchars($formData['baths'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="2">
                    </div>
                    <div class="form-field" style="grid-column: 1 / -1;">
                        <label for="type" style="color:#4B5563;">Property Type</label>
                        <select id="type" name="type" style="background:#F8F6F1; color:#1A1A2E;">
                            <?php foreach (['Detached', 'Semi-Detached', 'Townhouse', 'Condo'] as $option): ?>
                                <option value="<?php echo $option; ?>" <?php echo $formData['type'] === $option ? 'selected' : ''; ?>><?php echo $option; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-field" style="grid-column: 1 / -1;">
                        <label for="notes" style="color:#4B5563;">Additional Notes (Optional)</label>
                        <textarea id="notes" name="notes" placeholder="Any recent renovations or specific details?" style="background:#F8F6F1; color:#1A1A2E;"><?php echo htmlspecialchars($formData['notes'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <button class="btn-primary" type="submit" style="width:100%;">Request My Free Valuation →</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>

        <aside class="panel">
            <h3 class="dark-heading" style="color:white; margin-top:0;">The Process</h3>
            <ul class="service-list">
                <li class="panel" style="background: rgba(255,255,255,0.03);"><div class="inline-icon-row"><span style="color: var(--gold);">1</span><div><strong class="dark-heading">Submit Details</strong><p class="muted">Provide basic information about your property.</p></div></div></li>
                <li class="panel" style="background: rgba(255,255,255,0.03);"><div class="inline-icon-row"><span style="color: var(--gold);">2</span><div><strong class="dark-heading">Expert Review</strong><p class="muted">MAK analyzes recent sales and market trends.</p></div></div></li>
                <li class="panel" style="background: rgba(255,255,255,0.03);"><div class="inline-icon-row"><span style="color: var(--gold);">3</span><div><strong class="dark-heading">Receive Report</strong><p class="muted">Get a comprehensive valuation report within 48 hours.</p></div></div></li>
            </ul>

            <div class="panel" style="margin-top: 1rem; background: rgba(255,255,255,0.03);">
                <div class="inline-icon-row" style="margin-bottom: 1rem;">
                    <span style="width:48px; height:48px; border-radius:999px; overflow:hidden; display:block; flex:0 0 auto;"><img src="/assets/images/agent/Muhammad-Arshad.jpeg" alt="MAK" style="width:100%; height:100%; object-fit:cover;"></span>
                    <div>
                        <p style="margin:0; color:white; font-weight:700;">100% Free.</p>
                        <p class="muted" style="margin:0;">No Commitment. No Spam.</p>
                    </div>
                </div>
                <p class="muted" style="font-style: italic; line-height:1.8;">"I personally review every property evaluation to ensure the highest accuracy."</p>
                <p style="margin:0.5rem 0 0; color:white; font-weight:700;">- Muhammad Arshad Khan</p>
            </div>
        </aside>
    </div>
</section>

<section class="content-shell light">
    <div class="site-container">
        <div style="text-align:center; margin-bottom:2rem;">
            <div class="section-label" style="justify-content:center;">Why Choose Us</div>
            <h2 class="light-heading" style="font-size: clamp(2rem, 4vw, 3.4rem);">Why Trust MAK's Valuation?</h2>
        </div>
        <div class="value-grid">
            <div class="value-card panel light"><div class="light-heading" style="font-size:2rem; color:var(--gold);">01</div><h3 class="light-heading">Local Expertise</h3><p style="color: rgba(26,26,46,0.75);">Deep understanding of Toronto's micro-markets and neighborhood dynamics.</p></div>
            <div class="value-card panel light"><div class="light-heading" style="font-size:2rem; color:var(--gold);">02</div><h3 class="light-heading">Data-Driven</h3><p style="color: rgba(26,26,46,0.75);">Utilizing the latest MLS data, off-market sales, and proprietary analytics.</p></div>
            <div class="value-card panel light"><div class="light-heading" style="font-size:2rem; color:var(--gold);">03</div><h3 class="light-heading">Accurate Pricing</h3><p style="color: rgba(26,26,46,0.75);">Strategic pricing methodologies to maximize your return on investment.</p></div>
            <div class="value-card panel light"><div class="light-heading" style="font-size:2rem; color:var(--gold);">04</div><h3 class="light-heading">Confidential</h3><p style="color: rgba(26,26,46,0.75);">Your information and property details are kept strictly private and secure.</p></div>
        </div>
    </div>
</section>

<?php render_footer(); ?>
