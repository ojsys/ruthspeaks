<?php
use function App\e;
use function App\csrf_field;
?>

<div class="page-header">
  <div>
    <h1>Users</h1>
    <p>Manage user accounts</p>
  </div>
  <div class="page-actions">
    <a href="/admin/users/new" class="btn btn-primary">
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
  <div class="alert alert-success">
    <?php if ($_GET['success'] === 'created'): ?>
      User created successfully!
    <?php elseif ($_GET['success'] === 'updated'): ?>
      User updated successfully!
    <?php elseif ($_GET['success'] === 'deleted'): ?>
      User deleted successfully!
    <?php endif; ?>
  </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
  <div class="alert alert-error">
    <?php if ($_GET['error'] === 'self_delete'): ?>
      You cannot delete your own account!
    <?php endif; ?>
  </div>
<?php endif; ?>

<div class="card">
  <div class="table-responsive">
    <table class="table">
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
            <td colspan="7" class="text-center">No users found</td>
          </tr>
        <?php else: ?>
          <?php foreach ($users as $user): ?>
            <tr>
              <td>
                <div class="user-cell">
                  <?php if (!empty($user['avatar'])): ?>
                    <img src="<?= e($user['avatar']) ?>" alt="<?= e($user['name']) ?>" class="user-avatar" />
                  <?php else: ?>
                    <div class="user-avatar-placeholder">
                      <?= strtoupper(substr($user['name'], 0, 2)) ?>
                    </div>
                  <?php endif; ?>
                  <div class="user-info">
                    <div class="user-name"><?= e($user['name']) ?></div>
                    <div class="user-date">Joined <?= date('M j, Y', strtotime($user['created_at'])) ?></div>
                  </div>
                </div>
              </td>
              <td><?= e($user['email']) ?></td>
              <td>
                <code class="username-code"><?= e($user['username']) ?></code>
              </td>
              <td>
                <span class="badge badge-<?= $user['role'] === 'admin' ? 'primary' : 'secondary' ?>">
                  <?= e(ucfirst($user['role'])) ?>
                </span>
              </td>
              <td>
                <span class="badge badge-<?= $user['is_active'] ? 'success' : 'danger' ?>">
                  <?= $user['is_active'] ? 'Active' : 'Inactive' ?>
                </span>
              </td>
              <td>
                <?= $user['last_login_at'] ? date('M j, Y g:i A', strtotime($user['last_login_at'])) : '<span class="text-muted">Never</span>' ?>
              </td>
              <td>
                <div class="table-actions">
                  <a href="/admin/users/<?= $user['id'] ?>/edit" class="btn-icon" title="Edit">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                  </a>
                  <?php if ($user['id'] !== $_SESSION['user']['id']): ?>
                    <form method="POST" action="/admin/users/<?= $user['id'] ?>/delete" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                      <?= csrf_field() ?>
                      <button type="submit" class="btn-icon btn-icon-danger" title="Delete">
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

<style>
.user-cell {
  display: flex;
  align-items: center;
  gap: 12px;
}

.user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid var(--admin-border);
}

.user-avatar-placeholder {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  font-weight: 700;
  color: white;
  border: 2px solid var(--admin-border);
}

.user-info {
  display: flex;
  flex-direction: column;
}

.user-name {
  font-weight: 600;
  color: var(--admin-text);
}

.user-date {
  font-size: 13px;
  color: var(--admin-text-secondary);
}

.username-code {
  background: var(--admin-bg);
  padding: 2px 8px;
  border-radius: 4px;
  font-family: 'Courier New', monospace;
  font-size: 13px;
  color: var(--admin-primary);
}

.text-muted {
  color: var(--admin-text-secondary);
  font-style: italic;
}

.alert {
  padding: 16px;
  border-radius: 8px;
  margin-bottom: 24px;
  border: 1px solid;
}

.alert-success {
  background-color: #d1fae5;
  border-color: #10b981;
  color: #065f46;
}

.alert-error {
  background-color: #fee2e2;
  border-color: #ef4444;
  color: #991b1b;
}
</style>
