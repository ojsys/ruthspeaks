<?php
declare(strict_types=1);

namespace App;

function e(?string $s): string { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }

function db(): \PDO {
    return Database::pdo();
}

function slugify(string $text): string {
    $text = strtolower(trim($text));
    $text = preg_replace('~[^a-z0-9]+~', '-', $text) ?: '';
    $text = trim($text, '-');
    return $text ?: (string)time();
}

function view(string $template, array $params = []): string {
    // "layout" is the main shell. If $template == 'layout', expects 'content' param
    extract($params, EXTR_SKIP);
    ob_start();
    $templateFile = BASE_PATH . '/views/' . $template . '.php';
    if (!is_file($templateFile)) {
        echo '<p>View not found: ' . e($template) . '</p>';
    } else {
        include $templateFile;
    }
    return ob_get_clean();
}

function render(string $template, array $params = []): void {
    echo view($template, $params);
}

function admin_view(string $template, array $params = []): string {
    // Render an admin view with the admin layout
    $content = view('admin/' . $template, $params);
    return view('admin_layout', array_merge($params, ['content' => $content]));
}

function notFound(): void {
    http_response_code(404);
    echo view('layout', [
        'title' => 'Not Found',
        'content' => '<div class="container"><h1>Not Found</h1></div>'
    ]);
}

function serverJson(array $data, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
}

// CSRF helpers
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}
function csrf_field(): string {
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}
function verify_csrf(): void {
    $ok = isset($_POST['_csrf'], $_SESSION['csrf']) && hash_equals($_SESSION['csrf'], (string)$_POST['_csrf']);
    if (!$ok) { http_response_code(403); echo 'CSRF failed'; exit; }
}

function getJsonInput(): array {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?? '', true);
    return is_array($data) ? $data : [];
}

function estimateMinTimeMs(int $estMinutes): int {
    $min = (int) max(GIVEAWAY_MIN_TIME_FLOOR_MS, ($estMinutes * 60000) * GIVEAWAY_MIN_TIME_FACTOR);
    return $min;
}

function injectInArticleAds(string $html): string {
    // Only inject if ads enabled and in-article HTML is configured
    if (!defined(__NAMESPACE__ . '\\ADS_ENABLED') || !ADS_ENABLED) return $html;
    if (!defined(__NAMESPACE__ . '\\AD_IN_ARTICLE_HTML') || AD_IN_ARTICLE_HTML === '') return $html;

    $inserted = false;
    $result = preg_replace_callback('/(<h2[^>]*>.*?<\/h2>)/is', function($m) use (&$inserted){
        if ($inserted) return $m[0];
        $inserted = true;
        return $m[0] . "\n" . AD_IN_ARTICLE_HTML . "\n";
    }, $html, 1);
    if ($result === null) $result = $html;
    if (!$inserted) {
        $parts = preg_split('/(<p[^>]*>.*?<\/p>)/is', $result, -1, PREG_SPLIT_DELIM_CAPTURE);
        if ($parts && count($parts) > 6) {
            array_splice($parts, 6, 0, ["\n" . AD_IN_ARTICLE_HTML . "\n"]);
            $result = implode('', $parts);
        }
    }
    return $result;
}

function ensureUploadDir(): string {
    $dir = BASE_PATH . '/public/uploads';
    if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
    return $dir;
}

function handleUploadFromForm(string $field): ?string {
    if (empty($_FILES[$field]['name']) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) return null;
    $tmp = $_FILES[$field]['tmp_name'];
    $name = $_FILES[$field]['name'];
    $size = (int)$_FILES[$field]['size'];
    if ($size <= 0 || $size > 5*1024*1024) return null; // 5MB limit
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $tmp) ?: '';
    finfo_close($finfo);
    $allowed = ['image/jpeg'=>'.jpg','image/png'=>'.png','image/gif'=>'.gif','image/webp'=>'.webp'];
    if (!isset($allowed[$mime])) return null;
    $ext = $allowed[$mime];
    $base = pathinfo($name, PATHINFO_FILENAME);
    $file = slugify($base) . '-' . substr(bin2hex(random_bytes(4)),0,8) . $ext;
    $dir = ensureUploadDir();
    $dest = $dir . '/' . $file;
    if (!move_uploaded_file($tmp, $dest)) return null;
    return '/uploads/' . $file;
}
