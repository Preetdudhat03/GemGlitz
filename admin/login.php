<?php
require_once __DIR__ . '/../includes/functions.php';

if (is_admin()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['user_name'] = $admin['first_name'];
        $_SESSION['user_email'] = $admin['email'];
        $_SESSION['user_role'] = 'admin';

        set_flash_message('success', 'Logged into Administrator Portal');
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid administrator credentials.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Portal Login | GemGlitz</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="background:#0D0D0D; color:#FFF; min-height:100vh; display:flex; align-items:center; justify-content:center;">

<div style="width:100%; max-width:420px; padding:2rem;">
  <div style="background:rgba(20,20,20,0.9); border:1px solid var(--primary-gold); border-radius:16px; padding:2.5rem; backdrop-filter:blur(20px); box-shadow:0 15px 40px rgba(0,0,0,0.5);">
    
    <div style="text-align:center; margin-bottom:2rem;">
      <i class="fa-solid fa-shield-halved fa-2x" style="color:var(--primary-gold); margin-bottom:0.75rem;"></i>
      <h2 style="font-family:'Playfair Display', serif; font-size:1.8rem; color:#FFF;">GemGlitz Admin</h2>
      <span style="font-size:0.8rem; color:#888; text-transform:uppercase; letter-spacing:2px;">Secure Vault Management</span>
    </div>

    <?php if (!empty($error)): ?>
      <div style="background:rgba(229,57,53,0.15); border:1px solid var(--error); color:var(--error); padding:0.75rem; border-radius:6px; font-size:0.85rem; margin-bottom:1.5rem; text-align:center;">
        <i class="fa-solid fa-circle-exclamation"></i> <?php echo sanitize($error); ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <div class="form-group">
        <label class="form-label" style="color:#CCC;">Admin Email</label>
        <input type="email" name="email" value="admin@gemglitz.com" class="form-control" style="background:#1A1A1A; border-color:#333; color:#FFF;" required>
      </div>

      <div class="form-group">
        <label class="form-label" style="color:#CCC;">Password</label>
        <input type="password" name="password" value="Admin@123" class="form-control" style="background:#1A1A1A; border-color:#333; color:#FFF;" required>
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%; padding:0.9rem; font-size:0.95rem;">
        <i class="fa-solid fa-key"></i> Authenticate Administrator
      </button>
    </form>

  </div>
</div>

</body>
</html>
