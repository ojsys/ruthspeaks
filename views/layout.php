<?php
use function App\e;
use const App\SITE_NAME;
use const App\GIVEAWAY_PROGRESS_THRESHOLD;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= e($title ?? SITE_NAME) ?></title>
  <?php if (!empty($meta['description'])): ?>
  <meta name="description" content="<?= e($meta['description']) ?>" />
  <?php endif; ?>
  <?php if (!empty($meta['image'])): ?>
  <meta property="og:image" content="<?= e($meta['image']) ?>" />
  <?php endif; ?>
  <meta property="og:title" content="<?= e($title ?? SITE_NAME) ?>" />
  <meta property="og:site_name" content="<?= e(SITE_NAME) ?>" />
  <meta property="og:type" content="website" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/styles.css" />
  <?= App\view('partials/ad_slot', ['placement'=>'head']) ?>

  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-S3JH4RF57X"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-S3JH4RF57X');
  </script>
</head>
<body>
  <header class="app-bar" id="appBar">
    <div class="container app-bar-inner">
      <button class="hamburger" id="navOpen" aria-label="Open menu" aria-controls="navDrawer" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
      <a class="brand" href="/">RuthSpeaksTruth</a>
      <nav class="nav">
        <a href="/">Home</a>
        <a href="/about">About</a>
        <a href="/contact">Let's Talk</a>
        <?php if (isset($_SESSION['user'])): ?>
          <a href="/admin" class="nav-user-link">
            <?php if (!empty($_SESSION['user']['avatar'] ?? '')): ?>
              <img src="<?= e($_SESSION['user']['avatar']) ?>" alt="<?= e($_SESSION['user']['name']) ?>" class="nav-avatar" />
            <?php else: ?>
              <span class="nav-avatar-initials"><?= strtoupper(substr($_SESSION['user']['name'] ?? 'U', 0, 1)) ?></span>
            <?php endif; ?>
            <span><?= e($_SESSION['user']['name']) ?></span>
          </a>
          <a href="/logout">Logout</a>
        <?php else: ?>
          <a href="/login">Login</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <!-- Theme Toggle Floating Button -->
  <button class="theme-toggle-floating" id="themeToggle" type="button" aria-label="Toggle dark mode">
    <svg class="sun-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <circle cx="12" cy="12" r="5"/>
      <line x1="12" y1="1" x2="12" y2="3"/>
      <line x1="12" y1="21" x2="12" y2="23"/>
      <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
      <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
      <line x1="1" y1="12" x2="3" y2="12"/>
      <line x1="21" y1="12" x2="23" y2="12"/>
      <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
      <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
    </svg>
    <svg class="moon-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
    </svg>
  </button>

  <aside class="nav-drawer" id="navDrawer" aria-hidden="true">
    <div class="nav-drawer-inner">
      <button class="btn" id="navClose" aria-label="Close menu">Close</button>
      <?php if (isset($_SESSION['user'])): ?>
        <div class="drawer-user-info">
          <?php if (!empty($_SESSION['user']['avatar'] ?? '')): ?>
            <img src="<?= e($_SESSION['user']['avatar']) ?>" alt="<?= e($_SESSION['user']['name']) ?>" class="drawer-avatar" />
          <?php else: ?>
            <div class="drawer-avatar-initials"><?= strtoupper(substr($_SESSION['user']['name'] ?? 'U', 0, 2)) ?></div>
          <?php endif; ?>
          <div>
            <div class="drawer-user-name"><?= e($_SESSION['user']['name']) ?></div>
            <div class="drawer-user-role"><?= e(ucfirst($_SESSION['user']['role'])) ?></div>
          </div>
        </div>
      <?php endif; ?>
      <a href="/">Home</a>
      <a href="/about">About</a>
      <a href="/contact">Let's Talk</a>
      <?php if (isset($_SESSION['user'])): ?>
        <a href="/admin">Admin Dashboard</a>
        <a href="/logout">Logout</a>
      <?php else: ?>
        <a href="/login">Login</a>
      <?php endif; ?>
    </div>
  </aside>
  <div class="drawer-backdrop" id="drawerBackdrop" hidden></div>

  <?= App\view('partials/ad_slot', ['placement'=>'leaderboard']) ?>

  <div class="progress-wrap"><div class="progress-bar"></div></div>

  <?= $content ?? '' ?>

  <footer class="site-footer">
    <div class="container footer-grid">
      <section>
        <h4>About</h4>
        <p>I’m Ruth Goodness Onah. Here I pour out thoughts on faith, growth, womanhood, family — and those “God, are You seeing this?” moments.</p>
      </section>
      <section>
        <h4>Explore</h4>
        <p><a href="/">Home</a> • <a href="/about">About</a></p>
        <p><a href="/sitemap.xml">Sitemap</a></p>
      </section>
      <section>
        <h4>Newsletter</h4>
        <form id="subscribe-form" onsubmit="return false;" class="subscribe-form">
          <input class="input" type="email" id="subscribe-email" placeholder="Your email" required />
          <button class="btn primary" id="subscribe-btn" type="submit">Subscribe</button>
        </form>
        <div id="subscribe-result" class="help"></div>
      </section>
    </div>
    <div class="footer-bottom">
      <div class="container">
        <div class="left">&copy; <?= date('Y') ?> RuthSpeaksTruth</div>
        <div class="right">Made with grace and laughter.</div>
      </div>
    </div>
  </footer>

  <script>
    window.PROGRESS_GOAL = <?= (int)GIVEAWAY_PROGRESS_THRESHOLD ?>;
  </script>
  <script src="/assets/js/main.js" defer></script>
  <script>
    (function(){
      // Newsletter
      var form = document.getElementById('subscribe-form');
      var email = document.getElementById('subscribe-email');
      var btn = document.getElementById('subscribe-btn');
      var out = document.getElementById('subscribe-result');
      if (form) form.addEventListener('submit', function(){
        if (!email.value || !email.value.includes('@')) { out.textContent='Enter a valid email'; return; }
        btn.disabled = true; out.textContent='Subscribing...';
        fetch('/api/subscribe', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ email: email.value })})
          .then(r=>r.json()).then(function(res){
            out.textContent = res.ok ? 'Thanks for subscribing!' : (res.message||'Unable to subscribe');
            btn.disabled = false;
            if (res.ok) email.value='';
          }).catch(function(){ out.textContent='Network error'; btn.disabled=false; });
      });

      // Theme & Drawer
      var body = document.body;
      var toggle = document.getElementById('themeToggle');
      var navOpen = document.getElementById('navOpen');
      var navClose = document.getElementById('navClose');
      var drawer = document.getElementById('navDrawer');
      var backdrop = document.getElementById('drawerBackdrop');
      var appBar = document.getElementById('appBar');

      try {
        var pref = localStorage.getItem('theme');
        if (pref === 'dark') body.classList.add('theme-dark');
        if (toggle) {
          toggle.addEventListener('click', function(){
            body.classList.toggle('theme-dark');
            localStorage.setItem('theme', body.classList.contains('theme-dark') ? 'dark' : 'light');

            // Add a subtle animation
            toggle.style.transform = 'rotate(360deg) scale(0.9)';
            setTimeout(function() {
              toggle.style.transform = '';
            }, 300);
          });
        }
      } catch(e){}

      function openDrawer(){ drawer.setAttribute('aria-hidden','false'); backdrop.hidden=false; navOpen && navOpen.setAttribute('aria-expanded','true'); }
      function closeDrawer(){ drawer.setAttribute('aria-hidden','true'); backdrop.hidden=true; navOpen && navOpen.setAttribute('aria-expanded','false'); }
      if (navOpen) navOpen.addEventListener('click', openDrawer);
      if (navClose) navClose.addEventListener('click', closeDrawer);
      if (backdrop) backdrop.addEventListener('click', closeDrawer);

      var last = 0; var ticking = false;
      window.addEventListener('scroll', function(){
        last = window.scrollY || 0;
        if (!ticking){ requestAnimationFrame(function(){ appBar && (appBar.classList.toggle('scrolled', last>4)); ticking=false; }); ticking=true; }
      });
    })();
  </script>
</body>
</html>
