<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';

$categories = array_keys($faqSections);
$requestedCategory = (string)($_GET['category'] ?? $categories[0]);
$activeCategory = in_array($requestedCategory, $categories, true) ? $requestedCategory : $categories[0];

render_header(
    'FAQ',
    'Expert answers to your real estate questions about buying, selling, and investing in the Greater Toronto Area.',
    '/faq/'
);
?>

<section class="page-hero">
    <div class="site-container page-hero-center">
        <div class="section-label">Learn More</div>
        <h1>Frequently Asked Questions</h1>
        <p>Expert answers to your real estate questions.</p>
    </div>
</section>

<section class="faq-shell">
    <div class="site-container faq-grid">
        <aside class="faq-sidebar">
            <h3>Categories</h3>
            <ul class="faq-categories">
                <?php foreach ($categories as $category): ?>
                    <li>
                        <a class="<?php echo $category === $activeCategory ? 'is-active' : ''; ?>" href="?category=<?php echo urlencode($category); ?>">
                            <?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </aside>

        <div class="faq-content">
            <h2><?php echo htmlspecialchars($activeCategory, ENT_QUOTES, 'UTF-8'); ?></h2>

            <div class="faq-list">
                <?php foreach ($faqSections[$activeCategory] as $index => $item): ?>
                    <details class="faq-item" <?php echo $index === 0 ? 'open' : ''; ?>>
                        <summary><?php echo htmlspecialchars($item['question'], ENT_QUOTES, 'UTF-8'); ?></summary>
                        <div class="faq-answer">
                            <?php echo htmlspecialchars($item['answer'], ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    </details>
                <?php endforeach; ?>
            </div>

            <div class="faq-cta">
                <h4>Still have questions?</h4>
                <p>We're here to help. Contact our team directly for personalized advice regarding your real estate needs.</p>
                <a class="btn-primary" href="/contact/">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<?php render_footer(); ?>
