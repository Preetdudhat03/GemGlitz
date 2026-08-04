<?php
$page_title = "Checkout & Address";
$page_desc = "Complete your shipping address and choose your payment method for your GemGlitz order.";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

require_login();
$user_id = get_current_user_id();

// Fetch user profile info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user_info = $stmt->fetch();

// Fetch Cart Items
$stmt = $pdo->prepare("
    SELECT c.id as cart_id, c.quantity, p.* 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

if (count($cart_items) === 0) {
    header('Location: cart.php');
    exit;
}

// Order Calculations
$subtotal = 0;
foreach ($cart_items as $item) {
    $price = $item['discount_price'] ?? $item['price'];
    $subtotal += $price * $item['quantity'];
}

$applied_coupon = $_SESSION['coupon'] ?? null;
$discount_amount = 0;
if ($applied_coupon && $subtotal >= $applied_coupon['min_order_amount']) {
    $discount_amount = ($subtotal * $applied_coupon['discount_percent']) / 100;
    if ($discount_amount > $applied_coupon['max_discount']) {
        $discount_amount = $applied_coupon['max_discount'];
    }
}

$shipping_fee = ($subtotal > 2000) ? 0.00 : 150.00;
$tax_amount = ($subtotal - $discount_amount) * 0.045;
$grand_total = max(0, ($subtotal - $discount_amount) + $shipping_fee + $tax_amount);
?>

<section class="section-padding">
  <div class="container">
    <div style="margin-bottom: 2.5rem;">
      <span class="section-subtitle">Secure Order</span>
      <h1 class="section-title">Shipping & Payment Details</h1>
    </div>

    <form method="POST" action="payment.php">
      <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
      <input type="hidden" name="subtotal" value="<?php echo $subtotal; ?>">
      <input type="hidden" name="discount_amount" value="<?php echo $discount_amount; ?>">
      <input type="hidden" name="shipping_fee" value="<?php echo $shipping_fee; ?>">
      <input type="hidden" name="tax_amount" value="<?php echo $tax_amount; ?>">
      <input type="hidden" name="grand_total" value="<?php echo $grand_total; ?>">

      <div style="display:grid; grid-template-columns: 2fr 1fr; gap:3rem;">
        
        <!-- Address & Contact Form -->
        <div style="background:var(--white); border-radius:var(--radius-md); padding:2.5rem; border:1px solid var(--border-color); box-shadow:var(--shadow-sm);">
          
          <h3 style="font-size:1.3rem; margin-bottom:1.5rem; border-bottom:1px solid var(--border-color); padding-bottom:0.75rem;">
            <i class="fa-solid fa-truck-ramp-box" style="color:var(--primary-gold);"></i> 1. Shipping Address
          </h3>

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
            <div class="form-group">
              <label class="form-label">First Name *</label>
              <input type="text" name="shipping_first_name" value="<?php echo sanitize($user_info['first_name'] ?? ''); ?>" class="form-control" required>
            </div>
            <div class="form-group">
              <label class="form-label">Last Name *</label>
              <input type="text" name="shipping_last_name" value="<?php echo sanitize($user_info['last_name'] ?? ''); ?>" class="form-control" required>
            </div>
          </div>

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
            <div class="form-group">
              <label class="form-label">Email Address *</label>
              <input type="email" name="shipping_email" value="<?php echo sanitize($user_info['email'] ?? ''); ?>" class="form-control" required>
            </div>
            <div class="form-group">
              <label class="form-label">Phone Number *</label>
              <input type="text" name="shipping_phone" value="<?php echo sanitize($user_info['phone'] ?? ''); ?>" class="form-control" required>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Street Address *</label>
            <input type="text" name="shipping_address" value="<?php echo sanitize($user_info['address'] ?? ''); ?>" placeholder="e.g. 740 5th Avenue, Suite 1200" class="form-control" required>
          </div>

          <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem;">
            <div class="form-group">
              <label class="form-label">City *</label>
              <input type="text" name="shipping_city" value="<?php echo sanitize($user_info['city'] ?? ''); ?>" class="form-control" required>
            </div>
            <div class="form-group">
              <label class="form-label">State *</label>
              <input type="text" name="shipping_state" value="<?php echo sanitize($user_info['state'] ?? ''); ?>" class="form-control" required>
            </div>
            <div class="form-group">
              <label class="form-label">ZIP Code *</label>
              <input type="text" name="shipping_zip" value="<?php echo sanitize($user_info['zip'] ?? ''); ?>" class="form-control" required>
            </div>
          </div>

          <div class="form-group" style="margin-top:1rem;">
            <label class="form-label">Order Notes / Delivery Instructions (Optional)</label>
            <textarea name="notes" rows="2" placeholder="e.g. Discretionary white glove armoring request..." class="form-control"></textarea>
          </div>

          <h3 style="font-size:1.3rem; margin-top:2.5rem; margin-bottom:1.5rem; border-bottom:1px solid var(--border-color); padding-bottom:0.75rem;">
            <i class="fa-solid fa-credit-card" style="color:var(--primary-gold);"></i> 2. Payment Method
          </h3>

          <div style="display:flex; flex-direction:column; gap:1rem;">
            
            <label style="display:flex; align-items:center; gap:1rem; padding:1rem 1.25rem; border:1px solid var(--border-color); border-radius:var(--radius-sm); cursor:pointer;">
              <input type="radio" name="payment_method" value="Credit / Debit Card" checked>
              <i class="fa-solid fa-credit-card" style="font-size:1.2rem; color:var(--primary-gold);"></i>
              <div>
                <strong style="display:block; font-size:0.95rem;">Credit / Debit Card</strong>
                <span style="font-size:0.8rem; color:#888;">Visa, MasterCard, American Express</span>
              </div>
            </label>

            <label style="display:flex; align-items:center; gap:1rem; padding:1rem 1.25rem; border:1px solid var(--border-color); border-radius:var(--radius-sm); cursor:pointer;">
              <input type="radio" name="payment_method" value="UPI Instant">
              <i class="fa-solid fa-mobile-screen-button" style="font-size:1.2rem; color:var(--primary-gold);"></i>
              <div>
                <strong style="display:block; font-size:0.95rem;">UPI / GPay / PhonePe / Paytm</strong>
                <span style="font-size:0.8rem; color:#888;">Instant QR or VPA payment</span>
              </div>
            </label>

            <label style="display:flex; align-items:center; gap:1rem; padding:1rem 1.25rem; border:1px solid var(--border-color); border-radius:var(--radius-sm); cursor:pointer;">
              <input type="radio" name="payment_method" value="Net Banking">
              <i class="fa-solid fa-building-columns" style="font-size:1.2rem; color:var(--primary-gold);"></i>
              <div>
                <strong style="display:block; font-size:0.95rem;">Net Banking</strong>
                <span style="font-size:0.8rem; color:#888;">All major banks supported</span>
              </div>
            </label>

            <label style="display:flex; align-items:center; gap:1rem; padding:1rem 1.25rem; border:1px solid var(--border-color); border-radius:var(--radius-sm); cursor:pointer;">
              <input type="radio" name="payment_method" value="Cash on Delivery">
              <i class="fa-solid fa-money-bill-wave" style="font-size:1.2rem; color:var(--primary-gold);"></i>
              <div>
                <strong style="display:block; font-size:0.95rem;">Cash on Delivery (COD)</strong>
                <span style="font-size:0.8rem; color:#888;">Pay upon secure white-glove arrival</span>
              </div>
            </label>

          </div>

        </div>

        <!-- Right Side: Order Summary -->
        <div>
          <div style="background:var(--white); border-radius:var(--radius-md); padding:2rem; border:1px solid var(--border-color); box-shadow:var(--shadow-sm); position:sticky; top:110px;">
            <h3 style="font-size:1.2rem; margin-bottom:1.5rem; border-bottom:1px solid var(--border-color); padding-bottom:0.75rem;">Order Summary</h3>
            
            <div style="max-height:260px; overflow-y:auto; margin-bottom:1.5rem; padding-right:0.5rem;">
              <?php foreach ($cart_items as $item): 
                $price = $item['discount_price'] ?? $item['price'];
              ?>
                <div style="display:flex; align-items:center; gap:1rem; margin-bottom:1rem;">
                  <img src="assets/images/<?php echo $item['main_image']; ?>" style="width:45px; height:45px; object-fit:contain; background:#FAF7F2; border-radius:4px;">
                  <div style="flex-grow:1;">
                    <div style="font-size:0.85rem; font-weight:600;"><?php echo sanitize($item['name']); ?></div>
                    <div style="font-size:0.75rem; color:#888;">Qty: <?php echo $item['quantity']; ?></div>
                  </div>
                  <strong style="font-size:0.9rem; color:var(--primary-gold);"><?php echo format_price($price * $item['quantity']); ?></strong>
                </div>
              <?php endforeach; ?>
            </div>

            <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem; font-size:0.9rem; color:#666;">
              <span>Subtotal</span>
              <span><?php echo format_price($subtotal); ?></span>
            </div>

            <?php if ($discount_amount > 0): ?>
              <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem; font-size:0.9rem; color:var(--success);">
                <span>Discount</span>
                <span>-<?php echo format_price($discount_amount); ?></span>
              </div>
            <?php endif; ?>

            <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem; font-size:0.9rem; color:#666;">
              <span>Shipping</span>
              <span><?php echo $shipping_fee == 0 ? 'FREE' : format_price($shipping_fee); ?></span>
            </div>

            <div style="display:flex; justify-content:space-between; margin-bottom:1.5rem; font-size:0.9rem; color:#666;">
              <span>Tax (4.5%)</span>
              <span><?php echo format_price($tax_amount); ?></span>
            </div>

            <div style="display:flex; justify-content:space-between; margin-bottom:2rem; padding-top:1rem; border-top:1px solid var(--border-color); font-size:1.3rem; font-weight:700;">
              <span>Total Due</span>
              <span style="color:var(--primary-gold);"><?php echo format_price($grand_total); ?></span>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;">
              <i class="fa-solid fa-lock"></i> Proceed to Payment Gateway
            </button>
          </div>
        </div>

      </div>
    </form>

  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
