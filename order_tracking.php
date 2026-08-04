<?php
$page_title = "Order Tracking & Vault Progress";
$page_desc = "Track your GemGlitz white-glove armored shipment in real-time using your Order Number.";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$search_query = sanitize($_GET['order_id'] ?? $_GET['search'] ?? '');
$order = null;
$tracking_info = null;
$order_items = [];

if (!empty($search_query)) {
    // Check by ID or Order Number
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? OR order_number = ?");
    $stmt->execute([$search_query, $search_query]);
    $order = $stmt->fetch();

    if ($order) {
        $stmt = $pdo->prepare("SELECT * FROM tracking WHERE order_id = ? ORDER BY id DESC LIMIT 1");
        $stmt->execute([$order['id']]);
        $tracking_info = $stmt->fetch();

        $stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->execute([$order['id']]);
        $order_items = $stmt->fetchAll();
    }
}
?>

<section class="section-padding">
  <div class="container" style="max-width:850px;">
    
    <div class="text-center" style="margin-bottom: 3rem;">
      <span class="section-subtitle"><i class="fa-solid fa-truck-fast"></i> White Glove Dispatch</span>
      <h1 class="section-title">Track Armored Vault Shipment</h1>
      <p style="color:#777;">Enter your Order Number (e.g. <code>GG-ORD-88291</code>) to view live progress.</p>
    </div>

    <!-- Search Bar Form -->
    <form method="GET" action="order_tracking.php" style="display:flex; gap:1rem; max-width:550px; margin:0 auto 3.5rem;">
      <input type="text" name="search" value="<?php echo sanitize($search_query); ?>" placeholder="Enter Order Number or ID..." class="form-control" required style="font-size:1rem; padding:0.9rem 1.25rem;">
      <button type="submit" class="btn btn-primary" style="padding:0.9rem 1.8rem;"><i class="fa-solid fa-magnifying-glass"></i> Track</button>
    </form>

    <?php if ($order): ?>
      
      <?php
      // Calculate progress percentage
      $status = $order['order_status'];
      $progress_percent = 20;
      if ($status === 'Pending') $progress_percent = 20;
      if ($status === 'Processing') $progress_percent = 40;
      if ($status === 'Packed') $progress_percent = 60;
      if ($status === 'Shipped') $progress_percent = 80;
      if ($status === 'Delivered') $progress_percent = 100;
      ?>

      <!-- Tracking Status Result Card -->
      <div style="background:var(--white); border-radius:var(--radius-lg); padding:3rem; border:1px solid var(--border-color); box-shadow:var(--shadow-md);">
        
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--border-color); padding-bottom:1.5rem; margin-bottom:2.5rem;">
          <div>
            <h3 style="font-size:1.4rem;">Order #<?php echo sanitize($order['order_number']); ?></h3>
            <span style="font-size:0.85rem; color:#888;">Placed on <?php echo date('M d, Y', strtotime($order['created_at'])); ?></span>
          </div>
          <div style="text-align:right;">
            <span class="badge-status badge-info" style="font-size:0.9rem; padding:0.4rem 1rem;"><?php echo sanitize($order['order_status']); ?></span>
          </div>
        </div>

        <!-- Visual Timeline Bar -->
        <div style="position:relative; margin-bottom:3rem;">
          <div style="height:6px; background:#E5E5E5; border-radius:3px; position:absolute; top:20px; left:5%; width:90%; z-index:1;"></div>
          <div style="height:6px; background:linear-gradient(90deg, var(--primary-gold), var(--accent-gold)); border-radius:3px; position:absolute; top:20px; left:5%; width:<?php echo ($progress_percent * 0.9); ?>%; z-index:2; transition:width 1s ease;"></div>
          
          <div style="display:flex; justify-content:space-between; position:relative; z-index:3;">
            
            <div style="text-align:center; width:20%;">
              <div style="width:44px; height:44px; border-radius:50%; background:<?php echo $progress_percent >= 20 ? 'var(--primary-gold)' : '#E5E5E5'; ?>; color:<?php echo $progress_percent >= 20 ? '#111' : '#888'; ?>; display:flex; align-items:center; justify-content:center; margin:0 auto 0.5rem; font-weight:700;">
                <i class="fa-solid fa-file-invoice"></i>
              </div>
              <span style="font-size:0.8rem; font-weight:600; display:block;">Confirmed</span>
            </div>

            <div style="text-align:center; width:20%;">
              <div style="width:44px; height:44px; border-radius:50%; background:<?php echo $progress_percent >= 40 ? 'var(--primary-gold)' : '#E5E5E5'; ?>; color:<?php echo $progress_percent >= 40 ? '#111' : '#888'; ?>; display:flex; align-items:center; justify-content:center; margin:0 auto 0.5rem; font-weight:700;">
                <i class="fa-solid fa-box"></i>
              </div>
              <span style="font-size:0.8rem; font-weight:600; display:block;">Packed</span>
            </div>

            <div style="text-align:center; width:20%;">
              <div style="width:44px; height:44px; border-radius:50%; background:<?php echo $progress_percent >= 60 ? 'var(--primary-gold)' : '#E5E5E5'; ?>; color:<?php echo $progress_percent >= 60 ? '#111' : '#888'; ?>; display:flex; align-items:center; justify-content:center; margin:0 auto 0.5rem; font-weight:700;">
                <i class="fa-solid fa-shield-cat"></i>
              </div>
              <span style="font-size:0.8rem; font-weight:600; display:block;">Armored Vault</span>
            </div>

            <div style="text-align:center; width:20%;">
              <div style="width:44px; height:44px; border-radius:50%; background:<?php echo $progress_percent >= 80 ? 'var(--primary-gold)' : '#E5E5E5'; ?>; color:<?php echo $progress_percent >= 80 ? '#111' : '#888'; ?>; display:flex; align-items:center; justify-content:center; margin:0 auto 0.5rem; font-weight:700;">
                <i class="fa-solid fa-truck-fast"></i>
              </div>
              <span style="font-size:0.8rem; font-weight:600; display:block;">In Transit</span>
            </div>

            <div style="text-align:center; width:20%;">
              <div style="width:44px; height:44px; border-radius:50%; background:<?php echo $progress_percent >= 100 ? 'var(--primary-gold)' : '#E5E5E5'; ?>; color:<?php echo $progress_percent >= 100 ? '#111' : '#888'; ?>; display:flex; align-items:center; justify-content:center; margin:0 auto 0.5rem; font-weight:700;">
                <i class="fa-solid fa-house-chimney"></i>
              </div>
              <span style="font-size:0.8rem; font-weight:600; display:block;">Delivered</span>
            </div>

          </div>
        </div>

        <!-- Tracking Metadata -->
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1.5rem; background:var(--secondary-bg); padding:1.5rem; border-radius:var(--radius-sm); margin-bottom:2rem;">
          <div>
            <span style="font-size:0.75rem; color:#888; text-transform:uppercase; display:block;">Courier Partner</span>
            <strong style="font-size:0.95rem; color:var(--dark-bg);"><?php echo sanitize($tracking_info['courier_name'] ?? 'Royal Gold Express'); ?></strong>
          </div>
          <div>
            <span style="font-size:0.75rem; color:#888; text-transform:uppercase; display:block;">Tracking Number</span>
            <strong style="font-size:0.95rem; color:var(--primary-gold);"><?php echo sanitize($tracking_info['tracking_number'] ?? 'RGE-88402-NY'); ?></strong>
          </div>
          <div>
            <span style="font-size:0.75rem; color:#888; text-transform:uppercase; display:block;">Estimated Delivery</span>
            <strong style="font-size:0.95rem; color:var(--dark-bg);"><?php echo sanitize($tracking_info['estimated_delivery'] ?? date('Y-m-d', strtotime('+3 days'))); ?></strong>
          </div>
        </div>

        <!-- Items Overview -->
        <h4 style="font-size:1.1rem; margin-bottom:1rem;">Shipment Contents</h4>
        <div style="display:flex; flex-direction:column; gap:0.75rem;">
          <?php foreach ($order_items as $item): ?>
            <div style="display:flex; justify-content:space-between; align-items:center; padding:0.75rem 0; border-bottom:1px solid var(--border-color);">
              <span style="font-weight:600; font-size:0.95rem;"><?php echo sanitize($item['product_name']); ?> (x<?php echo $item['quantity']; ?>)</span>
              <span style="color:var(--primary-gold); font-weight:700;"><?php echo format_price($item['total']); ?></span>
            </div>
          <?php endforeach; ?>
        </div>

      </div>

    <?php elseif (!empty($search_query)): ?>
      <div style="text-align:center; padding:4rem; background:var(--secondary-bg); border-radius:var(--radius-md);">
        <i class="fa-solid fa-circle-question fa-3x" style="color:var(--primary-gold); margin-bottom:1rem;"></i>
        <h3>Order Not Found</h3>
        <p style="color:#888;">We could not find an active shipment matching "<strong><?php echo sanitize($search_query); ?></strong>". Please check your Order ID.</p>
      </div>
    <?php endif; ?>

  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
