<?php
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';

// Add Coupon
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = strtoupper(sanitize($_POST['code']));
    $discount_percent = (float)$_POST['discount_percent'];
    $min_order_amount = (float)$_POST['min_order_amount'];
    $max_discount = (float)$_POST['max_discount'];
    $expiry_date = sanitize($_POST['expiry_date']);

    $stmt = $pdo->prepare("INSERT INTO coupons (code, discount_percent, min_order_amount, max_discount, expiry_date, status) VALUES (?, ?, ?, ?, ?, 1)");
    $stmt->execute([$code, $discount_percent, $min_order_amount, $max_discount, $expiry_date]);
    set_flash_message('success', 'Coupon created successfully.');
    header('Location: coupons.php');
    exit;
}

// Toggle Coupon Status
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $stmt = $pdo->prepare("UPDATE coupons SET status = NOT status WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: coupons.php');
    exit;
}

$coupons = $pdo->query("SELECT * FROM coupons ORDER BY id DESC")->fetchAll();
?>

<main class="admin-main">
  <div class="admin-header">
    <div>
      <h2 style="font-size:1.6rem; font-family:'Playfair Display', serif;">Coupon Code Management</h2>
      <span style="font-size:0.85rem; color:#718096;">Create promotional discount codes</span>
    </div>
  </div>

  <div style="display:grid; grid-template-columns:2fr 1fr; gap:2rem;">
    
    <div class="table-card">
      <h3 style="font-size:1.1rem; font-family:'Playfair Display', serif; margin-bottom:1rem;">Active & Historical Coupons</h3>
      <table class="data-table">
        <thead>
          <tr>
            <th>Code</th>
            <th>Discount %</th>
            <th>Min Order</th>
            <th>Max Discount</th>
            <th>Expiry Date</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($coupons as $c): ?>
            <tr>
              <td style="font-weight:700; color:var(--admin-primary);"><?php echo sanitize($c['code']); ?></td>
              <td><?php echo $c['discount_percent']; ?>%</td>
              <td><?php echo format_price($c['min_order_amount']); ?></td>
              <td><?php echo format_price($c['max_discount']); ?></td>
              <td><?php echo $c['expiry_date']; ?></td>
              <td><span class="badge-status <?php echo $c['status'] ? 'badge-success' : 'badge-danger'; ?>"><?php echo $c['status'] ? 'Active' : 'Disabled'; ?></span></td>
              <td>
                <a href="coupons.php?toggle=<?php echo $c['id']; ?>" class="btn btn-outline" style="padding:0.25rem 0.6rem; font-size:0.75rem;">Toggle</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Create Coupon Form -->
    <div class="table-card">
      <h3 style="font-size:1.1rem; font-family:'Playfair Display', serif; margin-bottom:1rem;">Add New Coupon</h3>
      <form method="POST" action="coupons.php">
        <div class="form-group">
          <label class="form-label">Coupon Code *</label>
          <input type="text" name="code" placeholder="e.g. VIP25" class="form-control" required style="text-transform:uppercase;">
        </div>
        <div class="form-group">
          <label class="form-label">Discount Percentage (%) *</label>
          <input type="number" step="0.01" name="discount_percent" placeholder="15" class="form-control" required>
        </div>
        <div class="form-group">
          <label class="form-label">Min Order Amount ($)</label>
          <input type="number" step="0.01" name="min_order_amount" value="1000" class="form-control">
        </div>
        <div class="form-group">
          <label class="form-label">Max Discount Amount ($)</label>
          <input type="number" step="0.01" name="max_discount" value="2000" class="form-control">
        </div>
        <div class="form-group">
          <label class="form-label">Expiry Date *</label>
          <input type="date" name="expiry_date" value="2030-12-31" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;">Create Coupon</button>
      </form>
    </div>

  </div>
</main>

</div>
</body>
</html>
