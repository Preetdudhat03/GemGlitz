<?php
$page_title = "Patron Registration";
$page_desc = "Join the GemGlitz VIP Circle for exclusive vault access, bespoke jewelry invitations, and priority white-glove shipping.";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

if (is_logged_in()) {
    header('Location: profile.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $error = 'Security token invalid. Please refresh and try again.';
    } else {
        $first_name = sanitize($_POST['first_name'] ?? '');
        $last_name = sanitize($_POST['last_name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $address = sanitize($_POST['address'] ?? '');
        $city = sanitize($_POST['city'] ?? '');
        $state = sanitize($_POST['state'] ?? '');
        $zip = sanitize($_POST['zip'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
            $error = 'Please fill in all required fields marked with *.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please provide a valid email address.';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters long.';
        } elseif ($password !== $confirm_password) {
            $error = 'Passwords do not match.';
        } else {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'An account with this email address already exists.';
            } else {
                // Password Hash
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert User
                $stmt = $pdo->prepare("
                    INSERT INTO users (first_name, last_name, email, password, phone, address, city, state, zip, role) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'customer')
                ");
                $stmt->execute([$first_name, $last_name, $email, $hashed_password, $phone, $address, $city, $state, $zip]);
                $new_user_id = $pdo->lastInsertId();

                // Auto Login
                $_SESSION['user_id'] = $new_user_id;
                $_SESSION['user_name'] = $first_name;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_role'] = 'customer';

                set_flash_message('success', "Welcome to GemGlitz, {$first_name}! Your account is now active.");
                header('Location: profile.php');
                exit;
            }
        }
    }
}
?>

<div class="auth-wrapper" style="padding: 4rem 1rem;">
  <div class="glass-card" style="max-width: 680px;">
    
    <div class="text-center" style="margin-bottom:2rem;">
      <a href="index.php" class="brand-logo" style="justify-content:center; margin-bottom:0.75rem;">
        <img src="assets/images/logo.svg" alt="GemGlitz Logo" style="height:42px;">
      </a>
      <h2 style="font-size:1.8rem; margin-bottom:0.4rem;">Join The VIP Vault Circle</h2>
      <p style="color:#777; font-size:0.85rem;">Create your private account for bespoke jewelry privileges</p>
    </div>

    <?php if (!empty($error)): ?>
      <div style="background:rgba(229,57,53,0.1); border:1px solid var(--error); color:var(--error); padding:0.8rem 1rem; border-radius:var(--radius-sm); font-size:0.85rem; margin-bottom:1.5rem; text-align:center;">
        <i class="fa-solid fa-circle-exclamation"></i> <?php echo sanitize($error); ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="signup.php">
      <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
        <div class="form-group">
          <label class="form-label">First Name *</label>
          <input type="text" name="first_name" value="<?php echo sanitize($_POST['first_name'] ?? ''); ?>" placeholder="Keya" class="form-control" required>
        </div>
        <div class="form-group">
          <label class="form-label">Last Name *</label>
          <input type="text" name="last_name" value="<?php echo sanitize($_POST['last_name'] ?? ''); ?>" placeholder="Dudhat" class="form-control" required>
        </div>
      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
        <div class="form-group">
          <label class="form-label">Email Address *</label>
          <input type="email" name="email" value="<?php echo sanitize($_POST['email'] ?? ''); ?>" placeholder="keyadudhat@gmail.com" class="form-control" required>
        </div>
        <div class="form-group">
          <label class="form-label">Phone Number</label>
          <input type="text" name="phone" value="<?php echo sanitize($_POST['phone'] ?? ''); ?>" placeholder="+91 98765 43210" class="form-control">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Primary Delivery Address</label>
        <input type="text" name="address" value="<?php echo sanitize($_POST['address'] ?? ''); ?>" placeholder="Shri Bhagubhai Mafatlal Polytechnic, Vile Parle (W)" class="form-control">
      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem;">
        <div class="form-group">
          <label class="form-label">City</label>
          <input type="text" name="city" value="<?php echo sanitize($_POST['city'] ?? ''); ?>" placeholder="Mumbai" class="form-control">
        </div>
        <div class="form-group">
          <label class="form-label">State</label>
          <input type="text" name="state" value="<?php echo sanitize($_POST['state'] ?? ''); ?>" placeholder="Maharashtra" class="form-control">
        </div>
        <div class="form-group">
          <label class="form-label">ZIP Code</label>
          <input type="text" name="zip" value="<?php echo sanitize($_POST['zip'] ?? ''); ?>" placeholder="400056" class="form-control">
        </div>
      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
        <div class="form-group">
          <label class="form-label">Password *</label>
          <div style="position:relative;">
            <input type="password" id="reg-password" name="password" oninput="checkPasswordStrength(this.value)" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" class="form-control" style="padding-right:2.8rem;" required>
            <button type="button" onclick="togglePasswordVisibility('reg-password', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--primary-gold); cursor:pointer; font-size:1.1rem; padding:0;" title="Toggle Password Visibility">
              <i class="fa-solid fa-eye"></i>
            </button>
          </div>
          <div style="height:4px; background:#DDD; border-radius:2px; margin-top:0.4rem; overflow:hidden;">
            <div id="strength-bar" style="height:100%; width:0%; transition:all 0.3s ease;"></div>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Confirm Password *</label>
          <div style="position:relative;">
            <input type="password" id="reg-confirm-password" name="confirm_password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" class="form-control" style="padding-right:2.8rem;" required>
            <button type="button" onclick="togglePasswordVisibility('reg-confirm-password', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--primary-gold); cursor:pointer; font-size:1.1rem; padding:0;" title="Toggle Password Visibility">
              <i class="fa-solid fa-eye"></i>
            </button>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label style="display:flex; align-items:center; gap:0.5rem; font-size:0.85rem; cursor:pointer;">
          <input type="checkbox" name="terms" required checked style="accent-color:var(--primary-gold);">
          I agree to GemGlitz <a href="#" style="color:var(--primary-gold);">Terms of Service</a> & <a href="#" style="color:var(--primary-gold);">Privacy Policy</a>.
        </label>
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%; padding:1rem; font-size:0.95rem;">
        <i class="fa-solid fa-user-plus"></i> Register Private Account
      </button>

      <div style="text-align:center; margin-top:1.5rem; font-size:0.85rem; color:#777;">
        Already registered? <a href="login.php" style="color:var(--primary-gold); font-weight:600;">Sign In</a>
      </div>

    </form>

  </div>
</div>

<script>
function checkPasswordStrength(val) {
  const bar = document.getElementById('strength-bar');
  if (!bar) return;
  let score = 0;
  if (val.length >= 6) score += 25;
  if (val.match(/[A-Z]/)) score += 25;
  if (val.match(/[0-9]/)) score += 25;
  if (val.match(/[^A-Za-z0-9]/)) score += 25;

  bar.style.width = score + '%';
  if (score < 50) bar.style.background = '#E53935';
  else if (score < 75) bar.style.background = '#D4AF37';
  else bar.style.background = '#4CAF50';
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
