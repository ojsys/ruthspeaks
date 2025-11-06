<?php use function App\e; use function App\csrf_field; ?>

<div class="admin-page-header">
  <div>
    <h1 class="admin-page-title">Confirm Deletion</h1>
    <p class="admin-page-subtitle">This action cannot be undone</p>
  </div>
</div>

<div class="admin-card">
  <div class="admin-card-body" style="max-width: 600px; margin: 0 auto; text-align: center; padding: 60px 40px;">
    <svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 24px; color: var(--admin-danger); opacity: 0.8;">
      <circle cx="12" cy="12" r="10"/>
      <line x1="12" y1="8" x2="12" y2="12"/>
      <line x1="12" y1="16" x2="12.01" y2="16"/>
    </svg>

    <h2 style="margin: 0 0 12px; font-size: 24px; color: var(--admin-text);">
      Are you sure?
    </h2>

    <p style="margin: 0 0 8px; color: var(--admin-text); font-size: 16px;">
      You are about to delete this <?= e($resource ?? 'item') ?>:
    </p>

    <p style="margin: 0 0 32px; color: var(--admin-text-secondary); font-weight: 600; font-size: 18px;">
      "<?= e($name ?? 'Unknown') ?>"
    </p>

    <div class="admin-alert admin-alert-error" style="text-align: left; max-width: 500px; margin: 0 auto 32px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
        <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
      </svg>
      <div>
        <strong>Warning:</strong> This action is permanent and cannot be undone.
      </div>
    </div>

    <form method="post" action="<?= e($action ?? '') ?>" style="display: flex; gap: 12px; justify-content: center;">
      <?= csrf_field() ?>

      <a href="javascript:history.back()" class="admin-btn admin-btn-lg">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Cancel
      </a>

      <button type="submit" class="admin-btn admin-btn-danger admin-btn-lg">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="3 6 5 6 21 6"/>
          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
        </svg>
        Yes, Delete
      </button>
    </form>
  </div>
</div>
