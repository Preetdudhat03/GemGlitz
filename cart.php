<?php
require_once __DIR__ . '/includes/functions.php';

$user_id = get_current_user_id();
$session_id = get_session_id();

// Handle Coupon application before any HTML header output
$applied_coupon = $_SESSION['coupon'] ?? null;
if (isset($_POST['apply_coupon'])) {
    $coupon_code = strtoupper(sanitize($_POST['coupon_code'] ?? ''));
    $stmt = $pdo->prepare("SELECT * FROM coupons WHERE code = ? AND status = 1 AND expiry_date >= CURDATE()");
    $stmt->execute([$coupon_code]);
    $coupon = $stmt->fetch();
    
    if ($coupon) {
        $_SESSION['coupon'] = $coupon;
        set_flash_message('success', "Coupon '{$coupon['code']}' applied! {$coupon['discount_percent']}% discount added.");
        header('Location: cart.php');
        exit;
    } else {
        set_flash_message('error', 'Invalid or expired coupon code.');
        header('Location: cart.php');
        exit;
    }
}

if (isset($_POST['remove_coupon'])) {
    unset($_SESSION['coupon']);
    set_flash_message('info', 'Coupon removed.');
    header('Location: cart.php');
    exit;
}

$page_title = "Shopping Bag & Cart";
$page_desc = "Review your luxury jewelry items in your bag, apply promotional vault codes, and proceed to secure checkout.";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Fetch Cart Items
if ($user_id) {
    $stmt = $pdo->prepare("
        SELECT c.id as cart_id, c.quantity, p.*, cat.name as category_name 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        JOIN categories cat ON p.category_id = cat.id 
        WHERE c.user_id = ?
    ");
    $stmt->execute([$user_id]);
} else {
    $stmt = $pdo->prepare("
        SELECT c.id as cart_id, c.quantity, p.*, cat.name as category_name 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        JOIN categories cat ON p.category_id = cat.id 
        WHERE c.session_id = ? AND c.user_id IS NULL
    ");
    $stmt->execute([$session_id]);
}
$cart_items = $stmt->fetchAll();

// Calculations
$subtotal = 0;
foreach ($cart_items as $item) {
    $price = $item['discount_price'] ?? $item['price'];
    $subtotal += $price * $item['quantity'];
}

$discount_amount = 0;
if ($applied_coupon && $subtotal >= $applied_coupon['min_order_amount']) {
    $discount_amount = ($subtotal * $applied_coupon['discount_percent']) / 100;
    if ($discount_amount > $applied_coupon['max_discount']) {
        $discount_amount = $applied_coupon['max_discount'];
    }
}

$shipping_fee = ($subtotal > 2000 || $subtotal == 0) ? 0.00 : 150.00;
$tax_amount = ($subtotal - $discount_amount) * 0.045; // 4.5% Tax
$grand_total = max(0, ($subtotal - $discount_amount) + $shipping_fee + $tax_amount);
?>

<section class="section-padding">
  <div class="container">
    <div style="margin-bottom: 2.5rem;">
      <span class="section-subtitle">Shopping Bag</span>
      <h1 class="section-title">Your Selected Creations</h1>
    </div>

    <?php if (count($cart_items) > 0): ?>
      <div style="display:grid; grid-template-columns: 2fr 1fr; gap:3rem;">
        
        <!-- Cart Items Table -->
        <div>
          <table style="width:100%; border-collapse:collapse; background:var(--white); border-radius:var(--radius-md); border:1px solid var(--border-color); overflow:hidden;">
            <thead>
              <tr style="background:var(--secondary-bg); text-align:left; border-bottom:1px solid var(--border-color); font-size:0.85rem; color:#777; text-transform:uppercase;">
                <th style="padding:1.25rem;">Product</th>
                <th style="padding:1.25rem;">Price</th>
                <th style="padding:1.25rem;">Quantity</th>
                <th style="padding:1.25rem;">Total</th>
                <th style="padding:1.25rem; text-align:center;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($cart_items as $item): 
                $unit_price = $item['discount_price'] ?? $item['price'];
                $item_total = $unit_price * $item['quantity'];
              ?>
                <tr style="border-bottom:1px solid var(--border-color);">
                  <td style="padding:1.25rem; display:flex; align-items:center; gap:1rem;">
                    <img src="assets/images/<?php echo $item['main_image']; ?>" style="width:60px; height:60px; object-fit:contain; background:#FAF7F2; border-radius:6px; padding:4px;">
                    <div>
                      <a href="product.php?id=<?php echo $item['id']; ?>" style="font-weight:600; font-size:0.95rem; color:var(--dark-bg);"><?php echo sanitize($item['name']); ?></a>
                      <div style="font-size:0.8rem; color:#888;"><?php echo sanitize($item['metal_type']); ?></div>
                    </div>
                  </td>
                  <td style="padding:1.25rem; font-weight:600; color:var(--primary-gold);"><?php echo format_price($unit_price); ?></td>
                  <td style="padding:1.25rem;">
                    <div style="display:flex; align-items:center; border:1px solid var(--border-color); border-radius:4px; width:100px; padding:0.2rem;">
                      <button onclick="updateCartQty(<?php echo $item['cart_id']; ?>, <?php echo max(1, $item['quantity'] - 1); ?>)" style="background:none; border:none; cursor:pointer; flex:1;">-</button>
                      <span style="font-size:0.9rem; font-weight:600; text-align:center; flex:1;"><?php echo $item['quantity']; ?></span>
                      <button onclick="updateCartQty(<?php echo $item['cart_id']; ?>, <?php echo $item['quantity'] + 1; ?>)" style="background:none; border:none; cursor:pointer; flex:1;">+</button>
                    </div>
                  </td>
                  <td style="padding:1.25rem; font-weight:700;"><?php echo format_price($item_total); ?></td>
                  <td style="padding:1.25rem; text-align:center;">
                    <button onclick="removeCartItem(<?php echo $item['cart_id']; ?>)" style="background:none; border:none; color:var(--error); cursor:pointer; font-size:1.1rem;" title="Remove Item">
                      <i class="fa-solid fa-trash-can"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>

          <!-- Continue Shopping Button -->
          <div style="display:flex; justify-content:space-between; margin-top:2rem;">
            <a href="shop.php" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Continue Shopping</a>
          </div>
        </div>

        <!-- Order Summary & Checkout Card -->
        <div>
          <div style="background:var(--white); border-radius:var(--radius-md); padding:2rem; border:1px solid var(--border-color); box-shadow:var(--shadow-sm);">
            <h3 style="font-size:1.25rem; margin-bottom:1.5rem; border-bottom:1px solid var(--border-color); padding-bottom:0.75rem;">Summary</h3>
            
            <!-- Coupon Code Section -->
            <form method="POST" action="cart.php" style="margin-bottom:1.5rem;">
              <label class="form-label" style="font-size:0.8rem;">Vault Promo Code</label>
              <div style="display:flex; gap:0.5rem;">
                <input type="text" name="coupon_code" placeholder="e.g. LUXURY10" value="<?php echo $applied_coupon ? $applied_coupon['code'] : ''; ?>" class="form-control" style="font-size:0.85rem;" <?php echo $applied_coupon ? 'disabled' : ''; ?>>
                <?php if ($applied_coupon): ?>
                  <button type="submit" name="remove_coupon" class="btn btn-dark" style="padding:0.5rem 1rem;"><i class="fa-solid fa-xmark"></i></button>
                <?php else: ?>
                  <button type="submit" name="apply_coupon" class="btn btn-primary" style="padding:0.5rem 1rem;">Apply</button>
                <?php endif; ?>
              </div>
            </form>

            <!-- Price Breakdown -->
            <div style="display:flex; justify-content:space-between; margin-bottom:0.8rem; font-size:0.95rem; color:#666;">
              <span>Subtotal</span>
              <strong style="color:var(--dark-bg);"><?php echo format_price($subtotal); ?></strong>
            </div>

            <?php if ($discount_amount > 0): ?>
              <div style="display:flex; justify-content:space-between; margin-bottom:0.8rem; font-size:0.95rem; color:var(--success);">
                <span>Discount (<?php echo $applied_coupon['code']; ?>)</span>
                <strong>-<?php echo format_price($discount_amount); ?></strong>
              </div>
            <?php endif; ?>

            <div style="display:flex; justify-content:space-between; margin-bottom:0.8rem; font-size:0.95rem; color:#666;">
              <span>Insured Shipping</span>
              <strong style="color:var(--dark-bg);"><?php echo $shipping_fee == 0 ? 'COMPLIMENTARY' : format_price($shipping_fee); ?></strong>
            </div>

            <div style="display:flex; justify-content:space-between; margin-bottom:1.5rem; font-size:0.95rem; color:#666;">
              <span>Estimated Tax (4.5%)</span>
              <strong style="color:var(--dark-bg);"><?php echo format_price($tax_amount); ?></strong>
            </div>

            <div style="display:flex; justify-content:space-between; margin-bottom:2rem; padding-top:1rem; border-top:1px solid var(--border-color); font-size:1.4rem; font-weight:700;">
              <span>Grand Total</span>
              <span style="color:var(--primary-gold);"><?php echo format_price($grand_total); ?></span>
            </div>

            <!-- Proceed Checkout CTA -->
            <a href="checkout.php" class="btn btn-primary" style="width:100%; text-align:center;">
              <i class="fa-solid fa-lock"></i> Secure Checkout
            </a>
          </div>
        </div>

      </div>
    <?php else: ?>
      <div style="text-align:center; padding:5rem 2rem; background:var(--secondary-bg); border-radius:var(--radius-md);">
        <i class="fa-solid fa-bag-shopping fa-3x" style="color:var(--primary-gold); margin-bottom:1rem;"></i>
        <h2>Your Shopping Bag is Empty</h2>
        <p style="color:#888; margin-bottom:2rem;">Browse our haute joaillerie collections to add fine pieces.</p>
        <a href="shop.php" class="btn btn-primary">Explore Catalog</a>
      </div>
    <?php endif; ?>

  </div>
</section>

<script>
function updateCartQty(cartId, newQty) {
  fetch('api/cart_action.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `action=update&cart_id=${cartId}&quantity=${newQty}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      location.reload();
    }
  });
}

function removeCartItem(cartId) {
  fetch('api/cart_action.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `action=remove&cart_id=${cartId}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      location.reload();
    }
  });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
