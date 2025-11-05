-- Seed sample content

INSERT INTO users (email, password_hash, name, role) VALUES
('ruth@example.com', '$2y$10$1kczvJRTWkH8a0t7BoRP3OaV7p9mKRA5Z9vaH6Jw1OJl5QmNl3i8W', 'Ruth', 'admin');

INSERT INTO categories (name, slug) VALUES
('Faith', 'faith'), ('Growth', 'growth'), ('Womanhood', 'womanhood'), ('Family', 'family');

INSERT INTO posts (slug, title, excerpt, content, cover_image, category_id, author_id, estimated_read_minutes, published_at)
VALUES (
  'deep-and-delightful',
  'Deep and Delightful: Grace at Work',
  'Life with Jesus is deep and delightfully unpredictable — here’s why grace still works overtime.',
  CONCAT(
    '<p class="scripture"><strong>Truth nugget:</strong> Grace is not a line item — it is the whole budget.</p>',
    '<p>Welcome to the truth zone — my truth, your truth, and God\'s truth all dancing together. If you\'re reading this with a smile, good — we\'re off to a great start. Today, I\'m staring at the ceiling and laughing because God has jokes — and still, He has me.</p>',
    '<h2>Faith in the little things</h2>',
    '<p>There\'s a quiet kind of boldness in washing dishes after a long day and saying, "Lord, even this is worship." If you\'re like me, you\'ve asked: "God, are You seeing this?" Yes. He is.</p>',
    '<p class="pull-quote">Grace is the rhythm under our chaos.</p>',
    '<h2>Growth looks ordinary</h2>',
    '<p>We want fireworks. God gives firewood. Slow, steady, faithful — and somehow, we wake up warmed. Keyword for today\'s giveaway is <em>grace</em>.</p>',
    '<p>Womanhood, family, and all our in-betweens — they\'re not side quests. They\'re the arena where love learns its lines.</p>',
    '<p>Thank you for reading — take your time, breathe, and maybe share this with a friend who needs a smile.</p>'
  ),
  'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=60',
  (SELECT id FROM categories WHERE slug='faith' LIMIT 1),
  (SELECT id FROM users WHERE email='ruth@example.com' LIMIT 1),
  5,
  NOW()
);

-- Optional giveaway for the post above
INSERT INTO giveaways (post_id, title, start_at, end_at, max_winners, rules_json)
VALUES (
  (SELECT id FROM posts WHERE slug='deep-and-delightful' LIMIT 1),
  'Grace Goodie',
  NOW(),
  DATE_ADD(NOW(), INTERVAL 30 DAY),
  100,
  '{"keyword":"grace"}'
);
