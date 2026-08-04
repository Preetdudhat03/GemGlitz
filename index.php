<?php
$page_title = "Haute Joaillerie & Fine Luxury Jewelry";
$page_desc = "Explore GemGlitz haute joaillerie collections featuring GIA-certified diamond rings, 18K gold necklaces, royal sapphire drop earrings, and Swiss watches.";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Fetch Categories
$stmt = $pdo->query("SELECT * FROM categories WHERE status = 1 ORDER BY id ASC");
$categories = $stmt->fetchAll();

// Fetch Featured Products
$stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.is_featured = 1 LIMIT 4");
$featured_products = $stmt->fetchAll();

// Fetch Best Sellers
$stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.is_bestseller = 1 LIMIT 4");
$bestseller_products = $stmt->fetchAll();

// Fetch Trending
$stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.is_trending = 1 LIMIT 4");
$trending_products = $stmt->fetchAll();

// Fetch Customer Reviews
$stmt = $pdo->query("SELECT r.*, u.first_name, u.last_name, p.name as product_name FROM reviews r JOIN users u ON r.user_id = u.id JOIN products p ON r.product_id = p.id WHERE r.status = 'approved' ORDER BY r.id DESC LIMIT 3");
$reviews = $stmt->fetchAll();
?>

<!-- Animated Hero Banner -->
<section class="hero-section">
  <div class="container">
    <div class="hero-content">
      <span class="section-subtitle"><i class="fa-solid fa-crown"></i> Haute Joaillerie 2026</span>
      <h1 class="hero-title">Timeless Luxury, Handcrafted for Eternity</h1>
      <p class="hero-desc">Indulge in our master artisan creations forged in 18K yellow gold, rare platinum, and GIA-certified D-Flawless diamonds.</p>
      <div class="hero-btns">
        <a href="shop.php" class="btn btn-primary"><i class="fa-solid fa-gem"></i> Explore Collection</a>
        <a href="shop.php?category=high-solitaires" class="btn btn-outline"><i class="fa-regular fa-compass"></i> Solitaire Vault</a>
      </div>
    </div>
  </div>
</section>

<!-- Shop by Category Section -->
<section class="section-padding" style="background:var(--secondary-bg);">
  <div class="container">
    <div class="text-center" style="margin-bottom: 3.5rem;">
      <span class="section-subtitle">Curated Categories</span>
      <h2 class="section-title">Discover Our Fine Creations</h2>
    </div>
    
    <div class="grid-3">
      <?php foreach ($categories as $cat): ?>
        <a href="shop.php?category=<?php echo $cat['slug']; ?>" class="category-card">
          <img src="assets/images/<?php echo $cat['image_url']; ?>" alt="<?php echo sanitize($cat['name']); ?>">
          <div class="category-card-content">
            <h3 class="category-name"><?php echo sanitize($cat['name']); ?></h3>
            <span class="category-link">Shop Category <i class="fa-solid fa-arrow-right-long"></i></span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Featured Collection Section -->
<section class="section-padding">
  <div class="container">
    <div class="text-center" style="margin-bottom: 3.5rem;">
      <span class="section-subtitle">Private Reserve</span>
      <h2 class="section-title">Featured High Jewelry</h2>
    </div>

    <div class="grid-4">
      <?php foreach ($featured_products as $p): ?>
        <div class="product-card">
          <div class="product-thumb">
            <span class="product-badge">Featured</span>
            <img src="assets/images/<?php echo $p['main_image']; ?>" alt="<?php echo sanitize($p['name']); ?>">
            <div class="product-actions-floating">
              <button onclick="toggleWishlist(<?php echo $p['id']; ?>, this)" class="icon-btn-rounded" title="Add to Wishlist" style="color: <?php echo is_in_wishlist($p['id']) ? '#E53935' : 'inherit'; ?>;">
                <i class="fa-solid fa-heart"></i>
              </button>
              <button onclick="openQuickView(<?php echo $p['id']; ?>)" class="icon-btn-rounded" title="Quick View">
                <i class="fa-solid fa-eye"></i>
              </button>
            </div>
          </div>
          <div class="product-details">
            <span class="product-category-name"><?php echo sanitize($p['category_name']); ?></span>
            <h3 class="product-title"><a href="product.php?id=<?php echo $p['id']; ?>"><?php echo sanitize($p['name']); ?></a></h3>
            <div class="product-rating">
              <i class="fa-solid fa-star"></i> <?php echo number_format($p['rating'], 1); ?> (<?php echo $p['review_count']; ?>)
            </div>
            <div class="product-price-row">
              <span class="price-current"><?php echo format_price($p['discount_price'] ?? $p['price']); ?></span>
              <?php if ($p['discount_price']): ?>
                <span class="price-old"><?php echo format_price($p['price']); ?></span>
              <?php endif; ?>
              <button onclick="addToCart(<?php echo $p['id']; ?>)" class="btn btn-primary" style="padding:0.5rem 1rem; font-size:0.8rem; margin-left:auto;">
                <i class="fa-solid fa-bag-shopping"></i>
              </button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Luxury Promo / Flash Sale Banner -->
