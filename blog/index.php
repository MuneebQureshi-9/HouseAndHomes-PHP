<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/layout.php';

render_header('Real Estate Blog', 'Market insights, neighborhood guides, and real estate advice from Toronto\'s top REALTOR®.', '/blog');

$posts = [
    ['title' => 'Toronto Real Estate Market Forecast 2026: Trends & Predictions', 'excerpt' => "Explore expert analysis on Toronto's housing market for 2026. From interest rate shifts to inventory levels, discover what buyers and sellers need to know.", 'category' => 'Market Outlook', 'date' => 'Jan 15, 2026', 'readTime' => '10 min read', 'image' => 'https://images.unsplash.com/photo-1514924013411-cbf25faa35bb?w=800&q=80', 'featured' => true],
    ['title' => 'How to Invest in Toronto Pre-Construction Condos for Maximum ROI', 'excerpt' => 'Master the art of pre-construction investing. Learn how to identify Tier-1 developers and high-growth transit-oriented communities in the GTA.', 'category' => 'Investment', 'date' => 'Jan 10, 2026', 'readTime' => '8 min read', 'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&q=80', 'featured' => false],
    ['title' => 'Luxury Home Selling Guide: Get Top Dollar for Your Toronto Property', 'excerpt' => 'Selling a high-end estate requires more than just a listing. Discover advanced staging and global marketing strategies.', 'category' => 'Selling', 'date' => 'Jan 05, 2026', 'readTime' => '7 min read', 'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&q=80', 'featured' => false],
    ['title' => 'Top 5 Up-and-Coming Neighborhoods in the GTA for 2026', 'excerpt' => 'Beyond the downtown core, these emerging GTA communities are seeing massive infrastructure growth and consistent property value appreciation.', 'category' => 'Neighborhoods', 'date' => 'Dec 28, 2025', 'readTime' => '6 min read', 'image' => 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?w=800&q=80', 'featured' => false],
];
?>

<section class="page-hero page-hero-center">
    <div class="site-container">
        <div class="section-label" style="justify-content:center;">Market Intelligence</div>
        <h1>Real Estate Insights</h1>
        <p>Stay ahead of the market with expert analysis, tips, and neighborhood guides.</p>
    </div>
</section>

<section class="content-shell" style="padding: 3rem 0 5rem;">
    <div class="site-container">
        <!-- Category Filters -->
        <div style="display:flex; gap:1rem; margin-bottom:3rem; overflow-x:auto; padding-bottom:0.5rem;">
            <?php foreach (['All Articles', 'Buying', 'Selling', 'Investing', 'Neighborhoods'] as $i => $cat): ?>
            <span style="padding: 0.5rem 1.25rem; border-radius: 999px; font-size: 0.85rem; font-weight: 600; white-space:nowrap; <?php echo $i === 0 ? 'background: var(--gold); color: white;' : 'border: 1px solid rgba(255,255,255,0.2); color: rgba(255,255,255,0.8);'; ?>"><?php echo $cat; ?></span>
            <?php endforeach; ?>
        </div>

        <!-- Featured Post -->
        <?php foreach ($posts as $post): if (!$post['featured']) continue; ?>
        <a href="#" class="reveal-el" style="display:flex; flex-direction:column; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; overflow:hidden; margin-bottom:3rem; text-decoration:none;">
            <div style="position:relative; height:300px; overflow:hidden;">
                <img src="<?php echo htmlspecialchars($post['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="" style="width:100%; height:100%; object-fit:cover;">
                <span style="position:absolute; top:1.5rem; left:1.5rem; padding:0.35rem 0.9rem; background:var(--gold); color:white; font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; border-radius:4px;"><?php echo $post['category']; ?></span>
            </div>
            <div style="padding:2rem;">
                <div style="display:flex; gap:1.5rem; font-size:0.85rem; color:rgba(255,255,255,0.5); margin-bottom:0.75rem;">
                    <span>📅 <?php echo $post['date']; ?></span>
                    <span>🕐 <?php echo $post['readTime']; ?></span>
                </div>
                <h2 style="color:white; font-size:clamp(1.5rem,3vw,2.25rem); margin:0 0 0.75rem;"><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                <p class="muted" style="line-height:1.75; margin:0 0 1rem;"><?php echo htmlspecialchars($post['excerpt'], ENT_QUOTES, 'UTF-8'); ?></p>
                <span style="color:white; font-weight:600;">Read Article →</span>
            </div>
        </a>
        <?php endforeach; ?>

        <!-- Regular Posts Grid -->
        <div class="property-grid">
            <?php foreach ($posts as $post): if ($post['featured']) continue; ?>
            <a href="#" class="property-card reveal-el" style="text-decoration:none;">
                <div class="card-img" style="height:15rem;">
                    <img src="<?php echo htmlspecialchars($post['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="" style="width:100%; height:100%; object-fit:cover;">
                </div>
                <div class="card-body">
                    <span style="position:absolute; top:1rem; left:1rem; padding:0.25rem 0.7rem; background:var(--gold); color:white; font-size:0.65rem; font-weight:700; text-transform:uppercase; border-radius:4px;"><?php echo $post['category']; ?></span>
                    <div style="display:flex; gap:1rem; font-size:0.75rem; color:rgba(255,255,255,0.5); margin-bottom:0.5rem;">
                        <span>📅 <?php echo $post['date']; ?></span>
                        <span>🕐 <?php echo $post['readTime']; ?></span>
                    </div>
                    <h3 style="color:white; font-size:1.2rem; margin:0 0 0.5rem; line-height:1.3;"><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p class="muted" style="font-size:0.9rem; line-height:1.65; margin:0 0 0.75rem;"><?php echo htmlspecialchars($post['excerpt'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <span style="color:white; font-weight:600; font-size:0.9rem;">Read Article →</span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php render_footer(); ?>
