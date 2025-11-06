<?php use function App\e; ?>

<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title">Dashboard</h1>
    <p class="admin-page-subtitle">Welcome back! Here's what's happening with your site.</p>
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

<div class="admin-stats-grid">
  <div class="admin-stat-card">
    <div class="admin-stat-label">Total Posts</div>
    <h2 class="admin-stat-value"><?= $stats['posts'] ?? 0 ?></h2>
  </div>

  <div class="admin-stat-card">
    <div class="admin-stat-label">Published</div>
    <h2 class="admin-stat-value" style="color: var(--admin-success);"><?= $stats['published'] ?? 0 ?></h2>
  </div>

  <div class="admin-stat-card">
    <div class="admin-stat-label">Drafts</div>
    <h2 class="admin-stat-value" style="color: var(--admin-warning);"><?= $stats['drafts'] ?? 0 ?></h2>
  </div>

  <div class="admin-stat-card">
    <div class="admin-stat-label">Categories</div>
    <h2 class="admin-stat-value"><?= $stats['categories'] ?? 0 ?></h2>
  </div>

  <div class="admin-stat-card">
    <div class="admin-stat-label">Tags</div>
    <h2 class="admin-stat-value"><?= $stats['tags'] ?? 0 ?></h2>
  </div>

  <div class="admin-stat-card">
    <div class="admin-stat-label">Giveaways</div>
    <h2 class="admin-stat-value" style="color: var(--admin-primary);"><?= $stats['giveaways'] ?? 0 ?></h2>
  </div>
</div>

<div class="admin-card">
  <div class="admin-card-header">
    <h3 class="admin-card-title">Recent Posts</h3>
    <a href="/admin/posts" class="admin-btn admin-btn-sm">View All</a>
  </div>
  <div class="admin-card-body" style="padding: 0;">
    <?php if (empty($recentPosts)): ?>
      <div style="padding: 40px; text-align: center; color: var(--admin-text-secondary);">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 16px; opacity: 0.3;">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
        </svg>
        <p style="margin: 0; font-weight: 600;">No posts yet</p>
        <p style="margin: 8px 0 0; font-size: 14px;">Create your first post to get started</p>
        <a href="/admin/posts/new" class="admin-btn admin-btn-primary" style="margin-top: 16px;">Create Post</a>
      </div>
    <?php else: ?>
      <table class="admin-table">
        <thead>
          <tr>
            <th>Title</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($recentPosts as $post): ?>
            <tr>
              <td>
                <strong><?= e($post['title']) ?></strong>
              </td>
              <td>
                <?php if ($post['published_at']): ?>
                  <span class="admin-badge admin-badge-success">Published</span>
                <?php else: ?>
                  <span class="admin-badge admin-badge-warning">Draft</span>
                <?php endif; ?>
              </td>
              <td style="color: var(--admin-text-secondary);">
                <?= date('M j, Y', strtotime($post['created_at'])) ?>
              </td>
              <td>
                <div class="admin-table-actions">
                  <a href="/post/<?= e($post['slug']) ?>" target="_blank" class="admin-btn admin-btn-sm">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                      <circle cx="12" cy="12" r="3"/>
                    </svg>
                    View
                  </a>
                  <a href="/admin/posts/<?= (int)$post['id'] ?>/edit" class="admin-btn admin-btn-sm">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Edit
                  </a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>
