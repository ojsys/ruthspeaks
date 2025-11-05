<?php
declare(strict_types=1);

namespace App;

use PDO; use PDOException;

class Database {
    private static ?PDO $pdo = null;

    public static function pdo(): PDO {
        if (self::$pdo === null) {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            $opts = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            try {
                self::$pdo = new PDO($dsn, DB_USER, DB_PASS, $opts);
            } catch (PDOException $e) {
                http_response_code(500);
                echo 'DB connection failed.';
                exit;
            }
        }
        return self::$pdo;
    }
}

