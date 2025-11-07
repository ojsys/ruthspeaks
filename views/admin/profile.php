<?php
use function App\e;
use function App\csrf_field;
?>

<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title">My Profile</h1>
    <p class="admin-page-subtitle">Manage your account information</p>
  </div>
</div>

<?php if (!empty($success)): ?>
  <div class="admin-alert admin-alert-success">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
      <polyline points="22 4 12 14.01 9 11.01"/>
    </svg>
    <div><strong>Success!</strong> <?= e($success) ?></div>
  </div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
  <div class="admin-alert admin-alert-danger">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <circle cx="12" cy="12" r="10"/>
      <line x1="15" y1="9" x2="9" y2="15"/>
      <line x1="9" y1="9" x2="15" y2="15"/>
    </svg>
    <div>
      <strong>Please fix the following errors:</strong>
      <ul style="margin: 8px 0 0 0; padding-left: 20px;">
        <?php foreach ($errors as $error): ?>
          <li><?= e($error) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
<?php endif; ?>

<div style="display: grid; gap: 24px; max-width: 900px;">
  <!-- Profile Information -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Profile Information</h3>
    </div>
    <div class="admin-card-body">
      <form method="POST" action="/admin/profile" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="admin-user-avatar-section">
          <div class="admin-user-avatar-preview">
            <?php if (!empty($user['avatar'])): ?>
              <img src="<?= e($user['avatar']) ?>" alt="<?= e($user['name']) ?>" id="avatarPreview" />
            <?php else: ?>
              <div class="admin-user-avatar-placeholder" id="avatarPreview">
                <?= strtoupper(substr($user['name'], 0, 2)) ?>
              </div>
            <?php endif; ?>
          </div>
          <div>
            <label for="avatar" class="admin-btn">Change Avatar</label>
            <input type="file" id="avatar" name="avatar" accept="image/*" style="display: none;" />
            <p class="admin-form-help" style="margin-top: 8px;">JPG, PNG or GIF. Max 2MB.</p>
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
          <div class="admin-form-group">
            <label class="admin-form-label required" for="name">Full Name</label>
            <input
              type="text"
              class="admin-input"
              id="name"
              name="name"
              value="<?= e($old['name'] ?? $user['name']) ?>"
              required
            />
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label required" for="username">Username</label>
            <input
              type="text"
              class="admin-input"
              id="username"
              name="username"
              value="<?= e($old['username'] ?? $user['username']) ?>"
              required
              minlength="3"
            />
          </div>
        </div>

        <div class="admin-form-group">
          <label class="admin-form-label required" for="email">Email</label>
          <input
            type="email"
            class="admin-input"
            id="email"
            name="email"
            value="<?= e($old['email'] ?? $user['email']) ?>"
            required
          />
        </div>

        <div class="admin-form-group" style="margin-bottom: 0;">
          <label class="admin-form-label" for="bio">Bio</label>
          <textarea
            class="admin-textarea"
            id="bio"
            name="bio"
            rows="4"
            placeholder="Tell us about yourself..."
          ><?= e($old['bio'] ?? $user['bio'] ?? '') ?></textarea>
        </div>

        <div class="admin-form-actions" style="margin-top: 24px;">
          <button type="submit" class="admin-btn admin-btn-primary">Update Profile</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Password Change -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Change Password</h3>
    </div>
    <div class="admin-card-body">
      <form method="POST" action="/admin/profile">
        <?= csrf_field() ?>

        <!-- Hidden fields to preserve profile data -->
        <input type="hidden" name="name" value="<?= e($user['name']) ?>" />
        <input type="hidden" name="email" value="<?= e($user['email']) ?>" />
        <input type="hidden" name="username" value="<?= e($user['username']) ?>" />
        <input type="hidden" name="bio" value="<?= e($user['bio'] ?? '') ?>" />

        <div class="admin-form-group">
          <label class="admin-form-label" for="current_password">Current Password</label>
          <input
            type="password"
            class="admin-input"
            id="current_password"
            name="current_password"
            placeholder="Enter your current password"
          />
        </div>

        <div class="admin-form-group">
          <label class="admin-form-label" for="new_password">New Password</label>
          <input
            type="password"
            class="admin-input"
            id="new_password"
            name="new_password"
            placeholder="Enter new password"
            minlength="6"
          />
          <p class="admin-form-help">At least 6 characters</p>
        </div>

        <div class="admin-form-group" style="margin-bottom: 0;">
          <label class="admin-form-label" for="new_password_confirm">Confirm New Password</label>
          <input
            type="password"
            class="admin-input"
            id="new_password_confirm"
            name="new_password_confirm"
            placeholder="Confirm new password"
          />
        </div>

        <div class="admin-form-actions" style="margin-top: 24px;">
          <button type="submit" class="admin-btn admin-btn-primary">Update Password</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Account Information -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Account Information</h3>
    </div>
    <div class="admin-card-body">
      <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
        <div class="admin-info-item">
          <label>Role</label>
          <span class="admin-badge admin-badge-<?= $user['role'] === 'admin' ? 'primary' : 'secondary' ?>">
            <?= e(ucfirst($user['role'])) ?>
          </span>
        </div>
        <div class="admin-info-item">
          <label>Account Status</label>
          <span class="admin-badge admin-badge-<?= $user['is_active'] ? 'success' : 'danger' ?>">
            <?= $user['is_active'] ? 'Active' : 'Inactive' ?>
          </span>
        </div>
        <div class="admin-info-item">
          <label>Member Since</label>
          <span><?= date('F j, Y', strtotime($user['created_at'])) ?></span>
        </div>
        <div class="admin-info-item">
          <label>Last Login</label>
          <span><?= $user['last_login_at'] ? date('F j, Y g:i A', strtotime($user['last_login_at'])) : 'Never' ?></span>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Avatar preview
document.getElementById('avatar').addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      const preview = document.getElementById('avatarPreview');
      if (preview.tagName === 'IMG') {
        preview.src = e.target.result;
      } else {
        const img = document.createElement('img');
        img.src = e.target.result;
        img.alt = 'Avatar preview';
        img.style.width = '100px';
        img.style.height = '100px';
        img.style.borderRadius = '50%';
        img.style.objectFit = 'cover';
        preview.replaceWith(img);
        img.id = 'avatarPreview';
      }
    };
    reader.readAsDataURL(file);
  }
});
</script>
