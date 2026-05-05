<?php
declare(strict_types=1);

require_once __DIR__ . '/../../php/includes/layout.php';
require_once __DIR__ . '/../lib/ddf.php';

$id = trim((string)($_GET['id'] ?? ''));
if ($id === '') {
    header('Location: /listings/');
    exit;
}

function php_format_address(array $prop): string
{
  $street = '';
  if (!empty($prop['UnparsedAddress'])) {
    $street = trim((string)$prop['UnparsedAddress']);
  } else {
    $parts = [];
    $unit = trim((string)($prop['UnitNumber'] ?? ''));
    if ($unit !== '') {
      $parts[] = 'Unit ' . $unit . ' -';
    }
    if (!empty($prop['StreetNumber'])) {
      $parts[] = (string)$prop['StreetNumber'];
    }
    if (!empty($prop['StreetName'])) {
      $parts[] = (string)$prop['StreetName'];
    }
    if (!empty($prop['StreetSuffix'])) {
      $parts[] = (string)$prop['StreetSuffix'];
    }
    $street = trim(implode(' ', $parts));
  }

  $city = trim(implode(', ', array_filter([
    trim((string)($prop['City'] ?? '')),
    trim((string)($prop['StateOrProvince'] ?? '')),
    trim((string)($prop['PostalCode'] ?? '')),
  ])));

  if ($street !== '' && $city !== '') {
    return $street . ', ' . $city;
  }

  return $street !== '' ? $street : ($city !== '' ? $city : 'Property');
}

function php_format_price(array $prop): string
{
    if (!empty($prop['ListPrice'])) {
        return '$' . number_format((float)$prop['ListPrice']);
    }

    if (!empty($prop['LeaseAmount'])) {
        return '$' . number_format((float)$prop['LeaseAmount']) . '/' . strtolower((string)($prop['LeaseAmountFrequency'] ?? 'mo'));
    }

    return 'Contact for price';
}

function php_property_type(array $prop): string
{
    if (($prop['CommonInterest'] ?? null) === 'Condo/Strata') {
        return 'Condominium';
    }

    if (!empty($prop['StructureType']) && is_array($prop['StructureType']) && in_array('Row / Townhouse', $prop['StructureType'], true)) {
        return 'Townhouse';
    }

    return !empty($prop['PropertySubType']) ? strtoupper((string)$prop['PropertySubType']) : 'Property';
}

function php_property_beds(array $prop): string
{
    return isset($prop['BedroomsTotal']) && $prop['BedroomsTotal'] !== '' ? (string)(int)$prop['BedroomsTotal'] : '-';
}

function php_property_baths(array $prop): string
{
    return isset($prop['BathroomsTotalInteger']) && $prop['BathroomsTotalInteger'] !== '' ? (string)(int)$prop['BathroomsTotalInteger'] : '-';
}

function php_get_gallery_photos(array $prop): array
{
    $media = $prop['Media'] ?? [];
    if (!is_array($media) || empty($media)) {
        return [[
            'MediaURL' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1600&q=80',
            'MediaKey' => 'fallback',
        ]];
    }

    return array_values(array_filter($media, static function ($item) {
        return is_array($item) && !empty($item['MediaURL']);
    }));
}

try {
    $prop = ddf_get_property_by_id($id);
} catch (Throwable $e) {
    $prop = null;
}

if (!$prop) {
    render_header('Property Not Found', '', '/listings/');
    echo '<section class="content-shell"><div class="site-container"><div class="panel">Property not found.</div></div></section>';
    render_footer();
    exit;
}

$address = php_format_address($prop);
$price = php_format_price($prop);
$type = php_property_type($prop);
$beds = php_property_beds($prop);
$baths = php_property_baths($prop);
$sqft = !empty($prop['BuildingAreaTotal']) ? trim((string)$prop['BuildingAreaTotal'] . ' ' . (string)($prop['BuildingAreaUnits'] ?? '')) : '-';
$updated = !empty($prop['ModificationTimestamp']) ? date('m/d/Y', strtotime((string)$prop['ModificationTimestamp'])) : null;
$listingId = (string)($prop['ListingId'] ?? $id);
$displayAddress = ($address === '' || $address === 'Property') ? ('MLS® ' . $listingId) : $address;
$photos = php_get_gallery_photos($prop);
$photoCount = count($photos);
$galleryHero = $photos[0]['MediaURL'] ?? 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1600&q=80';

