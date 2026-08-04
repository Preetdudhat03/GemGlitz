<?php
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';

$message = '';
$error = '';

// Handle Delete Product
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$delete_id]);
    set_flash_message('success', 'Product deleted successfully!');
    header('Location: products.php');
    exit;
}

// Handle Add or Edit Product Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['product_id'] ?? 0);
    $category_id = (int)$_POST['category_id'];
    $name = sanitize($_POST['name']);
    $sku = sanitize($_POST['sku']);
    $price = (float)$_POST['price'];
    $discount_price = !empty($_POST['discount_price']) ? (float)$_POST['discount_price'] : null;
    $stock = (int)$_POST['stock'];
    $metal_type = sanitize($_POST['metal_type']);
    $gemstone_type = sanitize($_POST['gemstone_type']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_bestseller = isset($_POST['is_bestseller']) ? 1 : 0;
    $is_trending = isset($_POST['is_trending']) ? 1 : 0;
    $description = sanitize($_POST['description']);
    $short_description = sanitize($_POST['short_description']);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));

    // Image Upload Handler
    $main_image = sanitize($_POST['existing_image'] ?? 'product_ring_1.svg');
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['product_image']['tmp_name'];
        $file_name = time() . '_' . basename($_FILES['product_image']['name']);
        $target_dir = __DIR__ . '/../assets/images/';
        if (move_uploaded_file($file_tmp, $target_dir . $file_name)) {
            $main_image = $file_name;
        }
    }

    if ($id > 0) {
        // Update Product
        $stmt = $pdo->prepare("
            UPDATE products SET category_id = ?, name = ?, slug = ?, sku = ?, short_description = ?, description = ?, price = ?, discount_price = ?, stock = ?, metal_type = ?, gemstone_type = ?, is_featured = ?, is_bestseller = ?, is_trending = ?, main_image = ? WHERE id = ?
        ");
        $stmt->execute([$category_id, $name, $slug, $sku, $short_description, $description, $price, $discount_price, $stock, $metal_type, $gemstone_type, $is_featured, $is_bestseller, $is_trending, $main_image, $id]);
        set_flash_message('success', 'Product updated successfully!');
    } else {
        // Insert New Product
        $stmt = $pdo->prepare("
            INSERT INTO products (category_id, name, slug, sku, short_description, description, price, discount_price, stock, metal_type, gemstone_type, is_featured, is_bestseller, is_trending, main_image, gallery_images) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $gallery_json = json_encode([$main_image]);
        $stmt->execute([$category_id, $name, $slug, $sku, $short_description, $description, $price, $discount_price, $stock, $metal_type, $gemstone_type, $is_featured, $is_bestseller, $is_trending, $main_image, $gallery_json]);
        set_flash_message('success', 'New haute joaillerie product added!');
    }
    header('Location: products.php');
    exit;
}

// Fetch Products List
$products = $pdo->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories WHERE status = 1")->fetchAll();

// Product being edited if edit parameter passed
$edit_product = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_product = $stmt->fetch();
}
?>

