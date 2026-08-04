<?php
$page_title = "Patron Account & Orders";
$page_desc = "Manage your GemGlitz profile, view your luxury order history, update saved delivery addresses, and change passwords.";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

require_login();
$user_id = get_current_user_id();

// Fetch Profile Data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Fetch User Orders
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();

// Handle Profile Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $first_name = sanitize($_POST['first_name'] ?? '');
    $last_name = sanitize($_POST['last_name'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $address = sanitize($_POST['address'] ?? '');
    $city = sanitize($_POST['city'] ?? '');
    $state = sanitize($_POST['state'] ?? '');
    $zip = sanitize($_POST['zip'] ?? '');

    $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, phone = ?, address = ?, city = ?, state = ?, zip = ? WHERE id = ?");
    $stmt->execute([$first_name, $last_name, $phone, $address, $city, $state, $zip, $user_id]);

    $_SESSION['user_name'] = $first_name;
    set_flash_message('success', 'Profile and address details updated successfully!');
    header('Location: profile.php');
    exit;
}

// Handle Password Change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_pass = $_POST['current_password'] ?? '';
    $new_pass = $_POST['new_password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';

    if (!password_verify($current_pass, $user['password'])) {
        set_flash_message('error', 'Current password is incorrect.');
    } elseif (strlen($new_pass) < 6) {
        set_flash_message('error', 'New password must be at least 6 characters long.');
    } elseif ($new_pass !== $confirm_pass) {
        set_flash_message('error', 'New passwords do not match.');
    } else {
        $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashed, $user_id]);
        set_flash_message('success', 'Password updated successfully!');
        header('Location: profile.php');
        exit;
    }
}
?>

