<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/layout.php';

render_header('Neighbourhoods', 'Explore the finest neighbourhoods across Toronto and the Greater Toronto Area.', '/neighbourhoods');

$areas = [
    ['name' => 'Toronto', 'slug' => 'toronto', 'img' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800&q=80'],
    ['name' => 'Mississauga', 'slug' => 'mississauga', 'img' => 'https://images.unsplash.com/photo-1449844908441-8829872d2607?w=800&q=80'],
    ['name' => 'Brampton', 'slug' => 'brampton', 'img' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&q=80'],
    ['name' => 'Oakville', 'slug' => 'oakville', 'img' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&q=80'],
    ['name' => 'Milton', 'slug' => 'milton', 'img' => 'https://images.unsplash.com/photo-1600607686527-6fb886090705?w=800&q=80'],
    ['name' => 'GTA', 'slug' => 'gta', 'img' => 'https://images.unsplash.com/photo-1582407947304-fd86f028f716?w=800&q=80'],
];
?>

<section class="page-hero page-hero-center">
    <div class="site-container">
        <div class="section-label" style="justify-content:center;">Areas We Serve</div>
        <h1>Explore the GTA</h1>
        <p>Discover the perfect neighbourhood that matches your luxury lifestyle.</p>
    </div>
</section>

<section style="padding: 3rem 0 5rem; background: var(--bg-primary);">
    <div class="site-container">
        <div class="areas-grid" style="gap:1.5rem;">
            <?php foreach ($areas as $area): ?>
            <a class="area-tile reveal-el" href="/listings/?city=<?php echo urlencode($area['slug']); ?>" style="aspect-ratio:4/3;">
                <img src="<?php echo htmlspecialchars($area['img'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($area['name'], ENT_QUOTES, 'UTF-8'); ?>" loading="lazy">
                <div class="area-overlay"></div>
                <div class="area-content">
                    <h3 style="font-size:clamp(1.5rem,3vw,2rem);"><?php echo htmlspecialchars($area['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p style="opacity:0; transition: opacity 500ms;">Explore Area →</p>
                </div>
                <div class="area-gold-line"></div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php render_footer(); ?>