<main class="admin-main">
  
  <div class="admin-header">
    <div>
      <h2 style="font-size:1.6rem; font-family:'Playfair Display', serif;">Jewelry Catalog Management</h2>
      <span style="font-size:0.85rem; color:#718096;">Add, edit, or remove products from the vault</span>
    </div>
    <button onclick="document.getElementById('product-form-box').scrollIntoView({behavior:'smooth'})" class="btn btn-primary" style="padding:0.6rem 1.2rem; font-size:0.85rem;">
      <i class="fa-solid fa-plus"></i> Add New Creation
    </button>
  </div>

  <!-- Product List Table -->
  <div class="table-card" style="margin-bottom:3rem;">
    <h3 style="font-size:1.1rem; font-family:'Playfair Display', serif; margin-bottom:1rem;">Master Product Inventory</h3>
    
    <table class="data-table">
      <thead>
        <tr>
          <th>Image</th>
          <th>Title / SKU</th>
          <th>Category</th>
          <th>Price</th>
          <th>Stock</th>
          <th>Attributes</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $p): ?>
          <tr>
            <td><img src="../assets/images/<?php echo $p['main_image']; ?>" style="width:45px; height:45px; object-fit:contain; background:#FAF7F2; border-radius:4px; padding:2px;"></td>
            <td>
              <strong style="display:block; font-size:0.9rem;"><?php echo sanitize($p['name']); ?></strong>
              <span style="font-size:0.75rem; color:#888;">SKU: <?php echo sanitize($p['sku']); ?></span>
            </td>
            <td><?php echo sanitize($p['category_name']); ?></td>
            <td style="font-weight:700; color:var(--admin-primary);"><?php echo format_price($p['discount_price'] ?? $p['price']); ?></td>
            <td><span class="badge-status <?php echo $p['stock'] > 0 ? 'badge-success' : 'badge-danger'; ?>"><?php echo $p['stock']; ?> in stock</span></td>
            <td style="font-size:0.8rem; color:#666;">
              <?php if ($p['is_featured']): ?><span style="color:var(--admin-primary); margin-right:4px;">[Featured]</span><?php endif; ?>
              <?php if ($p['is_bestseller']): ?><span style="color:#111; margin-right:4px;">[Bestseller]</span><?php endif; ?>
            </td>
            <td>
              <a href="products.php?edit=<?php echo $p['id']; ?>#product-form-box" class="btn btn-outline" style="padding:0.25rem 0.6rem; font-size:0.75rem;">Edit</a>
              <a href="products.php?delete=<?php echo $p['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?')" class="btn btn-dark" style="padding:0.25rem 0.6rem; font-size:0.75rem; color:var(--error);">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Add / Edit Product Form Box -->
  <div id="product-form-box" class="table-card">
    <h3 style="font-size:1.3rem; font-family:'Playfair Display', serif; margin-bottom:1.5rem; border-bottom:1px solid var(--admin-border); padding-bottom:0.75rem;">
      <?php echo $edit_product ? 'Edit Product Creation' : 'Add New Jewelry Creation'; ?>
    </h3>

    <form method="POST" action="products.php" enctype="multipart/form-data">
      <input type="hidden" name="product_id" value="<?php echo $edit_product['id'] ?? 0; ?>">
      <input type="hidden" name="existing_image" value="<?php echo $edit_product['main_image'] ?? 'product_ring_1.svg'; ?>">

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
        
        <div class="form-group">
          <label class="form-label">Category *</label>
          <select name="category_id" class="form-control" required>
            <?php foreach ($categories as $cat): ?>
              <option value="<?php echo $cat['id']; ?>" <?php echo (isset($edit_product['category_id']) && $edit_product['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                <?php echo sanitize($cat['name']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Product Name *</label>
          <input type="text" name="name" value="<?php echo sanitize($edit_product['name'] ?? ''); ?>" placeholder="e.g. The Empress Solitaire Ring" class="form-control" required>
        </div>

      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:1rem;">
        
        <div class="form-group">
          <label class="form-label">SKU Code *</label>
          <input type="text" name="sku" value="<?php echo sanitize($edit_product['sku'] ?? ('GG-PROD-' . rand(100, 999))); ?>" class="form-control" required>
        </div>

        <div class="form-group">
          <label class="form-label">Regular Price ($) *</label>
          <input type="number" step="0.01" name="price" value="<?php echo $edit_product['price'] ?? ''; ?>" class="form-control" required>
        </div>

        <div class="form-group">
          <label class="form-label">Discount Price ($)</label>
          <input type="number" step="0.01" name="discount_price" value="<?php echo $edit_product['discount_price'] ?? ''; ?>" class="form-control">
        </div>

        <div class="form-group">
          <label class="form-label">Vault Stock *</label>
          <input type="number" name="stock" value="<?php echo $edit_product['stock'] ?? 10; ?>" class="form-control" required>
        </div>

      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
        
        <div class="form-group">
          <label class="form-label">Precious Metal</label>
          <input type="text" name="metal_type" value="<?php echo sanitize($edit_product['metal_type'] ?? '18K Yellow Gold'); ?>" class="form-control">
        </div>

        <div class="form-group">
          <label class="form-label">Gemstone Type</label>
          <input type="text" name="gemstone_type" value="<?php echo sanitize($edit_product['gemstone_type'] ?? 'Diamond'); ?>" class="form-control">
        </div>

      </div>

      <div class="form-group">
        <label class="form-label">Product Short Summary</label>
        <input type="text" name="short_description" value="<?php echo sanitize($edit_product['short_description'] ?? ''); ?>" class="form-control">
      </div>

      <div class="form-group">
        <label class="form-label">Full Description & Craftsmanship Details</label>
        <textarea name="description" rows="4" class="form-control" required><?php echo sanitize($edit_product['description'] ?? ''); ?></textarea>
      </div>

      <div style="display:flex; gap:2rem; margin-bottom:1.5rem;">
        <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
          <input type="checkbox" name="is_featured" <?php echo !empty($edit_product['is_featured']) ? 'checked' : ''; ?>> Featured Product
        </label>
        <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
          <input type="checkbox" name="is_bestseller" <?php echo !empty($edit_product['is_bestseller']) ? 'checked' : ''; ?>> Best Seller
        </label>
        <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
          <input type="checkbox" name="is_trending" <?php echo !empty($edit_product['is_trending']) ? 'checked' : ''; ?>> Trending Collection
        </label>
      </div>

      <div class="form-group">
        <label class="form-label">Product Image File</label>
        <input type="file" id="product-image-input" name="product_image" accept="image/*" class="form-control">
        <img id="product-image-preview" src="../assets/images/<?php echo $edit_product['main_image'] ?? 'product_ring_1.svg'; ?>" style="width:80px; height:80px; object-fit:contain; margin-top:0.5rem; border:1px solid #DDD; border-radius:4px; padding:4px;">
      </div>

      <button type="submit" class="btn btn-primary" style="padding:0.8rem 2rem;">
        <i class="fa-solid fa-floppy-disk"></i> <?php echo $edit_product ? 'Update Product' : 'Save New Product'; ?>
      </button>

      <?php if ($edit_product): ?>
        <a href="products.php" class="btn btn-dark" style="margin-left:1rem;">Cancel Edit</a>
      <?php endif; ?>

    </form>

  </div>

</main>

<script src="../assets/js/admin.js"></script>
</div>
</body>
</html>
