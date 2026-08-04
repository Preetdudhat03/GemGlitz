<?php
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';

// Delete Review
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->execute([$id]);
    set_flash_message('success', 'Review deleted.');
    header('Location: reviews.php');
    exit;
}

// Approve / Toggle Review
if (isset($_GET['approve'])) {
    $id = (int)$_GET['approve'];
    $stmt = $pdo->prepare("UPDATE reviews SET status = 'approved' WHERE id = ?");
    $stmt->execute([$id]);
    set_flash_message('success', 'Review approved.');
    header('Location: reviews.php');
    exit;
}

$reviews = $pdo->query("SELECT r.*, u.first_name, u.last_name, p.name as product_name FROM reviews r JOIN users u ON r.user_id = u.id JOIN products p ON r.product_id = p.id ORDER BY r.id DESC")->fetchAll();
?>

<main class="admin-main">
  <div class="admin-header">
    <div>
      <h2 style="font-size:1.6rem; font-family:'Playfair Display', serif;">Customer Review Moderation</h2>
      <span style="font-size:0.85rem; color:#718096;">Moderate patron reviews & ratings</span>
    </div>
  </div>

  <div class="table-card">
    <h3 style="font-size:1.1rem; font-family:'Playfair Display', serif; margin-bottom:1rem;">Submitted Reviews</h3>
    <table class="data-table">
      <thead>
        <tr>
          <th>Product</th>
          <th>Patron</th>
          <th>Rating</th>
          <th>Review Headline & Comment</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($reviews as $rev): ?>
          <tr>
            <td style="font-weight:600;"><?php echo sanitize($rev['product_name']); ?></td>
            <td><?php echo sanitize($rev['first_name'] . ' ' . $rev['last_name']); ?></td>
            <td style="color:var(--admin-primary);"><i class="fa-solid fa-star"></i> <?php echo $rev['rating']; ?></td>
            <td>
              <strong><?php echo sanitize($rev['review_title']); ?></strong>
              <p style="font-size:0.85rem; color:#666; margin-top:0.2rem;"><?php echo sanitize($rev['comment']); ?></p>
            </td>
            <td><span class="badge-status <?php echo $rev['status'] === 'approved' ? 'badge-success' : 'badge-warning'; ?>"><?php echo strtoupper($rev['status']); ?></span></td>
            <td>
              <?php if ($rev['status'] !== 'approved'): ?>
                <a href="reviews.php?approve=<?php echo $rev['id']; ?>" class="btn btn-primary" style="padding:0.25rem 0.6rem; font-size:0.75rem;">Approve</a>
              <?php endif; ?>
              <a href="reviews.php?delete=<?php echo $rev['id']; ?>" onclick="return confirm('Delete review?')" class="btn btn-dark" style="padding:0.25rem 0.6rem; font-size:0.75rem; color:var(--error);">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>

</div>
</body>
</html>
