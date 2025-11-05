RuthSpeaksTruth – Vanilla PHP Blog CMS (cPanel-ready)

Overview
- Vanilla PHP blog with simple CMS stubs, giveaway unlock system, and ad slots.
- Designed for cPanel (Apache + MySQL). No Composer dependencies required.

Key Features
- Posts, categories, tags (DB schema + public views)
- Reading progress + time-on-page tracking
- Giveaway unlock (scroll-depth + engaged time + optional keyword)
- Email capture on claim with unique code generation
- Ad slots (leaderboard, in-article, sidebar, footer)
- SEO basics: meta tags, sitemap.xml, rss.xml
- Lightweight admin stubs (login/dashboard placeholder)

Structure
- public/          -> Web root (upload to cPanel public_html)
- app/             -> PHP app code (controllers, models, db, helpers)
- views/           -> Templates
- migrations/      -> MySQL schema and seed examples

Quick Start (cPanel)
1) Create a MySQL database and user in cPanel.
2) Import `migrations/001_schema.sql` (and optionally `002_seed.sql`).
3) Edit `app/Config.php` with your DB credentials and site settings.
4) Upload contents of `public/` into `public_html/`.
5) Upload `app/`, `views/`, and `migrations/` alongside `public_html/` or into a protected folder.
   - If you must keep everything under public_html, add a `.htaccess` to deny access to `app/` and `migrations/`.

Routes
- /                    Home (recent posts)
- /post/{slug}         Post detail with reading progress + giveaway
- /api/track           POST JSON (progress, timeSpentMs)
- /api/unlock          POST JSON (email, postId, anonId, optional keyword)
- /rss.xml             RSS feed
- /sitemap.xml         Sitemap
- /admin/login         Admin login (stub)
- /admin               Admin dashboard (stub)

Giveaway Logic (configurable in Config.php)
- Requires both: scroll-depth threshold and engaged time threshold.
- Engaged time derives from each post’s `estimated_read_minutes`.
- Server validates and issues unique claim codes while stock lasts.

Security Notes
- Uses PDO with prepared statements.
- Basic session auth for admin stubs.
- CSRF protection added where forms are present; API expects same-origin calls.

Customization
- Colors, fonts: see `public/assets/css/styles.css`.
- Ad slots: see `views/partials/ad_slot.php` and usage in templates.

