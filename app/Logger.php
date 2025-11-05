<?php
declare(strict_types=1);

namespace App;

class Logger {
    private static function logDir(): string {
        $dir = BASE_PATH . '/storage/logs';
        if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
        return $dir;
    }

    private static function logFile(): string {
        return self::logDir() . '/app.log';
    }

    public static function write(string $level, string $message, array $context = []): void {
        $date = date('Y-m-d H:i:s');
        $ctx = '';
        if ($context) {
            $safe = [];
            foreach ($context as $k=>$v) {
                if (is_scalar($v)) $safe[$k] = $v; else $safe[$k] = json_encode($v);
            }
            $ctx = ' ' . json_encode($safe);
        }
        $line = "[$date] $level: $message$ctx\n";
        @error_log($line); // PHP error log
        @file_put_contents(self::logFile(), $line, FILE_APPEND | LOCK_EX);
    }

    public static function error(string $message, array $context = []): void { self::write('ERROR', $message, $context); }
    public static function warning(string $message, array $context = []): void { self::write('WARNING', $message, $context); }
    public static function info(string $message, array $context = []): void { self::write('INFO', $message, $context); }
}

