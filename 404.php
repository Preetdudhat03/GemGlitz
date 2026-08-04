<?php
$page_title = "404 Page Not Found";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="section-padding" style="min-height:70vh; display:flex; align-items:center; justify-content:center; text-align:center;">
  <div class="container" style="max-width:600px;">
    <div style="font-size:6rem; font-family:var(--font-heading); color:var(--primary-gold); line-height:1;">404</div>
    <h2 style="font-size:2rem; margin:1rem 0;">Vault Page Not Found</h2>
    <p style="color:#777; margin-bottom:2rem;">The requested haute joaillerie creation or page does not exist or has been moved to our private archives.</p>
    <a href="index.php" class="btn btn-primary"><i class="fa-solid fa-house"></i> Return to Homepage</a>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
