<?php use function App\e; ?>
<main class="contact-page">
  <div class="container">
    <div class="contact-hero">
      <div class="contact-hero-content">
        <span class="contact-badge">💬 Let's Connect</span>
        <h1>Let's Talk!</h1>
        <p>Whether you have a question, a prayer request, or just want to say hi — I'd love to hear from you. Your story matters, and I'm here to listen.</p>
      </div>
      <div class="contact-decorations">
        <div class="decoration-circle circle-1"></div>
        <div class="decoration-circle circle-2"></div>
        <div class="decoration-circle circle-3"></div>
      </div>
    </div>

    <div class="contact-content">
      <div class="contact-form-wrapper">
        <div class="contact-form-intro">
          <h2>Drop Me a Message</h2>
          <p>Fill out the form below and I'll get back to you as soon as I can. Usually within 24-48 hours!</p>
        </div>

        <form id="contact-form" class="contact-form">
          <div class="form-group">
            <label for="contact-name">Your Name</label>
            <input type="text" id="contact-name" name="name" class="input" placeholder="What should I call you?" required />
          </div>

          <div class="form-group">
            <label for="contact-email">Your Email</label>
            <input type="email" id="contact-email" name="email" class="input" placeholder="your@email.com" required />
          </div>

          <div class="form-group">
            <label for="contact-message">Your Message</label>
            <textarea id="contact-message" name="message" class="textarea" rows="6" placeholder="What's on your heart? I'm all ears..." required></textarea>
          </div>

          <button type="submit" class="btn primary btn-large" id="contact-submit">
            <span>Send Message</span>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/>
            </svg>
          </button>

          <div id="contact-result" class="contact-result"></div>
        </form>
      </div>

      <div class="contact-info">
        <div class="info-card">
          <div class="info-icon">📧</div>
          <h3>Email Me</h3>
          <p>ruth@ruthspeakstruth.com.ng</p>
          <p class="info-note">I check my inbox daily!</p>
        </div>

        <div class="info-card">
          <div class="info-icon">🙏</div>
          <h3>Prayer Requests</h3>
          <p>Need prayer? I'd be honored to pray for you. Share your request and I'll lift it up.</p>
        </div>

        <div class="info-card">
          <div class="info-icon">💡</div>
          <h3>Collaboration</h3>
          <p>Interested in working together? Let's chat about opportunities to partner in ministry.</p>
        </div>

        <div class="info-card social-card">
          <div class="info-icon">✨</div>
          <h3>Connect Online</h3>
          <p>Find me on social media for daily encouragement and behind-the-scenes glimpses.</p>
          <div class="social-links">
            <a href="#" class="social-btn">Instagram</a>
            <a href="#" class="social-btn">Twitter</a>
            <a href="#" class="social-btn">Facebook</a>
          </div>
        </div>
      </div>
    </div>

    <div class="contact-faq">
      <h2>Quick Questions</h2>
      <div class="faq-grid">
        <div class="faq-item">
          <h4>📖 Can I share your posts?</h4>
          <p>Absolutely! Please share with credit. Let's spread encouragement together.</p>
        </div>
        <div class="faq-item">
          <h4>✍️ Do you accept guest posts?</h4>
          <p>Sometimes! Send me your pitch and we'll see if it's a good fit.</p>
        </div>
        <div class="faq-item">
          <h4>💬 Can we meet for coffee?</h4>
          <p>I'd love that! If you're local, let's set something up.</p>
        </div>
        <div class="faq-item">
          <h4>🎤 Speaking engagements?</h4>
          <p>I occasionally speak at events. Let's talk about your needs!</p>
        </div>
      </div>
    </div>
  </div>
</main>

<style>
.contact-page {
  background: var(--bg-secondary);
}

.contact-hero {
  position: relative;
  padding: 80px 0;
  text-align: center;
  overflow: hidden;
}

.contact-hero-content {
  position: relative;
  z-index: 2;
  max-width: 720px;
  margin: 0 auto;
}

.contact-badge {
  display: inline-block;
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
  color: #fff;
  font-size: 0.875rem;
  font-weight: 700;
  padding: 10px 20px;
  border-radius: 999px;
  margin-bottom: 24px;
  box-shadow: 0 4px 12px rgba(37,99,235,0.25);
}

.contact-hero h1 {
  font-size: 3.5rem;
  font-weight: 900;
  margin: 0 0 24px;
  letter-spacing: -0.04em;
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.contact-hero p {
  font-size: 1.25rem;
  color: var(--text-secondary);
  line-height: 1.7;
  margin: 0;
}

.contact-decorations {
  position: absolute;
  inset: 0;
  pointer-events: none;
  z-index: 1;
}

.decoration-circle {
  position: absolute;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(37,99,235,0.1) 0%, transparent 70%);
}

.circle-1 {
  width: 300px;
  height: 300px;
  top: -50px;
  left: -50px;
  animation: float 8s ease-in-out infinite;
}

.circle-2 {
  width: 200px;
  height: 200px;
  bottom: -30px;
  right: 10%;
  animation: float 6s ease-in-out infinite reverse;
}

.circle-3 {
  width: 150px;
  height: 150px;
  top: 20%;
  right: -30px;
  animation: float 10s ease-in-out infinite;
}

@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-20px); }
}

.contact-content {
  display: grid;
  grid-template-columns: 1.5fr 1fr;
  gap: 56px;
  margin-top: 40px;
}

