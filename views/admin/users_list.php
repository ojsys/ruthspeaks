<?php
use function App\e;
use function App\csrf_field;
?>

<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title">Users</h1>
    <p class="admin-page-subtitle">Manage user accounts</p>
  </div>
  <div class="admin-page-actions">
    <a href="/admin/users/new" class="admin-btn admin-btn-primary">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
        <circle cx="8.5" cy="7" r="4"/>
        <line x1="20" y1="8" x2="20" y2="14"/>
        <line x1="23" y1="11" x2="17" y2="11"/>
      </svg>
      Add User
    </a>
  </div>
</div>

<?php if (isset($_GET['success'])): ?>
  <div class="admin-alert admin-alert-success">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
      <polyline points="22 4 12 14.01 9 11.01"/>
    </svg>
    <div>
      <strong>Success!</strong>
      <?php if ($_GET['success'] === 'created'): ?>
        User created successfully!
      <?php elseif ($_GET['success'] === 'updated'): ?>
        User updated successfully!
      <?php elseif ($_GET['success'] === 'deleted'): ?>
        User deleted successfully!
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
  <div class="admin-alert admin-alert-danger">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <circle cx="12" cy="12" r="10"/>
      <line x1="15" y1="9" x2="9" y2="15"/>
      <line x1="9" y1="9" x2="15" y2="15"/>
    </svg>
    <div>
      <strong>Error!</strong>
      <?php if ($_GET['error'] === 'self_delete'): ?>
        You cannot delete your own account!
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>

<div class="admin-card">
  <div class="admin-table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>User</th>
          <th>Email</th>
          <th>Username</th>
          <th>Role</th>
          <th>Status</th>
          <th>Last Login</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($users)): ?>
          <tr>
            <td colspan="7" class="admin-table-empty">No users found</td>
          </tr>
        <?php else: ?>
          <?php foreach ($users as $user): ?>
            <tr>
              <td>
                <div class="admin-user-cell">
                  <?php if (!empty($user['avatar'])): ?>
                    <img src="<?= e($user['avatar']) ?>" alt="<?= e($user['name']) ?>" class="admin-user-cell-avatar" />
                  <?php else: ?>
                    <div class="admin-user-cell-initials">
                      <?= strtoupper(substr($user['name'], 0, 2)) ?>
                    </div>
                  <?php endif; ?>
                  <div>
                    <div class="admin-user-cell-name"><?= e($user['name']) ?></div>
                    <div class="admin-user-cell-meta">Joined <?= date('M j, Y', strtotime($user['created_at'])) ?></div>
                  </div>
                </div>
              </td>
              <td><?= e($user['email']) ?></td>
              <td>
                <code class="admin-code"><?= e($user['username']) ?></code>
              </td>
              <td>
                <span class="admin-badge <?= $user['role'] === 'admin' ? 'admin-badge-primary' : 'admin-badge-secondary' ?>">
                  <?= e(ucfirst($user['role'])) ?>
                </span>
              </td>
              <td>
                <span class="admin-badge <?= $user['is_active'] ? 'admin-badge-success' : 'admin-badge-danger' ?>">
                  <?= $user['is_active'] ? 'Active' : 'Inactive' ?>
                </span>
              </td>
              <td class="admin-text-secondary">
                <?= $user['last_login_at'] ? date('M j, Y g:i A', strtotime($user['last_login_at'])) : 'Never' ?>
              </td>
              <td>
                <div class="admin-table-actions">
                  <a href="/admin/users/<?= $user['id'] ?>/edit" class="admin-btn-icon" title="Edit">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                  </a>
                  <?php
                  $currentUserId = $_SESSION['user']['id'] ?? null;
                  $canDelete = !$currentUserId || ($user['id'] !== $currentUserId);
                  ?>
                  <?php if ($canDelete): ?>
                    <form method="POST" action="/admin/users/<?= $user['id'] ?>/delete" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                      <?= csrf_field() ?>
                      <button type="submit" class="admin-btn-icon admin-btn-icon-danger" title="Delete">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <polyline points="3 6 5 6 21 6"/>
                          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                          <line x1="10" y1="11" x2="10" y2="17"/>
                          <line x1="14" y1="11" x2="14" y2="17"/>
                        </svg>
                      </button>
                    </form>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
