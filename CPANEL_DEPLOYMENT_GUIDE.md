# cPanel Deployment Guide for RuthSpeaksTruth

## Issues Fixed

### 1. Critical Bug: Duplicate Code in index.php
- **Status**: FIXED ✓
- **Issue**: The `public/index.php` file contained 4 copies of the same code (lines 95-410)
- **Impact**: Could cause parse errors or unexpected behavior
- **Fix**: Removed duplicate code blocks

### 2. Missing .env File
- **Status**: FIXED ✓
- **Issue**: No `.env` file existed, causing configuration loading failures
- **Impact**: Application cannot read database credentials and other settings
- **Fix**: Created `.env` from `.env.example` template

---

## cPanel Deployment Options

You have **two deployment options** for cPanel:

### Option A: Secure (Recommended)
Upload only `public/` folder contents to `public_html/`, keep sensitive files outside web root.

### Option B: Simple (Git-friendly)
Upload entire project to `public_html/`, let `.htaccess` protect sensitive files.

---

## Option A: Secure Deployment (Recommended)

### Step 1: Directory Structure
```
/home/username/
├── ruthspeaks/           # Outside web root (not publicly accessible)
│   ├── app/
│   ├── views/
│   ├── migrations/
│   ├── storage/
│   ├── .env            # Your configuration file
│   └── .htaccess
└── public_html/          # Web root (publicly accessible)
    ├── index.php
    ├── .htaccess
    ├── assets/
    ├── uploads/
    ├── robots.txt
    └── 500.html
```

### Step 2: Upload Files

1. **Via cPanel File Manager:**
   - Create folder: `/home/username/ruthspeaks/`
   - Upload `app/`, `views/`, `migrations/`, `storage/` to `/home/username/ruthspeaks/`
   - Upload `.env` and root `.htaccess` to `/home/username/ruthspeaks/`
   - Upload contents of `public/` folder to `/home/username/public_html/`

2. **Via FTP/SFTP:**
   ```bash
   # Upload structure:
   /home/username/ruthspeaks/     ← app, views, migrations, storage, .env
   /home/username/public_html/    ← contents of public/ folder
   ```

### Step 3: Update BASE_PATH in index.php

Edit `/home/username/public_html/index.php`:

```php
// Find line 20:
define('BASE_PATH', $__BASE_PATH);

// Change to:
define('BASE_PATH', '/home/username/ruthspeaks');
```

Also update line 5:
```php
// Find line 5:
$__BASE_PATH = dirname(__DIR__);

// Change to:
$__BASE_PATH = '/home/username/ruthspeaks';
```

**Important**: Replace `username` with your actual cPanel username!

---

## Option B: Simple Deployment (Git-friendly)

### Step 1: Upload Everything

Upload the **entire project** to `public_html/`:

```
/home/username/public_html/
├── app/
├── views/
├── migrations/
├── storage/
├── public/
├── .env
├── .htaccess          # Root .htaccess protects sensitive files
└── README.md
```

### Step 2: Verify Root .htaccess

The root `.htaccess` file should contain security rules (already in place):

```apache
RewriteEngine On
RewriteBase /

# Block sensitive paths
RewriteRule ^(?:app|views|migrations|storage)(?:/|$) - [F,L]
RewriteRule ^\.env - [F,L]
RewriteRule \.sql$ - [F,L]

# Route to public/index.php
RewriteCond %{REQUEST_URI} !^/public/
RewriteRule ^ public/index.php [L]
```

### Step 3: No Code Changes Needed

With this option, `BASE_PATH` automatically works as `dirname(__DIR__)` points to the project root.

---

## Configuration: .env File Setup

### Step 1: Edit .env File

**Location**: `/home/username/ruthspeaks/.env` (Option A) or `/home/username/public_html/.env` (Option B)

Edit the file and update these values:

```bash
# General - SET TO FALSE FOR PRODUCTION!
APP_DEBUG=false

# Database - Get these from cPanel > MySQL Databases
DB_HOST=localhost              # Usually 'localhost' on cPanel
DB_NAME=username_ruthspeaks    # Your database name (usually prefixed with cpanel username)
DB_USER=username_ruthspeaks    # Your database user
DB_PASS=your_secure_password   # Your database password
DB_CHARSET=utf8mb4

# Site
SITE_NAME=RuthSpeaksTruth
SITE_BASE_URL=https://ruthspeakstruth.com.ng   # Your domain (with https://)

# Admin Login Credentials
ADMIN_EMAIL=ruth@ruthspeakstruth.com.ng
# Generate hash: See "Generate Password Hash" section below
ADMIN_PASSWORD_HASH=$2y$10$1kczvJRTWkH8a0t7BoRP3OaV7p9mKRA5Z9vaH6Jw1OJl5QmNl3i8W

# Giveaways (optional - defaults are fine)
GIVEAWAY_PROGRESS_THRESHOLD=75
GIVEAWAY_MIN_TIME_FACTOR=0.4
GIVEAWAY_MIN_TIME_FLOOR_MS=45000

# Ads (optional)
ADS_ENABLED=true
AD_HEAD_SNIPPET=
AD_LEADERBOARD_HTML=
AD_IN_ARTICLE_HTML=
AD_SIDEBAR_HTML=
AD_FOOTER_HTML=
```

