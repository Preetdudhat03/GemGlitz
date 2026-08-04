<?php
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';

// Fetch Statistics Metrics
$total_orders = (int)$pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$total_revenue = (float)$pdo->query("SELECT SUM(grand_total) FROM orders WHERE payment_status = 'Paid'")->fetchColumn();
$todays_orders = (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE DATE(created_at) = CURDATE()")->fetchColumn();
$total_products = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_users = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
$pending_orders = (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE order_status = 'Pending' OR order_status = 'Processing'")->fetchColumn();

// Fetch Recent Orders
$recent_orders = $pdo->query("SELECT o.*, u.first_name, u.last_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.id DESC LIMIT 5")->fetchAll();
?>

<main class="admin-main">
  
  <div class="admin-header">
    <div>
      <h2 style="font-size:1.6rem; font-family:'Playfair Display', serif;">Dashboard Overview</h2>
      <span style="font-size:0.85rem; color:#718096;">Welcome back, Administrator</span>
    </div>
    <a href="products.php" class="btn btn-primary" style="padding:0.6rem 1.2rem; font-size:0.85rem;"><i class="fa-solid fa-plus"></i> Add New Product</a>
  </div>

  <!-- Metric Cards Grid -->
  <div class="metrics-grid">
    
    <div class="metric-card">
      <div>
        <span style="font-size:0.8rem; color:#718096; text-transform:uppercase; font-weight:600;">Total Revenue</span>
        <div class="metric-val"><?php echo format_price($total_revenue); ?></div>
      </div>
      <div class="metric-icon"><i class="fa-solid fa-sack-dollar"></i></div>
    </div>

    <div class="metric-card">
      <div>
        <span style="font-size:0.8rem; color:#718096; text-transform:uppercase; font-weight:600;">Total Orders</span>
        <div class="metric-val"><?php echo $total_orders; ?></div>
      </div>
      <div class="metric-icon"><i class="fa-solid fa-box"></i></div>
    </div>

    <div class="metric-card">
      <div>
        <span style="font-size:0.8rem; color:#718096; text-transform:uppercase; font-weight:600;">Today's Orders</span>
        <div class="metric-val"><?php echo $todays_orders; ?></div>
      </div>
      <div class="metric-icon"><i class="fa-solid fa-calendar-day"></i></div>
    </div>

    <div class="metric-card">
      <div>
        <span style="font-size:0.8rem; color:#718096; text-transform:uppercase; font-weight:600;">Jewelry Catalog</span>
        <div class="metric-val"><?php echo $total_products; ?></div>
      </div>
      <div class="metric-icon"><i class="fa-solid fa-gem"></i></div>
    </div>

    <div class="metric-card">
      <div>
        <span style="font-size:0.8rem; color:#718096; text-transform:uppercase; font-weight:600;">Registered Patrons</span>
        <div class="metric-val"><?php echo $total_users; ?></div>
      </div>
      <div class="metric-icon"><i class="fa-solid fa-users"></i></div>
    </div>

    <div class="metric-card">
      <div>
        <span style="font-size:0.8rem; color:#718096; text-transform:uppercase; font-weight:600;">Processing Orders</span>
        <div class="metric-val" style="color:var(--admin-primary);"><?php echo $pending_orders; ?></div>
      </div>
      <div class="metric-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
    </div>

  </div>

  <!-- Visual Analytics & Recent Orders Section -->
  <div style="display:grid; grid-template-columns: 2fr 1fr; gap:2rem;">
    
    <!-- Recent Orders Table -->
    <div class="table-card">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
        <h3 style="font-size:1.1rem; font-family:'Playfair Display', serif;">Recent Order Transactions</h3>
        <a href="orders.php" style="font-size:0.85rem; color:var(--admin-primary); font-weight:600;">View All Orders &rarr;</a>
      </div>

      <table class="data-table">
        <thead>
          <tr>
            <th>Order #</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Payment</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($recent_orders as $ro): ?>
            <tr>
              <td style="font-weight:600;"><?php echo sanitize($ro['order_number']); ?></td>
              <td><?php echo sanitize($ro['first_name'] . ' ' . $ro['last_name']); ?></td>
              <td style="font-weight:700; color:var(--admin-primary);"><?php echo format_price($ro['grand_total']); ?></td>
              <td><?php echo sanitize($ro['payment_method']); ?></td>
              <td><span class="badge-status badge-info"><?php echo sanitize($ro['order_status']); ?></span></td>
              <td><a href="orders.php?edit=<?php echo $ro['id']; ?>" class="btn btn-outline" style="padding:0.25rem 0.6rem; font-size:0.75rem;">Manage</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Static Revenue Analytics Widget -->
    <div class="table-card" style="display:flex; flex-direction:column; justify-content:space-between;">
      <h3 style="font-size:1.1rem; font-family:'Playfair Display', serif; margin-bottom:1rem;">Monthly Sales Trend</h3>
      
      <!-- Visual SVG Chart -->
      <svg viewBox="0 0 300 160" width="100%" height="160">
        <rect x="20" y="40" width="30" height="100" fill="#E2E8F0" rx="4"/>
        <rect x="70" y="20" width="30" height="120" fill="#E2E8F0" rx="4"/>
        <rect x="120" y="50" width="30" height="90" fill="#E2E8F0" rx="4"/>
        <rect x="170" y="10" width="30" height="130" fill="var(--admin-primary)" rx="4"/>
        <rect x="220" y="30" width="30" height="110" fill="#E2E8F0" rx="4"/>
        <text x="35" y="155" font-size="10" text-anchor="middle" fill="#718096">Apr</text>
        <text x="85" y="155" font-size="10" text-anchor="middle" fill="#718096">May</text>
        <text x="135" y="155" font-size="10" text-anchor="middle" fill="#718096">Jun</text>
        <text x="185" y="155" font-size="10" text-anchor="middle" fill="var(--admin-primary)" font-weight="700">Jul</text>
        <text x="235" y="155" font-size="10" text-anchor="middle" fill="#718096">Aug</text>
      </svg>

      <div style="background:#FAF7F2; padding:1rem; border-radius:8px; font-size:0.85rem; color:#666; margin-top:1rem;">
        <i class="fa-solid fa-arrow-trend-up" style="color:var(--admin-primary);"></i> Revenue is up <strong>+24.8%</strong> this month driven by high solitaire sales.
      </div>
    </div>

  </div>

</main>

</div>
</body>
</html>
