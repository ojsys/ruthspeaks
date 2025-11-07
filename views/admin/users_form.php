<?php
use function App\e;
use function App\csrf_field;

$isEdit = isset($user);
$formAction = $isEdit ? "/admin/users/{$user['id']}/edit" : "/admin/users/new";
?>

<div class="page-header">
  <div>
    <h1><?= $isEdit ? 'Edit User' : 'Add New User' ?></h1>
    <p><?= $isEdit ? 'Update user information' : 'Create a new user account' ?></p>
  </div>
  <div class="page-actions">
    <a href="/admin/users" class="btn btn-secondary">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="19" y1="12" x2="5" y2="12"/>
        <polyline points="12 19 5 12 12 5"/>
      </svg>
      Back to Users
    </a>
  </div>
</div>

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

<form method="POST" action="<?= $formAction ?>" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <div class="form-grid">
    <!-- User Information -->
    <div class="card">
      <div class="card-header">
        <h2>User Information</h2>
      </div>
      <div class="card-body">
        <div class="profile-avatar-section">
          <div class="avatar-preview">
            <?php if ($isEdit && !empty($user['avatar'])): ?>
              <img src="<?= e($user['avatar']) ?>" alt="<?= e($user['name']) ?>" id="avatarPreview" />
            <?php else: ?>
              <div class="avatar-placeholder" id="avatarPreview">
                <?= strtoupper(substr($old['name'] ?? $user['name'] ?? 'U', 0, 2)) ?>
              </div>
            <?php endif; ?>
          </div>
          <div class="avatar-upload">
            <label for="avatar" class="btn btn-secondary btn-sm">Choose Avatar</label>
            <input type="file" id="avatar" name="avatar" accept="image/*" style="display: none;" />
            <small class="help-text">JPG, PNG or GIF. Max 2MB.</small>
          </div>
        </div>

        <div class="form-group">
          <label for="name">Full Name <span class="required">*</span></label>
          <input
            type="text"
            class="input"
            id="name"
            name="name"
            value="<?= e($old['name'] ?? $user['name'] ?? '') ?>"
            required
          />
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="email">Email <span class="required">*</span></label>
            <input
              type="email"
              class="input"
              id="email"
              name="email"
              value="<?= e($old['email'] ?? $user['email'] ?? '') ?>"
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
              value="<?= e($old['username'] ?? $user['username'] ?? '') ?>"
              required
              minlength="3"
            />
          </div>
        </div>

        <div class="form-group">
          <label for="bio">Bio</label>
          <textarea
            class="input"
            id="bio"
            name="bio"
            rows="3"
            placeholder="User biography..."
          ><?= e($old['bio'] ?? $user['bio'] ?? '') ?></textarea>
        </div>
      </div>
    </div>

    <!-- Account Settings -->
    <div class="card">
      <div class="card-header">
        <h2>Account Settings</h2>
      </div>
      <div class="card-body">
        <?php
        $currentUserId = $_SESSION['user']['id'] ?? null;
        $isEditingSelf = $isEdit && $currentUserId && $user['id'] === $currentUserId;
        ?>
        <div class="form-group">
          <label for="role">Role <span class="required">*</span></label>
          <select class="input" id="role" name="role" <?= $isEditingSelf ? 'disabled' : '' ?>>
            <option value="editor" <?= ($old['role'] ?? $user['role'] ?? 'editor') === 'editor' ? 'selected' : '' ?>>Editor</option>
            <option value="admin" <?= ($old['role'] ?? $user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
          </select>
          <?php if ($isEditingSelf): ?>
            <small class="help-text">You cannot change your own role</small>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label class="checkbox-label">
            <input
              type="checkbox"
              name="is_active"
              <?= ($old['is_active'] ?? $user['is_active'] ?? 1) ? 'checked' : '' ?>
              <?= $isEditingSelf ? 'disabled' : '' ?>
            />
            <span>Account is active</span>
          </label>
          <?php if ($isEditingSelf): ?>
            <small class="help-text">You cannot deactivate your own account</small>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Password -->
    <div class="card">
      <div class="card-header">
        <h2><?= $isEdit ? 'Change Password' : 'Password' ?></h2>
      </div>
      <div class="card-body">
        <div class="form-group">
          <label for="password">Password <?= !$isEdit ? '<span class="required">*</span>' : '' ?></label>
          <input
            type="password"
            class="input"
            id="password"
            name="password"
            placeholder="<?= $isEdit ? 'Leave blank to keep current password' : 'Enter password' ?>"
            <?= !$isEdit ? 'required' : '' ?>
            minlength="6"
          />
          <small class="help-text">At least 6 characters</small>
        </div>

        <div class="form-group">
          <label for="password_confirm">Confirm Password <?= !$isEdit ? '<span class="required">*</span>' : '' ?></label>
          <input
            type="password"
            class="input"
            id="password_confirm"
            name="password_confirm"
            placeholder="Confirm password"
            <?= !$isEdit ? 'required' : '' ?>
          />
        </div>
      </div>
    </div>
  </div>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="20 6 9 17 4 12"/>
      </svg>
      <?= $isEdit ? 'Update User' : 'Create User' ?>
    </button>
    <a href="/admin/users" class="btn btn-secondary">Cancel</a>
  </div>
</form>

<style>
.form-grid {
  display: grid;
  gap: 24px;
  margin-bottom: 24px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}

.profile-avatar-section {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 16px;
  background: var(--admin-bg);
  border-radius: 8px;
  margin-bottom: 24px;
}

.avatar-preview {
  flex-shrink: 0;
}

.avatar-preview img,
.avatar-placeholder {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid var(--admin-border);
}

.avatar-placeholder {
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
  font-weight: 700;
  background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
  color: white;
}

.avatar-upload {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.required {
  color: #ef4444;
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  font-weight: 500;
}

.checkbox-label input[type="checkbox"] {
  cursor: pointer;
  width: 18px;
  height: 18px;
}

.help-text {
  display: block;
  margin-top: 6px;
  font-size: 13px;
  color: var(--admin-text-secondary);
}

.alert {
  padding: 16px;
  border-radius: 8px;
  margin-bottom: 24px;
  border: 1px solid;
}

.alert-error {
  background-color: #fee2e2;
  border-color: #ef4444;
  color: #991b1b;
}

.alert ul {
  margin: 8px 0 0;
  padding-left: 20px;
}

.alert li {
  margin: 4px 0;
}

@media (max-width: 768px) {
  .form-row {
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
        img.style.width = '80px';
        img.style.height = '80px';
        img.style.borderRadius = '50%';
        img.style.objectFit = 'cover';
        img.style.border = '3px solid var(--admin-border)';
        preview.replaceWith(img);
        img.id = 'avatarPreview';
      }
    };
    reader.readAsDataURL(file);
  }
});
</script>
