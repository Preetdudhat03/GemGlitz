<?php
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';

// Analytics calculations
$total_revenue = (float)$pdo->query("SELECT SUM(grand_total) FROM orders WHERE payment_status = 'Paid'")->fetchColumn();
$total_orders = (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE payment_status = 'Paid'")->fetchColumn();
$avg_order_value = $total_orders > 0 ? ($total_revenue / $total_orders) : 0;

// Sales by Category
$category_sales = $pdo->query("
    SELECT c.name as category_name, SUM(oi.quantity) as items_sold, SUM(oi.total) as category_revenue 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    JOIN categories c ON p.category_id = c.id 
    JOIN orders o ON oi.order_id = o.id 
    WHERE o.payment_status = 'Paid' 
    GROUP BY c.id 
    ORDER BY category_revenue DESC
")->fetchAll();

// Top Selling Products
$top_products = $pdo->query("
    SELECT p.name, p.main_image, SUM(oi.quantity) as units_sold, SUM(oi.total) as total_sales 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    JOIN orders o ON oi.order_id = o.id 
    WHERE o.payment_status = 'Paid' 
    GROUP BY p.id 
    ORDER BY units_sold DESC 
    LIMIT 5
")->fetchAll();
?>

<main class="admin-main">
  <div class="admin-header">
    <div>
      <h2 style="font-size:1.6rem; font-family:'Playfair Display', serif;">Sales & Revenue Analytics</h2>
      <span style="font-size:0.85rem; color:#718096;">Haute joaillerie financial metrics</span>
    </div>
  </div>

  <div class="metrics-grid">
    <div class="metric-card">
      <div>
        <span style="font-size:0.8rem; color:#718096; text-transform:uppercase;">Gross Revenue</span>
        <div class="metric-val"><?php echo format_price($total_revenue); ?></div>
      </div>
      <div class="metric-icon"><i class="fa-solid fa-sack-dollar"></i></div>
    </div>

    <div class="metric-card">
      <div>
        <span style="font-size:0.8rem; color:#718096; text-transform:uppercase;">Completed Orders</span>
        <div class="metric-val"><?php echo $total_orders; ?></div>
      </div>
      <div class="metric-icon"><i class="fa-solid fa-circle-check"></i></div>
    </div>

    <div class="metric-card">
      <div>
        <span style="font-size:0.8rem; color:#718096; text-transform:uppercase;">Average Order Value</span>
        <div class="metric-val"><?php echo format_price($avg_order_value); ?></div>
      </div>
      <div class="metric-icon"><i class="fa-solid fa-chart-line"></i></div>
    </div>
  </div>

  <div style="display:grid; grid-template-columns:1fr 1fr; gap:2rem;">
    
    <!-- Category Performance -->
    <div class="table-card">
      <h3 style="font-size:1.1rem; font-family:'Playfair Display', serif; margin-bottom:1rem;">Revenue by Category</h3>
      <table class="data-table">
        <thead>
          <tr>
            <th>Category</th>
            <th>Units Sold</th>
            <th>Total Revenue</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($category_sales as $cs): ?>
            <tr>
              <td style="font-weight:600;"><?php echo sanitize($cs['category_name']); ?></td>
              <td><?php echo $cs['items_sold']; ?> units</td>
              <td style="font-weight:700; color:var(--admin-primary);"><?php echo format_price($cs['category_revenue']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Top Products -->
    <div class="table-card">
      <h3 style="font-size:1.1rem; font-family:'Playfair Display', serif; margin-bottom:1rem;">Top Performing Jewelry</h3>
      <table class="data-table">
        <thead>
          <tr>
            <th>Product</th>
            <th>Units Sold</th>
            <th>Sales Total</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($top_products as $tp): ?>
            <tr>
              <td style="font-weight:600; display:flex; align-items:center; gap:0.5rem;">
                <img src="../assets/images/<?php echo $tp['main_image']; ?>" style="width:30px; height:30px; object-fit:contain;">
                <?php echo sanitize($tp['name']); ?>
              </td>
              <td><?php echo $tp['units_sold']; ?> units</td>
              <td style="font-weight:700; color:var(--admin-primary);"><?php echo format_price($tp['total_sales']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

  </div>
</main>

</div>
</body>
</html>
