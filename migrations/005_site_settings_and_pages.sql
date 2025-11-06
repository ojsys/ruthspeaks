-- Add site settings and pages management tables

CREATE TABLE IF NOT EXISTS site_settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(100) NOT NULL UNIQUE,
  setting_value TEXT NULL,
  setting_type ENUM('text', 'textarea', 'image', 'boolean') NOT NULL DEFAULT 'text',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS pages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(160) NOT NULL UNIQUE,
  title VARCHAR(200) NOT NULL,
  content LONGTEXT NOT NULL,
  excerpt VARCHAR(300) NULL,
  featured_image VARCHAR(400) NULL,
  is_published TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_slug (slug),
  INDEX idx_published (is_published)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default settings
INSERT INTO site_settings (setting_key, setting_value, setting_type) VALUES
('site_logo', '', 'image'),
('site_favicon', '', 'image'),
('hero_image', 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?auto=format&fit=crop&w=800&q=80', 'image'),
('about_page_image', '', 'image'),
('site_tagline', 'Deep, delightful, and real faith. Honest posts on faith, growth, womanhood, and family — with grace that still works overtime.', 'textarea'),
('footer_text', 'Made with grace and laughter.', 'text'),
('contact_email', 'ruth@ruthspeakstruth.com.ng', 'text'),
('social_instagram', '', 'text'),
('social_twitter', '', 'text'),
('social_facebook', '', 'text')
ON DUPLICATE KEY UPDATE setting_key = setting_key;
