<?php
declare(strict_types=1);

require_once __DIR__ . '/data.php';

function render_header(string $pageTitle, string $pageDescription = '', string $activePath = '/'): void
{
    global $navLinks;

    $title = $pageTitle !== '' ? $pageTitle . ' | Houses & Homes Toronto' : 'Houses & Homes Toronto';
    $description = $pageDescription !== '' ? $pageDescription : 'Houses & Homes Toronto';
    $currentPath = strtok($_SERVER['REQUEST_URI'] ?? '/', '?') ?: '/';
    $activeClass = static fn(string $path): string => rtrim($currentPath, '/') === rtrim($path, '/') ? ' is-active' : '';

    echo <<<HTML
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$title}</title>
    <meta name="description" content="{$description}">
    <link rel="icon" href="/assets/images/logo/logo.png">
    <link rel="stylesheet" href="/assets/css/site.css">
</head>
<body class="site-body">
    <header class="site-header" data-site-header>
        <div class="site-container header-inner">
            <a class="site-logo" href="/">
                <img src="/assets/images/logo/logo.png" alt="Houses & Homes Logo">
            </a>

            <nav class="desktop-nav" aria-label="Primary">
HTML;

    foreach ($navLinks as $link) {
        $label = htmlspecialchars($link['label'], ENT_QUOTES, 'UTF-8');
        $href = htmlspecialchars(site_url($link['href']), ENT_QUOTES, 'UTF-8');
        $active = $activeClass($link['href']);

        if (!empty($link['children'])) {
            echo '<div class="nav-item has-dropdown">';
            echo '<a class="nav-link' . $active . '" href="' . $href . '">' . $label . ' <span aria-hidden="true">▾</span></a>';
            echo '<div class="dropdown-panel">';
            foreach ($link['children'] as $child) {
                $childLabel = htmlspecialchars($child['label'], ENT_QUOTES, 'UTF-8');
                $childHref = htmlspecialchars(site_url($child['href']), ENT_QUOTES, 'UTF-8');
                echo '<a class="dropdown-link" href="' . $childHref . '">' . $childLabel . '</a>';
            }
            echo '</div></div>';
            continue;
        }

        echo '<a class="nav-link' . $active . '" href="' . $href . '">' . $label . '</a>';
    }

    echo <<<HTML
            </nav>

            <div class="header-cta-wrap">
                <a class="header-phone" href="tel:4168261777">416-826-1777</a>
                <a class="btn-primary header-cta" href="/contact/">Book Consultation →</a>
            </div>

            <button class="mobile-toggle" type="button" aria-label="Toggle menu" aria-expanded="false" data-mobile-toggle>
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>

        <div class="mobile-drawer" data-mobile-drawer>
            <div class="mobile-drawer-inner site-container">
                <nav class="mobile-nav" aria-label="Mobile">
HTML;

    foreach ($navLinks as $link) {
        $label = htmlspecialchars($link['label'], ENT_QUOTES, 'UTF-8');
        $href = htmlspecialchars(site_url($link['href']), ENT_QUOTES, 'UTF-8');

        if (!empty($link['children'])) {
            echo '<details class="mobile-group">';
            echo '<summary>' . $label . '</summary>';
            echo '<div class="mobile-submenu">';
            foreach ($link['children'] as $child) {
                $childLabel = htmlspecialchars($child['label'], ENT_QUOTES, 'UTF-8');
                $childHref = htmlspecialchars(site_url($child['href']), ENT_QUOTES, 'UTF-8');
                echo '<a href="' . $childHref . '">' . $childLabel . '</a>';
            }
            echo '</div></details>';
            continue;
        }

        echo '<a href="' . $href . '">' . $label . '</a>';
    }

    echo <<<HTML
                </nav>
                <div class="mobile-contact">
                    <a class="header-phone mobile-phone" href="tel:4168261777">416-826-1777</a>
                    <a class="btn-primary mobile-cta" href="/contact/">Get Free Valuation</a>
                </div>
            </div>
        </div>
    </header>
    <main class="site-main">
HTML;
}

