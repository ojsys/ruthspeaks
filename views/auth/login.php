<?php
use function App\e;
use function App\csrf_field;
?>
<div class="auth-container">
  <div class="auth-card">
    <div class="auth-header">
      <h1>Welcome Back</h1>
      <p>Sign in to your account</p>
    </div>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-error">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?= e($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST" action="/login" class="auth-form">
      <?= csrf_field() ?>

      <div class="form-group">
        <label for="email">Email or Username</label>
        <input
          type="text"
          class="input"
          id="email"
          name="email"
          value="<?= e($email ?? '') ?>"
          placeholder="Enter your email or username"
          required
          autofocus
        />
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input
          type="password"
          class="input"
          id="password"
          name="password"
          placeholder="Enter your password"
          required
        />
      </div>

      <div class="form-group-row">
        <label class="checkbox-label">
          <input type="checkbox" name="remember" />
          <span>Remember me</span>
        </label>
        <a href="/forgot-password" class="link-text">Forgot password?</a>
      </div>

      <button type="submit" class="btn btn-primary btn-block">Sign In</button>
    </form>

    <div class="auth-footer">
      <p>Don't have an account? <a href="/register" class="link-primary">Sign up</a></p>
    </div>
  </div>
</div>

<style>
.auth-container {
  min-height: calc(100vh - 400px);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem 1rem;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.auth-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  max-width: 440px;
  width: 100%;
  padding: 3rem 2.5rem;
}

body.theme-dark .auth-card {
  background: #1e293b;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
}

.auth-header {
  text-align: center;
  margin-bottom: 2rem;
}

.auth-header h1 {
  font-size: 2rem;
  font-weight: 700;
  color: #1e293b;
  margin: 0 0 0.5rem;
}

body.theme-dark .auth-header h1 {
  color: #f1f5f9;
}

.auth-header p {
  color: #64748b;
  font-size: 1rem;
  margin: 0;
}

body.theme-dark .auth-header p {
  color: #94a3b8;
}

.auth-form {
  margin-bottom: 1.5rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #334155;
  font-size: 0.95rem;
}

body.theme-dark .form-group label {
  color: #cbd5e1;
}

.form-group-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  font-size: 0.9rem;
  color: #475569;
}

body.theme-dark .checkbox-label {
  color: #94a3b8;
}

.checkbox-label input[type="checkbox"] {
  cursor: pointer;
}

.link-text {
  color: #2563eb;
  text-decoration: none;
  font-size: 0.9rem;
  transition: color 0.2s;
}

.link-text:hover {
  color: #1d4ed8;
}

body.theme-dark .link-text {
  color: #60a5fa;
}

body.theme-dark .link-text:hover {
  color: #93c5fd;
}

.btn-block {
  width: 100%;
  padding: 0.875rem;
  font-size: 1rem;
  font-weight: 600;
}

.auth-footer {
  text-align: center;
  padding-top: 1.5rem;
  border-top: 1px solid #e2e8f0;
}

body.theme-dark .auth-footer {
  border-top-color: #334155;
}

.auth-footer p {
  margin: 0;
  color: #64748b;
  font-size: 0.95rem;
}

body.theme-dark .auth-footer p {
  color: #94a3b8;
}

.link-primary {
  color: #2563eb;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.2s;
}

.link-primary:hover {
  color: #1d4ed8;
}

body.theme-dark .link-primary {
  color: #60a5fa;
}

body.theme-dark .link-primary:hover {
  color: #93c5fd;
}

.alert {
  padding: 1rem;
  border-radius: 8px;
  margin-bottom: 1.5rem;
}

.alert-error {
  background-color: #fef2f2;
  border: 1px solid #fecaca;
  color: #991b1b;
}

body.theme-dark .alert-error {
  background-color: #7f1d1d;
  border-color: #991b1b;
  color: #fecaca;
}

.alert ul {
  margin: 0;
  padding-left: 1.25rem;
}

.alert li {
  margin: 0.25rem 0;
}

@media (max-width: 640px) {
  .auth-card {
    padding: 2rem 1.5rem;
  }

  .auth-header h1 {
    font-size: 1.75rem;
  }

  .form-group-row {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.75rem;
  }
}
</style>
