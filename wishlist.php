<?php
$page_title = "My Saved Wishlist";
$page_desc = "View your saved luxury jewelry pieces in your private GemGlitz wishlist.";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

require_login();
$user_id = get_current_user_id();

// Fetch Wishlist items
$stmt = $pdo->prepare("
    SELECT w.id as wishlist_id, p.*, c.name as category_name 
    FROM wishlist w 
    JOIN products p ON w.product_id = p.id 
    JOIN categories c ON p.category_id = c.id 
    WHERE w.user_id = ?
");
$stmt->execute([$user_id]);
$wishlist_items = $stmt->fetchAll();
?>

<section class="section-padding">
  <div class="container">
    <div style="margin-bottom: 2.5rem;">
      <span class="section-subtitle">Private Vault</span>
      <h1 class="section-title">My Wishlist Creations</h1>
    </div>

    <?php if (count($wishlist_items) > 0): ?>
      <div class="grid-4">
        <?php foreach ($wishlist_items as $w): ?>
          <div class="product-card">
            <div class="product-thumb">
              <img src="assets/images/<?php echo $w['main_image']; ?>" alt="<?php echo sanitize($w['name']); ?>">
              <div class="product-actions-floating">
                <button onclick="toggleWishlist(<?php echo $w['id']; ?>, this); setTimeout(() => location.reload(), 400);" class="icon-btn-rounded" title="Remove from Wishlist" style="color:#E53935;">
                  <i class="fa-solid fa-trash-can"></i>
                </button>
              </div>
            </div>
            <div class="product-details">
              <span class="product-category-name"><?php echo sanitize($w['category_name']); ?></span>
              <h3 class="product-title"><a href="product.php?id=<?php echo $w['id']; ?>"><?php echo sanitize($w['name']); ?></a></h3>
              <div class="product-price-row">
                <span class="price-current"><?php echo format_price($w['discount_price'] ?? $w['price']); ?></span>
                <button onclick="addToCart(<?php echo $w['id']; ?>);" class="btn btn-primary" style="padding:0.5rem 0.9rem; font-size:0.8rem; margin-left:auto;">
                  <i class="fa-solid fa-bag-shopping"></i> Move to Cart
                </button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div style="text-align:center; padding:5rem 2rem; background:var(--secondary-bg); border-radius:var(--radius-md);">
        <i class="fa-regular fa-heart fa-3x" style="color:var(--primary-gold); margin-bottom:1rem;"></i>
        <h2>Your Wishlist is Empty</h2>
        <p style="color:#888; margin-bottom:2rem;">Save your favorite haute joaillerie pieces to your personal vault.</p>
        <a href="shop.php" class="btn btn-primary">Browse Fine Creations</a>
      </div>
    <?php endif; ?>

  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