### Step 2: Get Database Credentials from cPanel

1. Log into cPanel
2. Go to **MySQL Databases**
3. Note your:
   - Database name (format: `cpaneluser_dbname`)
   - Database user (format: `cpaneluser_dbuser`)
   - Database password (you set this when creating the user)

---

## Database Setup

### Step 1: Create Database in cPanel

1. Go to **cPanel > MySQL Databases**
2. Create new database: `ruthspeaks` (actual name will be `username_ruthspeaks`)
3. Create new user with secure password
4. Add user to database with **ALL PRIVILEGES**

### Step 2: Import Schema

1. Go to **cPanel > phpMyAdmin**
2. Select your database (`username_ruthspeaks`)
3. Click **Import** tab
4. Upload and execute: `migrations/001_schema.sql`
5. Upload and execute: `migrations/002_seed.sql` (optional - sample content)

---

## File Permissions Setup

### Required Permissions

```bash
# Directories
chmod 755 app/
chmod 755 views/
chmod 755 public/
chmod 755 public/assets/
chmod 777 public/uploads/     # Needs write access
chmod 777 storage/
chmod 777 storage/logs/        # Needs write access

# Files
chmod 644 .env                 # Protect configuration
chmod 644 public/index.php
chmod 644 public/.htaccess
chmod 644 .htaccess
```

### Set Permissions in cPanel

1. Go to **File Manager**
2. Right-click folder → **Change Permissions**
3. Set as shown above

**Critical**: `public/uploads/` and `storage/logs/` MUST be writable (777 or 755 with correct ownership)

---

## PHP Requirements

### Minimum Requirements

- **PHP Version**: 7.2 or higher (PHP 8.0+ recommended)
- **Required Extensions**:
  - PDO
  - PDO_MySQL
  - mbstring
  - session
  - json

### Set PHP Version in cPanel

1. Go to **cPanel > Select PHP Version** (or **MultiPHP Manager**)
2. Select **PHP 7.4** or **PHP 8.0+**
3. Enable required extensions (check boxes for PDO, PDO_MySQL, mbstring)

### Verify PHP Version

Create `public/test.php`:
```php
<?php phpinfo(); ?>
```

Visit: `https://yourdomain.com/test.php`

**DELETE THIS FILE AFTER CHECKING!**

---

## Generate Admin Password Hash

You need to generate a password hash for your admin login.

### Method 1: Using PHP CLI (on your local machine)

```bash
php -r "echo password_hash('your_password_here', PASSWORD_BCRYPT);"
```

### Method 2: Using a PHP File on cPanel

1. Create `public/gen-hash.php`:
```php
<?php
$password = 'your_desired_password';  // Change this!
echo password_hash($password, PASSWORD_BCRYPT);
?>
```

2. Visit: `https://yourdomain.com/gen-hash.php`
3. Copy the hash output
4. Paste into `.env` file as `ADMIN_PASSWORD_HASH=...`
5. **DELETE `gen-hash.php` IMMEDIATELY AFTER USE!**

### Method 3: Use the Seed Data Default

The seed data (002_seed.sql) includes a default admin user:
- Email: `ruth@ruthspeakstruth.com.ng`
- Password: `password` (hash: `$2y$10$1kczvJRTWkH8a0t7BoRP3OaV7p9mKRA5Z9vaH6Jw1OJl5QmNl3i8W`)

**Change this immediately in production!**

---

## Troubleshooting 500 Errors

### 1. Check Error Logs

**cPanel Error Log**:
- Go to **cPanel > Errors**
- Or check: `~/public_html/error_log`

**Application Error Log**:
- Check: `storage/logs/errors.log` (Option A: `/home/username/ruthspeaks/storage/logs/`)
- This shows PHP errors and application errors

### 2. Enable Debug Mode (Temporarily)

Edit `.env`:
```bash
APP_DEBUG=true
```

Visit your site - you'll see detailed error messages.

**IMPORTANT**: Set back to `false` when done!

### 3. Common 500 Error Causes & Fixes

#### A. Missing .env File
**Error**: Cannot load configuration
**Fix**: Upload `.env` file with correct values

#### B. Wrong Database Credentials
**Error**: "DB connection failed"
**Fix**: Double-check `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` in `.env`

#### C. Database Doesn't Exist
**Fix**: Create database in cPanel and import schema

#### D. Wrong BASE_PATH (Option A only)
**Error**: "Failed opening required..." or "No such file or directory"
**Fix**: Update `BASE_PATH` in `public_html/index.php` to `/home/username/ruthspeaks`

#### E. Wrong File Permissions
**Error**: "Permission denied" or "Failed to open stream"
**Fix**:
- `chmod 777 storage/logs/`
- `chmod 777 public/uploads/`

