<?php use function App\e; ?>

<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title">Pages</h1>
    <p class="admin-page-subtitle">Manage your static pages</p>
  </div>
  <div class="admin-page-actions">
    <a href="/admin/pages/new" class="admin-btn admin-btn-primary">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M12 5v14M5 12h14"/>
      </svg>
      New Page
    </a>
  </div>
</div>

<div class="admin-card">
  <?php if (empty($pages)): ?>
    <div style="padding: 80px 40px; text-align: center;">
      <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 24px; opacity: 0.2; color: var(--admin-text-secondary);">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
        <polyline points="14 2 14 8 20 8"/>
      </svg>
      <h3 style="margin: 0 0 12px; color: var(--admin-text); font-size: 20px;">No pages yet</h3>
      <p style="margin: 0 0 24px; color: var(--admin-text-secondary);">Create custom pages for your site</p>
      <a href="/admin/pages/new" class="admin-btn admin-btn-primary admin-btn-lg">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M12 5v14M5 12h14"/>
        </svg>
        Create Your First Page
      </a>
    </div>
  <?php else: ?>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Title</th>
          <th style="width: 150px;">Status</th>
          <th style="width: 130px;">Date</th>
          <th style="width: 200px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($pages as $page): ?>
          <tr>
            <td>
              <div style="font-weight: 600; color: var(--admin-text); margin-bottom: 4px;">
                <?= e($page['title']) ?>
              </div>
              <div style="font-size: 13px; color: var(--admin-text-secondary); font-family: monospace;">
                /page/<?= e($page['slug']) ?>
              </div>
            </td>
            <td>
              <?php if ($page['is_published']): ?>
                <span class="admin-badge admin-badge-success">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="margin-right: 4px;">
                    <polyline points="20 6 9 17 4 12"/>
                  </svg>
                  Published
                </span>
              <?php else: ?>
                <span class="admin-badge admin-badge-warning">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="margin-right: 4px;">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                  </svg>
                  Draft
                </span>
              <?php endif; ?>
            </td>
            <td style="color: var(--admin-text-secondary); font-size: 13px;">
              <?= date('M j, Y', strtotime($page['created_at'])) ?>
            </td>
            <td>
              <div class="admin-table-actions">
                <a href="/page/<?= e($page['slug']) ?>" target="_blank" class="admin-btn admin-btn-sm" title="View Page">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                  </svg>
                  View
                </a>
                <a href="/admin/pages/<?= (int)$page['id'] ?>/edit" class="admin-btn admin-btn-sm" title="Edit Page">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                  </svg>
                  Edit
                </a>
                <a href="/admin/pages/<?= (int)$page['id'] ?>/delete" class="admin-btn admin-btn-sm admin-btn-danger" title="Delete Page">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                  </svg>
                  Delete
                </a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div style="padding: 20px 24px; border-top: 1px solid var(--admin-border); background: var(--admin-bg); text-align: center; color: var(--admin-text-secondary); font-size: 14px;">
      Showing <?= count($pages) ?> page<?= count($pages) !== 1 ? 's' : '' ?>
    </div>
  <?php endif; ?>
</div>
