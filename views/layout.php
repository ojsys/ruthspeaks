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
</head>
<body>
  <header class="site-header">
    <div class="container">
      <div>
        <div class="brand">RuthSpeaksTruth</div>
        <div class="tagline">Deep, delightful, and real faith.</div>
      </div>
      <nav class="nav">
        <a href="/">Home</a>
        <a href="/rss.xml">RSS</a>
      </nav>
    </div>
  </header>

  <div class="progress-wrap"><div class="progress-bar"></div></div>

  <?= $content ?? '' ?>

  <footer class="site-footer">
    <div class="container">
      <?= App\view('partials/ad_slot', ['placement'=>'leaderboard']) ?>
      <p>&copy; <?= date('Y') ?> RuthSpeaksTruth. Built with love.</p>
      <p><a href="/sitemap.xml">Sitemap</a></p>
      <div id="newsletter" style="margin-top:10px;">
        <form id="subscribe-form" onsubmit="return false;" style="display:flex;gap:8px;max-width:520px;">
          <input class="input" type="email" id="subscribe-email" placeholder="Get updates — your@email.com" required />
          <button class="btn primary" id="subscribe-btn" type="submit">Subscribe</button>
        </form>
        <div id="subscribe-result" class="help"></div>
      </div>
    </div>
  </footer>

  <script>
    window.PROGRESS_GOAL = <?= (int)GIVEAWAY_PROGRESS_THRESHOLD ?>;
  </script>
  <script src="/assets/js/main.js" defer></script>
  <script>
    (function(){
      var form = document.getElementById('subscribe-form'); if (!form) return;
      var email = document.getElementById('subscribe-email');
      var btn = document.getElementById('subscribe-btn');
      var out = document.getElementById('subscribe-result');
      form.addEventListener('submit', function(){
        if (!email.value || !email.value.includes('@')) { out.textContent='Enter a valid email'; return; }
        btn.disabled = true; out.textContent='Subscribing...';
        fetch('/api/subscribe', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ email: email.value })})
          .then(r=>r.json()).then(function(res){
            out.textContent = res.ok ? 'Thanks for subscribing!' : (res.message||'Unable to subscribe');
            btn.disabled = false;
            if (res.ok) email.value='';
          }).catch(function(){ out.textContent='Network error'; btn.disabled=false; });
      });
    })();
  </script>
</body>
</html>
