<?php
declare(strict_types=1);

// EARLY logging setup to catch parse/compile errors
$__BASE_PATH = dirname(__DIR__);
$__LOG_DIR = $__BASE_PATH . '/storage/logs';
if (!is_dir($__LOG_DIR)) { @mkdir($__LOG_DIR, 0775, true); }
@ini_set('log_errors', '1');
@ini_set('error_log', $__LOG_DIR . '/errors.log');
@error_reporting(E_ALL);
register_shutdown_function(function() use ($__LOG_DIR){
    $e = error_get_last();
    if ($e && in_array($e['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $line = '['.date('Y-m-d H:i:s').'] FATAL: '.$e['message'].' {"file":"'.$e['file'].'","line":'.$e['line'].'}'."\n";
        @file_put_contents($__LOG_DIR . '/errors.log', $line, FILE_APPEND | LOCK_EX);
    }
});

session_start();
define('BASE_PATH', $__BASE_PATH);

require_once BASE_PATH . '/app/Config.php';
require_once BASE_PATH . '/app/Autoload.php';
require_once BASE_PATH . '/app/ErrorHandling.php';
\App\setup_error_handlers();
require_once BASE_PATH . '/app/Database.php';
require_once BASE_PATH . '/app/Helpers.php';
require_once BASE_PATH . '/app/Request.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Normalize base path when installed in subdir
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$baseDir = rtrim(str_replace('index.php', '', $scriptName), '/');
if ($baseDir && $baseDir !== '/' && strpos($uri, $baseDir) === 0) {
    $uri = substr($uri, strlen($baseDir));
    if ($uri === false) { $uri = '/'; }
}

// Initialize request context for logging
\App\RequestContext::init($method, $uri, $_SERVER['REMOTE_ADDR'] ?? '', $_SERVER['HTTP_USER_AGENT'] ?? '');
\App\Logger::info('Request start');

// Routes
if ($uri === '/' && $method === 'GET') { \App\Controllers\HomeController::index(); exit; }
if (preg_match('#^/post/([a-z0-9\-]+)$#', $uri, $m) && $method === 'GET') { \App\Controllers\PostController::show($m[1]); exit; }

// API
if ($uri === '/api/track' && $method === 'POST') { \App\Controllers\ApiController::track(); exit; }
if ($uri === '/api/unlock' && $method === 'POST') { \App\Controllers\ApiController::unlock(); exit; }
if ($uri === '/api/upload' && $method === 'POST') { \App\Controllers\UploadController::upload(); exit; }
if ($uri === '/api/subscribe' && $method === 'POST') { \App\Controllers\NewsletterController::subscribe(); exit; }
if ($uri === '/health' && $method === 'GET') { \App\Controllers\HealthController::check(); exit; }
if (\App\APP_DEBUG && $uri === '/debug/status' && $method === 'GET') { \App\Controllers\DebugController::status(); exit; }
if (\App\APP_DEBUG && $uri === '/debug/trigger' && $method === 'GET') { \App\Controllers\DebugController::trigger(); exit; }

// Sitemap
if ($uri === '/sitemap.xml' && $method === 'GET') { \App\Controllers\FeedController::sitemap(); exit; }

// Static pages
if ($uri === '/about' && $method === 'GET') { \App\Controllers\PageController::about(); exit; }

// Admin
if ($uri === '/admin/login') { \App\Controllers\AdminController::login(); exit; }
if ($uri === '/admin/logout') { \App\Controllers\AdminController::logout(); exit; }
if ($uri === '/admin') { \App\Controllers\AdminController::dashboard(); exit; }

// Admin Posts
if ($uri === '/admin/posts') { \App\Controllers\AdminPostsController::index(); exit; }
if ($uri === '/admin/posts/new') { \App\Controllers\AdminPostsController::create(); exit; }
if (preg_match('#^/admin/posts/(\d+)/edit$#', $uri, $m)) { \App\Controllers\AdminPostsController::edit((int)$m[1]); exit; }
if (preg_match('#^/admin/posts/(\d+)/delete$#', $uri, $m)) { \App\Controllers\AdminPostsController::delete((int)$m[1]); exit; }

// Admin Taxonomies
if ($uri === '/admin/categories') { \App\Controllers\AdminTaxonomyController::categories(); exit; }
if ($uri === '/admin/tags') { \App\Controllers\AdminTaxonomyController::tags(); exit; }

// Admin Giveaways
if ($uri === '/admin/giveaways') { \App\Controllers\AdminGiveawaysController::index(); exit; }
if ($uri === '/admin/giveaways/new') { \App\Controllers\AdminGiveawaysController::create(); exit; }
if (preg_match('#^/admin/giveaways/(\d+)/edit$#', $uri, $m)) { \App\Controllers\AdminGiveawaysController::edit((int)$m[1]); exit; }
if (preg_match('#^/admin/giveaways/(\d+)/delete$#', $uri, $m)) { \App\Controllers\AdminGiveawaysController::delete((int)$m[1]); exit; }

// Admin logs
if ($uri === '/admin/logs') { \App\Controllers\AdminLogsController::show(); exit; }

// 404
http_response_code(404);
\App\Logger::warning('Route not found', ['uri'=>$uri, 'method'=>$method]);
echo \App\view('layout', [
    'title' => 'Not Found',
    'content' => '<div class="container"><h1>404 Not Found</h1><p>The page you requested does not exist.</p></div>'
]);
<?php
declare(strict_types=1);

session_start();

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/app/Config.php';
require_once BASE_PATH . '/app/Autoload.php';
require_once BASE_PATH . '/app/ErrorHandling.php';
\App\setup_error_handlers();
require_once BASE_PATH . '/app/Database.php';
require_once BASE_PATH . '/app/Helpers.php';

// Controllers
require_once BASE_PATH . '/app/Controllers/HomeController.php';
require_once BASE_PATH . '/app/Controllers/PostController.php';
require_once BASE_PATH . '/app/Controllers/ApiController.php';
require_once BASE_PATH . '/app/Controllers/AdminController.php';
require_once BASE_PATH . '/app/Controllers/AdminPostsController.php';
require_once BASE_PATH . '/app/Controllers/AdminTaxonomyController.php';
require_once BASE_PATH . '/app/Controllers/AdminGiveawaysController.php';
require_once BASE_PATH . '/app/Controllers/UploadController.php';
require_once BASE_PATH . '/app/Controllers/NewsletterController.php';
require_once BASE_PATH . '/app/Controllers/PageController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Normalize base path when installed in subdir
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$baseDir = rtrim(str_replace('index.php', '', $scriptName), '/');
if ($baseDir && $baseDir !== '/' && strpos($uri, $baseDir) === 0) {
    $uri = substr($uri, strlen($baseDir));
    if ($uri === false) { $uri = '/'; }
}

// Simple router
if ($uri === '/' && $method === 'GET') {
    \App\Controllers\HomeController::index();
    exit;
}

if (preg_match('#^/post/([a-z0-9\-]+)$#', $uri, $m) && $method === 'GET') {
    \App\Controllers\PostController::show($m[1]);
    exit;
}

if ($uri === '/api/track' && $method === 'POST') {
    \App\Controllers\ApiController::track();
    exit;
}

if ($uri === '/api/unlock' && $method === 'POST') {
    \App\Controllers\ApiController::unlock();
    exit;
}

if ($uri === '/sitemap.xml' && $method === 'GET') {
    \App\Controllers\FeedController::sitemap();
    exit;
}

// Admin routes (stubs)
if ($uri === '/admin/login') {
    \App\Controllers\AdminController::login();
    exit;
}
if ($uri === '/admin/logout') {
    \App\Controllers\AdminController::logout();
    exit;
}
if ($uri === '/admin') {
    \App\Controllers\AdminController::dashboard();
    exit;
}

// Admin Posts
if ($uri === '/admin/posts') { \App\Controllers\AdminPostsController::index(); exit; }
if ($uri === '/admin/posts/new') { \App\Controllers\AdminPostsController::create(); exit; }
if (preg_match('#^/admin/posts/(\d+)/edit$#', $uri, $m)) { \App\Controllers\AdminPostsController::edit((int)$m[1]); exit; }
if (preg_match('#^/admin/posts/(\d+)/delete$#', $uri, $m)) { \App\Controllers\AdminPostsController::delete((int)$m[1]); exit; }

// Admin Taxonomies
if ($uri === '/admin/categories') { \App\Controllers\AdminTaxonomyController::categories(); exit; }
if ($uri === '/admin/tags') { \App\Controllers\AdminTaxonomyController::tags(); exit; }

// Admin Giveaways
if ($uri === '/admin/giveaways') { \App\Controllers\AdminGiveawaysController::index(); exit; }
if ($uri === '/admin/giveaways/new') { \App\Controllers\AdminGiveawaysController::create(); exit; }
if (preg_match('#^/admin/giveaways/(\d+)/edit$#', $uri, $m)) { \App\Controllers\AdminGiveawaysController::edit((int)$m[1]); exit; }
if (preg_match('#^/admin/giveaways/(\d+)/delete$#', $uri, $m)) { \App\Controllers\AdminGiveawaysController::delete((int)$m[1]); exit; }

// Upload endpoint
if ($uri === '/api/upload' && $method === 'POST') { \App\Controllers\UploadController::upload(); exit; }

// Newsletter subscribe
if ($uri === '/api/subscribe' && $method === 'POST') { \App\Controllers\NewsletterController::subscribe(); exit; }

// 404
http_response_code(404);
echo \App\view('layout', [
    'title' => 'Not Found',
    'content' => '<div class="container"><h1>404 Not Found</h1><p>The page you requested does not exist.</p></div>'
]);
<?php
declare(strict_types=1);

session_start();

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/app/Config.php';
require_once BASE_PATH . '/app/Autoload.php';
require_once BASE_PATH . '/app/ErrorHandling.php';
\App\setup_error_handlers();
require_once BASE_PATH . '/app/Database.php';
require_once BASE_PATH . '/app/Helpers.php';

// Controllers
require_once BASE_PATH . '/app/Controllers/HomeController.php';
require_once BASE_PATH . '/app/Controllers/PostController.php';
require_once BASE_PATH . '/app/Controllers/ApiController.php';
require_once BASE_PATH . '/app/Controllers/AdminController.php';
require_once BASE_PATH . '/app/Controllers/AdminPostsController.php';
require_once BASE_PATH . '/app/Controllers/AdminTaxonomyController.php';
require_once BASE_PATH . '/app/Controllers/AdminGiveawaysController.php';
require_once BASE_PATH . '/app/Controllers/UploadController.php';
require_once BASE_PATH . '/app/Controllers/NewsletterController.php';
require_once BASE_PATH . '/app/Controllers/PageController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Normalize base path when installed in subdir
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$baseDir = rtrim(str_replace('index.php', '', $scriptName), '/');
if ($baseDir && $baseDir !== '/' && strpos($uri, $baseDir) === 0) {
    $uri = substr($uri, strlen($baseDir));
    if ($uri === false) { $uri = '/'; }
}

// Simple router
if ($uri === '/' && $method === 'GET') {
    \App\Controllers\HomeController::index();
    exit;
}

if (preg_match('#^/post/([a-z0-9\-]+)$#', $uri, $m) && $method === 'GET') {
    \App\Controllers\PostController::show($m[1]);
    exit;
}

if ($uri === '/api/track' && $method === 'POST') {
    \App\Controllers\ApiController::track();
    exit;
}

if ($uri === '/api/unlock' && $method === 'POST') {
    \App\Controllers\ApiController::unlock();
    exit;
}

if ($uri === '/sitemap.xml' && $method === 'GET') {
    \App\Controllers\FeedController::sitemap();
    exit;
}

// Admin routes (stubs)
if ($uri === '/admin/login') {
    \App\Controllers\AdminController::login();
    exit;
}
if ($uri === '/admin/logout') {
    \App\Controllers\AdminController::logout();
    exit;
}
if ($uri === '/admin') {
    \App\Controllers\AdminController::dashboard();
    exit;
}

// Admin Posts
if ($uri === '/admin/posts') { \App\Controllers\AdminPostsController::index(); exit; }
if ($uri === '/admin/posts/new') { \App\Controllers\AdminPostsController::create(); exit; }
if (preg_match('#^/admin/posts/(\d+)/edit$#', $uri, $m)) { \App\Controllers\AdminPostsController::edit((int)$m[1]); exit; }
if (preg_match('#^/admin/posts/(\d+)/delete$#', $uri, $m)) { \App\Controllers\AdminPostsController::delete((int)$m[1]); exit; }

// Admin Taxonomies
if ($uri === '/admin/categories') { \App\Controllers\AdminTaxonomyController::categories(); exit; }
if ($uri === '/admin/tags') { \App\Controllers\AdminTaxonomyController::tags(); exit; }

// Admin Giveaways
if ($uri === '/admin/giveaways') { \App\Controllers\AdminGiveawaysController::index(); exit; }
if ($uri === '/admin/giveaways/new') { \App\Controllers\AdminGiveawaysController::create(); exit; }
if (preg_match('#^/admin/giveaways/(\d+)/edit$#', $uri, $m)) { \App\Controllers\AdminGiveawaysController::edit((int)$m[1]); exit; }
if (preg_match('#^/admin/giveaways/(\d+)/delete$#', $uri, $m)) { \App\Controllers\AdminGiveawaysController::delete((int)$m[1]); exit; }

// Upload endpoint
if ($uri === '/api/upload' && $method === 'POST') { \App\Controllers\UploadController::upload(); exit; }

// Newsletter subscribe
if ($uri === '/api/subscribe' && $method === 'POST') { \App\Controllers\NewsletterController::subscribe(); exit; }

// 404
http_response_code(404);
echo \App\view('layout', [
    'title' => 'Not Found',
    'content' => '<div class="container"><h1>404 Not Found</h1><p>The page you requested does not exist.</p></div>'
]);
<?php
declare(strict_types=1);

session_start();

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/app/Config.php';
require_once BASE_PATH . '/app/Autoload.php';
require_once BASE_PATH . '/app/ErrorHandling.php';
\App\setup_error_handlers();
require_once BASE_PATH . '/app/Database.php';
require_once BASE_PATH . '/app/Helpers.php';

// Controllers
require_once BASE_PATH . '/app/Controllers/HomeController.php';
require_once BASE_PATH . '/app/Controllers/PostController.php';
require_once BASE_PATH . '/app/Controllers/ApiController.php';
require_once BASE_PATH . '/app/Controllers/AdminController.php';
require_once BASE_PATH . '/app/Controllers/AdminPostsController.php';
require_once BASE_PATH . '/app/Controllers/AdminTaxonomyController.php';
require_once BASE_PATH . '/app/Controllers/AdminGiveawaysController.php';
require_once BASE_PATH . '/app/Controllers/UploadController.php';
require_once BASE_PATH . '/app/Controllers/NewsletterController.php';
require_once BASE_PATH . '/app/Controllers/PageController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Normalize base path when installed in subdir
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$baseDir = rtrim(str_replace('index.php', '', $scriptName), '/');
if ($baseDir && $baseDir !== '/' && strpos($uri, $baseDir) === 0) {
    $uri = substr($uri, strlen($baseDir));
    if ($uri === false) { $uri = '/'; }
}

// Simple router
if ($uri === '/' && $method === 'GET') {
    \App\Controllers\HomeController::index();
    exit;
}

if (preg_match('#^/post/([a-z0-9\-]+)$#', $uri, $m) && $method === 'GET') {
    \App\Controllers\PostController::show($m[1]);
    exit;
}

if ($uri === '/api/track' && $method === 'POST') {
    \App\Controllers\ApiController::track();
    exit;
}

if ($uri === '/api/unlock' && $method === 'POST') {
    \App\Controllers\ApiController::unlock();
    exit;
}

if ($uri === '/sitemap.xml' && $method === 'GET') {
    \App\Controllers\FeedController::sitemap();
    exit;
}

// Admin routes (stubs)
if ($uri === '/admin/login') {
    \App\Controllers\AdminController::login();
    exit;
}
if ($uri === '/admin/logout') {
    \App\Controllers\AdminController::logout();
    exit;
}
if ($uri === '/admin') {
    \App\Controllers\AdminController::dashboard();
    exit;
}

// Admin Posts
if ($uri === '/admin/posts') { \App\Controllers\AdminPostsController::index(); exit; }
if ($uri === '/admin/posts/new') { \App\Controllers\AdminPostsController::create(); exit; }
if (preg_match('#^/admin/posts/(\d+)/edit$#', $uri, $m)) { \App\Controllers\AdminPostsController::edit((int)$m[1]); exit; }
if (preg_match('#^/admin/posts/(\d+)/delete$#', $uri, $m)) { \App\Controllers\AdminPostsController::delete((int)$m[1]); exit; }

// Admin Taxonomies
if ($uri === '/admin/categories') { \App\Controllers\AdminTaxonomyController::categories(); exit; }
if ($uri === '/admin/tags') { \App\Controllers\AdminTaxonomyController::tags(); exit; }

// Admin Giveaways
if ($uri === '/admin/giveaways') { \App\Controllers\AdminGiveawaysController::index(); exit; }
if ($uri === '/admin/giveaways/new') { \App\Controllers\AdminGiveawaysController::create(); exit; }
if (preg_match('#^/admin/giveaways/(\d+)/edit$#', $uri, $m)) { \App\Controllers\AdminGiveawaysController::edit((int)$m[1]); exit; }
if (preg_match('#^/admin/giveaways/(\d+)/delete$#', $uri, $m)) { \App\Controllers\AdminGiveawaysController::delete((int)$m[1]); exit; }

// Upload endpoint
if ($uri === '/api/upload' && $method === 'POST') { \App\Controllers\UploadController::upload(); exit; }

// Newsletter subscribe
if ($uri === '/api/subscribe' && $method === 'POST') { \App\Controllers\NewsletterController::subscribe(); exit; }

// 404
http_response_code(404);
echo \App\view('layout', [
    'title' => 'Not Found',
    'content' => '<div class="container"><h1>404 Not Found</h1><p>The page you requested does not exist.</p></div>'
]);
