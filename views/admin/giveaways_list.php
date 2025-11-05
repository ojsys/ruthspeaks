<?php use function App\e; ?>
<main>
  <div class="container">
    <div class="content">
      <h2>Giveaways</h2>
      <p><a class="btn primary" href="/admin/giveaways/new">New Giveaway</a> &nbsp; <a class="btn" href="/admin">Back</a></p>
      <table style="width:100%;border-collapse:collapse;">
        <thead><tr><th style="text-align:left;border-bottom:1px solid #eee;">Title</th><th style="text-align:left;border-bottom:1px solid #eee;">Post</th><th style="text-align:left;border-bottom:1px solid #eee;">Window</th><th style="text-align:left;border-bottom:1px solid #eee;">Max Winners</th><th style="text-align:left;border-bottom:1px solid #eee;">Actions</th></tr></thead>
        <tbody>
        <?php foreach(($items??[]) as $g): ?>
          <tr>
            <td style="padding:6px;"><?= e($g['title']) ?></td>
            <td style="padding:6px;"><?= e($g['post_title']) ?></td>
            <td style="padding:6px;"><?= e(($g['start_at']?:'—') . ' → ' . ($g['end_at']?:'—')) ?></td>
            <td style="padding:6px;"><?= (int)$g['max_winners'] ?></td>
            <td style="padding:6px;">
              <a class="btn" href="/admin/giveaways/<?= (int)$g['id'] ?>/edit">Edit</a>
              <a class="btn" href="/admin/giveaways/<?= (int)$g['id'] ?>/delete">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