$similar = [];
$neighbourhood = trim((string)($prop['SubdivisionName'] ?? $prop['CityRegion'] ?? ''));
$city = trim((string)($prop['City'] ?? ''));
$currentListingKey = (string)($prop['ListingKey'] ?? $id);
if ($neighbourhood !== '') {
    try {
    $similar = ddf_get_by_neighbourhood($neighbourhood, $city, $currentListingKey, 3);
    } catch (Throwable $e) {
        $similar = [];
    }
}

if (empty($similar) && $city !== '') {
  try {
    $fallback = ddf_get_featured_listings($city, 8);
    $similar = array_values(array_filter($fallback, static function ($item) use ($currentListingKey, $listingId) {
      if (!is_array($item)) {
        return false;
      }
      $itemKey = (string)($item['ListingKey'] ?? '');
      $itemId = (string)($item['ListingId'] ?? '');
      return $itemKey !== $currentListingKey && $itemId !== $listingId;
    }));
    $similar = array_slice($similar, 0, 3);
  } catch (Throwable $e) {
    $similar = [];
  }
}

render_header($displayAddress, (string)($prop['PublicRemarks'] ?? ''), '/listings/');

$galleryJson = json_encode(array_values(array_map(static function ($item) {
  return [
    'url' => (string)($item['MediaURL'] ?? ''),
    'label' => (string)($item['LongDescription'] ?? ''),
  ];
}, $photos)), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>

<style>
.listing-detail-shell { background: #050608; color: #fff; padding: 6.5rem 0 4rem; }
.listing-detail-grid { display: grid; grid-template-columns: 1fr; gap: 2rem; margin-top: 1.6rem; }
.listing-gallery { display: grid; grid-template-columns: 1fr; gap: 0.85rem; }
.listing-gallery-main,
.listing-gallery-side-item,
.listing-gallery-more { position: relative; overflow: hidden; border-radius: 28px; background: #111; cursor: pointer; }
.listing-gallery-main { aspect-ratio: 16 / 9; min-height: 320px; }
.listing-gallery-main img,
.listing-gallery-side-item img,
.listing-gallery-more img { width: 100%; height: 100%; object-fit: cover; display: block; }
.listing-gallery-side { display: grid; gap: 0.85rem; }
.listing-gallery-side-item { aspect-ratio: 16 / 9; min-height: 160px; }
.listing-gallery-more { aspect-ratio: 16 / 9; min-height: 160px; }
.listing-gallery-more::after,
.listing-gallery-main::after,
.listing-gallery-side-item::after { content: ''; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(0,0,0,0) 55%, rgba(0,0,0,0.45) 100%); }
.listing-gallery-more-label { position: absolute; inset: 0; display: grid; place-items: center; z-index: 1; background: rgba(10,10,10,0.38); color: #fff; font-size: 1.05rem; font-weight: 700; text-align: center; padding: 1rem; }
.listing-detail-identity { max-width: 100%; }
.listing-type-badge { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.45rem 0.8rem; border-radius: 999px; background: rgba(201,161,74,0.12); color: var(--gold); border: 1px solid rgba(201,161,74,0.2); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; }
.listing-id-line { display: inline-flex; gap: 0.75rem; flex-wrap: wrap; margin-left: 0.75rem; color: rgba(255,255,255,0.55); font-size: 0.95rem; }
.listing-price { margin: 0.8rem 0 0.55rem; font-family: var(--font-display); font-size: clamp(2.5rem, 5vw, 4.3rem); line-height: 1; font-weight: 800; }
.listing-address { display: flex; align-items: flex-start; gap: 0.6rem; color: rgba(255,255,255,0.85); font-size: clamp(1rem, 2vw, 1.35rem); line-height: 1.55; }
.listing-spec-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; padding: 1.5rem 0; border-top: 1px solid rgba(255,255,255,0.09); border-bottom: 1px solid rgba(255,255,255,0.09); margin-top: 1.35rem; }
.listing-spec { display: grid; gap: 0.45rem; }
.listing-spec-label { color: rgba(255,255,255,0.45); font-size: 0.72rem; letter-spacing: 0.14em; text-transform: uppercase; }
.listing-spec-value { display: flex; align-items: center; gap: 0.5rem; font-size: 1.6rem; font-weight: 600; }
.listing-spec-value small { font-size: 1rem; font-weight: 500; color: rgba(255,255,255,0.82); }
.listing-section-card { margin-top: 2rem; padding: 1.6rem; border-radius: 24px; border: 1px solid rgba(255,255,255,0.08); background: rgba(255,255,255,0.03); }
.listing-section-card h2 { margin: 0 0 1rem; color: var(--gold); font-size: clamp(1.5rem, 2.5vw, 2rem); }
.listing-description { color: rgba(255,255,255,0.78); line-height: 1.85; font-size: 1.05rem; }
.listing-description p { margin: 0 0 1rem; }
.listing-disclaimer { margin-top: 1.8rem; }
.listing-sidebar { position: relative; }
.listing-sidebar-card { position: sticky; top: 5.25rem; padding: 1.6rem; border-radius: 24px; border: 1px solid rgba(255,255,255,0.08); background: #0f0f11; box-shadow: 0 20px 50px rgba(0,0,0,0.2); }
.listing-sidebar-card h3 { margin: 0 0 1.1rem; font-size: 1.55rem; }
.listing-form { display: grid; gap: 0.9rem; }
.listing-form-field { display: grid; gap: 0.45rem; }
.listing-form-field input,
.listing-form-field textarea { width: 100%; border-radius: 18px; border: 1px solid rgba(255,255,255,0.08); background: #06080d; color: #fff; padding: 0.95rem 1rem; font: inherit; }
.listing-form-field textarea { min-height: 140px; resize: vertical; }
.listing-form-field input::placeholder,
.listing-form-field textarea::placeholder { color: rgba(255,255,255,0.28); }
.listing-form button { margin-top: 0.35rem; }
.listing-attribution-block { margin-top: 1rem; padding-top: 0.95rem; border-top: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.42); font-size: 0.78rem; text-align: center; }
.listing-similar { margin-top: 3rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.09); }
.listing-similar h2 { text-align: center; margin-bottom: 1.5rem; font-size: clamp(2rem, 4vw, 3rem); }
.listing-similar-grid { display: grid; grid-template-columns: 1fr; gap: 1.25rem; }
.listing-lightbox {
  position: fixed;
  inset: 0;
  z-index: 9999;
  display: none;
  background: rgba(0,0,0,0.94);
}
.listing-lightbox.is-open { display: block; }
.listing-lightbox-inner { position: absolute; inset: 0; display: grid; grid-template-rows: auto minmax(0, 1fr) auto; }
.listing-lightbox-header { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1rem 1.2rem; color: #fff; }
.listing-lightbox-counter { font-size: 0.95rem; color: rgba(255,255,255,0.75); }
.listing-lightbox-close,
.listing-lightbox-nav {
  width: 46px; height: 46px; border-radius: 999px; border: 1px solid rgba(255,255,255,0.12);
  background: rgba(255,255,255,0.08); color: #fff; display: grid; place-items: center; cursor: pointer;
}
.listing-lightbox-stage { position: relative; display: grid; place-items: center; padding: 0.5rem 4rem 0.35rem; min-height: 0; }
.listing-lightbox-stage img { max-width: 100%; max-height: 78vh; object-fit: contain; border-radius: 18px; }
.listing-lightbox-prev { position: absolute; left: 1rem; }
.listing-lightbox-next { position: absolute; right: 1rem; }
.listing-lightbox-thumbs { display: flex; gap: 0.65rem; overflow-x: auto; padding: 0.65rem 1rem calc(0.65rem + env(safe-area-inset-bottom)); margin: 0 1rem 1rem; background: rgba(0,0,0,0.45); border: 1px solid rgba(255,255,255,0.08); border-radius: 18px; -ms-overflow-style: none; scrollbar-width: none; }
.listing-lightbox-thumbs::-webkit-scrollbar { display: none; }
.listing-lightbox-thumb { width: 96px; height: 72px; flex: 0 0 auto; border-radius: 12px; overflow: hidden; border: 2px solid transparent; background: #111; cursor: pointer; opacity: 0.55; }
.listing-lightbox-thumb.is-active { opacity: 1; border-color: var(--gold); }
.listing-lightbox-thumb img { width: 100%; height: 100%; object-fit: cover; }
@media (min-width: 768px) {
  .listing-detail-grid { grid-template-columns: minmax(0, 1fr) 390px; align-items: start; }
  .listing-gallery { grid-template-columns: minmax(0, 3fr) minmax(0, 1fr); }
  .listing-gallery-side { grid-template-rows: repeat(3, minmax(0, 1fr)); }
  .listing-similar-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
  .listing-spec-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
}

@media (max-height: 820px) {
  .listing-lightbox-stage { padding-top: 0.25rem; }
  .listing-lightbox-stage img { max-height: 68vh; }
  .listing-lightbox-thumbs { margin-bottom: 0.65rem; }
}

@media (max-height: 720px) {
  .listing-lightbox-header { padding-top: 0.75rem; padding-bottom: 0.75rem; }
  .listing-lightbox-stage img { max-height: 62vh; }
  .listing-lightbox-thumb { width: 84px; height: 64px; }
}
</style>

<div class="listing-lightbox" data-listing-lightbox aria-hidden="true">
  <div class="listing-lightbox-inner">
    <div class="listing-lightbox-header">
      <div class="listing-lightbox-counter" data-lightbox-counter>1 / 1</div>
      <button class="listing-lightbox-close" type="button" data-lightbox-close aria-label="Close gallery">✕</button>
    </div>
    <div class="listing-lightbox-stage">
      <button class="listing-lightbox-nav listing-lightbox-prev" type="button" data-lightbox-prev aria-label="Previous photo">‹</button>
      <img data-lightbox-image src="" alt="Gallery image">
      <button class="listing-lightbox-nav listing-lightbox-next" type="button" data-lightbox-next aria-label="Next photo">›</button>
    </div>
    <div class="listing-lightbox-thumbs" data-lightbox-thumbs></div>
  </div>
</div>

<div class="listing-detail-shell">
  <div class="site-container">
    <div class="listing-gallery" data-gallery-root>
      <div class="listing-gallery-main" data-gallery-index="0" data-gallery-open>
        <img src="<?php echo htmlspecialchars($galleryHero, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($displayAddress, ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="listing-gallery-side">
        <?php if ($photoCount > 1): ?>
          <?php for ($i = 1; $i < min(3, $photoCount); $i++): ?>
            <div class="listing-gallery-side-item" data-gallery-index="<?php echo (int)$i; ?>" data-gallery-open>
              <img src="<?php echo htmlspecialchars((string)($photos[$i]['MediaURL'] ?? $galleryHero), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($displayAddress, ENT_QUOTES, 'UTF-8'); ?> - photo <?php echo $i + 1; ?>">
            </div>
          <?php endfor; ?>
          <div class="listing-gallery-more" data-gallery-index="<?php echo (int)min(3, $photoCount - 1); ?>" data-gallery-open>
            <img src="<?php echo htmlspecialchars((string)($photos[min(3, $photoCount - 1)]['MediaURL'] ?? $galleryHero), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?> - more photos">
            <div class="listing-gallery-more-label">View all <?php echo (int)$photoCount; ?> photos</div>
          </div>
        <?php else: ?>
          <div class="listing-gallery-side-item" data-gallery-index="0" data-gallery-open>
            <img src="<?php echo htmlspecialchars($galleryHero, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($displayAddress, ENT_QUOTES, 'UTF-8'); ?>">
          </div>
          <div class="listing-gallery-side-item" style="display:grid; place-items:center; color: rgba(255,255,255,0.35); font-weight:600;">No additional photos</div>
          <div class="listing-gallery-side-item" style="display:grid; place-items:center; color: rgba(255,255,255,0.35); font-weight:600;">No additional photos</div>
        <?php endif; ?>
      </div>
    </div>

    <div class="listing-detail-grid">
      <div>
        <div class="listing-detail-identity">
          <div class="listing-type-badge"><?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?></div>
          <span class="listing-id-line">MLS® <?php echo htmlspecialchars($listingId, ENT_QUOTES, 'UTF-8'); ?><?php if ($updated): ?> <span>Updated <?php echo htmlspecialchars($updated, ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?></span>
          <h1 class="listing-price"><?php echo htmlspecialchars($price, ENT_QUOTES, 'UTF-8'); ?></h1>
          <div class="listing-address">
            <span style="color: var(--gold);">📍</span>
            <span><?php echo htmlspecialchars($displayAddress, ENT_QUOTES, 'UTF-8'); ?></span>
          </div>

          <div class="listing-spec-grid">
            <div class="listing-spec">
              <div class="listing-spec-label">Bedrooms</div>
              <div class="listing-spec-value">🛏 <small><?php echo htmlspecialchars($beds, ENT_QUOTES, 'UTF-8'); ?></small></div>
            </div>
            <div class="listing-spec">
              <div class="listing-spec-label">Bathrooms</div>
              <div class="listing-spec-value">🛁 <small><?php echo htmlspecialchars($baths, ENT_QUOTES, 'UTF-8'); ?></small></div>
            </div>
            <div class="listing-spec">
              <div class="listing-spec-label">Property Type</div>
              <div class="listing-spec-value">🏷 <small><?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?></small></div>
            </div>
            <div class="listing-spec">
              <div class="listing-spec-label">Size/Area</div>
              <div class="listing-spec-value">◻ <small><?php echo htmlspecialchars($sqft !== '' ? $sqft : '-', ENT_QUOTES, 'UTF-8'); ?></small></div>
            </div>
          </div>
        </div>

        <div class="listing-section-card">
          <h2>About this Property</h2>
          <div class="listing-description">
            <?php if (!empty($prop['PublicRemarks'])): ?>
              <?php foreach (preg_split('/\n+/', trim((string)$prop['PublicRemarks'])) as $paragraph): ?>
                <?php if (trim($paragraph) !== ''): ?>
                  <p><?php echo htmlspecialchars(trim($paragraph), ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php else: ?>
              <p>No description available for this property.</p>
            <?php endif; ?>
          </div>
        </div>

        <div class="listing-section-card listing-disclaimer">
          <div class="listings-disclaimer">
            <div class="listings-disclaimer-badge">
              <svg width="36" height="20" viewBox="0 0 36 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <rect width="36" height="20" rx="3" fill="#C41230"></rect>
                <text x="18" y="14" text-anchor="middle" fill="white" font-size="10" font-weight="700" font-family="Arial, sans-serif">MLS®</text>
              </svg>
              <span>CREA Member</span>
            </div>
            <div class="listings-disclaimer-copy">
              <p>The trademarks MLS®, Multiple Listing Service® and the associated logos are owned by The Canadian Real Estate Association (CREA) and identify the quality of services provided by real estate professionals who are members of CREA.</p>
              <p>Listing data is deemed reliable but not guaranteed accurate by CREA<?php if ($updated): ?>. Last updated: <?php echo htmlspecialchars($updated, ENT_QUOTES, 'UTF-8'); ?><?php endif; ?></p>
            </div>
          </div>
        </div>
      </div>

      <div class="listing-sidebar">
        <div class="listing-sidebar-card">
          <h3>Inquire About This Property</h3>
          <form class="listing-form" method="post" action="/api/contact.php" data-property-form>
            <div class="listing-form-field">
              <input name="name" type="text" placeholder="Full Name *" required>
            </div>
            <div class="listing-form-field">
              <input name="email" type="email" placeholder="Email Address *" required>
            </div>
            <div class="listing-form-field">
              <input name="phone" type="tel" placeholder="Phone Number (Optional)">
            </div>
            <div class="listing-form-field">
              <textarea name="message" required><?php echo htmlspecialchars('I am interested in ' . $displayAddress . ' (' . $listingId . '). Please contact me to arrange a viewing.', ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>
            <input type="hidden" name="propertyAddress" value="<?php echo htmlspecialchars($displayAddress . ' (' . $listingId . ')', ENT_QUOTES, 'UTF-8'); ?>">
            <button class="btn-primary" type="submit">Send Inquiry →</button>
            <div class="listing-attribution-block">We'll forward your inquiry directly to the listing team.</div>
          </form>
        </div>
      </div>
    </div>

    <?php if (!empty($similar)): ?>
      <div class="listing-similar">
        <h2><?php echo $neighbourhood !== '' ? ('Similar in ' . htmlspecialchars($neighbourhood, ENT_QUOTES, 'UTF-8')) : 'Similar Listings'; ?></h2>
        <div class="listing-similar-grid">
          <?php foreach ($similar as $idx => $simProp):
              $simMedia = $simProp['Media'][0]['MediaURL'] ?? null;
              $simAddress = !empty($simProp['UnparsedAddress']) ? (string)$simProp['UnparsedAddress'] : trim((($simProp['StreetNumber'] ?? '') . ' ' . ($simProp['StreetName'] ?? '')));
              $simPrice = !empty($simProp['ListPrice']) ? '$' . number_format((float)$simProp['ListPrice']) : 'Contact for price';
              $simBeds = $simProp['BedroomsTotal'] ?? null;
              $simBaths = $simProp['BathroomsTotalInteger'] ?? null;
              $simLink = '/listings/view.php?id=' . urlencode($simProp['ListingKey'] ?? $simProp['ListingId'] ?? '');
          ?>
            <a class="property-card" href="<?php echo htmlspecialchars($simLink, ENT_QUOTES, 'UTF-8'); ?>">
              <?php if ($simMedia): ?>
                <div class="card-img"><img src="<?php echo htmlspecialchars($simMedia, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($simAddress, ENT_QUOTES, 'UTF-8'); ?>"></div>
              <?php endif; ?>
              <div class="card-body">
                <div class="card-price"><?php echo htmlspecialchars($simPrice, ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="card-address"><?php echo htmlspecialchars($simAddress, ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="card-specs">
                  <?php if ($simBeds !== null): ?><?php echo (int)$simBeds; ?> bd<?php endif; ?>
                  <?php if ($simBeds !== null && $simBaths !== null): ?> · <?php endif; ?>
                  <?php if ($simBaths !== null): ?><?php echo (int)$simBaths; ?> ba<?php endif; ?>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('[data-property-form]');
  if (!form) return;

  form.addEventListener('submit', async function (event) {
    event.preventDefault();
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton ? submitButton.textContent : '';
    if (submitButton) {
      submitButton.disabled = true;
      submitButton.textContent = 'Sending...';
    }

    try {
      const response = await fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
      });
      const json = await response.json();
      if (!response.ok || !json.success) {
        throw new Error(json.message || 'Failed to send inquiry');
      }
      form.innerHTML = '<div class="success-box"><h3 class="dark-heading">Inquiry Sent</h3><p class="muted">Thank you. An agent will contact you shortly.</p></div>';
    } catch (error) {
      alert(error.message || 'Failed to send inquiry');
      if (submitButton) {
        submitButton.disabled = false;
        submitButton.textContent = originalText;
      }
    }
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const galleryData = <?php echo $galleryJson ?: '[]'; ?>;
  const lightbox = document.querySelector('[data-listing-lightbox]');
  const lightboxImage = document.querySelector('[data-lightbox-image]');
  const lightboxCounter = document.querySelector('[data-lightbox-counter]');
  const thumbs = document.querySelector('[data-lightbox-thumbs]');
  const btnClose = document.querySelector('[data-lightbox-close]');
  const btnPrev = document.querySelector('[data-lightbox-prev]');
  const btnNext = document.querySelector('[data-lightbox-next]');
  const openers = document.querySelectorAll('[data-gallery-open]');
  let currentIndex = 0;

  if (!lightbox || !lightboxImage || !lightboxCounter || !thumbs || galleryData.length === 0) return;

  function renderThumbs() {
    thumbs.innerHTML = '';
    galleryData.forEach((photo, index) => {
      const thumb = document.createElement('button');
      thumb.type = 'button';
      thumb.className = 'listing-lightbox-thumb' + (index === currentIndex ? ' is-active' : '');
      thumb.innerHTML = '<img src="' + photo.url.replace(/"/g, '&quot;') + '" alt="Thumbnail ' + (index + 1) + '">';
      thumb.addEventListener('click', function () {
        showIndex(index);
      });
      thumbs.appendChild(thumb);
    });
  }

  function showIndex(index) {
    currentIndex = (index + galleryData.length) % galleryData.length;
    const photo = galleryData[currentIndex];
    lightboxImage.src = photo.url;
    lightboxImage.alt = photo.label || ('Photo ' + (currentIndex + 1));
    lightboxCounter.textContent = (currentIndex + 1) + ' / ' + galleryData.length;
    renderThumbs();
  }

  function openLightbox(index) {
    showIndex(index);
    lightbox.classList.add('is-open');
    lightbox.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  function closeLightbox() {
    lightbox.classList.remove('is-open');
    lightbox.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  openers.forEach(function (el) {
    el.addEventListener('click', function () {
      openLightbox(parseInt(el.getAttribute('data-gallery-index') || '0', 10));
    });
  });

  btnClose.addEventListener('click', closeLightbox);
  btnPrev.addEventListener('click', function () { showIndex(currentIndex - 1); });
  btnNext.addEventListener('click', function () { showIndex(currentIndex + 1); });

  lightbox.addEventListener('click', function (event) {
    if (event.target === lightbox) {
      closeLightbox();
    }
  });

  document.addEventListener('keydown', function (event) {
    if (!lightbox.classList.contains('is-open')) return;
    if (event.key === 'Escape') closeLightbox();
    if (event.key === 'ArrowLeft') showIndex(currentIndex - 1);
    if (event.key === 'ArrowRight') showIndex(currentIndex + 1);
  });
});
</script>

<?php render_footer(); ?>