.contact-form-wrapper {
  background: var(--bg-primary);
  border-radius: var(--radius-xl);
  padding: 48px;
  box-shadow: var(--shadow-lg);
  border: 1px solid var(--border);
}

.contact-form-intro h2 {
  font-size: 2rem;
  font-weight: 800;
  margin: 0 0 12px;
  color: var(--text-primary);
}

.contact-form-intro p {
  color: var(--text-secondary);
  margin: 0 0 32px;
}

.form-group {
  margin-bottom: 24px;
}

.form-group label {
  display: block;
  font-weight: 600;
  margin-bottom: 8px;
  color: var(--text-primary);
  font-size: 0.9375rem;
}

.textarea {
  width: 100%;
  padding: 14px 18px;
  border-radius: var(--radius-md);
  border: 1.5px solid var(--border);
  background: var(--bg-primary);
  color: var(--text-primary);
  font-size: 0.9375rem;
  font-family: inherit;
  resize: vertical;
  transition: all 0.2s ease;
}

.textarea:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 4px rgba(37,99,235,0.1);
}

.btn-large {
  width: 100%;
  padding: 16px 32px;
  font-size: 1rem;
  justify-content: center;
}

.btn-large svg {
  transition: transform 0.2s ease;
}

.btn-large:hover svg {
  transform: translateX(4px);
}

.contact-result {
  margin-top: 16px;
  padding: 12px;
  border-radius: var(--radius-sm);
  text-align: center;
  font-weight: 500;
  display: none;
}

.contact-result.success {
  background: #D1FAE5;
  color: #065F46;
  display: block;
}

.contact-result.error {
  background: #FEE2E2;
  color: #991B1B;
  display: block;
}

.contact-info {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.info-card {
  background: var(--bg-primary);
  border-radius: var(--radius-lg);
  padding: 28px;
  box-shadow: var(--shadow-sm);
  border: 1px solid var(--border);
  transition: all 0.3s ease;
}

.info-card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-md);
}

.info-icon {
  font-size: 2.5rem;
  margin-bottom: 16px;
}

.info-card h3 {
  font-size: 1.25rem;
  font-weight: 700;
  margin: 0 0 8px;
  color: var(--text-primary);
}

.info-card p {
  color: var(--text-secondary);
  margin: 0;
  line-height: 1.6;
  font-size: 0.9375rem;
}

.info-note {
  margin-top: 8px;
  font-size: 0.875rem;
  color: var(--text-muted);
  font-style: italic;
}

.social-links {
  display: flex;
  gap: 8px;
  margin-top: 16px;
  flex-wrap: wrap;
}

.social-btn {
  padding: 8px 16px;
  background: var(--bg-tertiary);
  color: var(--text-primary);
  text-decoration: none;
  border-radius: var(--radius-sm);
  font-size: 0.875rem;
  font-weight: 600;
  transition: all 0.2s ease;
}

.social-btn:hover {
  background: var(--primary);
  color: #fff;
  transform: translateY(-2px);
}

.contact-faq {
  margin-top: 80px;
  text-align: center;
}

.contact-faq h2 {
  font-size: 2rem;
  font-weight: 800;
  margin: 0 0 40px;
  color: var(--text-primary);
}

.faq-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 24px;
}

.faq-item {
  background: var(--bg-primary);
  border-radius: var(--radius-lg);
  padding: 28px;
  box-shadow: var(--shadow-sm);
  border: 1px solid var(--border);
  text-align: left;
  transition: all 0.3s ease;
}

.faq-item:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-md);
  border-color: var(--primary);
}

.faq-item h4 {
  font-size: 1.125rem;
  font-weight: 700;
  margin: 0 0 12px;
  color: var(--text-primary);
}

.faq-item p {
  color: var(--text-secondary);
  margin: 0;
  line-height: 1.6;
}

@media (max-width: 1024px) {
  .contact-content {
    grid-template-columns: 1fr;
    gap: 40px;
  }
}

@media (max-width: 768px) {
  .contact-hero {
    padding: 60px 0;
  }

  .contact-hero h1 {
    font-size: 2.5rem;
  }

  .contact-hero p {
    font-size: 1.125rem;
  }

  .contact-form-wrapper {
    padding: 32px 24px;
  }

  .faq-grid {
    grid-template-columns: 1fr;
  }
}
</style>

<script>
(function() {
  var form = document.getElementById('contact-form');
  var result = document.getElementById('contact-result');
  var submitBtn = document.getElementById('contact-submit');

  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();

      var formData = new FormData(form);
      submitBtn.disabled = true;
      submitBtn.querySelector('span').textContent = 'Sending...';
      result.className = 'contact-result';
      result.style.display = 'none';

      fetch('/contact/submit', {
        method: 'POST',
        body: formData
      })
      .then(function(response) { return response.json(); })
      .then(function(data) {
        result.textContent = data.message;
        result.className = 'contact-result ' + (data.ok ? 'success' : 'error');
        result.style.display = 'block';

        if (data.ok) {
          form.reset();
        }

        submitBtn.disabled = false;
        submitBtn.querySelector('span').textContent = 'Send Message';
      })
      .catch(function(error) {
        result.textContent = 'Something went wrong. Please try again.';
        result.className = 'contact-result error';
        result.style.display = 'block';
        submitBtn.disabled = false;
        submitBtn.querySelector('span').textContent = 'Send Message';
      });
    });
  }
})();
</script>
