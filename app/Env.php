<?php
declare(strict_types=1);

namespace App;

class Env {
    private static bool $loaded = false;

    public static function load(?string $path = null): void {
        if (self::$loaded) return;
        $path = $path ?: (defined('BASE_PATH') ? BASE_PATH . '/.env' : __DIR__ . '/../.env');
        if (!is_file($path)) { self::$loaded = true; return; }
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!$lines) { self::$loaded = true; return; }
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#') continue;
            $pos = strpos($line, '=');
            if ($pos === false) continue;
            $key = trim(substr($line, 0, $pos));
            $val = trim(substr($line, $pos+1));
            if ($val !== '' && ($val[0] === '"' || $val[0] === "'")) {
                $q = $val[0];
                if ($val[strlen($val)-1] === $q) {
                    $val = substr($val, 1, -1);
                } else {
                    $val = substr($val, 1);
                }
            }
            // interpret common escapes
            $val = str_replace(['\\n','\\r','\\t'], ["\n","\r","\t"], $val);
            $_ENV[$key] = $val;
            $_SERVER[$key] = $val;
            if (function_exists('putenv')) @putenv($key.'='.$val);
        }
        self::$loaded = true;
    }

    public static function get(string $key, $default = null) {
        if (!self::$loaded) self::load();
        if (array_key_exists($key, $_ENV)) return $_ENV[$key];
        $val = getenv($key);
        return $val !== false ? $val : $default;
    }

    public static function bool(string $key, bool $default = false): bool {
        $val = self::get($key, null);
        if ($val === null) return $default;
        $v = strtolower((string)$val);
        return in_array($v, ['1','true','yes','on'], true);
    }

    public static function int(string $key, int $default = 0): int {
        $val = self::get($key, null);
        if ($val === null || $val === '') return $default;
        return (int)$val;
    }

    public static function float(string $key, float $default = 0.0): float {
        $val = self::get($key, null);
        if ($val === null || $val === '') return $default;
        return (float)$val;
    }
}

