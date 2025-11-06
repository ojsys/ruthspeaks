<?php use function App\e; ?>
<main>
  <div class="container">
    <article class="content" style="max-width: 900px; margin: 0 auto;">
      <?php if (!empty($page['featured_image'])): ?>
        <div style="margin-bottom: 32px;">
          <img
            src="<?= e($page['featured_image']) ?>"
            alt="<?= e($page['title']) ?>"
            style="width: 100%; height: auto; border-radius: 12px; box-shadow: var(--shadow-md);"
          />
        </div>
      <?php endif; ?>

      <header style="margin-bottom: 32px;">
        <h1 style="font-size: 2.5rem; margin-bottom: 16px;"><?= e($page['title']) ?></h1>
        <?php if (!empty($page['excerpt'])): ?>
          <p style="font-size: 1.25rem; color: var(--text-secondary); margin: 0;">
            <?= e($page['excerpt']) ?>
          </p>
        <?php endif; ?>
      </header>

      <div class="post-content">
        <?= $page['content'] ?>
      </div>
    </article>
  </div>
</main>
