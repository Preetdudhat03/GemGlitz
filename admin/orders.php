<?php
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';

// Update Order Status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order_status'])) {
    $order_id = (int)$_POST['order_id'];
    $new_status = sanitize($_POST['order_status']);
    $payment_status = sanitize($_POST['payment_status']);

    $stmt = $pdo->prepare("UPDATE orders SET order_status = ?, payment_status = ? WHERE id = ?");
    $stmt->execute([$new_status, $payment_status, $order_id]);

    // Insert tracking update entry
    $status_desc = "Order status updated to {$new_status} by vault administrator.";
    $stmt = $pdo->prepare("INSERT INTO tracking (order_id, status_title, description) VALUES (?, ?, ?)");
    $stmt->execute([$order_id, $new_status, $status_desc]);

    set_flash_message('success', "Order #{$order_id} status updated to {$new_status}");
    header('Location: orders.php');
    exit;
}

// Fetch all orders
$orders = $pdo->query("SELECT o.*, u.first_name, u.last_name, u.email FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.id DESC")->fetchAll();

// Edit Single Order Details if requested
$selected_order = null;
$selected_items = [];
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT o.*, u.first_name, u.last_name, u.email, u.phone FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
    $stmt->execute([$edit_id]);
    $selected_order = $stmt->fetch();

    if ($selected_order) {
        $stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->execute([$edit_id]);
        $selected_items = $stmt->fetchAll();
    }
}
?>

<main class="admin-main">
  <div class="admin-header">
    <div>
      <h2 style="font-size:1.6rem; font-family:'Playfair Display', serif;">Order Management & Fulfillment</h2>
      <span style="font-size:0.85rem; color:#718096;">Track, fulfill, and update dispatch status</span>
    </div>
  </div>

  <?php if ($selected_order): ?>
    <!-- Single Order Management Box -->
    <div class="table-card" style="margin-bottom:2.5rem; border:2px solid var(--admin-primary);">
      <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--admin-border); padding-bottom:1rem; margin-bottom:1.5rem;">
        <h3 style="font-size:1.3rem; font-family:'Playfair Display', serif;">Managing Order #<?php echo sanitize($selected_order['order_number']); ?></h3>
        <a href="orders.php" class="btn btn-dark" style="padding:0.4rem 0.8rem; font-size:0.8rem;">Close Details</a>
      </div>

      <div style="display:grid; grid-template-columns: 2fr 1fr; gap:2rem;">
        
        <div>
          <h4 style="font-size:1rem; margin-bottom:1rem;">Customer & Shipping Address</h4>
          <p style="font-size:0.9rem; color:#4A5568; line-height:1.6;">
            <strong><?php echo sanitize($selected_order['shipping_name']); ?></strong><br>
            Email: <?php echo sanitize($selected_order['shipping_email']); ?> | Phone: <?php echo sanitize($selected_order['shipping_phone']); ?><br>
            Address: <?php echo sanitize($selected_order['shipping_address']); ?>, <?php echo sanitize($selected_order['shipping_city']); ?> <?php echo sanitize($selected_order['shipping_zip']); ?>
          </p>

          <h4 style="font-size:1rem; margin:1.5rem 0 0.75rem;">Purchased Items</h4>
          <table class="data-table">
            <thead>
              <tr><th>Item</th><th>Qty</th><th>Price</th><th>Total</th></tr>
            </thead>
            <tbody>
              <?php foreach ($selected_items as $item): ?>
                <tr>
                  <td><?php echo sanitize($item['product_name']); ?></td>
                  <td><?php echo $item['quantity']; ?></td>
                  <td><?php echo format_price($item['price']); ?></td>
                  <td style="font-weight:700; color:var(--admin-primary);"><?php echo format_price($item['total']); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Status Update Form -->
        <div style="background:#FAF7F2; padding:1.5rem; border-radius:10px; border:1px solid var(--admin-border);">
          <h4 style="font-size:1rem; margin-bottom:1rem;">Update Order Status</h4>
          
          <form method="POST" action="orders.php">
            <input type="hidden" name="order_id" value="<?php echo $selected_order['id']; ?>">
            
            <div class="form-group">
              <label class="form-label">Fulfillment Status</label>
              <select name="order_status" class="form-control">
                <option value="Pending" <?php echo $selected_order['order_status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="Processing" <?php echo $selected_order['order_status'] === 'Processing' ? 'selected' : ''; ?>>Processing</option>
                <option value="Packed" <?php echo $selected_order['order_status'] === 'Packed' ? 'selected' : ''; ?>>Packed</option>
                <option value="Shipped" <?php echo $selected_order['order_status'] === 'Shipped' ? 'selected' : ''; ?>>Shipped / In Transit</option>
                <option value="Delivered" <?php echo $selected_order['order_status'] === 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                <option value="Cancelled" <?php echo $selected_order['order_status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">Payment Status</label>
              <select name="payment_status" class="form-control">
                <option value="Paid" <?php echo $selected_order['payment_status'] === 'Paid' ? 'selected' : ''; ?>>Paid</option>
                <option value="Pending" <?php echo $selected_order['payment_status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="Refunded" <?php echo $selected_order['payment_status'] === 'Refunded' ? 'selected' : ''; ?>>Refunded</option>
              </select>
            </div>

            <button type="submit" name="update_order_status" class="btn btn-primary" style="width:100%;">
              <i class="fa-solid fa-floppy-disk"></i> Save Order Status
            </button>
          </form>
        </div>

      </div>
    </div>
  <?php endif; ?>

  <!-- Orders Master Table -->
  <div class="table-card">
    <h3 style="font-size:1.1rem; font-family:'Playfair Display', serif; margin-bottom:1rem;">All Orders</h3>
    <table class="data-table">
      <thead>
        <tr>
          <th>Order Number</th>
          <th>Patron Name</th>
          <th>Date</th>
          <th>Grand Total</th>
          <th>Payment</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $o): ?>
          <tr>
            <td style="font-weight:600;"><?php echo sanitize($o['order_number']); ?></td>
            <td><?php echo sanitize($o['first_name'] . ' ' . $o['last_name']); ?></td>
            <td><?php echo date('M d, Y', strtotime($o['created_at'])); ?></td>
            <td style="font-weight:700; color:var(--admin-primary);"><?php echo format_price($o['grand_total']); ?></td>
            <td><span class="badge-status badge-success"><?php echo sanitize($o['payment_status']); ?></span></td>
            <td><span class="badge-status badge-info"><?php echo sanitize($o['order_status']); ?></span></td>
            <td>
              <a href="orders.php?edit=<?php echo $o['id']; ?>" class="btn btn-outline" style="padding:0.25rem 0.6rem; font-size:0.75rem;">Manage</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>

</div>
</body>
</html>
