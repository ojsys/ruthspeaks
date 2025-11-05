<?php
declare(strict_types=1);

namespace App\Controllers;

use function App\serverJson;

class DebugController {
    private static function ensureDebug(): void {
        if (!\App\APP_DEBUG) { http_response_code(404); echo 'Not found'; exit; }
    }

    public static function status(): void {
        self::ensureDebug();
        $errLog = BASE_PATH . '/storage/logs/errors.log';
        $appLog = BASE_PATH . '/storage/logs/app.log';
        $resp = [
            'php_version' => PHP_VERSION,
            'ini_error_log' => ini_get('error_log'),
            'logs_dir_writable' => is_writable(dirname($errLog)),
            'errors_log_exists' => file_exists($errLog),
            'errors_log_size' => file_exists($errLog) ? filesize($errLog) : 0,
            'app_log_exists' => file_exists($appLog),
            'app_log_size' => file_exists($appLog) ? filesize($appLog) : 0,
            'env_loaded' => isset($_ENV) && count($_ENV) > 0,
        ];
        // DB test
        try { $pdo = \App\Database::pdo(); $pdo->query('SELECT 1'); $resp['db_ok'] = true; } catch (\Throwable $e) { $resp['db_ok'] = false; $resp['db_err'] = $e->getMessage(); }
        serverJson($resp);
    }

    public static function trigger(): void {
        self::ensureDebug();
        throw new \RuntimeException('Debug exception – testing error logging');
    }
}