function render_footer(): void
{
    global $footerQuickLinks, $footerCompanyLinks;

    $year = date('Y');

    echo <<<HTML
    </main>
    <footer class="site-footer">
        <div class="site-container footer-grid">
            <div>
                <a class="footer-logo" href="/">
                    <img src="/assets/images/logo/logo.png" alt="Houses & Homes Logo">
                </a>
                <p class="footer-copy">Canada's premier luxury real estate team. Serving the GTA with integrity, expertise, and an unmatched client experience.</p>
            </div>

            <div>
                <h4>Quick Links</h4>
                <ul class="footer-links">
HTML;

    foreach ($footerQuickLinks as $link) {
        $label = htmlspecialchars($link['label'], ENT_QUOTES, 'UTF-8');
        $href = htmlspecialchars(site_url($link['href']), ENT_QUOTES, 'UTF-8');
        echo '<li><a href="' . $href . '">' . $label . '</a></li>';
    }

    echo <<<HTML
                </ul>
            </div>

            <div>
                <h4>Company</h4>
                <ul class="footer-links">
HTML;

    foreach ($footerCompanyLinks as $link) {
        $label = htmlspecialchars($link['label'], ENT_QUOTES, 'UTF-8');
        $href = htmlspecialchars(site_url($link['href']), ENT_QUOTES, 'UTF-8');
        echo '<li><a href="' . $href . '">' . $label . '</a></li>';
    }

    echo <<<HTML
                </ul>
            </div>

            <div>
                <h4>Contact</h4>
                <a class="footer-phone" href="tel:4168261777">416-826-1777</a>
                <div class="footer-meta">
                    <a href="mailto:info@houseandhomesintoronto.com">info@houseandhomesintoronto.com</a>
                    <p>267 Matheson Blvd E Unit 3, Mississauga, ON L4Z 1X8, Canada</p>
                    <p>Mon-Fri 9am-7pm | Sat 10am-5pm</p>
                </div>
            </div>
        </div>

        <div class="site-container footer-bottom">
            <div>
                <p>&copy; {$year} Houses &amp; Homes Toronto. All Rights Reserved</p>
                <p>Muhammad Arshad — Licensed REALTOR® in Ontario</p>
            </div>
            <div class="footer-legal">
                <a href="/privacy/">Privacy Policy</a>
                <span></span>
                <a href="/disclaimer/">Disclaimer</a>
                <span></span>
                <a href="/terms/">Terms</a>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Float -->
    <div class="whatsapp-float">
        <div class="wa-panel">
            <div class="wa-panel-inner">
                <div class="wa-avatar">
                    <svg viewBox="0 0 32 32" width="18" height="18" fill="white" xmlns="http://www.w3.org/2000/svg"><path d="M16 3C9.373 3 4 8.373 4 15c0 2.385.695 4.61 1.898 6.485L4 29l7.727-1.875A11.94 11.94 0 0016 28c6.627 0 12-5.373 12-12S22.627 3 16 3z"/></svg>
                </div>
                <div class="wa-panel-text">
                    <strong>Chat with Muhammad Arshad on WhatsApp</strong>
                    <span>Quick answers about listings, showings and availability.</span>
                    <div class="wa-panel-actions">
                        <a class="wa-start-btn" href="https://wa.me/14168261777?text=Hi%20Muhammad%20Arshad%2C%20I%20found%20you%20on%20Houses%20%26%20Homes%20Toronto%20and%20I%27d%20like%20to%20learn%20more." target="_blank" rel="noopener noreferrer">Start Chat</a>
                        <button class="wa-close-btn" type="button" aria-label="Close">✕</button>
                    </div>
                </div>
            </div>
        </div>
        <button class="wa-toggle" type="button" aria-label="Open chat panel">
            <svg viewBox="0 0 32 32" width="20" height="20" fill="white" xmlns="http://www.w3.org/2000/svg"><path d="M16 3C9.373 3 4 8.373 4 15c0 2.385.695 4.61 1.898 6.485L4 29l7.727-1.875A11.94 11.94 0 0016 28c6.627 0 12-5.373 12-12S22.627 3 16 3zm0 21.75a9.714 9.714 0 01-5.063-1.42l-.362-.215-3.762.913.944-3.66-.235-.377A9.71 9.71 0 016.25 15c0-5.376 4.374-9.75 9.75-9.75S25.75 9.624 25.75 15 21.376 24.75 16 24.75z"/></svg>
        </button>
    </div>

    <!-- Mobile CTA Bar -->
    <div class="mobile-cta-bar">
        <a class="mcta-icon mcta-phone" href="tel:4168261777" aria-label="Call Now">📞</a>
        <a class="mcta-icon mcta-wa" href="https://wa.me/14168261777?text=Hi%20Muhammad%20Arshad%2C%20I%20found%20you%20on%20Houses%20%26%20Homes%20Toronto" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">💬</a>
        <a class="mcta-main" href="/contact/">Get Free Valuation</a>
    </div>

    <script src="/assets/js/site.js"></script>
</body>
</html>
HTML;
}
