<?php
require_once __DIR__ . '/includes/functions.php';

$product_id = (int)($_GET['id'] ?? 0);

if (!$product_id) {
    header('Location: shop.php');
    exit;
}

// Fetch Product Details
$stmt = $pdo->prepare("
    SELECT p.*, c.name as category_name, c.slug as category_slug 
    FROM products p 
    JOIN categories c ON p.category_id = c.id 
    WHERE p.id = ?
");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: 404.php');
    exit;
}

$page_title = $product['name'];
$page_desc = $product['short_description'] ?? $product['name'];
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Decode gallery images
$gallery = !empty($product['gallery_images']) ? json_decode($product['gallery_images'], true) : [$product['main_image']];
if (empty($gallery)) $gallery = [$product['main_image']];

// Fetch Approved Reviews
$stmt = $pdo->prepare("
    SELECT r.*, u.first_name, u.last_name 
    FROM reviews r 
    JOIN users u ON r.user_id = u.id 
    WHERE r.product_id = ? AND r.status = 'approved' 
    ORDER BY r.id DESC
");
$stmt->execute([$product_id]);
$reviews = $stmt->fetchAll();

// Fetch Related Products
$stmt = $pdo->prepare("
    SELECT p.*, c.name as category_name 
    FROM products p 
    JOIN categories c ON p.category_id = c.id 
    WHERE p.category_id = ? AND p.id != ? 
    LIMIT 4
");
$stmt->execute([$product['category_id'], $product_id]);
$related_products = $stmt->fetchAll();
?>

<!-- Breadcrumb -->
<div style="background:var(--secondary-bg); padding:1rem 0; font-size:0.85rem; border-bottom:1px solid var(--border-color);">
  <div class="container">
    <a href="index.php">Home</a> &gt; 
    <a href="shop.php">Vault</a> &gt; 
    <a href="shop.php?category=<?php echo $product['category_slug']; ?>"><?php echo sanitize($product['category_name']); ?></a> &gt; 
    <span style="color:var(--primary-gold); font-weight:600;"><?php echo sanitize($product['name']); ?></span>
  </div>
</div>

<!-- Product Details Main Section -->
<section class="section-padding">
  <div class="container">
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:4rem;">
      
      <!-- Image Gallery -->
      <div>
        <div style="background:#FAF7F2; border-radius:var(--radius-md); padding:3rem; border:1px solid var(--border-color); text-align:center; position:relative; overflow:hidden; margin-bottom:1.5rem;">
          <img id="main-product-image" src="assets/images/<?php echo $product['main_image']; ?>" alt="<?php echo sanitize($product['name']); ?>" style="max-height:420px; margin:0 auto; object-fit:contain; transition:transform 0.4s ease;" onmousemove="this.style.transform='scale(1.35)'" onmouseleave="this.style.transform='scale(1)'">
          <span style="position:absolute; bottom:1rem; right:1rem; font-size:0.75rem; color:#888;"><i class="fa-solid fa-magnifying-glass-plus"></i> Hover to Zoom</span>
        </div>

        <!-- Thumbnails -->
        <div style="display:flex; gap:1rem; justify-content:center;">
          <?php foreach ($gallery as $img): ?>
            <div class="gallery-thumb-item" data-src="assets/images/<?php echo $img; ?>" style="width:80px; height:80px; background:#FAF7F2; border:2px solid var(--border-color); border-radius:var(--radius-sm); padding:0.5rem; cursor:pointer; overflow:hidden;">
              <img src="assets/images/<?php echo $img; ?>" style="width:100%; height:100%; object-fit:contain;">
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Specs & Purchase Panel -->
      <div>
        <span style="color:var(--primary-gold); font-size:0.85rem; font-weight:600; text-transform:uppercase; letter-spacing:2px; display:block; margin-bottom:0.5rem;">
          <?php echo sanitize($product['category_name']); ?> &bull; SKU: <?php echo sanitize($product['sku']); ?>
        </span>
        
        <h1 style="font-size:2.4rem; margin-bottom:1rem; line-height:1.2;"><?php echo sanitize($product['name']); ?></h1>

        <div style="display:flex; align-items:center; gap:1rem; margin-bottom:1.5rem;">
          <div style="color:var(--accent-gold); font-size:1.1rem;">
            <i class="fa-solid fa-star"></i> <?php echo number_format($product['rating'], 1); ?>
          </div>
          <span style="color:#888; font-size:0.9rem;">(<?php echo $product['review_count']; ?> verified patron reviews)</span>
        </div>

        <!-- Price Display -->
        <div style="display:flex; align-items:baseline; gap:1.5rem; margin-bottom:2rem; padding-bottom:1.5rem; border-bottom:1px solid var(--border-color);">
          <span style="font-size:2.5rem; font-weight:700; color:var(--primary-gold);">
            <?php echo format_price($product['discount_price'] ?? $product['price']); ?>
          </span>
          <?php if ($product['discount_price']): ?>
            <span style="font-size:1.25rem; text-decoration:line-through; color:#999;">
              <?php echo format_price($product['price']); ?>
            </span>
            <span style="background:var(--primary-gold); color:#111; font-size:0.75rem; font-weight:700; padding:0.25rem 0.75rem; border-radius:50px;">SAVE DISCOUNT</span>
          <?php endif; ?>
        </div>

        <p style="color:#666; font-size:1rem; margin-bottom:2rem; line-height:1.8;">
          <?php echo sanitize($product['description']); ?>
        </p>

        <!-- Specifications Grid -->
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:2rem; background:var(--secondary-bg); padding:1.25rem; border-radius:var(--radius-sm);">
          <div>
            <span style="font-size:0.8rem; color:#888; display:block;">Metal Specification</span>
            <strong style="font-size:0.95rem; color:var(--dark-bg);"><?php echo sanitize($product['metal_type']); ?></strong>
          </div>
          <div>
            <span style="font-size:0.8rem; color:#888; display:block;">Gemstone Cut</span>
            <strong style="font-size:0.95rem; color:var(--dark-bg);"><?php echo sanitize($product['gemstone_type']); ?></strong>
          </div>
          <div>
            <span style="font-size:0.8rem; color:#888; display:block;">Vault Stock Status</span>
            <strong style="font-size:0.95rem; color:<?php echo $product['stock'] > 0 ? 'var(--success)' : 'var(--error)'; ?>;">
              <?php echo $product['stock'] > 0 ? "In Stock ({$product['stock']} units available)" : 'Out of Stock'; ?>
            </strong>
          </div>
          <div>
            <span style="font-size:0.8rem; color:#888; display:block;">Certification</span>
            <strong style="font-size:0.95rem; color:var(--dark-bg);">GIA / HRD International</strong>
          </div>
        </div>

        <!-- Quantity & CTA Buttons -->
        <div style="display:flex; gap:1.25rem; align-items:center; margin-bottom:2.5rem;">
          <div style="display:flex; align-items:center; border:1px solid var(--border-color); border-radius:50px; padding:0.4rem 0.8rem;">
            <button onclick="let q=document.getElementById('p-qty'); if(q.value>1) q.value--;" style="background:none; border:none; cursor:pointer; font-size:1.2rem; padding:0 0.5rem;">-</button>
            <input type="number" id="p-qty" value="1" min="1" max="<?php echo $product['stock']; ?>" style="width:45px; text-align:center; border:none; outline:none; font-weight:600; background:transparent;">
            <button onclick="let q=document.getElementById('p-qty'); if(q.value<<?php echo $product['stock']; ?>) q.value++;" style="background:none; border:none; cursor:pointer; font-size:1.2rem; padding:0 0.5rem;">+</button>
          </div>

          <button onclick="addToCart(<?php echo $product['id']; ?>, document.getElementById('p-qty').value)" class="btn btn-primary" style="flex-grow:1;">
            <i class="fa-solid fa-bag-shopping"></i> Add to Cart
          </button>

          <button onclick="toggleWishlist(<?php echo $product['id']; ?>, this)" class="icon-btn-rounded" title="Add to Wishlist" style="width:50px; height:50px; font-size:1.2rem; color:<?php echo is_in_wishlist($product['id']) ? '#E53935' : 'inherit'; ?>;">
            <i class="fa-solid fa-heart"></i>
          </button>
        </div>

        <!-- Trust Badges -->
        <div style="display:flex; justify-content:space-between; border-top:1px solid var(--border-color); padding-top:1.5rem; font-size:0.8rem; color:#777;">
          <div><i class="fa-solid fa-truck-shield" style="color:var(--primary-gold);"></i> Insured Transport</div>
          <div><i class="fa-solid fa-rotate-left" style="color:var(--primary-gold);"></i> 30-Day Returns</div>
          <div><i class="fa-solid fa-award" style="color:var(--primary-gold);"></i> Lifetime Guarantee</div>
        </div>

      </div>

    </div>

    <!-- Product Reviews & Ratings Section -->
    <div style="margin-top:5rem; padding-top:4rem; border-top:1px solid var(--border-color);">
      <h2 style="font-size:2rem; margin-bottom:2rem;">Patron Reviews & Ratings</h2>
      
      <div style="display:grid; grid-template-columns:1fr 2fr; gap:3rem;">
        
        <!-- Submit Review Form -->
        <div style="background:var(--secondary-bg); padding:2rem; border-radius:var(--radius-md); border:1px solid var(--border-color);">
          <h3 style="font-size:1.2rem; margin-bottom:1rem;">Write a Review</h3>
          <?php if (is_logged_in()): ?>
            <form onsubmit="event.preventDefault(); submitProductReview(this, <?php echo $product['id']; ?>);">
              <div class="form-group">
                <label class="form-label">Rating</label>
                <select name="rating" class="form-control" required>
                  <option value="5">5 Stars - Exceptional</option>
                  <option value="4">4 Stars - Very Good</option>
                  <option value="3">3 Stars - Good</option>
                  <option value="2">2 Stars - Fair</option>
                  <option value="1">1 Star - Poor</option>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label">Review Headline</label>
                <input type="text" name="review_title" placeholder="e.g. Absolutely stunning diamond cut" class="form-control" required>
              </div>
              <div class="form-group">
                <label class="form-label">Detailed Experience</label>
                <textarea name="comment" rows="4" placeholder="Share your experience with this creation..." class="form-control" required></textarea>
              </div>
              <button type="submit" class="btn btn-primary" style="width:100%;"><i class="fa-solid fa-paper-plane"></i> Submit Review</button>
            </form>
          <?php else: ?>
            <p style="color:#888; font-size:0.9rem; margin-bottom:1.5rem;">Please log in to submit a verified patron review.</p>
            <a href="login.php" class="btn btn-outline" style="width:100%; text-align:center;">Login to Review</a>
          <?php endif; ?>
        </div>

        <!-- Reviews List -->
        <div>
          <?php if (count($reviews) > 0): ?>
            <?php foreach ($reviews as $rev): ?>
              <div style="padding:1.5rem 0; border-bottom:1px solid var(--border-color);">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
                  <strong style="font-size:1rem;"><?php echo sanitize($rev['first_name'] . ' ' . $rev['last_name']); ?></strong>
                  <span style="font-size:0.8rem; color:#999;"><?php echo date('M d, Y', strtotime($rev['created_at'])); ?></span>
                </div>
                <div style="color:var(--accent-gold); font-size:0.9rem; margin-bottom:0.5rem;">
                  <?php for ($i=0; $i<$rev['rating']; $i++): ?><i class="fa-solid fa-star"></i><?php endfor; ?>
                </div>
                <h4 style="font-size:1.05rem; margin-bottom:0.4rem;"><?php echo sanitize($rev['review_title']); ?></h4>
                <p style="color:#666; font-size:0.9rem; line-height:1.6;"><?php echo sanitize($rev['comment']); ?></p>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p style="color:#888; font-style:italic;">No reviews yet. Be the first patron to review this piece.</p>
          <?php endif; ?>
        </div>

      </div>
    </div>

    <!-- Related Products -->
    <?php if (count($related_products) > 0): ?>
      <div style="margin-top:5rem;">
        <h2 style="font-size:2rem; margin-bottom:2rem; text-align:center;">Complementary Creations</h2>
        <div class="grid-4">
          <?php foreach ($related_products as $rp): ?>
            <div class="product-card">
              <div class="product-thumb">
                <img src="assets/images/<?php echo $rp['main_image']; ?>" alt="<?php echo sanitize($rp['name']); ?>">
              </div>
              <div class="product-details">
                <span class="product-category-name"><?php echo sanitize($rp['category_name']); ?></span>
                <h3 class="product-title"><a href="product.php?id=<?php echo $rp['id']; ?>"><?php echo sanitize($rp['name']); ?></a></h3>
                <div class="product-price-row">
                  <span class="price-current"><?php echo format_price($rp['discount_price'] ?? $rp['price']); ?></span>
                  <a href="product.php?id=<?php echo $rp['id']; ?>" class="btn btn-primary" style="padding:0.4rem 0.8rem; font-size:0.75rem; margin-left:auto;">View</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>

<script>
function submitProductReview(form, productId) {
  const formData = new FormData(form);
  formData.append('product_id', productId);

  fetch('api/review_action.php', {
    method: 'POST',
    body: new URLSearchParams(formData)
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      showToast(data.message);
      setTimeout(() => location.reload(), 1500);
    } else {
      showToast(data.message, 'error');
    }
  });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
