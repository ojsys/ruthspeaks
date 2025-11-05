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
3) Copy `.env.example` to `.env` and fill in values (DB creds, admin email/hash, site/base URL, ads, thresholds). `.env` is git-ignored.
4) Option A (recommended): Upload `public/` to `public_html/` and keep `app/`, `views/`, `migrations/`, `.env` one level above web root.
5) Option B (easy Git): Clone this repo directly into `public_html/`. The root `.htaccess` provided will:
   - Block access to sensitive folders/files (`app/`, `views/`, `migrations/`, `.env`, etc.)
   - Rewrite requests to `/public/index.php`
   - Serve `/public/assets/...` transparently

Routes
- /                    Home (recent posts)
- /post/{slug}         Post detail with reading progress + giveaway
- /api/track           POST JSON (progress, timeSpentMs)
- /api/unlock          POST JSON (email, postId, anonId, optional keyword)
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
- Secrets and environment: configuration reads from `.env` (see `.env.example`).

Customization
- Colors, fonts: see `public/assets/css/styles.css`.
- Ad slots: see `views/partials/ad_slot.php` and usage in templates.
