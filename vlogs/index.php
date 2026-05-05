<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/layout.php';

render_header('Vlogs', 'Watch real estate property tours, market updates, and expert tips from Muhammad Arshad Khan.', '/vlogs');

$videos = [
    ['title' => 'Touring a $12.5M Bridle Path Estate', 'cat' => 'Property Tours', 'date' => 'Oct 15, 2024', 'dur' => '12:45', 'img' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&q=80'],
    ['title' => 'Canada Real Estate Market Update - Fall 2024', 'cat' => 'Market Updates', 'date' => 'Sep 28, 2024', 'dur' => '08:20', 'img' => 'https://images.unsplash.com/photo-1550565118-3a14e8d0386f?w=800&q=80'],
    ['title' => 'Top 5 Pre-Construction Projects to Invest In Right Now', 'cat' => 'Pre-Construction', 'date' => 'Sep 10, 2024', 'dur' => '15:10', 'img' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&q=80'],
    ['title' => 'Neighbourhood Spotlight: The True Value of Forest Hill', 'cat' => 'GTA Neighbourhoods', 'date' => 'Aug 22, 2024', 'dur' => '10:05', 'img' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&q=80'],
    ['title' => 'Client Story: Finding an Off-Market Yorkville Penthouse', 'cat' => 'Client Stories', 'date' => 'Aug 05, 2024', 'dur' => '06:30', 'img' => 'https://images.unsplash.com/photo-1594944983053-ec56184a441e?w=800&q=80'],
    ['title' => '5 Crucial Tips Before Selling Your Luxury Home', 'cat' => 'Tips', 'date' => 'Jul 18, 2024', 'dur' => '09:45', 'img' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=800&q=80'],
];
?>

<section class="page-hero page-hero-center">
    <div class="site-container">
        <div class="section-label" style="justify-content:center;">MAK Media</div>
        <h1>Real Estate on Video</h1>
    </div>
</section>

<!-- Featured Video -->
<section style="padding:0 1.5rem; margin-top:-2rem; position:relative; z-index:2;">
    <div class="site-container">
        <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" style="display:block; position:relative; aspect-ratio:16/9; border-radius:12px; overflow:hidden; border:4px solid var(--navy-deep); box-shadow: 0 25px 50px rgba(0,0,0,0.3);">
            <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1920&q=80" alt="Featured Video" style="width:100%; height:100%; object-fit:cover;">
            <div style="position:absolute; inset:0; background:rgba(0,0,0,0.35);"></div>
            <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center;">
                <div style="width:5rem; height:5rem; background:var(--gold); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:2rem; box-shadow:0 20px 50px rgba(201,161,74,0.3);">▶</div>
            </div>
            <div style="position:absolute; bottom:0; left:0; width:100%; padding:2rem; background:linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                <span style="display:inline-block; padding:0.25rem 0.75rem; background:var(--gold); color:white; font-size:0.7rem; font-weight:700; text-transform:uppercase; border-radius:4px; margin-bottom:0.75rem;">Featured</span>
                <h2 style="color:white; font-size:clamp(1.5rem,3vw,2.5rem); margin:0; font-weight:700;">Canada's Ultra-Luxury Market: What $20M Buys You Today</h2>
            </div>
        </a>
    </div>
</section>

<!-- Video Grid -->
<section style="padding: 3rem 0 5rem;">
    <div class="site-container">
        <div style="display:flex; flex-wrap:wrap; justify-content:center; gap:1rem; margin-bottom:3rem;">
            <?php foreach (['All', 'Property Tours', 'Market Updates', 'Pre-Construction', 'GTA Neighbourhoods', 'Client Stories', 'Tips'] as $i => $cat): ?>
            <span style="padding:0.5rem 1.25rem; border-radius:999px; font-size:0.85rem; font-weight:600; cursor:pointer; <?php echo $i === 0 ? 'background:var(--gold);color:white;' : 'border:1px solid rgba(255,255,255,0.2);color:rgba(255,255,255,0.8);'; ?>"><?php echo $cat; ?></span>
            <?php endforeach; ?>
        </div>

        <div class="property-grid">
            <?php foreach ($videos as $video): ?>
            <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" class="reveal-el" style="display:block; text-decoration:none;">
                <div class="video-thumb" style="margin-bottom:1rem; border:1px solid rgba(255,255,255,0.1); border-radius:12px;">
                    <img src="<?php echo htmlspecialchars($video['img'], ENT_QUOTES, 'UTF-8'); ?>" alt="" loading="lazy">
                    <div class="play-badge"><span>▶</span></div>
                    <span style="position:absolute; bottom:0.5rem; right:0.5rem; padding:0.2rem 0.5rem; background:rgba(0,0,0,0.8); color:white; font-size:0.75rem; font-weight:600; border-radius:4px; z-index:2;"><?php echo $video['dur']; ?></span>
                </div>
                <div style="display:flex; align-items:center; gap:0.5rem; font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.5rem;">
                    <span style="color:var(--gold);"><?php echo $video['cat']; ?></span>
                    <span style="color:rgba(255,255,255,0.4);">•</span>
                    <span style="color:rgba(255,255,255,0.6);"><?php echo $video['date']; ?></span>
                </div>
                <h3 style="color:white; font-size:1.2rem; font-weight:700; margin:0; line-height:1.3;"><?php echo htmlspecialchars($video['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Subscribe Strip -->
<section style="background:var(--bg-secondary); padding:4rem 0; border-top:1px solid rgba(255,255,255,0.1);">
    <div class="site-container" style="text-align:center; max-width:40rem;">
        <h3 style="color:white; font-size:1.85rem; margin:0 0 1rem;">Never Miss an Update</h3>
        <p class="muted" style="margin:0 0 2rem;">Subscribe to MAK's YouTube channel for weekly market updates, exclusive property tours, and expert real estate advice.</p>
        <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" style="display:inline-flex; align-items:center; gap:0.75rem; background:#FF0000; color:white; padding:1rem 2rem; border-radius:8px; font-weight:700; font-size:1rem;">▶ Subscribe on YouTube</a>
    </div>
</section>

<?php render_footer(); ?>
