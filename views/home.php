<?php use function App\e; ?>
<main>
  <div class="container">
    <section class="hero">
      <h1>Welcome to RuthSpeaksTruth</h1>
      <p>I’m Ruth Goodness Onah. Here I pour out thoughts on faith, growth, womanhood, family—and those “God, are You seeing this?” moments. Grab a drink; let’s do life, love, and laughter together.</p>
    </section>

    <?= App\view('partials/ad_slot', ['placement'=>'leaderboard']) ?>

    <section>
      <div class="post-list">
        <?php foreach (($posts ?? []) as $p): ?>
          <article class="card">
            <?php if (!empty($p['cover_image'])): ?>
              <a href="/post/<?= e($p['slug']) ?>"><img src="<?= e($p['cover_image']) ?>" alt="<?= e($p['title']) ?>" loading="lazy"></a>
            <?php endif; ?>
            <div class="card-body">
              <a href="/post/<?= e($p['slug']) ?>" class="card-title"><?= e($p['title']) ?></a>
              <div class="card-meta">
                <?= e((int)$p['estimated_read_minutes']) ?> min read
                <?php if (!empty($p['published_at'])): ?> • <?= date('M j, Y', strtotime($p['published_at'])) ?><?php endif; ?>
              </div>
              <?php if (!empty($p['excerpt'])): ?>
                <p><?= e($p['excerpt']) ?></p>
              <?php endif; ?>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
  </div>
</main>
