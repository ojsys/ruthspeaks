<?php
declare(strict_types=1);

namespace App;

require_once __DIR__ . '/Env.php';
Env::load(defined('BASE_PATH') ? BASE_PATH . '/.env' : null);

// Database
define(__NAMESPACE__ . '\\DB_HOST', Env::get('DB_HOST', 'localhost'));
define(__NAMESPACE__ . '\\DB_NAME', Env::get('DB_NAME', 'your_db_name'));
define(__NAMESPACE__ . '\\DB_USER', Env::get('DB_USER', 'your_db_user'));
define(__NAMESPACE__ . '\\DB_PASS', Env::get('DB_PASS', 'your_db_pass'));
define(__NAMESPACE__ . '\\DB_CHARSET', Env::get('DB_CHARSET', 'utf8mb4'));

// Site
define(__NAMESPACE__ . '\\SITE_NAME', Env::get('SITE_NAME', 'RuthSpeaksTruth'));
define(__NAMESPACE__ . '\\SITE_BASE_URL', Env::get('SITE_BASE_URL', ''));

// Giveaway thresholds
define(__NAMESPACE__ . '\\GIVEAWAY_PROGRESS_THRESHOLD', Env::int('GIVEAWAY_PROGRESS_THRESHOLD', 75));
define(__NAMESPACE__ . '\\GIVEAWAY_MIN_TIME_FACTOR', (float)Env::get('GIVEAWAY_MIN_TIME_FACTOR', 0.4));
define(__NAMESPACE__ . '\\GIVEAWAY_MIN_TIME_FLOOR_MS', Env::int('GIVEAWAY_MIN_TIME_FLOOR_MS', 45000));

// Ads
define(__NAMESPACE__ . '\\ADS_ENABLED', Env::bool('ADS_ENABLED', true));
define(__NAMESPACE__ . '\\AD_HEAD_SNIPPET', Env::get('AD_HEAD_SNIPPET', ''));
define(__NAMESPACE__ . '\\AD_LEADERBOARD_HTML', Env::get('AD_LEADERBOARD_HTML', ''));
define(__NAMESPACE__ . '\\AD_IN_ARTICLE_HTML', Env::get('AD_IN_ARTICLE_HTML', ''));
define(__NAMESPACE__ . '\\AD_SIDEBAR_HTML', Env::get('AD_SIDEBAR_HTML', ''));
define(__NAMESPACE__ . '\\AD_FOOTER_HTML', Env::get('AD_FOOTER_HTML', ''));

// Admin stub credentials
define(__NAMESPACE__ . '\\ADMIN_EMAIL', Env::get('ADMIN_EMAIL', 'admin@example.com'));
define(__NAMESPACE__ . '\\ADMIN_PASSWORD_HASH', Env::get('ADMIN_PASSWORD_HASH', '$2y$10$obA0g2rBHmVDEjXW5xYoUOAzxS3hND8q8PfYH4c51wF9G6djyx9wG'));
