<?php use function App\e; use function App\csrf_field; ?>
<main>
  <div class="container" style="max-width:560px;">
    <div class="content">
      <h2>Delete <?= e($resource ?? 'item') ?></h2>
      <p>Are you sure you want to delete <strong><?= e($name ?? '') ?></strong>?</p>
      <form method="post" action="<?= e($action ?? '') ?>">
        <?= csrf_field() ?>
        <div style="display:flex;gap:10px;">
          <button class="btn primary" type="submit">Confirm Delete</button>
          <a class="btn" href="/admin">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</main>

