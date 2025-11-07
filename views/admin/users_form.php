<?php
use function App\e;
use function App\csrf_field;

$isEdit = isset($user);
$formAction = $isEdit ? "/admin/users/{$user['id']}/edit" : "/admin/users/new";
?>

<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title"><?= $isEdit ? 'Edit User' : 'Add New User' ?></h1>
    <p class="admin-page-subtitle"><?= $isEdit ? 'Update user information' : 'Create a new user account' ?></p>
  </div>
  <div class="admin-page-actions">
    <a href="/admin/users" class="admin-btn">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="19" y1="12" x2="5" y2="12"/>
        <polyline points="12 19 5 12 12 5"/>
      </svg>
      Back to Users
    </a>
  </div>
</div>

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

<form method="POST" action="<?= $formAction ?>" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <div style="display: grid; gap: 24px;">
    <!-- User Information -->
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title">User Information</h3>
      </div>
      <div class="admin-card-body">
        <div class="admin-user-avatar-section">
          <div class="admin-user-avatar-preview">
            <?php if ($isEdit && !empty($user['avatar'])): ?>
              <img src="<?= e($user['avatar']) ?>" alt="<?= e($user['name']) ?>" id="avatarPreview" />
            <?php else: ?>
              <div class="admin-user-avatar-placeholder" id="avatarPreview">
                <?= strtoupper(substr($old['name'] ?? $user['name'] ?? 'U', 0, 2)) ?>
              </div>
            <?php endif; ?>
          </div>
          <div>
            <label for="avatar" class="admin-btn">Choose Avatar</label>
            <input type="file" id="avatar" name="avatar" accept="image/*" style="display: none;" />
            <p class="admin-form-help" style="margin-top: 8px;">JPG, PNG or GIF. Max 2MB.</p>
          </div>
        </div>

        <div class="admin-form-group">
          <label class="admin-form-label required" for="name">Full Name</label>
          <input
            type="text"
            class="admin-input"
            id="name"
            name="name"
            value="<?= e($old['name'] ?? $user['name'] ?? '') ?>"
            required
          />
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
          <div class="admin-form-group">
            <label class="admin-form-label required" for="email">Email</label>
            <input
              type="email"
              class="admin-input"
              id="email"
              name="email"
              value="<?= e($old['email'] ?? $user['email'] ?? '') ?>"
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
              value="<?= e($old['username'] ?? $user['username'] ?? '') ?>"
              required
              minlength="3"
            />
          </div>
        </div>

        <div class="admin-form-group" style="margin-bottom: 0;">
          <label class="admin-form-label" for="bio">Bio</label>
          <textarea
            class="admin-textarea"
            id="bio"
            name="bio"
            rows="3"
            placeholder="User biography..."
          ><?= e($old['bio'] ?? $user['bio'] ?? '') ?></textarea>
        </div>
      </div>
    </div>

    <!-- Account Settings -->
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title">Account Settings</h3>
      </div>
      <div class="admin-card-body">
        <?php
        $currentUserId = $_SESSION['user']['id'] ?? null;
        $isEditingSelf = $isEdit && $currentUserId && $user['id'] === $currentUserId;
        ?>
        <div class="admin-form-group">
          <label class="admin-form-label required" for="role">Role</label>
          <select class="admin-select" id="role" name="role" <?= $isEditingSelf ? 'disabled' : '' ?>>
            <option value="editor" <?= ($old['role'] ?? $user['role'] ?? 'editor') === 'editor' ? 'selected' : '' ?>>Editor</option>
            <option value="admin" <?= ($old['role'] ?? $user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
          </select>
          <?php if ($isEditingSelf): ?>
            <p class="admin-form-help">You cannot change your own role</p>
          <?php endif; ?>
        </div>

        <div class="admin-form-group" style="margin-bottom: 0;">
          <label class="admin-checkbox-label">
            <input
              type="checkbox"
              name="is_active"
              <?= ($old['is_active'] ?? $user['is_active'] ?? 1) ? 'checked' : '' ?>
              <?= $isEditingSelf ? 'disabled' : '' ?>
            />
            <span>Account is active</span>
          </label>
          <?php if ($isEditingSelf): ?>
            <p class="admin-form-help">You cannot deactivate your own account</p>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Password -->
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title"><?= $isEdit ? 'Change Password' : 'Password' ?></h3>
      </div>
      <div class="admin-card-body">
        <div class="admin-form-group">
          <label class="admin-form-label <?= !$isEdit ? 'required' : '' ?>" for="password">Password</label>
          <input
            type="password"
            class="admin-input"
            id="password"
            name="password"
            placeholder="<?= $isEdit ? 'Leave blank to keep current password' : 'Enter password' ?>"
            <?= !$isEdit ? 'required' : '' ?>
            minlength="6"
          />
          <p class="admin-form-help">At least 6 characters</p>
        </div>

        <div class="admin-form-group" style="margin-bottom: 0;">
          <label class="admin-form-label <?= !$isEdit ? 'required' : '' ?>" for="password_confirm">Confirm Password</label>
          <input
            type="password"
            class="admin-input"
            id="password_confirm"
            name="password_confirm"
            placeholder="Confirm password"
            <?= !$isEdit ? 'required' : '' ?>
          />
        </div>
      </div>
    </div>
  </div>

  <div class="admin-form-actions">
    <button type="submit" class="admin-btn admin-btn-primary">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="20 6 9 17 4 12"/>
      </svg>
      <?= $isEdit ? 'Update User' : 'Create User' ?>
    </button>
    <a href="/admin/users" class="admin-btn">Cancel</a>
  </div>
</form>

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
        preview.replaceWith(img);
        img.id = 'avatarPreview';
      }
    };
    reader.readAsDataURL(file);
  }
});
</script>
