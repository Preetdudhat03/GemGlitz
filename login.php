<?php
$page_title = "Member Vault Login";
$page_desc = "Log in to your GemGlitz private account to manage your haute joaillerie orders, wishlist, and exclusive vault access.";
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
        $error = 'Security session expired. Please try again.';
    } else {
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $error = 'Please enter both email and password.';
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['first_name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];

                set_flash_message('success', "Welcome back, {$user['first_name']}!");

                if ($user['role'] === 'admin') {
                    header('Location: admin/index.php');
                } else {
                    $redirect = $_SESSION['redirect_after_login'] ?? 'profile.php';
                    unset($_SESSION['redirect_after_login']);
                    header("Location: {$redirect}");
                }
                exit;
            } else {
                $error = 'Invalid email address or password.';
            }
        }
    }
}
?>

<div class="auth-wrapper">
  <div class="glass-card">
    
    <div class="text-center" style="margin-bottom:2rem;">
      <a href="index.php" class="brand-logo" style="justify-content:center; margin-bottom:0.75rem;">
        <img src="assets/images/logo.svg" alt="GemGlitz Logo" style="height:42px;">
      </a>
      <h2 style="font-size:1.8rem; margin-bottom:0.4rem;">Patron Login</h2>
      <p style="color:#777; font-size:0.85rem;">Access your private vault & haute joaillerie order history</p>
    </div>

    <?php if (!empty($error)): ?>
      <div style="background:rgba(229,57,53,0.1); border:1px solid var(--error); color:var(--error); padding:0.8rem 1rem; border-radius:var(--radius-sm); font-size:0.85rem; margin-bottom:1.5rem; text-align:center;">
        <i class="fa-solid fa-circle-exclamation"></i> <?php echo sanitize($error); ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">

      <div class="form-group">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" value="<?php echo sanitize($_POST['email'] ?? ''); ?>" placeholder="name@domain.com" class="form-control" required>
      </div>

      <div class="form-group">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.4rem;">
          <label class="form-label" style="margin-bottom:0;">Password</label>
          <a href="#" onclick="alert('Demo password reset link sent to your registered email.')" style="font-size:0.75rem; color:var(--primary-gold);">Forgot Password?</a>
        </div>
        <input type="password" name="password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" class="form-control" required>
      </div>

      <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.8rem;">
        <label style="display:flex; align-items:center; gap:0.5rem; font-size:0.85rem; cursor:pointer;">
          <input type="checkbox" name="remember" checked style="accent-color:var(--primary-gold);"> Remember Session
        </label>
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%; padding:1rem; font-size:0.95rem;">
        <i class="fa-solid fa-right-to-bracket"></i> Sign In to Vault
      </button>

      <!-- Static OAuth Options -->
      <div style="text-align:center; margin:1.5rem 0; position:relative;">
        <hr style="border:none; border-top:1px solid var(--border-color);">
        <span style="position:absolute; top:-10px; left:50%; transform:translateX(-50%); background:var(--white); padding:0 0.75rem; font-size:0.75rem; color:#888;">OR SIGN IN WITH</span>
      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
        <button type="button" onclick="alert('Google Sign-In is configured in static UI mode for demo.')" class="btn btn-dark" style="padding:0.7rem; font-size:0.8rem; text-transform:none;">
          <i class="fa-brands fa-google" style="color:#EA4335;"></i> Google
        </button>
        <button type="button" onclick="alert('Facebook Sign-In is configured in static UI mode for demo.')" class="btn btn-dark" style="padding:0.7rem; font-size:0.8rem; text-transform:none;">
          <i class="fa-brands fa-facebook-f" style="color:#1877F2;"></i> Facebook
        </button>
      </div>

      <div style="text-align:center; margin-top:2rem; font-size:0.85rem; color:#777;">
        Not yet a member? <a href="signup.php" style="color:var(--primary-gold); font-weight:600;">Create Account</a>
      </div>

      <!-- Demo Accounts Box -->
      <div style="margin-top:2rem; padding:1rem; background:rgba(200, 169, 106, 0.1); border-radius:var(--radius-sm); border:1px dashed var(--primary-gold); font-size:0.8rem;">
        <strong>Demo Login Credentials:</strong><br>
        &bull; Customer: <code>customer@gemglitz.com</code> / <code>Customer@123</code><br>
        &bull; Admin: <code>admin@gemglitz.com</code> / <code>Admin@123</code>
      </div>

    </form>

  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