#### F. PHP Version Too Old
**Error**: "Parse error: syntax error..." or "Unexpected 'declare'"
**Fix**: Upgrade to PHP 7.2+ in cPanel

#### G. Missing PDO Extension
**Error**: "Class 'PDO' not found"
**Fix**: Enable PDO and PDO_MySQL in cPanel > Select PHP Version > Extensions

#### H. .htaccess Syntax Error
**Fix**: Verify `.htaccess` files match the ones in this repo

#### I. mod_rewrite Not Enabled
**Error**: All pages show 404 except direct links
**Fix**: Contact hosting support to enable mod_rewrite (usually enabled by default on cPanel)

---

## Post-Deployment Checklist

- [ ] Database created and schema imported
- [ ] `.env` file uploaded with correct credentials
- [ ] `APP_DEBUG=false` in production
- [ ] File permissions set correctly (especially `storage/logs/` and `public/uploads/`)
- [ ] PHP version set to 7.2+ with required extensions
- [ ] Admin password hash generated and updated
- [ ] Test homepage: `https://yourdomain.com`
- [ ] Test admin login: `https://yourdomain.com/admin/login`
- [ ] Delete test files: `phpinfo.php`, `gen-hash.php`, `test.php`
- [ ] Check error logs: `storage/logs/errors.log`
- [ ] Verify uploads work: Try creating a post with an image
- [ ] SSL certificate installed (use cPanel AutoSSL or Let's Encrypt)

---

## Security Recommendations

### 1. Remove Debug/Test Files

Delete these files from production:
- `public/phpinfo.php`
- `public/test-logger.php`
- Any `test.php` or `gen-hash.php` you created

### 2. Protect .env File

Your `.env` is already protected by `.htaccess`, but verify:

```bash
# Try accessing: https://yourdomain.com/.env
# Should return: 403 Forbidden
```

### 3. Use Strong Passwords

- Database password: 16+ characters, mixed case, numbers, symbols
- Admin password: 12+ characters, mixed case, numbers, symbols

### 4. Regular Backups

Use cPanel backup features:
- **Full Account Backup** (includes database + files)
- **MySQL Database Backup** (for database only)

### 5. Keep storage/logs/ Outside Web Root (Option A)

If using Option A, logs are automatically outside `public_html/` - much safer!

---

## Testing Your Deployment

### 1. Test Homepage
```
https://yourdomain.com
```
Should show: Recent posts or welcome page

### 2. Test Admin Login
```
https://yourdomain.com/admin/login
```
Login with your admin credentials

### 3. Test API Health Check
```
https://yourdomain.com/health
```
Should return: `{"status":"ok"}`

### 4. Test Database Connection
```
https://yourdomain.com/admin
```
Should show: Admin dashboard with data from database

### 5. Check Error Logs
```
# Via cPanel File Manager or FTP:
storage/logs/errors.log
storage/logs/app.log
```

Should be empty (no critical errors) or contain only INFO/WARNING messages

---

## Getting Help

### Check Logs First

1. **Application logs**: `storage/logs/errors.log`
2. **cPanel error log**: `~/public_html/error_log` or cPanel > Errors
3. **PHP error log**: Look for `error_log` in public_html

### Enable Debug Mode Temporarily

```bash
# In .env:
APP_DEBUG=true
```

Visit your site to see detailed error messages (disable when done!)

### Common Commands

```bash
# Check PHP version
php -v

# Check file permissions
ls -la public/uploads/
ls -la storage/logs/

# View error logs
tail -f storage/logs/errors.log
tail -f ~/public_html/error_log
```

---

## Summary: What Was Fixed

1. **Removed duplicate code** in `public/index.php` (lines 95-410 were duplicates)
2. **Created `.env` file** from template
3. Your project structure is clean and ready for cPanel deployment

---

## Quick Start Deployment Steps

### For Option B (Simple - Git Friendly):

1. **Upload**: Upload entire project to `public_html/`
2. **Database**: Create database + import `migrations/*.sql`
3. **Configure**: Edit `.env` with database credentials
4. **Permissions**: `chmod 777 storage/logs/ public/uploads/`
5. **PHP**: Set PHP 7.4+ with PDO extension
6. **Test**: Visit your domain

### For Option A (Secure):

1. **Upload**:
   - App files to `/home/username/ruthspeaks/`
   - Public files to `/home/username/public_html/`
2. **Update**: Edit `public_html/index.php` BASE_PATH (lines 5 & 20)
3. **Database**: Create database + import `migrations/*.sql`
4. **Configure**: Edit `.env` with database credentials
5. **Permissions**: `chmod 777 storage/logs/ public_html/uploads/`
6. **PHP**: Set PHP 7.4+ with PDO extension
7. **Test**: Visit your domain

---

## Need Help?

If you still encounter 500 errors after following this guide:

1. Check `storage/logs/errors.log`
2. Check `~/public_html/error_log`
3. Enable `APP_DEBUG=true` temporarily
4. Verify all checklist items above
5. Contact your hosting support with the error log contents
