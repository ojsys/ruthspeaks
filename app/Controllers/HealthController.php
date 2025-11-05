<?php
declare(strict_types=1);

namespace App\Controllers;

use function App\serverJson;

class HealthController {
    public static function check(): void {
        $dbOk = false; $dbErr = null;
        try {
            $pdo = \App\Database::pdo();
            $pdo->query('SELECT 1');
            $dbOk = true;
        } catch (\Throwable $e) { $dbErr = $e->getMessage(); }
        serverJson([
            'ok' => true,
            'php' => PHP_VERSION,
            'db_ok' => $dbOk,
            'db_err' => $dbErr,
            'time' => date('c')
        ]);
    }
}

