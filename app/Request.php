<?php
declare(strict_types=1);

namespace App;

class RequestContext {
    private static string $id;
    private static string $method = '';
    private static string $uri = '';
    private static string $ip = '';
    private static string $ua = '';

    public static function init(string $method, string $uri, string $ip = '', string $ua = ''): void {
        self::$id = bin2hex(random_bytes(6));
        self::$method = $method;
        self::$uri = $uri;
        self::$ip = $ip;
        self::$ua = $ua;
    }

    public static function id(): string { return self::$id ?? ''; }
    public static function method(): string { return self::$method; }
    public static function uri(): string { return self::$uri; }
    public static function ip(): string { return self::$ip; }
    public static function ua(): string { return self::$ua; }
}

