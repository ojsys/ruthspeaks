<?php use function App\e; use function App\csrf_field; ?>
<main>
  <div class="container">
    <div class="content">
      <h2>Posts</h2>
      <p><a class="btn primary" href="/admin/posts/new">New Post</a> &nbsp; <a class="btn" href="/admin">Back</a></p>
      <table style="width:100%;border-collapse:collapse;">
        <thead>
          <tr><th style="text-align:left;border-bottom:1px solid #eee;">Title</th><th style="text-align:left;border-bottom:1px solid #eee;">Slug</th><th style="text-align:left;border-bottom:1px solid #eee;">Published</th><th style="text-align:left;border-bottom:1px solid #eee;">Actions</th></tr>
        </thead>
        <tbody>
          <?php foreach(($posts??[]) as $p): ?>
            <tr>
              <td style="padding:8px 6px;"><?= e($p['title']) ?></td>
              <td style="padding:8px 6px;">/post/<?= e($p['slug']) ?></td>
              <td style="padding:8px 6px;"><?= e($p['published_at'] ?: '—') ?></td>
              <td style="padding:8px 6px;">
                <a class="btn" href="/post/<?= e($p['slug']) ?>" target="_blank">View</a>
                <a class="btn" href="/admin/posts/<?= (int)$p['id'] ?>/edit">Edit</a>
                <a class="btn" href="/admin/posts/<?= (int)$p['id'] ?>/delete">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

