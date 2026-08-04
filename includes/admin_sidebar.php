<aside class="admin-sidebar">
  <div class="sidebar-header">
    <i class="fa-solid fa-gem" style="margin-right:8px;"></i> GEMGLITZ ADMIN
  </div>
  <div class="sidebar-menu">
    <a href="index.php" class="sidebar-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
      <i class="fa-solid fa-gauge"></i> Dashboard
    </a>
    <a href="products.php" class="sidebar-item <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>">
      <i class="fa-solid fa-ring"></i> Manage Products
    </a>
    <a href="categories.php" class="sidebar-item <?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>">
      <i class="fa-solid fa-list-ul"></i> Categories
    </a>
    <a href="orders.php" class="sidebar-item <?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : ''; ?>">
      <i class="fa-solid fa-box-archive"></i> Manage Orders
    </a>
    <a href="users.php" class="sidebar-item <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>">
      <i class="fa-solid fa-users"></i> Manage Users
    </a>
    <a href="coupons.php" class="sidebar-item <?php echo basename($_SERVER['PHP_SELF']) == 'coupons.php' ? 'active' : ''; ?>">
      <i class="fa-solid fa-ticket"></i> Coupons
    </a>
    <a href="reviews.php" class="sidebar-item <?php echo basename($_SERVER['PHP_SELF']) == 'reviews.php' ? 'active' : ''; ?>">
      <i class="fa-solid fa-star"></i> Reviews
    </a>
    <a href="reports.php" class="sidebar-item <?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>">
      <i class="fa-solid fa-chart-line"></i> Sales Reports
    </a>
    <a href="../index.php" target="_blank" class="sidebar-item" style="margin-top:2rem; color:var(--admin-primary);">
      <i class="fa-solid fa-globe"></i> View Website
    </a>
    <a href="logout.php" class="sidebar-item" style="color:var(--error);">
      <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>
  </div>
</aside>
