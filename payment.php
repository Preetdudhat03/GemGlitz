<?php
require_once __DIR__ . '/includes/functions.php';

require_login();
$user_id = get_current_user_id();

// Verify CSRF or direct POST access
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $shipping_name = sanitize(($_POST['shipping_first_name'] ?? '') . ' ' . ($_POST['shipping_last_name'] ?? ''));
    $shipping_email = sanitize($_POST['shipping_email'] ?? '');
    $shipping_phone = sanitize($_POST['shipping_phone'] ?? '');
    $shipping_address = sanitize($_POST['shipping_address'] ?? '');
    $shipping_city = sanitize($_POST['shipping_city'] ?? '');
    $shipping_zip = sanitize($_POST['shipping_zip'] ?? '');
    $notes = sanitize($_POST['notes'] ?? '');
    $payment_method = sanitize($_POST['payment_method'] ?? 'Credit Card');

    $subtotal = (float)($_POST['subtotal'] ?? 0);
    $discount_amount = (float)($_POST['discount_amount'] ?? 0);
    $shipping_fee = (float)($_POST['shipping_fee'] ?? 0);
    $tax_amount = (float)($_POST['tax_amount'] ?? 0);
    $grand_total = (float)($_POST['grand_total'] ?? 0);

    // Fetch Cart Items
    $stmt = $pdo->prepare("SELECT c.quantity, p.* FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll();

    if (count($cart_items) === 0) {
        header('Location: cart.php');
        exit;
    }

    // Generate Order Number & Transaction ID
    $order_number = 'GG-ORD-' . rand(10000, 99999);
    $txn_id = 'TXN_GG_' . rand(100000000, 999999999);

    // Insert Order into DB
    $stmt = $pdo->prepare("
        INSERT INTO orders (order_number, user_id, total_amount, discount_amount, shipping_fee, tax_amount, grand_total, payment_method, payment_status, order_status, shipping_name, shipping_email, shipping_phone, shipping_address, shipping_city, shipping_zip, notes) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Paid', 'Processing', ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $order_number, $user_id, $subtotal, $discount_amount, $shipping_fee, $tax_amount, $grand_total, $payment_method,
        $shipping_name, $shipping_email, $shipping_phone, $shipping_address, $shipping_city, $shipping_zip, $notes
    ]);
    $order_id = $pdo->lastInsertId();

    // Insert Order Items
    foreach ($cart_items as $ci) {
        $item_price = $ci['discount_price'] ?? $ci['price'];
        $item_total = $item_price * $ci['quantity'];
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity, total) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$order_id, $ci['id'], $ci['name'], $item_price, $ci['quantity'], $item_total]);
    }

    // Insert Payment record
    $stmt = $pdo->prepare("INSERT INTO payments (order_id, transaction_id, payment_method, amount, status) VALUES (?, ?, ?, ?, 'Success')");
    $stmt->execute([$order_id, $txn_id, $payment_method, $grand_total]);

    // Insert Order Tracking Record
    $est_delivery = date('Y-m-d', strtotime('+4 days'));
    $tracking_num = 'RGE-' . rand(100000, 999999) . '-NY';
    $stmt = $pdo->prepare("INSERT INTO tracking (order_id, status_title, description, courier_name, tracking_number, estimated_delivery) VALUES (?, 'Order Confirmed', 'Your order has been logged in GemGlitz Vault and is awaiting armored packaging.', 'Royal Gold Express', ?, ?)");
    $stmt->execute([$order_id, $tracking_num, $est_delivery]);

    // Clear Cart & Coupon Session
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    unset($_SESSION['coupon']);

    $_SESSION['last_order_id'] = $order_id;
} else {
    $order_id = $_SESSION['last_order_id'] ?? 0;
    if (!$order_id) {
        header('Location: shop.php');
        exit;
    }
}

// Fetch Created Order Details for UI Gateway Simulation
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

$page_title = "Static Payment Gateway Simulation";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="section-padding" style="background:var(--secondary-bg);">
  <div class="container" style="max-width:650px;">
    
    <div style="background:var(--white); border-radius:var(--radius-lg); padding:3rem; border:1px solid var(--border-color); box-shadow:var(--shadow-md); text-align:center;">
      
      <div style="width:70px; height:70px; background:rgba(200,169,106,0.15); color:var(--primary-gold); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:2rem; margin:0 auto 1.5rem;">
        <i class="fa-solid fa-shield-halved"></i>
      </div>

      <span class="section-subtitle">GemGlitz Payment Gateway</span>
      <h2 style="font-size:1.8rem; margin-bottom:0.5rem;">Simulated Payment Portal</h2>
      <p style="color:#777; font-size:0.9rem; margin-bottom:2rem;">Order Number: <strong style="color:var(--dark-bg);"><?php echo sanitize($order['order_number']); ?></strong></p>

      <div style="background:#FAF7F2; border-radius:var(--radius-md); padding:1.5rem; margin-bottom:2rem; border:1px solid var(--border-color); text-align:left;">
        <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem;">
          <span style="color:#666;">Payment Method Selected:</span>
          <strong><?php echo sanitize($order['payment_method']); ?></strong>
        </div>
        <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem;">
          <span style="color:#666;">Generated Transaction Ref:</span>
          <span style="font-family:monospace; color:var(--primary-gold); font-weight:700;">TXN_GG_<?php echo rand(10000000, 99999999); ?></span>
        </div>
        <div style="display:flex; justify-content:space-between; margin-top:1rem; padding-top:0.75rem; border-top:1px dashed #DDD; font-size:1.2rem; font-weight:700;">
          <span>Amount Payable:</span>
          <span style="color:var(--primary-gold);"><?php echo format_price($order['grand_total']); ?></span>
        </div>
      </div>

      <!-- Payment Simulation Animation Form -->
      <div id="payment-process-box">
        <p style="font-size:0.85rem; color:#888; margin-bottom:1.5rem;"><i class="fa-solid fa-lock"></i> 256-Bit Encrypted Secure Payment Channel</p>
        <button onclick="processFakePayment()" id="pay-now-btn" class="btn btn-primary" style="width:100%; padding:1.1rem; font-size:1rem;">
          <i class="fa-solid fa-circle-check"></i> Authorize & Complete Order
        </button>
      </div>

    </div>

  </div>
</section>

<script>
function processFakePayment() {
  const btn = document.getElementById('pay-now-btn');
  btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing Armored Authorization...';
  btn.disabled = true;

  setTimeout(() => {
    window.location.href = 'order_success.php?order_id=<?php echo $order['id']; ?>';
  }, 1800);
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