<section class="section-padding">
  <div class="container">
    
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:3rem; border-bottom:1px solid var(--border-color); padding-bottom:1.5rem;">
      <div>
        <span class="section-subtitle">Patron Vault Portal</span>
        <h1 class="section-title" style="margin-bottom:0;">Hello, <?php echo sanitize($user['first_name'] . ' ' . $user['last_name']); ?></h1>
        <span style="font-size:0.85rem; color:#888;"><?php echo sanitize($user['email']); ?> &bull; Member since <?php echo date('M Y', strtotime($user['created_at'])); ?></span>
      </div>
      <a href="logout.php" class="btn btn-dark"><i class="fa-solid fa-right-from-bracket"></i> Logout Session</a>
    </div>

    <div style="display:grid; grid-template-columns: 260px 1fr; gap:3rem;">
      
      <!-- Profile Navigation Tabs -->
      <aside>
        <div style="background:var(--white); border-radius:var(--radius-md); border:1px solid var(--border-color); overflow:hidden; box-shadow:var(--shadow-sm);">
          <a href="#orders" class="profile-tab-btn active" onclick="showTab('orders', this)" style="display:flex; align-items:center; gap:0.75rem; padding:1rem 1.25rem; font-weight:600; border-bottom:1px solid var(--border-color); color:var(--dark-bg);">
            <i class="fa-solid fa-box-open" style="color:var(--primary-gold);"></i> My Orders (<?php echo count($orders); ?>)
          </a>
          <a href="#details" class="profile-tab-btn" onclick="showTab('details', this)" style="display:flex; align-items:center; gap:0.75rem; padding:1rem 1.25rem; font-weight:600; border-bottom:1px solid var(--border-color); color:var(--dark-bg);">
            <i class="fa-solid fa-user-gear" style="color:var(--primary-gold);"></i> Account & Address
          </a>
          <a href="#security" class="profile-tab-btn" onclick="showTab('security', this)" style="display:flex; align-items:center; gap:0.75rem; padding:1rem 1.25rem; font-weight:600; border-bottom:1px solid var(--border-color); color:var(--dark-bg);">
            <i class="fa-solid fa-key" style="color:var(--primary-gold);"></i> Change Password
          </a>
          <a href="wishlist.php" style="display:flex; align-items:center; gap:0.75rem; padding:1rem 1.25rem; font-weight:600; color:var(--dark-bg);">
            <i class="fa-solid fa-heart" style="color:var(--primary-gold);"></i> Saved Wishlist
          </a>
        </div>
      </aside>

      <!-- Main Profile Content Area -->
      <main>
        
        <!-- Tab 1: Orders History -->
        <div id="tab-orders" class="tab-pane">
          <h3 style="font-size:1.4rem; margin-bottom:1.5rem;">Order History</h3>
          
          <?php if (count($orders) > 0): ?>
            <div style="background:var(--white); border-radius:var(--radius-md); border:1px solid var(--border-color); overflow:hidden;">
              <table style="width:100%; border-collapse:collapse;">
                <thead>
                  <tr style="background:var(--secondary-bg); text-align:left; font-size:0.8rem; color:#777; text-transform:uppercase;">
                    <th style="padding:1rem 1.25rem;">Order Number</th>
                    <th style="padding:1rem 1.25rem;">Date</th>
                    <th style="padding:1rem 1.25rem;">Amount</th>
                    <th style="padding:1rem 1.25rem;">Status</th>
                    <th style="padding:1rem 1.25rem; text-align:right;">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($orders as $o): ?>
                    <tr style="border-bottom:1px solid var(--border-color);">
                      <td style="padding:1.25rem; font-weight:600;"><?php echo sanitize($o['order_number']); ?></td>
                      <td style="padding:1.25rem; color:#666; font-size:0.9rem;"><?php echo date('M d, Y', strtotime($o['created_at'])); ?></td>
                      <td style="padding:1.25rem; font-weight:700; color:var(--primary-gold);"><?php echo format_price($o['grand_total']); ?></td>
                      <td style="padding:1.25rem;"><span class="badge-status badge-info"><?php echo sanitize($o['order_status']); ?></span></td>
                      <td style="padding:1.25rem; text-align:right;">
                        <a href="order_success.php?order_id=<?php echo $o['id']; ?>" class="btn btn-outline" style="padding:0.4rem 0.8rem; font-size:0.75rem;">Invoice</a>
                        <a href="order_tracking.php?order_id=<?php echo $o['id']; ?>" class="btn btn-primary" style="padding:0.4rem 0.8rem; font-size:0.75rem;">Track</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div style="text-align:center; padding:4rem 2rem; background:var(--secondary-bg); border-radius:var(--radius-md);">
              <i class="fa-solid fa-box-open fa-3x" style="color:var(--primary-gold); margin-bottom:1rem;"></i>
              <h3>No Orders Placed Yet</h3>
              <p style="color:#888; margin-bottom:1.5rem;">Explore our fine jewelry catalog to make your first acquisition.</p>
              <a href="shop.php" class="btn btn-primary">Start Shopping</a>
            </div>
          <?php endif; ?>
        </div>

        <!-- Tab 2: Profile & Address Details -->
        <div id="tab-details" class="tab-pane" style="display:none;">
          <h3 style="font-size:1.4rem; margin-bottom:1.5rem;">Account & Shipping Details</h3>
          
          <form method="POST" action="profile.php" style="background:var(--white); padding:2rem; border-radius:var(--radius-md); border:1px solid var(--border-color);">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
              <div class="form-group">
                <label class="form-label">First Name</label>
                <input type="text" name="first_name" value="<?php echo sanitize($user['first_name']); ?>" class="form-control" required>
              </div>
              <div class="form-group">
                <label class="form-label">Last Name</label>
                <input type="text" name="last_name" value="<?php echo sanitize($user['last_name']); ?>" class="form-control" required>
              </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
              <div class="form-group">
                <label class="form-label">Email Address (Read Only)</label>
                <input type="email" value="<?php echo sanitize($user['email']); ?>" class="form-control" disabled style="background:#F0F0F0;">
              </div>
              <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" value="<?php echo sanitize($user['phone'] ?? ''); ?>" class="form-control">
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">Primary Street Address</label>
              <input type="text" name="address" value="<?php echo sanitize($user['address'] ?? ''); ?>" class="form-control">
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem;">
              <div class="form-group">
                <label class="form-label">City</label>
                <input type="text" name="city" value="<?php echo sanitize($user['city'] ?? ''); ?>" class="form-control">
              </div>
              <div class="form-group">
                <label class="form-label">State</label>
                <input type="text" name="state" value="<?php echo sanitize($user['state'] ?? ''); ?>" class="form-control">
              </div>
              <div class="form-group">
                <label class="form-label">ZIP Code</label>
                <input type="text" name="zip" value="<?php echo sanitize($user['zip'] ?? ''); ?>" class="form-control">
              </div>
            </div>

            <button type="submit" name="update_profile" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Save Profile Details</button>
          </form>
        </div>

        <!-- Tab 3: Security / Change Password -->
        <div id="tab-security" class="tab-pane" style="display:none;">
          <h3 style="font-size:1.4rem; margin-bottom:1.5rem;">Security & Password</h3>
          
          <form method="POST" action="profile.php" style="background:var(--white); padding:2rem; border-radius:var(--radius-md); border:1px solid var(--border-color); max-width:500px;">
            <div class="form-group">
              <label class="form-label">Current Password</label>
              <input type="password" name="current_password" class="form-control" required>
            </div>
            <div class="form-group">
              <label class="form-label">New Password</label>
              <input type="password" name="new_password" class="form-control" required>
            </div>
            <div class="form-group">
              <label class="form-label">Confirm New Password</label>
              <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" name="change_password" class="btn btn-primary"><i class="fa-solid fa-key"></i> Update Password</button>
          </form>
        </div>

      </main>

    </div>
  </div>
</section>

<script>
function showTab(tabName, btn) {
  document.querySelectorAll('.tab-pane').forEach(el => el.style.display = 'none');
  document.querySelectorAll('.profile-tab-btn').forEach(el => el.style.background = 'transparent');
  document.getElementById('tab-' + tabName).style.display = 'block';
  btn.style.background = 'rgba(200, 169, 106, 0.15)';
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
