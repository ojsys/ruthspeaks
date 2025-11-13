<?php use function App\e; use function App\csrf_field; ?>

<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title">Site Settings</h1>
    <p class="admin-page-subtitle">Manage your site's appearance and configuration</p>
  </div>
</div>

<?php if ($saved ?? false): ?>
  <div class="admin-alert admin-alert-success">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
      <polyline points="22 4 12 14.01 9 11.01"/>
    </svg>
    <div><strong>Success!</strong> Settings have been saved.</div>
  </div>
<?php endif; ?>

<?php if (isset($error)): ?>
  <div class="admin-alert admin-alert-error">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <circle cx="12" cy="12" r="10"/>
      <line x1="12" y1="8" x2="12" y2="12"/>
      <line x1="12" y1="16" x2="12.01" y2="16"/>
    </svg>
    <div><strong>Error!</strong> <?= e($error) ?></div>
  </div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <div style="display: grid; gap: 24px;">
    <!-- Site Identity -->
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title">Site Identity</h3>
      </div>
      <div class="admin-card-body">
        <div class="admin-form-group">
          <label class="admin-form-label" for="site-logo-file">Site Logo</label>
          <input
            type="file"
            id="site-logo-file"
            name="site_logo"
            class="admin-input admin-file-input"
            accept="image/*"
          />
          <p class="admin-form-help">Or enter image URL below</p>
          <input
            type="url"
            name="site_logo_url"
            class="admin-input"
            value="<?= e($settings['site_logo'] ?? '') ?>"
            placeholder="https://..."
            style="margin-top: 8px;"
          />
          <?php if (!empty($settings['site_logo'])): ?>
            <div style="margin-top: 12px;">
              <img src="<?= e($settings['site_logo']) ?>" alt="Current logo" style="max-width: 200px; height: auto; border-radius: 8px; border: 1px solid var(--admin-border);" />
            </div>
          <?php endif; ?>
        </div>

        <div class="admin-form-group">
          <label class="admin-form-label" for="site-favicon-file">Favicon</label>
          <input
            type="file"
            id="site-favicon-file"
            name="site_favicon"
            class="admin-input admin-file-input"
            accept="image/*"
          />
          <p class="admin-form-help">Or enter image URL below (recommended: 32x32px or 64x64px)</p>
          <input
            type="url"
            name="site_favicon_url"
            class="admin-input"
            value="<?= e($settings['site_favicon'] ?? '') ?>"
            placeholder="https://..."
            style="margin-top: 8px;"
          />
          <?php if (!empty($settings['site_favicon'])): ?>
            <div style="margin-top: 12px;">
              <img src="<?= e($settings['site_favicon']) ?>" alt="Current favicon" style="max-width: 64px; height: auto; border-radius: 4px; border: 1px solid var(--admin-border);" />
            </div>
          <?php endif; ?>
        </div>

        <div class="admin-form-group" style="margin-bottom: 0;">
          <label class="admin-form-label" for="site-tagline">Site Tagline</label>
          <textarea
            id="site-tagline"
            name="site_tagline"
            class="admin-textarea"
            rows="3"
            placeholder="Your site's tagline or description..."
          ><?= e($settings['site_tagline'] ?? '') ?></textarea>
          <p class="admin-form-help">Appears in hero section and meta descriptions</p>
        </div>
      </div>
    </div>

    <!-- Images -->
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title">Site Images</h3>
      </div>
      <div class="admin-card-body">
        <div class="admin-form-group">
          <label class="admin-form-label" for="hero-image-file">Hero Image</label>
          <input
            type="file"
            id="hero-image-file"
            name="hero_image"
            class="admin-input admin-file-input"
            accept="image/*"
          />
          <p class="admin-form-help">Or enter image URL below</p>
          <input
            type="url"
            name="hero_image_url"
            class="admin-input"
            value="<?= e($settings['hero_image'] ?? '') ?>"
            placeholder="https://..."
            style="margin-top: 8px;"
          />
          <?php if (!empty($settings['hero_image'])): ?>
            <div style="margin-top: 12px;">
              <img src="<?= e($settings['hero_image']) ?>" alt="Current hero" style="max-width: 100%; height: auto; border-radius: 8px; border: 1px solid var(--admin-border);" />
            </div>
          <?php endif; ?>
        </div>

        <div class="admin-form-group" style="margin-bottom: 0;">
          <label class="admin-form-label" for="about-image-file">About Page Image</label>
          <input
            type="file"
            id="about-image-file"
            name="about_page_image"
            class="admin-input admin-file-input"
            accept="image/*"
          />
          <p class="admin-form-help">Or enter image URL below</p>
          <input
            type="url"
            name="about_page_image_url"
            class="admin-input"
            value="<?= e($settings['about_page_image'] ?? '') ?>"
            placeholder="https://..."
            style="margin-top: 8px;"
          />
          <?php if (!empty($settings['about_page_image'])): ?>
            <div style="margin-top: 12px;">
              <img src="<?= e($settings['about_page_image']) ?>" alt="Current about page image" style="max-width: 100%; height: auto; border-radius: 8px; border: 1px solid var(--admin-border);" />
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Contact & Footer -->
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title">Contact & Footer</h3>
      </div>
      <div class="admin-card-body">
        <div class="admin-form-group">
          <label class="admin-form-label" for="contact-email">Contact Email</label>
          <input
            type="email"
            id="contact-email"
            name="contact_email"
            class="admin-input"
            value="<?= e($settings['contact_email'] ?? '') ?>"
            placeholder="contact@example.com"
          />
        </div>

        <div class="admin-form-group" style="margin-bottom: 0;">
          <label class="admin-form-label" for="footer-text">Footer Text</label>
          <input
            type="text"
            id="footer-text"
            name="footer_text"
            class="admin-input"
            value="<?= e($settings['footer_text'] ?? '') ?>"
            placeholder="Made with grace and laughter."
          />
          <p class="admin-form-help">Appears at the bottom of every page</p>
        </div>
      </div>
    </div>

    <!-- Social Media -->
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title">Social Media</h3>
      </div>
      <div class="admin-card-body">
        <div class="admin-form-group">
          <label class="admin-form-label" for="social-instagram">Instagram URL</label>
          <input
            type="url"
            id="social-instagram"
            name="social_instagram"
            class="admin-input"
            value="<?= e($settings['social_instagram'] ?? '') ?>"
            placeholder="https://instagram.com/username"
          />
        </div>

        <div class="admin-form-group">
          <label class="admin-form-label" for="social-twitter">Twitter URL</label>
          <input
            type="url"
            id="social-twitter"
            name="social_twitter"
            class="admin-input"
            value="<?= e($settings['social_twitter'] ?? '') ?>"
            placeholder="https://twitter.com/username"
          />
        </div>

        <div class="admin-form-group" style="margin-bottom: 0;">
          <label class="admin-form-label" for="social-facebook">Facebook URL</label>
          <input
            type="url"
            id="social-facebook"
            name="social_facebook"
            class="admin-input"
            value="<?= e($settings['social_facebook'] ?? '') ?>"
            placeholder="https://facebook.com/username"
          />
        </div>
      </div>
    </div>

    <!-- Save Button -->
    <div style="display: flex; gap: 12px;">
      <button type="submit" class="admin-btn admin-btn-primary admin-btn-lg">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
          <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
        </svg>
        Save Settings
      </button>
    </div>
  </div>
</form>
