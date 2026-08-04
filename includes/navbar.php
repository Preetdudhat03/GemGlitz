<?php
$cart_count = get_cart_count();
$wishlist_count = get_wishlist_count();
$is_user = is_logged_in();
$user_name = $_SESSION['user_name'] ?? 'Account';
?>

<!-- Minimal Announcement Bar -->
<div class="announcement-bar">
  <i class="fa-solid fa-gem" style="margin-right: 6px;"></i> Complimentary Armored Worldwide Delivery &bull; Code: <strong style="color:#FFF;">LUXURY10</strong>
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
      <a href="shop.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'shop.php') ? 'active' : ''; ?>">Catalog</a>
      <a href="shop.php?category=diamond-rings" class="nav-link">Rings</a>
      <a href="shop.php?category=luxury-necklaces" class="nav-link">Necklaces</a>
      <a href="shop.php?category=luxury-watches" class="nav-link">Watches</a>
      <a href="about.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'active' : ''; ?>">About</a>
      <a href="contact.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'active' : ''; ?>">Contact</a>
    </nav>

    <!-- Right Navigation Action Icons -->
    <div class="nav-actions">
      
      <!-- Search Input Box -->
      <div style="position: relative;">
        <div style="display:flex; align-items:center; background:var(--secondary-bg); padding:0.35rem 0.8rem; border:1px solid var(--border-color);">
          <i class="fa-solid fa-magnifying-glass" style="color:var(--primary-gold); margin-right:0.4rem; font-size:0.85rem;"></i>
          <input type="text" id="main-search-input" placeholder="Search..." style="background:transparent; border:none; outline:none; font-family:var(--font-body); font-size:0.8rem; width:110px; color:inherit;">
        </div>
        <div id="search-results-dropdown" style="position:absolute; top:110%; right:0; width:280px; background:var(--white); box-shadow:0 10px 30px rgba(0,0,0,0.1); display:none; z-index:1050; border:1px solid var(--border-color);"></div>
      </div>

      <!-- Dark Mode Toggle -->
      <button id="dark-mode-toggle" class="action-icon" title="Toggle Theme">
        <i class="fa-solid fa-moon"></i>
      </button>

      <!-- Wishlist Badge -->
      <a href="wishlist.php" class="action-icon" title="Wishlist">
        <i class="fa-regular fa-heart"></i>
        <span class="badge" id="wishlist-badge-count"><?php echo $wishlist_count; ?></span>
      </a>

      <!-- Cart Badge -->
      <a href="cart.php" class="action-icon" title="Bag">
        <i class="fa-solid fa-bag-shopping"></i>
        <span class="badge" id="cart-badge-count"><?php echo $cart_count; ?></span>
      </a>

      <!-- User Profile Dropdown -->
      <div class="profile-dropdown-container">
        <div class="action-icon" title="Account">
          <i class="fa-regular fa-user"></i>
        </div>
        <div class="profile-dropdown">
          <?php if ($is_user): ?>
            <div style="padding:0.6rem 1.2rem; border-bottom:1px solid var(--border-color); font-size:0.8rem; font-weight:600;">
              Hello, <?php echo sanitize($user_name); ?>
            </div>
            <a href="profile.php" class="dropdown-item"><i class="fa-solid fa-user-gear"></i> My Profile</a>
            <a href="profile.php#orders" class="dropdown-item"><i class="fa-solid fa-box-open"></i> My Orders</a>
            <a href="order_tracking.php" class="dropdown-item"><i class="fa-solid fa-truck-fast"></i> Order Tracking</a>
            <?php if (is_admin()): ?>
              <a href="admin/index.php" class="dropdown-item" style="color:var(--primary-gold);"><i class="fa-solid fa-gauge-high"></i> Admin Portal</a>
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
