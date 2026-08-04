<?php
$cart_count = get_cart_count();
$wishlist_count = get_wishlist_count();
$is_user = is_logged_in();
$user_name = $_SESSION['user_name'] ?? 'Account';
?>

<!-- Announcement Bar -->
<div class="announcement-bar">
  <i class="fa-solid fa-gem" style="margin-right: 6px;"></i> Complimentary Worldwide Armored Shipping & White Glove Delivery | Code: <strong style="color:#FFF;">LUXURY10</strong>
</div>

<!-- Sticky Navigation Header -->
<header class="navbar-sticky">
  <div class="container nav-container">
    
    <!-- Brand Logo -->
    <a href="index.php" class="brand-logo">
      <img src="assets/images/logo.svg" alt="GemGlitz Logo">
      <span>GEMGLITZ</span>
    </a>

    <!-- Desktop Navigation Links -->
    <nav class="nav-menu">
      <a href="index.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">Home</a>
      <a href="shop.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'shop.php') ? 'active' : ''; ?>">Shop</a>
      <a href="shop.php?category=diamond-rings" class="nav-link">Rings</a>
      <a href="shop.php?category=luxury-necklaces" class="nav-link">Necklaces</a>
      <a href="shop.php?category=luxury-watches" class="nav-link">Watches</a>
      <a href="about.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'active' : ''; ?>">About</a>
      <a href="contact.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'active' : ''; ?>">Contact</a>
    </nav>

    <!-- Navigation Action Icons -->
    <div class="nav-actions">
      
      <!-- Live Search Box -->
      <div style="position: relative;">
        <div style="display:flex; align-items:center; background:rgba(200, 169, 106, 0.08); border-radius:50px; padding:0.4rem 0.9rem; border:1px solid var(--border-color);">
          <i class="fa-solid fa-magnifying-glass" style="color:var(--primary-gold); margin-right:0.5rem; font-size:0.9rem;"></i>
          <input type="text" id="main-search-input" placeholder="Search jewelry..." style="background:transparent; border:none; outline:none; font-family:var(--font-body); font-size:0.85rem; width:140px; color:inherit;">
        </div>
        <div id="search-results-dropdown" style="position:absolute; top:110%; right:0; width:300px; background:var(--white); border-radius:var(--radius-sm); box-shadow:var(--shadow-md); display:none; z-index:1050; border:1px solid var(--border-color);"></div>
      </div>

      <!-- Dark Mode Toggle Button -->
      <button id="dark-mode-toggle" class="action-icon" title="Toggle Theme">
        <i class="fa-solid fa-moon"></i>
      </button>

      <!-- Wishlist Badge Icon -->
      <a href="wishlist.php" class="action-icon" title="Wishlist">
        <i class="fa-regular fa-heart"></i>
        <span class="badge" id="wishlist-badge-count"><?php echo $wishlist_count; ?></span>
      </a>

      <!-- Cart Badge Icon -->
      <a href="cart.php" class="action-icon" title="Shopping Cart">
        <i class="fa-solid fa-bag-shopping"></i>
        <span class="badge" id="cart-badge-count"><?php echo $cart_count; ?></span>
      </a>

      <!-- User Account / Profile Dropdown -->
      <div class="profile-dropdown-container">
        <div class="action-icon" title="User Account">
          <i class="fa-regular fa-user"></i>
        </div>
        <div class="profile-dropdown">
          <?php if ($is_user): ?>
            <div style="padding:0.75rem 1.25rem; border-bottom:1px solid var(--border-color); font-weight:600; font-size:0.85rem;">
              Hello, <?php echo sanitize($user_name); ?>
            </div>
            <a href="profile.php" class="dropdown-item"><i class="fa-solid fa-user-gear"></i> My Profile</a>
            <a href="profile.php#orders" class="dropdown-item"><i class="fa-solid fa-box-open"></i> My Orders</a>
            <a href="order_tracking.php" class="dropdown-item"><i class="fa-solid fa-truck-fast"></i> Order Tracking</a>
            <?php if (is_admin()): ?>
              <a href="admin/index.php" class="dropdown-item" style="color:var(--primary-gold);"><i class="fa-solid fa-gauge-high"></i> Admin Dashboard</a>
            <?php endif; ?>
            <a href="logout.php" class="dropdown-item" style="color:var(--error);"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
          <?php else: ?>
            <a href="login.php" class="dropdown-item"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
            <a href="signup.php" class="dropdown-item"><i class="fa-solid fa-user-plus"></i> Register</a>
            <a href="order_tracking.php" class="dropdown-item"><i class="fa-solid fa-truck-fast"></i> Order Tracking</a>
          <?php endif; ?>
        </div>
      </div>

    </div>

  </div>
</header>

<!-- Quick View Modal Placeholder -->
<div id="quick-view-modal" class="modal-overlay">
  <div class="modal-content">
    <span class="modal-close" onclick="closeQuickView()">&times;</span>
    <div id="quick-view-content"></div>
  </div>
</div>