<section style="background: linear-gradient(135deg, #111, #1F1A10); color: #FFF; padding: 5rem 0; border-y: 1px solid var(--primary-gold);">
  <div class="container" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:2rem;">
    <div style="max-width:600px;">
      <span class="section-subtitle" style="color:var(--primary-gold);"><i class="fa-solid fa-bolt"></i> Exclusive Vault Event</span>
      <h2 style="font-size: 3rem; color: #FFF; margin-bottom: 1rem;">The Crown Jewel Solitaire Event</h2>
      <p style="color: #AAA; margin-bottom: 1.5rem; font-size: 1.05rem;">Receive up to $2,000 complimentary gift credit on all GIA 3ct+ solitaire diamond purchases this week only. Use code <strong>LUXURY10</strong>.</p>
      <a href="shop.php?category=high-solitaires" class="btn btn-primary"><i class="fa-solid fa-crown"></i> Claim Privilege</a>
    </div>
    <div style="text-align:center;">
      <div style="display:flex; gap:1rem; font-family:var(--font-heading);">
        <div style="background:rgba(200, 169, 106, 0.15); border:1px solid var(--primary-gold); padding:1rem 1.5rem; border-radius:12px;">
          <span style="font-size:2.2rem; font-weight:700; color:var(--primary-gold);">02</span>
          <div style="font-size:0.75rem; text-transform:uppercase;">Days</div>
        </div>
        <div style="background:rgba(200, 169, 106, 0.15); border:1px solid var(--primary-gold); padding:1rem 1.5rem; border-radius:12px;">
          <span style="font-size:2.2rem; font-weight:700; color:var(--primary-gold);">14</span>
          <div style="font-size:0.75rem; text-transform:uppercase;">Hours</div>
        </div>
        <div style="background:rgba(200, 169, 106, 0.15); border:1px solid var(--primary-gold); padding:1rem 1.5rem; border-radius:12px;">
          <span style="font-size:2.2rem; font-weight:700; color:var(--primary-gold);">38</span>
          <div style="font-size:0.75rem; text-transform:uppercase;">Mins</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Best Sellers & Trending Section -->
