<?php
use function App\e;
use function App\injectInArticleAds;
use const App\GIVEAWAY_PROGRESS_THRESHOLD;

$contentHtml = $post['content'] ?? '';
$contentHtml = injectInArticleAds($contentHtml);
?>
<main>
  <div class="container">
    <div class="post-layout">
      <article class="content">
        <header>
          <h1><?= e($post['title']) ?></h1>
          <div class="card-meta">
            <?= e((int)$post['estimated_read_minutes']) ?> min read
            <?php if (!empty($post['published_at'])): ?>• <?= date('M j, Y', strtotime($post['published_at'])) ?><?php endif; ?>
          </div>
          <?php if (!empty($post['cover_image'])): ?>
            <img src="<?= e($post['cover_image']) ?>" alt="<?= e($post['title']) ?>" style="width:100%;border-radius:10px;margin:10px 0;"/>
          <?php endif; ?>
        </header>
        <?= App\view('partials/ad_slot', ['placement'=>'in-article']) ?>
        <section class="prose">
          <?= $contentHtml ?>
        </section>
        <hr style="margin-top:20px;"/>
        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
          <span class="help">Share:</span>
          <?php $shareUrl = (App\SITE_BASE_URL ?: '') . '/post/' . $post['slug']; $enc = urlencode($shareUrl); $text=urlencode($post['title']); ?>
          <a class="btn" href="https://wa.me/?text=<?= $text ?>%20<?= $enc ?>" target="_blank">WhatsApp</a>
          <a class="btn" href="https://twitter.com/intent/tweet?url=<?= $enc ?>&text=<?= $text ?>" target="_blank">Twitter</a>
          <a class="btn" href="https://www.facebook.com/sharer/sharer.php?u=<?= $enc ?>" target="_blank">Facebook</a>
        </div>
      </article>
      <?php if (App\ADS_ENABLED && App\AD_SIDEBAR_HTML !== ''): ?>
        <aside>
          <?= App\view('partials/ad_slot', ['placement'=>'sidebar']) ?>
        </aside>
      <?php endif; ?>
    </div>
  </div>
  
  <?php if (!empty($giveaway)): ?>
    <button class="giveaway-cta" disabled>Unlock Giveaway</button>
    <div class="modal-backdrop">
      <div class="modal">
        <h3>Claim Your Giveaway</h3>
        <p class="help">You unlocked this by reading <?= (int)GIVEAWAY_PROGRESS_THRESHOLD ?>% and spending time with the post. Enter your email to receive your code.</p>
        <label>Email</label>
        <input class="input" type="email" id="claim-email" placeholder="you@example.com" />
        <?php $rules = json_decode($giveaway['rules_json'] ?? 'null', true); if (is_array($rules) && !empty($rules['keyword'])): ?>
          <label style="margin-top:10px;display:block;">Keyword (from the post)</label>
          <input class="input" type="text" id="claim-keyword" placeholder="Type the keyword" />
        <?php endif; ?>
        <div class="actions" style="margin-top:10px;">
          <button class="btn" onclick="document.querySelector('.modal-backdrop').style.display='none'">Close</button>
          <button class="btn primary" id="claim-btn">Claim</button>
        </div>
        <div id="claim-result" class="help" style="margin-top:8px;"></div>
      </div>
    </div>
    <script>
      window.POST_ID = <?= (int)$post['id'] ?>;
      window.MIN_TIME_MS = <?= (int)$minTimeMs ?>;
    </script>
  <?php endif; ?>
</main>
