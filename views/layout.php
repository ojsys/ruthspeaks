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
  <header class="app-bar">
    <div class="container app-bar-inner">
      <a class="brand" href="/">RuthSpeaksTruth</a>
      <nav class="nav">
        <a href="/">Home</a>
        <a href="/rss.xml">RSS</a>
      </nav>
    </div>
  </header>

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
        <p><a href="/">Home</a></p>
        <p><a href="/rss.xml">RSS</a> • <a href="/sitemap.xml">Sitemap</a></p>
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
