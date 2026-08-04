<?php
$page_title = "Jewelry Vault & Catalog";
$page_desc = "Explore our complete haute joaillerie catalog with advanced filters for metals, gemstones, solitaires, and luxury Swiss watches.";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Filter Parameters
$selected_category = sanitize($_GET['category'] ?? '');
$selected_metal = sanitize($_GET['metal'] ?? '');
$selected_gemstone = sanitize($_GET['gemstone'] ?? '');
$min_price = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float)$_GET['max_price'] : 100000;
$sort_by = sanitize($_GET['sort'] ?? 'newest');
$search_q = sanitize($_GET['search'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 9;
$offset = ($page - 1) * $limit;

// Build Dynamic SQL Query with Prepared Statements
$where_clauses = ["1=1"];
$params = [];

if (!empty($selected_category)) {
    $where_clauses[] = "c.slug = ?";
    $params[] = $selected_category;
}
if (!empty($selected_metal)) {
    $where_clauses[] = "p.metal_type = ?";
    $params[] = $selected_metal;
}
if (!empty($selected_gemstone)) {
    $where_clauses[] = "p.gemstone_type = ?";
    $params[] = $selected_gemstone;
}
if ($min_price > 0) {
    $where_clauses[] = "COALESCE(p.discount_price, p.price) >= ?";
    $params[] = $min_price;
}
if ($max_price < 100000) {
    $where_clauses[] = "COALESCE(p.discount_price, p.price) <= ?";
    $params[] = $max_price;
}
if (!empty($search_q)) {
    $where_clauses[] = "(p.name LIKE ? OR p.description LIKE ?)";
    $params[] = "%{$search_q}%";
    $params[] = "%{$search_q}%";
}

$where_sql = implode(" AND ", $where_clauses);

// Sorting
$sort_sql = "ORDER BY p.id DESC";
if ($sort_by === 'price_asc') $sort_sql = "ORDER BY COALESCE(p.discount_price, p.price) ASC";
if ($sort_by === 'price_desc') $sort_sql = "ORDER BY COALESCE(p.discount_price, p.price) DESC";
if ($sort_by === 'rating') $sort_sql = "ORDER BY p.rating DESC";

// Count total matching items
$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM products p JOIN categories c ON p.category_id = c.id WHERE {$where_sql}");
$count_stmt->execute($params);
$total_items = (int)$count_stmt->fetchColumn();
$total_pages = ceil($total_items / $limit);

// Fetch products for page
$sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
        WHERE {$where_sql} 
        {$sort_sql} 
        LIMIT {$limit} OFFSET {$offset}";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Fetch categories for sidebar
$categories_list = $pdo->query("SELECT * FROM categories WHERE status = 1")->fetchAll();
?>

<!-- Shop Header Banner -->
<section style="background: linear-gradient(180deg, rgba(10,10,10,0.95), rgba(5,5,5,0.98)), url('assets/images/hero_bg.svg') center/cover; color: #FFF; padding: 4.5rem 0 3.5rem; text-align: center; border-bottom: 1px solid var(--border-color);">
  <div class="container">
    <span class="section-subtitle">Private Collection</span>
    <h1 style="font-size: 3rem; color: #FFF; margin-bottom: 0.5rem;">Haute Joaillerie Vault</h1>
    <p style="color: #CCCCCC;">Discover extraordinary pieces handcrafted to perfection.</p>
  </div>
</section>

<!-- Shop Body & Sidebar Layout -->
<section class="section-padding">
  <div class="container">
    <div style="display: grid; grid-template-columns: 280px 1fr; gap: 3rem;">
      
      <!-- Filter Sidebar -->
      <aside>
        <form method="GET" action="shop.php" style="background:var(--bg-card); padding:2rem; border-radius:var(--radius-md); border:1px solid var(--border-color); box-shadow:0 4px 15px rgba(0,0,0,0.03);">
          
          <h3 style="font-size:1.2rem; margin-bottom:1.5rem; border-bottom:1px solid var(--border-color); padding-bottom:0.75rem; color:var(--text-primary);">
            <i class="fa-solid fa-sliders" style="color:var(--primary-gold);"></i> Refine Vault
          </h3>

          <!-- Category Filter -->
          <div class="form-group">
            <label class="form-label">Category</label>
            <select name="category" class="form-control" onchange="this.form.submit()">
              <option value="">All Categories</option>
              <?php foreach ($categories_list as $cat): ?>
                <option value="<?php echo $cat['slug']; ?>" <?php echo $selected_category === $cat['slug'] ? 'selected' : ''; ?>>
                  <?php echo sanitize($cat['name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Metal Type Filter -->
          <div class="form-group">
            <label class="form-label">Precious Metal</label>
            <select name="metal" class="form-control" onchange="this.form.submit()">
              <option value="">All Metals</option>
              <option value="18K Yellow Gold" <?php echo $selected_metal === '18K Yellow Gold' ? 'selected' : ''; ?>>18K Yellow Gold</option>
              <option value="Rose Gold" <?php echo $selected_metal === 'Rose Gold' ? 'selected' : ''; ?>>18K Rose Gold</option>
              <option value="Platinum" <?php echo $selected_metal === 'Platinum' ? 'selected' : ''; ?>>Solid Platinum</option>
              <option value="White Gold" <?php echo $selected_metal === 'White Gold' ? 'selected' : ''; ?>>18K White Gold</option>
            </select>
          </div>

          <!-- Gemstone Filter -->
          <div class="form-group">
            <label class="form-label">Gemstone</label>
            <select name="gemstone" class="form-control" onchange="this.form.submit()">
              <option value="">All Gemstones</option>
              <option value="Diamond" <?php echo $selected_gemstone === 'Diamond' ? 'selected' : ''; ?>>GIA Diamond</option>
              <option value="Emerald" <?php echo $selected_gemstone === 'Emerald' ? 'selected' : ''; ?>>Colombian Emerald</option>
              <option value="Sapphire" <?php echo $selected_gemstone === 'Sapphire' ? 'selected' : ''; ?>>Ceylon Sapphire</option>
              <option value="Solitaire" <?php echo $selected_gemstone === 'Solitaire' ? 'selected' : ''; ?>>Single Solitaire</option>
            </select>
          </div>

          <!-- Price Range Filter -->
          <div class="form-group">
            <label class="form-label">Price Range ($)</label>
            <div style="display:flex; gap:0.5rem;">
              <input type="number" name="min_price" placeholder="Min" value="<?php echo $min_price ?: ''; ?>" class="form-control" style="padding:0.5rem;">
              <input type="number" name="max_price" placeholder="Max" value="<?php echo $max_price < 100000 ? $max_price : ''; ?>" class="form-control" style="padding:0.5rem;">
            </div>
          </div>

          <!-- Apply Filter Button -->
          <button type="submit" class="btn btn-primary" style="width:100%; margin-top:1rem;">
            <i class="fa-solid fa-filter"></i> Apply Filters
          </button>
          
          <?php if (!empty($selected_category) || !empty($selected_metal) || !empty($selected_gemstone) || $min_price > 0 || $max_price < 100000): ?>
            <a href="shop.php" class="btn btn-dark" style="width:100%; margin-top:0.5rem; text-align:center; padding:0.6rem;">Reset Filters</a>
          <?php endif; ?>

        </form>
      </aside>

      <!-- Main Products Grid -->
      <main>
        
        <!-- Sorting Bar -->
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem; background:var(--bg-secondary); padding:1rem 1.5rem; border-radius:var(--radius-sm); border:1px solid var(--border-color);">
          <div style="font-size:0.9rem; color:var(--text-secondary);">
            Showing <strong style="color:var(--text-primary);"><?php echo count($products); ?></strong> of <strong style="color:var(--text-primary);"><?php echo $total_items; ?></strong> master creations
          </div>
          <form method="GET" action="shop.php" style="display:flex; align-items:center; gap:0.5rem;">
            <input type="hidden" name="category" value="<?php echo $selected_category; ?>">
            <input type="hidden" name="metal" value="<?php echo $selected_metal; ?>">
            <input type="hidden" name="gemstone" value="<?php echo $selected_gemstone; ?>">
            <label style="font-size:0.85rem; font-weight:600; color:var(--text-primary);">Sort By:</label>
            <select name="sort" class="form-control" onchange="this.form.submit()" style="padding:0.5rem 1rem; width:auto;">
              <option value="newest" <?php echo $sort_by === 'newest' ? 'selected' : ''; ?>>Newest Additions</option>
              <option value="price_asc" <?php echo $sort_by === 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
              <option value="price_desc" <?php echo $sort_by === 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
              <option value="rating" <?php echo $sort_by === 'rating' ? 'selected' : ''; ?>>Highest Rated</option>
            </select>
          </form>
        </div>

        <!-- Products Grid -->
        <?php if (count($products) > 0): ?>
          <div class="grid-3">
            <?php foreach ($products as $p): ?>
              <div class="product-card">
                <div class="product-thumb">
                  <?php if ($p['is_featured']): ?>
                    <span class="product-badge">Featured</span>
                  <?php endif; ?>
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
                  <span class="product-category-name"><?php echo sanitize($p['category_name']); ?> &bull; <?php echo sanitize($p['metal_type']); ?></span>
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

          <!-- Pagination -->
          <?php if ($total_pages > 1): ?>
            <div style="display:flex; justify-content:center; gap:0.5rem; margin-top:3.5rem;">
              <?php for ($i=1; $i<=$total_pages; $i++): ?>
                <a href="shop.php?page=<?php echo $i; ?>&category=<?php echo $selected_category; ?>&metal=<?php echo $selected_metal; ?>&gemstone=<?php echo $selected_gemstone; ?>&sort=<?php echo $sort_by; ?>" 
                   class="btn <?php echo $page === $i ? 'btn-primary' : 'btn-dark'; ?>" style="width:40px; height:40px; padding:0;">
                  <?php echo $i; ?>
                </a>
              <?php endfor; ?>
            </div>
          <?php endif; ?>

        <?php else: ?>
          <div style="text-align:center; padding:5rem 2rem; background:var(--bg-secondary); border-radius:var(--radius-md); border:1px solid var(--border-color);">
            <i class="fa-solid fa-gem fa-3x" style="color:var(--primary-gold); margin-bottom:1rem;"></i>
            <h3 style="color:var(--text-primary);">No Jewelry Found</h3>
            <p style="color:var(--text-secondary); margin-bottom:1.5rem;">Try relaxing your filter parameters to view other pieces.</p>
            <a href="shop.php" class="btn btn-primary">Clear All Filters</a>
          </div>
        <?php endif; ?>

      </main>

    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