<section class="section-padding">
  <div class="container">
    <div class="text-center" style="margin-bottom: 3.5rem;">
      <span class="section-subtitle">Most Coveted</span>
      <h2 class="section-title">Best Sellers & Iconic Designs</h2>
    </div>

    <div class="grid-4">
      <?php foreach ($bestseller_products as $p): ?>
        <div class="product-card">
          <div class="product-thumb">
            <span class="product-badge" style="background:#111; color:#C8A96A;">Best Seller</span>
            <img src="assets/images/<?php echo $p['main_image']; ?>" alt="<?php echo sanitize($p['name']); ?>">
            <div class="product-actions-floating">
              <button onclick="toggleWishlist(<?php echo $p['id']; ?>, this)" class="icon-btn-rounded" title="Add to Wishlist">
                <i class="fa-solid fa-heart"></i>
              </button>
              <button onclick="openQuickView(<?php echo $p['id']; ?>)" class="icon-btn-rounded" title="Quick View">
                <i class="fa-solid fa-eye"></i>
              </button>
            </div>
          </div>
          <div class="product-details">
            <span class="product-category-name"><?php echo sanitize($p['category_name']); ?></span>
            <h3 class="product-title"><a href="product.php?id=<?php echo $p['id']; ?>"><?php echo sanitize($p['name']); ?></a></h3>
            <div class="product-rating">
              <i class="fa-solid fa-star"></i> <?php echo number_format($p['rating'], 1); ?> (<?php echo $p['review_count']; ?>)
            </div>
            <div class="product-price-row">
              <span class="price-current"><?php echo format_price($p['discount_price'] ?? $p['price']); ?></span>
              <button onclick="addToCart(<?php echo $p['id']; ?>)" class="btn btn-primary" style="padding:0.5rem 1rem; font-size:0.8rem; margin-left:auto;">
                <i class="fa-solid fa-bag-shopping"></i>
              </button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Brand Heritage & Craftsmanship Story -->
<section class="section-padding" style="background:var(--secondary-bg);">
  <div class="container" style="display:grid; grid-template-columns:1fr 1fr; gap:4rem; align-items:center;">
    <div>
      <span class="section-subtitle">Heritage & Artistry</span>
      <h2 class="section-title" style="margin-bottom:1.5rem;">Two Centuries of Master Craftsmanship</h2>
      <p style="color:#666; margin-bottom:1.5rem; line-height:1.8;">
        Every GemGlitz creation is a symphony of rare gemstones, master goldsmithing, and rigorous precision. From initial hand-sketched design to final GIA diamond setting in Paris, we preserve the timeless traditions of haute joaillerie.
      </p>
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:2rem;">
        <div>
          <h4 style="color:var(--primary-gold); font-size:1.8rem; font-weight:700;">100%</h4>
          <span style="font-size:0.85rem; color:#888;">Ethically Sourced Gold</span>
        </div>
        <div>
          <h4 style="color:var(--primary-gold); font-size:1.8rem; font-weight:700;">GIA</h4>
          <span style="font-size:0.85rem; color:#888;">Certified Flawless Gems</span>
        </div>
      </div>
      <a href="about.php" class="btn btn-primary"><i class="fa-solid fa-book-open"></i> Read Our Story</a>
    </div>
    <div style="background:var(--dark-card); padding:2rem; border-radius:var(--radius-lg); border:1px solid var(--border-color);">
      <img src="assets/images/product_solitaire_1.svg" alt="Craftsmanship" style="border-radius:var(--radius-md);">
    </div>
  </div>
</section>

<!-- Customer Reviews Section -->
<section class="section-padding">
  <div class="container">
    <div class="text-center" style="margin-bottom: 3.5rem;">
      <span class="section-subtitle">Client Testimonials</span>
      <h2 class="section-title">Words From Our Patrons</h2>
    </div>

    <div class="grid-3">
      <?php foreach ($reviews as $rev): ?>
        <div style="background:var(--white); padding:2.5rem; border-radius:var(--radius-md); border:1px solid var(--border-color); box-shadow:var(--shadow-sm);">
          <div style="color:var(--accent-gold); margin-bottom:1rem;">
            <?php for ($i=0; $i<$rev['rating']; $i++): ?><i class="fa-solid fa-star"></i><?php endfor; ?>
          </div>
          <h4 style="font-size:1.1rem; margin-bottom:0.75rem;">"<?php echo sanitize($rev['review_title']); ?>"</h4>
          <p style="color:#666; font-size:0.9rem; margin-bottom:1.5rem; font-style:italic;">
            <?php echo sanitize($rev['comment']); ?>
          </p>
          <div style="font-weight:600; font-size:0.9rem; color:var(--dark-bg);">
            <?php echo sanitize($rev['first_name'] . ' ' . $rev['last_name']); ?>
            <span style="display:block; font-weight:400; font-size:0.8rem; color:#888;">Verified Buyer &bull; <?php echo sanitize($rev['product_name']); ?></span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
