<?php use function App\e; use function App\csrf_field; ?>
<main>
  <div class="container">
    <div class="content">
      <h2><?= $giveaway ? 'Edit Giveaway' : 'New Giveaway' ?></h2>
      <form method="post">
        <?= csrf_field() ?>
        <label>Title</label>
        <input class="input" type="text" name="title" required value="<?= e($giveaway['title'] ?? '') ?>" />
        <label style="margin-top:8px;">Post</label>
        <select class="input" name="post_id" required>
          <?php foreach(($posts??[]) as $p): $sel = $giveaway && (int)$giveaway['post_id']===(int)$p['id']; ?>
            <option value="<?= (int)$p['id'] ?>" <?= $sel?'selected':'' ?>><?= e($p['title']) ?></option>
          <?php endforeach; ?>
        </select>
        <label style="margin-top:8px;">Start At</label>
        <input class="input" type="text" name="start_at" placeholder="YYYY-MM-DD HH:MM:SS or blank" value="<?= e($giveaway['start_at'] ?? '') ?>" />
        <label style="margin-top:8px;">End At</label>
        <input class="input" type="text" name="end_at" placeholder="YYYY-MM-DD HH:MM:SS or blank" value="<?= e($giveaway['end_at'] ?? '') ?>" />
        <label style="margin-top:8px;">Max Winners (0 = unlimited)</label>
        <input class="input" type="number" min="0" name="max_winners" value="<?= e((string)($giveaway['max_winners'] ?? 0)) ?>" />
        <label style="margin-top:8px;">Keyword (optional — must appear in post)</label>
        <?php $rules = json_decode($giveaway['rules_json'] ?? 'null', true); ?>
        <input class="input" type="text" name="keyword" value="<?= e($rules['keyword'] ?? '') ?>" />
        <div style="margin-top:12px;display:flex;gap:10px;">
          <button class="btn primary" type="submit">Save</button>
          <a class="btn" href="/admin/giveaways">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</main>

