<?php
require_once __DIR__ . '/functions.php';
$flash = get_flash_message();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($page_title) ? sanitize($page_title) . ' | GemGlitz Luxury Jewelry' : 'GemGlitz | High Haute Joaillerie & Fine Luxury Jewelry'; ?></title>
  <meta name="description" content="<?php echo isset($page_desc) ? sanitize($page_desc) : 'Discover exquisite solitaire diamond rings, handcrafted gold necklaces, royal sapphire earrings, and luxury Swiss watches at GemGlitz.'; ?>">
  
  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="assets/images/logo.svg">
  
  <!-- FontAwesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Master Stylesheet -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php if ($flash): ?>
  <div class="toast-container" id="toast-container">
    <div class="toast <?php echo $flash['type'] === 'error' ? 'toast-error' : ''; ?>">
      <i class="fa-solid <?php echo $flash['type'] === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check'; ?>"></i>
      <span><?php echo sanitize($flash['message']); ?></span>
    </div>
  </div>
<?php endif; ?>
