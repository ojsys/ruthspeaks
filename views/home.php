<?php use function App\e; ?>
<main>
  <div class="container">
    <section class="hero">
      <div style="display: flex; justify-content: space-between; align-items: start; gap: 24px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 280px;">
          <h1>RuthSpeaksTruth</h1>
          <p>Deep, delightful, and real faith. Honest posts on faith, growth, womanhood, and family — with grace that still works overtime.</p>
        </div>
        <form method="get" action="/" style="max-width:320px; width:100%; min-width:260px;">
          <input class="input" type="search" name="q" value="<?= e($q ?? '') ?>" placeholder="Search posts..." />
        </form>
      </div>
      <?php if (!empty($q)): ?>
        <div class="help" style="margin-top:16px;">Search results for "<?= e($q) ?>"</div>
      <?php endif; ?>
    </section>

    <?php if (!empty($posts) && !isset($q) && !isset($selectedCat)): ?>
      <section class="featured-section">
        <?php $featured = $posts[0]; ?>
        <div class="featured-card">
          <?php if (!empty($featured['cover_image'])): ?>
            <img src="<?= e($featured['cover_image']) ?>" alt="<?= e($featured['title']) ?>" loading="eager">
          <?php endif; ?>
          <div class="featured-content">
            <span class="featured-badge">Featured</span>
            <?php if (!empty($featured['category_name'])): ?>
              <div class="card-meta"><?= e($featured['category_name']) ?> • <?= e((int)$featured['estimated_read_minutes']) ?> min read</div>
            <?php endif; ?>
            <h2><?= e($featured['title']) ?></h2>
            <?php if (!empty($featured['excerpt'])): ?>
              <p><?= e($featured['excerpt']) ?></p>
            <?php endif; ?>
            <a href="/post/<?= e($featured['slug']) ?>" class="btn primary">Read More →</a>
          </div>
        </div>
      </section>
    <?php endif; ?>

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
      <h2 class="section-title"><?= !empty($q) ? 'Search Results' : (isset($selectedCat) ? 'Category Posts' : 'Our Recent Articles') ?></h2>
      <p class="section-subtitle"><?= !empty($q) || isset($selectedCat) ? '' : 'Stay informed with our latest insights' ?></p>

      <div class="post-list">
        <?php if (empty($posts)): ?>
          <div class="content">
            <h3>No posts found</h3>
            <p>Try a different search or clear filters.</p>
          </div>
        <?php endif; ?>
        <?php
        $postsToShow = $posts ?? [];
        // Skip first post if it's featured
        if (!empty($postsToShow) && !isset($q) && !isset($selectedCat)) {
          $postsToShow = array_slice($postsToShow, 1);
        }
        foreach ($postsToShow as $p):
        ?>
          <article class="card">
            <?php if (!empty($p['cover_image'])): ?>
              <a href="/post/<?= e($p['slug']) ?>"><img src="<?= e($p['cover_image']) ?>" alt="<?= e($p['title']) ?>" loading="lazy"></a>
            <?php endif; ?>
            <div class="card-body">
              <?php if (!empty($p['category_name'])): ?>
                <div class="card-category"><?= e($p['category_name']) ?></div>
              <?php endif; ?>
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
