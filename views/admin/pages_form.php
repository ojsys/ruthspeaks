<?php use function App\e; use function App\csrf_field; ?>

<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title"><?= $page ? 'Edit Page' : 'New Page' ?></h1>
    <p class="admin-page-subtitle"><?= $page ? 'Update your page content and settings' : 'Create a new page' ?></p>
  </div>
  <div class="admin-page-actions">
    <a href="/admin/pages" class="admin-btn">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
      </svg>
      Back to Pages
    </a>
  </div>
</div>

<form method="post" enctype="multipart/form-data" id="page-form">
  <?= csrf_field() ?>

  <div style="display: grid; grid-template-columns: 1fr 360px; gap: 24px; align-items: start;">
    <!-- Main Content -->
    <div>
      <div class="admin-card" style="margin-bottom: 24px;">
        <div class="admin-card-body">
          <div class="admin-form-group">
            <label class="admin-form-label required" for="page-title">Page Title</label>
            <input
              type="text"
              id="page-title"
              name="title"
              class="admin-input"
              required
              value="<?= e($page['title'] ?? '') ?>"
              placeholder="Enter page title..."
              style="font-size: 20px; font-weight: 600;"
            />
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="page-slug">URL Slug</label>
            <input
              type="text"
              id="page-slug"
              name="slug"
              class="admin-input"
              value="<?= e($page['slug'] ?? '') ?>"
              placeholder="auto-generated-from-title"
            />
            <p class="admin-form-help">Leave empty to auto-generate from title</p>
          </div>

          <div class="admin-form-group">
            <label class="admin-form-label" for="page-excerpt">Excerpt</label>
            <textarea
              id="page-excerpt"
              name="excerpt"
              class="admin-textarea"
              rows="2"
              placeholder="A brief summary..."
            ><?= e($page['excerpt'] ?? '') ?></textarea>
          </div>
        </div>
      </div>

      <div class="admin-card">
        <div class="admin-card-header">
          <h3 class="admin-card-title">Page Content</h3>
        </div>
        <div class="admin-card-body">
          <!-- Quill Editor -->
          <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
          <div id="quill-editor" style="min-height: 450px; background: white;">
            <?= $page['content'] ?? '' ?>
          </div>
          <textarea name="content" id="content-field" style="display:none;"></textarea>
        </div>
      </div>
    </div>

    <!-- Sidebar -->
    <div>
      <!-- Publish Settings -->
      <div class="admin-card" style="margin-bottom: 24px;">
        <div class="admin-card-header">
          <h3 class="admin-card-title">Publish</h3>
        </div>
        <div class="admin-card-body">
          <div class="admin-form-group">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
              <input
                type="checkbox"
                name="is_published"
                <?= ($page['is_published'] ?? 1) ? 'checked' : '' ?>
                style="width: 18px; height: 18px;"
              />
              <span style="font-weight: 600; font-size: 14px;">Published</span>
            </label>
            <p class="admin-form-help">Uncheck to save as draft</p>
          </div>

          <div style="display: flex; gap: 8px; margin-top: 16px;">
            <button type="submit" class="admin-btn admin-btn-primary" style="flex: 1;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
              </svg>
              <?= $page ? 'Update' : 'Save' ?> Page
            </button>
          </div>
        </div>
      </div>

      <!-- Featured Image -->
      <div class="admin-card">
        <div class="admin-card-header">
          <h3 class="admin-card-title">Featured Image</h3>
        </div>
        <div class="admin-card-body">
          <div class="admin-form-group" style="margin-bottom: 12px;">
            <label class="admin-form-label" for="featured-image-file">Upload Image</label>
            <input
              type="file"
              id="featured-image-file"
              name="featured_image"
              class="admin-input admin-file-input"
              accept="image/*"
            />
          </div>

          <div class="admin-form-group" style="margin-bottom: 0;">
            <label class="admin-form-label" for="featured-image-url">Or Image URL</label>
            <input
              type="url"
              id="featured-image-url"
              name="featured_image_url"
              class="admin-input"
              value="<?= e($page['featured_image'] ?? '') ?>"
              placeholder="https://..."
            />
          </div>

          <?php if (!empty($page['featured_image'])): ?>
            <div style="margin-top: 12px;">
              <img src="<?= e($page['featured_image']) ?>" alt="Current image" style="width: 100%; height: auto; border-radius: 8px; border: 1px solid var(--admin-border);" />
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</form>

<!-- Quill.js Editor -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
(function() {
  // Initialize Quill editor
  var quill = new Quill('#quill-editor', {
    theme: 'snow',
    modules: {
      toolbar: [
        [{ 'header': [2, 3, false] }],
        ['bold', 'italic', 'underline', 'strike'],
        ['blockquote', 'code-block'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        ['link', 'image'],
        ['clean']
      ]
    },
    placeholder: 'Write your page content here...'
  });

  // Image upload handler
  quill.getModule('toolbar').addHandler('image', function() {
    var input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');
    input.click();

    input.onchange = function() {
      var file = input.files[0];
      if (file) {
        var formData = new FormData();
        formData.append('file', file);

        fetch('/api/upload', {
          method: 'POST',
          body: formData
        })
        .then(function(response) { return response.json(); })
        .then(function(result) {
          if (result.ok && result.url) {
            var range = quill.getSelection(true);
            quill.insertEmbed(range.index, 'image', result.url);
          } else {
            alert(result.message || 'Upload failed');
          }
        })
        .catch(function(error) {
          alert('Upload failed: ' + error.message);
        });
      }
    };
  });

  // Auto-generate slug from title
  var titleInput = document.getElementById('page-title');
  var slugInput = document.getElementById('page-slug');
  var isEditMode = <?= $page ? 'true' : 'false' ?>;

  if (!isEditMode) {
    titleInput.addEventListener('input', function() {
      if (!slugInput.value || slugInput.dataset.autoGenerated === 'true') {
        var slug = titleInput.value
          .toLowerCase()
          .trim()
          .replace(/[^a-z0-9]+/g, '-')
          .replace(/^-+|-+$/g, '');
        slugInput.value = slug;
        slugInput.dataset.autoGenerated = 'true';
      }
    });

    slugInput.addEventListener('input', function() {
      if (slugInput.value) {
        slugInput.dataset.autoGenerated = 'false';
      }
    });
  }

  // Form submission - copy Quill content to hidden field
  document.getElementById('page-form').addEventListener('submit', function() {
    document.getElementById('content-field').value = quill.root.innerHTML;
  });
})();
</script>
