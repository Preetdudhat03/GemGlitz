<?php
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';

// Delete Category
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$delete_id]);
    set_flash_message('success', 'Category deleted.');
    header('Location: categories.php');
    exit;
}

// Add/Update Category
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_id = (int)($_POST['category_id'] ?? 0);
    $name = sanitize($_POST['name']);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    $description = sanitize($_POST['description']);
    $image_url = sanitize($_POST['image_url'] ?? 'ring_cat.svg');

    if ($cat_id > 0) {
        $stmt = $pdo->prepare("UPDATE categories SET name = ?, slug = ?, description = ?, image_url = ? WHERE id = ?");
        $stmt->execute([$name, $slug, $description, $image_url, $cat_id]);
        set_flash_message('success', 'Category updated.');
    } else {
        $stmt = $pdo->prepare("INSERT INTO categories (name, slug, description, image_url, status) VALUES (?, ?, ?, ?, 1)");
        $stmt->execute([$name, $slug, $description, $image_url]);
        set_flash_message('success', 'New category created.');
    }
    header('Location: categories.php');
    exit;
}

$categories = $pdo->query("SELECT c.*, COUNT(p.id) as product_count FROM categories c LEFT JOIN products p ON c.id = p.category_id GROUP BY c.id ORDER BY c.id ASC")->fetchAll();
?>

<main class="admin-main">
  <div class="admin-header">
    <div>
      <h2 style="font-size:1.6rem; font-family:'Playfair Display', serif;">Category Management</h2>
      <span style="font-size:0.85rem; color:#718096;">Organize haute joaillerie collections</span>
    </div>
  </div>

  <div style="display:grid; grid-template-columns: 2fr 1fr; gap:2rem;">
    
    <!-- Table -->
    <div class="table-card">
      <h3 style="font-size:1.1rem; font-family:'Playfair Display', serif; margin-bottom:1rem;">Active Categories</h3>
      <table class="data-table">
        <thead>
          <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Slug</th>
            <th>Products</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($categories as $cat): ?>
            <tr>
              <td><img src="../assets/images/<?php echo $cat['image_url']; ?>" style="width:40px; height:40px; object-fit:cover; border-radius:4px;"></td>
              <td style="font-weight:600;"><?php echo sanitize($cat['name']); ?></td>
              <td><code><?php echo sanitize($cat['slug']); ?></code></td>
              <td><span class="badge-status badge-info"><?php echo $cat['product_count']; ?> items</span></td>
              <td>
                <a href="categories.php?delete=<?php echo $cat['id']; ?>" onclick="return confirm('Delete category?')" style="color:var(--error); font-size:0.85rem;"><i class="fa-solid fa-trash-can"></i></a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Form -->
    <div class="table-card">
      <h3 style="font-size:1.1rem; font-family:'Playfair Display', serif; margin-bottom:1rem;">Create Category</h3>
      <form method="POST" action="categories.php">
        <div class="form-group">
          <label class="form-label">Category Name *</label>
          <input type="text" name="name" placeholder="e.g. Solitaires" class="form-control" required>
        </div>
        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea name="description" rows="3" class="form-control"></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">SVG Graphic Image</label>
          <input type="text" name="image_url" value="ring_cat.svg" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;">Save Category</button>
      </form>
    </div>

  </div>
</main>

</div>
</body>
</html>
