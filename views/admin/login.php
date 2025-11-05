<?php use function App\e; ?>
<main>
  <div class="container" style="max-width:420px;">
    <div class="content">
      <h2>Admin Login</h2>
      <?php if (!empty($error)): ?>
        <div style="color:#b00020;margin-bottom:10px;"><?= e($error) ?></div>
      <?php endif; ?>
      <form method="post" action="/admin/login">
        <label>Email</label>
        <input class="input" type="email" name="email" required />
        <label style="margin-top:8px;">Password</label>
        <input class="input" type="password" name="password" required />
        <div style="margin-top:12px;display:flex;gap:10px;">
          <button class="btn primary" type="submit">Login</button>
          <a class="btn" href="/">Back</a>
        </div>
      </form>
    </div>
  </div>
</main>

