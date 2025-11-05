<?php use function App\e; use function App\csrf_field; ?>
<main>
  <div class="container">
    <div class="content">
      <h2><?= $type === 'tag' ? 'Tags' : 'Categories' ?></h2>
      <form method="post" style="margin-bottom:10px;display:flex;gap:8px;align-items:flex-end;">
        <?= csrf_field() ?>
        <div style="flex:1;">
          <label>Name</label>
          <input class="input" type="text" name="name" required />
        </div>
        <div style="flex:1;">
          <label>Slug</label>
          <input class="input" type="text" name="slug" placeholder="auto" />
        </div>
        <input type="hidden" name="create" value="1" />
        <button class="btn primary" type="submit">Add</button>
        <a class="btn" href="/admin">Back</a>
      </form>
      <table style="width:100%;border-collapse:collapse;">
        <thead>
        <tr><th style="text-align:left;border-bottom:1px solid #eee;">Name</th><th style="text-align:left;border-bottom:1px solid #eee;">Slug</th><th style="text-align:left;border-bottom:1px solid #eee;">Actions</th></tr>
        </thead>
        <tbody>
        <?php foreach(($items??[]) as $it): ?>
          <tr>
            <td style="padding:6px;">
              <form method="post" style="display:flex;gap:8px;align-items:center;">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= (int)$it['id'] ?>" />
                <input class="input" name="name" value="<?= e($it['name']) ?>" />
                <input class="input" name="slug" value="<?= e($it['slug']) ?>" />
                <button class="btn" name="update" value="1">Save</button>
                <button class="btn" name="delete" value="1" onclick="return confirm('Delete?')">Delete</button>
              </form>
            </td>
            <td></td>
            <td></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

