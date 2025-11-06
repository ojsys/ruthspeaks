<?php
use function App\e;
use const App\SITE_NAME;
$currentPath = $_SERVER['REQUEST_URI'] ?? '';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= e($title ?? 'Admin') ?> - <?= e(SITE_NAME) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/admin.css" />
</head>
<body class="admin-body">
  <!-- Mobile Header -->
  <header class="admin-mobile-header">
    <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
      <span></span><span></span><span></span>
    </button>
    <div class="admin-brand"><?= e(SITE_NAME) ?> Admin</div>
    <div></div>
  </header>

  <!-- Sidebar -->
  <aside class="admin-sidebar" id="adminSidebar">
    <div class="admin-sidebar-header">
      <h1 class="admin-brand-title"><?= e(SITE_NAME) ?></h1>
      <p class="admin-brand-subtitle">Admin Panel</p>
    </div>

    <nav class="admin-nav">
      <a href="/admin" class="admin-nav-item <?= $currentPath === '/admin' ? 'active' : '' ?>">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
          <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
        </svg>
        <span>Dashboard</span>
      </a>

      <a href="/admin/posts" class="admin-nav-item <?= strpos($currentPath, '/admin/posts') === 0 ? 'active' : '' ?>">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
          <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
          <polyline points="10 9 9 9 8 9"/>
        </svg>
        <span>Posts</span>
      </a>

      <a href="/admin/categories" class="admin-nav-item <?= $currentPath === '/admin/categories' ? 'active' : '' ?>">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
        </svg>
        <span>Categories</span>
      </a>

      <a href="/admin/tags" class="admin-nav-item <?= $currentPath === '/admin/tags' ? 'active' : '' ?>">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
          <line x1="7" y1="7" x2="7.01" y2="7"/>
        </svg>
        <span>Tags</span>
      </a>

      <a href="/admin/giveaways" class="admin-nav-item <?= strpos($currentPath, '/admin/giveaways') === 0 ? 'active' : '' ?>">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="20 12 20 22 4 22 4 12"/>
          <rect x="2" y="7" width="20" height="5"/>
          <line x1="12" y1="22" x2="12" y2="7"/>
          <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/>
          <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>
        </svg>
        <span>Giveaways</span>
      </a>

      <a href="/admin/logs" class="admin-nav-item <?= $currentPath === '/admin/logs' ? 'active' : '' ?>">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
          <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
        </svg>
        <span>Logs</span>
      </a>
    </nav>

    <div class="admin-sidebar-footer">
      <a href="/" class="admin-nav-item" target="_blank">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
          <polyline points="15 3 21 3 21 9"/>
          <line x1="10" y1="14" x2="21" y2="3"/>
        </svg>
        <span>View Site</span>
      </a>
      <a href="/admin/logout" class="admin-nav-item">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
          <polyline points="16 17 21 12 16 7"/>
          <line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
        <span>Logout</span>
      </a>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="admin-main">
    <div class="admin-content">
      <?= $content ?? '' ?>
    </div>
  </main>

  <!-- Backdrop for mobile -->
  <div class="admin-backdrop" id="adminBackdrop"></div>

  <script>
    (function() {
      var toggle = document.getElementById('mobileMenuToggle');
      var sidebar = document.getElementById('adminSidebar');
      var backdrop = document.getElementById('adminBackdrop');

      function openSidebar() {
        sidebar.classList.add('open');
        backdrop.classList.add('active');
        document.body.style.overflow = 'hidden';
      }

      function closeSidebar() {
        sidebar.classList.remove('open');
        backdrop.classList.remove('active');
        document.body.style.overflow = '';
      }

      if (toggle) toggle.addEventListener('click', openSidebar);
      if (backdrop) backdrop.addEventListener('click', closeSidebar);
    })();
  </script>
</body>
</html>
