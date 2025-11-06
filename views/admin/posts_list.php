<?php use function App\e; ?>

<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title">Posts</h1>
    <p class="admin-page-subtitle">Manage your blog posts and content</p>
  </div>
  <div class="admin-page-actions">
    <a href="/admin/posts/new" class="admin-btn admin-btn-primary">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M12 5v14M5 12h14"/>
      </svg>
      New Post
    </a>
  </div>
</div>

<div class="admin-card">
  <?php if (empty($posts)): ?>
    <div style="padding: 80px 40px; text-align: center;">
      <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 24px; opacity: 0.2; color: var(--admin-text-secondary);">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
        <polyline points="14 2 14 8 20 8"/>
        <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
      </svg>
      <h3 style="margin: 0 0 12px; color: var(--admin-text); font-size: 20px;">No posts yet</h3>
      <p style="margin: 0 0 24px; color: var(--admin-text-secondary);">Start creating content for your blog</p>
      <a href="/admin/posts/new" class="admin-btn admin-btn-primary admin-btn-lg">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M12 5v14M5 12h14"/>
        </svg>
        Create Your First Post
      </a>
    </div>
  <?php else: ?>
    <table class="admin-table">
      <thead>
        <tr>
          <th style="width: 50px;"></th>
          <th>Title</th>
          <th style="width: 150px;">Status</th>
          <th style="width: 130px;">Date</th>
          <th style="width: 200px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($posts as $post): ?>
          <tr>
            <td>
              <?php if (!empty($post['cover_image'])): ?>
                <img
                  src="<?= e($post['cover_image']) ?>"
                  alt=""
                  style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px; border: 1px solid var(--admin-border);"
                />
              <?php else: ?>
                <div style="width: 40px; height: 40px; background: var(--admin-bg); border-radius: 6px; border: 1px solid var(--admin-border); display: flex; align-items: center; justify-content: center;">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--admin-text-light);">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                    <circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                  </svg>
                </div>
              <?php endif; ?>
            </td>
            <td>
              <div style="font-weight: 600; color: var(--admin-text); margin-bottom: 4px;">
                <?= e($post['title']) ?>
              </div>
              <div style="font-size: 13px; color: var(--admin-text-secondary); font-family: monospace;">
                /post/<?= e($post['slug']) ?>
              </div>
            </td>
            <td>
              <?php if ($post['published_at']): ?>
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
              <?php
                $date = $post['published_at'] ?: $post['created_at'];
                echo date('M j, Y', strtotime($date));
              ?>
            </td>
            <td>
              <div class="admin-table-actions">
                <a href="/post/<?= e($post['slug']) ?>" target="_blank" class="admin-btn admin-btn-sm" title="View Post">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                  </svg>
                  View
                </a>
                <a href="/admin/posts/<?= (int)$post['id'] ?>/edit" class="admin-btn admin-btn-sm" title="Edit Post">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                  </svg>
                  Edit
                </a>
                <a href="/admin/posts/<?= (int)$post['id'] ?>/delete" class="admin-btn admin-btn-sm admin-btn-danger" title="Delete Post">
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
      Showing <?= count($posts) ?> post<?= count($posts) !== 1 ? 's' : '' ?>
    </div>
  <?php endif; ?>
</div>
