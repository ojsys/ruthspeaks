<?php
declare(strict_types=1);

namespace App\Controllers;

use function App\view;
use function App\admin_view;

class AdminLogsController {
    private static function guard(): void {
        if (!isset($_SESSION['admin'])) {
            header('Location: /admin/login');
            exit;
        }
    }

    public static function show(): void {
        self::guard();
        $appLog = BASE_PATH . '/storage/logs/app.log';
        $errLog = BASE_PATH . '/storage/logs/errors.log';
        $app = self::tail($appLog, 200);
        $errs = self::tail($errLog, 200);
        echo admin_view('logs', [
            'title' => 'Logs',
            'app' => $app,
            'errs' => $errs
        ]);
    }

    private static function tail(string $file, int $lines): string {
        if (!is_file($file)) return '(missing)';
        $arr = @file($file, FILE_IGNORE_NEW_LINES) ?: [];
        $slice = array_slice($arr, -$lines);
        return implode("\n", $slice);
    }
}
