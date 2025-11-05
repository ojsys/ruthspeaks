<?php
declare(strict_types=1);

namespace App;

// Update these for your cPanel MySQL
const DB_HOST = 'localhost';
const DB_NAME = 'your_db_name';
const DB_USER = 'your_db_user';
const DB_PASS = 'your_db_pass';
const DB_CHARSET = 'utf8mb4';

// Site
const SITE_NAME = 'RuthSpeaksTruth';
const SITE_BASE_URL = ''; // e.g., 'https://ruthspeakstruth.com' (optional but used in RSS/Sitemap)

// Giveaway thresholds
const GIVEAWAY_PROGRESS_THRESHOLD = 75; // percent
const GIVEAWAY_MIN_TIME_FACTOR = 0.4;   // of estimated_read_minutes
const GIVEAWAY_MIN_TIME_FLOOR_MS = 45000; // 45s minimum floor

// Ads
const ADS_ENABLED = true;
// Paste your ad provider snippets here (optional). If empty, placeholders render.
const AD_HEAD_SNIPPET = '';
const AD_LEADERBOARD_HTML = '';
const AD_IN_ARTICLE_HTML = '';
const AD_SIDEBAR_HTML = '';
const AD_FOOTER_HTML = '';

// Admin stub credentials (create proper users later)
// Set these in production via environment or update here securely
const ADMIN_EMAIL = 'admin@example.com';
// Password: change_me_please
const ADMIN_PASSWORD_HASH = '$2y$10$obA0g2rBHmVDEjXW5xYoUOAzxS3hND8q8PfYH4c51wF9G6djyx9wG';
