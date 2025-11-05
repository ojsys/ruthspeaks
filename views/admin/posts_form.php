<?php use function App\e; use function App\csrf_field; ?>
<main>
  <div class="container">
    <div class="content">
      <h2><?= $post ? 'Edit Post' : 'New Post' ?></h2>
      <form method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <label>Title</label>
        <input class="input" type="text" name="title" required value="<?= e($post['title'] ?? '') ?>" />
        <label style="margin-top:8px;">Slug</label>
        <input class="input" type="text" name="slug" value="<?= e($post['slug'] ?? '') ?>" />
        <label style="margin-top:8px;">Excerpt</label>
        <textarea class="input" name="excerpt" rows="2"><?= e($post['excerpt'] ?? '') ?></textarea>
        <label style="margin-top:8px;">Cover Image (upload or URL)</label>
        <input type="file" name="cover_image" accept="image/*" />
        <input class="input" type="url" placeholder="https://..." name="cover_image_url" value="<?= e($post['cover_image'] ?? '') ?>" />
        <label style="margin-top:8px;">Category</label>
        <select class="input" name="category_id">
          <option value="">—</option>
          <?php foreach(($categories??[]) as $c): $sel = isset($post['category_id']) && (int)$post['category_id']===(int)$c['id']; ?>
            <option value="<?= (int)$c['id'] ?>" <?= $sel?'selected':'' ?>><?= e($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
        <label style="margin-top:8px;">Tags (comma separated)</label>
        <input class="input" type="text" name="tags" value="<?= e($tags ?? '') ?>" />
        <label style="margin-top:8px;">Estimated Read Minutes</label>
        <input class="input" type="number" min="1" name="estimated_read_minutes" value="<?= e((string)($post['estimated_read_minutes'] ?? 4)) ?>" />
        <label style="margin-top:8px;">Published At (YYYY-MM-DD HH:MM:SS or blank)</label>
        <input class="input" type="text" name="published_at" value="<?= e($post['published_at'] ?? '') ?>" />

        <label style="margin-top:12px;">Content</label>
        <div id="editor-toolbar" style="display:flex;gap:6px;margin-bottom:6px;">
          <button class="btn" type="button" data-cmd="bold"><b>B</b></button>
          <button class="btn" type="button" data-cmd="italic"><i>I</i></button>
          <button class="btn" type="button" data-cmd="formatBlock" data-val="h2">H2</button>
          <button class="btn" type="button" data-cmd="formatBlock" data-val="blockquote">Quote</button>
          <button class="btn" type="button" id="insert-link">Link</button>
          <label class="btn" style="cursor:pointer;">
            Upload Image<input type="file" id="inline-image" style="display:none;" accept="image/*"/>
          </label>
        </div>
        <div id="editor" contenteditable="true" style="min-height:260px;border:1px solid #ddd;border-radius:10px;padding:10px;background:#fff;">
          <?= $post['content'] ?? '' ?>
        </div>
        <textarea name="content" id="content-field" style="display:none;"></textarea>

        <div style="margin-top:12px;display:flex;gap:10px;">
          <button class="btn primary" type="submit">Save</button>
          <a class="btn" href="/admin/posts">Cancel</a>
        </div>
      </form>
    </div>
  </div>
  <script>
    (function(){
      var toolbar = document.getElementById('editor-toolbar');
      var editor = document.getElementById('editor');
      var contentField = document.getElementById('content-field');
      toolbar.addEventListener('click', function(e){
        var btn = e.target.closest('button'); if (!btn) return;
        var cmd = btn.getAttribute('data-cmd');
        var val = btn.getAttribute('data-val') || null;
        if (cmd) document.execCommand(cmd, false, val);
      });
      document.getElementById('insert-link').addEventListener('click', function(){
        var url = prompt('Enter URL'); if (!url) return;
        document.execCommand('createLink', false, url);
      });
      var inline = document.getElementById('inline-image');
      inline.addEventListener('change', function(){
        var f = inline.files[0]; if (!f) return;
        var fd = new FormData(); fd.append('file', f);
        fetch('/api/upload', { method:'POST', body: fd }).then(r=>r.json()).then(function(res){
          if (res.ok && res.url){
            var img = document.createElement('img'); img.src = res.url; img.style.maxWidth='100%';
            editor.appendChild(img);
          } else { alert(res.message||'Upload failed'); }
        }).catch(function(){ alert('Upload failed'); });
      });
      // On submit, copy editor HTML to hidden field
      document.querySelector('form').addEventListener('submit', function(){
        contentField.value = editor.innerHTML;
      });
    })();
  </script>
</main>
