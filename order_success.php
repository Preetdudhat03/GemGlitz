<?php
require_once __DIR__ . '/includes/functions.php';

$order_id = (int)($_GET['order_id'] ?? ($_SESSION['last_order_id'] ?? 0));

if (!$order_id) {
    header('Location: shop.php');
    exit;
}

// Fetch Order & Items
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: shop.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll();

$page_title = "Order Confirmed | Invoice " . $order['order_number'];
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="section-padding">
  <div class="container" style="max-width:800px;">
    
    <!-- Success Animation Banner -->
    <div style="text-align:center; margin-bottom:3rem;">
      <div style="width:90px; height:90px; background:linear-gradient(135deg, var(--primary-gold), var(--accent-gold)); color:#111; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:3rem; margin:0 auto 1.5rem; box-shadow:0 10px 30px rgba(200, 169, 106, 0.4);">
        <i class="fa-solid fa-check"></i>
      </div>
      <span class="section-subtitle">Order Complete</span>
      <h1 style="font-size:2.8rem; margin-bottom:0.5rem;">Thank You For Your Patronage</h1>
      <p style="color:#777; font-size:1.05rem;">Your order <strong><?php echo sanitize($order['order_number']); ?></strong> has been received and confirmed by our vault master.</p>
    </div>

    <!-- Printable Invoice Box -->
    <div id="printable-invoice" style="background:var(--white); border-radius:var(--radius-lg); padding:3rem; border:1px solid var(--border-color); box-shadow:var(--shadow-sm); margin-bottom:3rem;">
      
      <!-- Invoice Header -->
      <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:2px solid var(--primary-gold); padding-bottom:1.5rem; margin-bottom:2rem;">
        <div>
          <h2 style="font-family:var(--font-heading); font-size:1.8rem; color:var(--primary-gold);">GEMGLITZ</h2>
          <span style="font-size:0.8rem; color:#888; text-transform:uppercase; letter-spacing:2px;">Haute Joaillerie Official Invoice</span>
        </div>
        <div style="text-align:right;">
          <h4 style="font-size:1.1rem;">INVOICE #<?php echo sanitize($order['order_number']); ?></h4>
          <span style="font-size:0.85rem; color:#888;"><?php echo date('F d, Y - H:i', strtotime($order['created_at'])); ?></span>
        </div>
      </div>

      <!-- Customer & Shipping Summary Grid -->
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:2rem; margin-bottom:2rem; background:var(--secondary-bg); padding:1.5rem; border-radius:var(--radius-sm);">
        <div>
          <strong style="display:block; font-size:0.85rem; color:#888; text-transform:uppercase; margin-bottom:0.5rem;">Billed & Shipped To:</strong>
          <div style="font-weight:600; font-size:1rem;"><?php echo sanitize($order['shipping_name']); ?></div>
          <div style="font-size:0.85rem; color:#666;"><?php echo sanitize($order['shipping_address']); ?></div>
          <div style="font-size:0.85rem; color:#666;"><?php echo sanitize($order['shipping_city']); ?>, <?php echo sanitize($order['shipping_zip']); ?></div>
          <div style="font-size:0.85rem; color:#666;"><?php echo sanitize($order['shipping_phone']); ?></div>
        </div>
        <div>
          <strong style="display:block; font-size:0.85rem; color:#888; text-transform:uppercase; margin-bottom:0.5rem;">Payment Information:</strong>
          <div style="font-size:0.9rem; margin-bottom:0.3rem;">Payment Method: <strong><?php echo sanitize($order['payment_method']); ?></strong></div>
          <div style="font-size:0.9rem; margin-bottom:0.3rem;">Status: <span class="badge-status badge-success"><?php echo sanitize($order['payment_status']); ?></span></div>
          <div style="font-size:0.9rem;">Dispatch Status: <span class="badge-status badge-info"><?php echo sanitize($order['order_status']); ?></span></div>
        </div>
      </div>

      <!-- Items Table -->
      <table style="width:100%; border-collapse:collapse; margin-bottom:2rem;">
        <thead>
          <tr style="border-bottom:1px solid var(--border-color); text-align:left; font-size:0.8rem; color:#888; text-transform:uppercase;">
            <th style="padding:0.75rem 0;">Item Description</th>
            <th style="padding:0.75rem 0; text-align:center;">Qty</th>
            <th style="padding:0.75rem 0; text-align:right;">Unit Price</th>
            <th style="padding:0.75rem 0; text-align:right;">Total</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($order_items as $item): ?>
            <tr style="border-bottom:1px solid var(--border-color);">
              <td style="padding:1rem 0; font-weight:600;"><?php echo sanitize($item['product_name']); ?></td>
              <td style="padding:1rem 0; text-align:center;"><?php echo $item['quantity']; ?></td>
              <td style="padding:1rem 0; text-align:right; color:#666;"><?php echo format_price($item['price']); ?></td>
              <td style="padding:1rem 0; text-align:right; font-weight:600; color:var(--primary-gold);"><?php echo format_price($item['total']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- Totals -->
      <div style="width:300px; margin-left:auto; font-size:0.9rem;">
        <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem; color:#666;">
          <span>Subtotal:</span>
          <span><?php echo format_price($order['total_amount']); ?></span>
        </div>
        <?php if ($order['discount_amount'] > 0): ?>
          <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem; color:var(--success);">
            <span>Discount Applied:</span>
            <span>-<?php echo format_price($order['discount_amount']); ?></span>
          </div>
        <?php endif; ?>
        <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem; color:#666;">
          <span>Insured Transport:</span>
          <span><?php echo $order['shipping_fee'] == 0 ? 'FREE' : format_price($order['shipping_fee']); ?></span>
        </div>
        <div style="display:flex; justify-content:space-between; margin-bottom:0.8rem; color:#666;">
          <span>Tax (4.5%):</span>
          <span><?php echo format_price($order['tax_amount']); ?></span>
        </div>
        <div style="display:flex; justify-content:space-between; padding-top:0.75rem; border-top:2px solid var(--dark-bg); font-size:1.25rem; font-weight:700;">
          <span>Total Paid:</span>
          <span style="color:var(--primary-gold);"><?php echo format_price($order['grand_total']); ?></span>
        </div>
      </div>

    </div>

    <!-- Actions -->
    <div style="display:flex; gap:1.5rem; justify-content:center;">
      <button onclick="window.print()" class="btn btn-outline"><i class="fa-solid fa-print"></i> Download / Print Invoice</button>
      <a href="order_tracking.php?order_id=<?php echo $order['id']; ?>" class="btn btn-primary"><i class="fa-solid fa-truck-fast"></i> Track Order Status</a>
      <a href="shop.php" class="btn btn-dark"><i class="fa-solid fa-bag-shopping"></i> Continue Shopping</a>
    </div>

  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
