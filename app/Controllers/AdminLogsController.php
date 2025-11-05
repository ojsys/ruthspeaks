<?php
declare(strict_types=1);

namespace App\Controllers;

use function App\view;

class AdminLogsController {
    private static function guard(): void { if (!isset($_SESSION['admin'])) { header('Location: /admin/login'); exit; } }

    public static function show(): void {
        self::guard();
        $appLog = BASE_PATH . '/storage/logs/app.log';
        $phpLogInternal = BASE_PATH . '/storage/logs/php-error.log';
        $phpLogCpanel = BASE_PATH . '/public/error_log'; // typical cPanel location
        $app = self::tail($appLog, 200);
        $phpInt = self::tail($phpLogInternal, 200);
        $phpCpanel = self::tail($phpLogCpanel, 200);
        echo view('layout', [
            'title' => 'Logs',
            'content' => view('admin/logs', ['app'=>$app, 'phpInt'=>$phpInt, 'phpCpanel'=>$phpCpanel])
        ]);
    }

    private static function tail(string $file, int $lines): string {
        if (!is_file($file)) return '(missing)';
        $arr = @file($file, FILE_IGNORE_NEW_LINES) ?: [];
        $slice = array_slice($arr, -$lines);
        return implode("\n", $slice);
    }
}
