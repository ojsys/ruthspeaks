<?php use function App\e; ?>
<main>
  <div class="container">
    <section class="hero">
      <form method="get" action="/" style="float:right; max-width:320px; width:100%;">
        <input class="input" type="search" name="q" value="<?= e($q ?? '') ?>" placeholder="Search posts..." />
      </form>
      <h1>RuthSpeaksTruth</h1>
      <p>Deep, delightful, and real faith. Honest posts on faith, growth, womanhood, and family — with grace that still works overtime.</p>
      <div style="clear:both"></div>
      <?php if (!empty($q)): ?>
        <div class="help" style="margin-top:6px;">Search results for "<?= e($q) ?>"</div>
      <?php endif; ?>
    </section>

    <?php if (!empty($categories)): ?>
      <div class="chip-row">
        <?php foreach ($categories as $c): $active = isset($selectedCat) && (int)$selectedCat === (int)$c['id']; ?>
          <a class="chip<?= $active ? ' active' : '' ?>" href="/?cat=<?= (int)$c['id'] ?>"><?= e($c['name']) ?></a>
        <?php endforeach; ?>
        <?php if (!empty($selectedCat)): ?>
          <a class="chip" href="/">Clear</a>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <section>
      <div class="post-list">
        <?php if (empty($posts)): ?>
          <div class="content">
            <h3>No posts found</h3>
            <p>Try a different search or clear filters.</p>
          </div>
        <?php endif; ?>
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
