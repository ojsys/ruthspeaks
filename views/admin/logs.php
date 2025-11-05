<main>
  <div class="container">
    <div class="content">
      <h2>Logs</h2>
      <p>Newest entries at the bottom. Only the last 200 lines are shown.</p>
      <h3>App Log (storage/logs/app.log)</h3>
      <pre style="white-space:pre-wrap; background:#0b0b0b; color:#d8d8d8; padding:12px; border-radius:8px; max-height:320px; overflow:auto; border:1px solid #222;"><?= htmlspecialchars($app ?? '(missing)') ?></pre>
      <h3>Errors Log (storage/logs/errors.log)</h3>
      <pre style="white-space:pre-wrap; background:#0b0b0b; color:#d8d8d8; padding:12px; border-radius:8px; max-height:320px; overflow:auto; border:1px solid #222;"><?= htmlspecialchars($errs ?? '(missing)') ?></pre>
      <p><a class="btn" href="/admin">Back</a></p>
    </div>
  </div>
</main>
