<?php
use function App\e;
use function App\csrf_field;
?>

<div class="page-header">
  <h1>My Profile</h1>
  <p>Manage your account information</p>
</div>

<?php if (!empty($success)): ?>
  <div class="alert alert-success">
    <?= e($success) ?>
  </div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
  <div class="alert alert-error">
    <strong>Please fix the following errors:</strong>
    <ul>
      <?php foreach ($errors as $error): ?>
        <li><?= e($error) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<div class="profile-grid">
  <!-- Profile Information -->
  <div class="card">
    <div class="card-header">
      <h2>Profile Information</h2>
    </div>
    <div class="card-body">
      <form method="POST" action="/admin/profile" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="profile-avatar-section">
          <div class="avatar-preview">
            <?php if (!empty($user['avatar'])): ?>
              <img src="<?= e($user['avatar']) ?>" alt="<?= e($user['name']) ?>" id="avatarPreview" />
            <?php else: ?>
              <div class="avatar-placeholder" id="avatarPreview">
                <?= strtoupper(substr($user['name'], 0, 2)) ?>
              </div>
            <?php endif; ?>
          </div>
          <div class="avatar-upload">
            <label for="avatar" class="btn btn-secondary btn-sm">Change Avatar</label>
            <input type="file" id="avatar" name="avatar" accept="image/*" style="display: none;" />
            <small class="help-text">JPG, PNG or GIF. Max 2MB.</small>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="name">Full Name <span class="required">*</span></label>
            <input
              type="text"
              class="input"
              id="name"
              name="name"
              value="<?= e($old['name'] ?? $user['name']) ?>"
              required
            />
          </div>

          <div class="form-group">
            <label for="username">Username <span class="required">*</span></label>
            <input
              type="text"
              class="input"
              id="username"
              name="username"
              value="<?= e($old['username'] ?? $user['username']) ?>"
              required
              minlength="3"
            />
          </div>
        </div>

        <div class="form-group">
          <label for="email">Email <span class="required">*</span></label>
          <input
            type="email"
            class="input"
            id="email"
            name="email"
            value="<?= e($old['email'] ?? $user['email']) ?>"
            required
          />
        </div>

        <div class="form-group">
          <label for="bio">Bio</label>
          <textarea
            class="input"
            id="bio"
            name="bio"
            rows="4"
            placeholder="Tell us about yourself..."
          ><?= e($old['bio'] ?? $user['bio'] ?? '') ?></textarea>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Update Profile</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Password Change -->
  <div class="card">
    <div class="card-header">
      <h2>Change Password</h2>
    </div>
    <div class="card-body">
      <form method="POST" action="/admin/profile">
        <?= csrf_field() ?>

        <!-- Hidden fields to preserve profile data -->
        <input type="hidden" name="name" value="<?= e($user['name']) ?>" />
        <input type="hidden" name="email" value="<?= e($user['email']) ?>" />
        <input type="hidden" name="username" value="<?= e($user['username']) ?>" />
        <input type="hidden" name="bio" value="<?= e($user['bio'] ?? '') ?>" />

        <div class="form-group">
          <label for="current_password">Current Password</label>
          <input
            type="password"
            class="input"
            id="current_password"
            name="current_password"
            placeholder="Enter your current password"
          />
        </div>

        <div class="form-group">
          <label for="new_password">New Password</label>
          <input
            type="password"
            class="input"
            id="new_password"
            name="new_password"
            placeholder="Enter new password"
            minlength="6"
          />
          <small class="help-text">At least 6 characters</small>
        </div>

        <div class="form-group">
          <label for="new_password_confirm">Confirm New Password</label>
          <input
            type="password"
            class="input"
            id="new_password_confirm"
            name="new_password_confirm"
            placeholder="Confirm new password"
          />
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Update Password</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Account Information -->
  <div class="card">
    <div class="card-header">
      <h2>Account Information</h2>
    </div>
    <div class="card-body">
      <div class="info-grid">
        <div class="info-item">
          <label>Role</label>
          <span class="badge badge-<?= $user['role'] === 'admin' ? 'primary' : 'secondary' ?>">
            <?= e(ucfirst($user['role'])) ?>
          </span>
        </div>
        <div class="info-item">
          <label>Account Status</label>
          <span class="badge badge-<?= $user['is_active'] ? 'success' : 'danger' ?>">
            <?= $user['is_active'] ? 'Active' : 'Inactive' ?>
          </span>
        </div>
        <div class="info-item">
          <label>Member Since</label>
          <span><?= date('F j, Y', strtotime($user['created_at'])) ?></span>
        </div>
        <div class="info-item">
          <label>Last Login</label>
          <span><?= $user['last_login_at'] ? date('F j, Y g:i A', strtotime($user['last_login_at'])) : 'Never' ?></span>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.profile-grid {
  display: grid;
  gap: 1.5rem;
  max-width: 900px;
}

.profile-avatar-section {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  padding: 1.5rem;
  background: #f8fafc;
  border-radius: 8px;
  margin-bottom: 1.5rem;
}

body.theme-dark .profile-avatar-section {
  background: #0f172a;
}

.avatar-preview {
  flex-shrink: 0;
}

.avatar-preview img,
.avatar-placeholder {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #e2e8f0;
}

body.theme-dark .avatar-preview img,
body.theme-dark .avatar-placeholder {
  border-color: #334155;
}

.avatar-placeholder {
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  font-weight: 700;
  background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
  color: white;
}

.avatar-upload {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.required {
  color: #ef4444;
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1.5rem;
}

.info-item {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.info-item label {
  font-size: 0.875rem;
  font-weight: 600;
  color: #64748b;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

body.theme-dark .info-item label {
  color: #94a3b8;
}

.info-item span {
  font-size: 1rem;
  color: #1e293b;
}

body.theme-dark .info-item span {
  color: #f1f5f9;
}

.badge {
  display: inline-flex;
  align-items: center;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.875rem;
  font-weight: 600;
  width: fit-content;
}

.badge-primary {
  background: #dbeafe;
  color: #1e40af;
}

body.theme-dark .badge-primary {
  background: #1e3a8a;
  color: #bfdbfe;
}

.badge-secondary {
  background: #f1f5f9;
  color: #475569;
}

body.theme-dark .badge-secondary {
  background: #334155;
  color: #cbd5e1;
}

.badge-success {
  background: #d1fae5;
  color: #065f46;
}

body.theme-dark .badge-success {
  background: #064e3b;
  color: #a7f3d0;
}

.badge-danger {
  background: #fee2e2;
  color: #991b1b;
}

body.theme-dark .badge-danger {
  background: #7f1d1d;
  color: #fecaca;
}

@media (max-width: 768px) {
  .form-row,
  .info-grid {
    grid-template-columns: 1fr;
  }

  .profile-avatar-section {
    flex-direction: column;
    text-align: center;
  }
}
</style>

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
        img.style.border = '3px solid #e2e8f0';
        preview.replaceWith(img);
        img.id = 'avatarPreview';
      }
    };
    reader.readAsDataURL(file);
  }
});
</script>
