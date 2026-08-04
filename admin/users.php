<?php
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/admin_sidebar.php';

// Toggle User Role
if (isset($_GET['toggle_role'])) {
    $user_id = (int)$_GET['toggle_role'];
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $u = $stmt->fetch();
    if ($u) {
        $new_role = ($u['role'] === 'admin') ? 'customer' : 'admin';
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$new_role, $user_id]);
        set_flash_message('success', 'User role updated.');
    }
    header('Location: users.php');
    exit;
}

$users = $pdo->query("SELECT u.*, COUNT(o.id) as order_count FROM users u LEFT JOIN orders o ON u.id = o.user_id GROUP BY u.id ORDER BY u.id ASC")->fetchAll();
?>

<main class="admin-main">
  <div class="admin-header">
    <div>
      <h2 style="font-size:1.6rem; font-family:'Playfair Display', serif;">User Account Management</h2>
      <span style="font-size:0.85rem; color:#718096;">Manage registered patrons and administrators</span>
    </div>
  </div>

  <div class="table-card">
    <h3 style="font-size:1.1rem; font-family:'Playfair Display', serif; margin-bottom:1rem;">Registered Users</h3>
    <table class="data-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>City</th>
          <th>Orders</th>
          <th>Role</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $u): ?>
          <tr>
            <td>#<?php echo $u['id']; ?></td>
            <td style="font-weight:600;"><?php echo sanitize($u['first_name'] . ' ' . $u['last_name']); ?></td>
            <td><?php echo sanitize($u['email']); ?></td>
            <td><?php echo sanitize($u['phone'] ?? 'N/A'); ?></td>
            <td><?php echo sanitize($u['city'] ?? 'N/A'); ?></td>
            <td><span class="badge-status badge-info"><?php echo $u['order_count']; ?> orders</span></td>
            <td>
              <span class="badge-status <?php echo $u['role'] === 'admin' ? 'badge-warning' : 'badge-success'; ?>">
                <?php echo strtoupper($u['role']); ?>
              </span>
            </td>
            <td>
              <?php if ($u['id'] != get_current_user_id()): ?>
                <a href="users.php?toggle_role=<?php echo $u['id']; ?>" class="btn btn-outline" style="padding:0.25rem 0.6rem; font-size:0.75rem;">
                  Toggle Role
                </a>
              <?php endif; ?>
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
