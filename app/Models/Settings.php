<?php
declare(strict_types=1);

namespace App\Models;

use App\Database;
use PDO;

class Settings {
    public static function get(string $key, string $default = ''): string {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare('SELECT setting_value FROM site_settings WHERE setting_key = :key');
        $stmt->execute([':key' => $key]);
        $result = $stmt->fetchColumn();
        return $result !== false ? (string)$result : $default;
    }

    public static function set(string $key, string $value, string $type = 'text'): void {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare('
            INSERT INTO site_settings (setting_key, setting_value, setting_type)
            VALUES (:key, :value, :type)
            ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), setting_type = VALUES(setting_type)
        ');

        $stmt->execute([
            ':key' => $key,
            ':value' => $value,
            ':type' => $type
        ]);
    }

    public static function all(): array {
        $pdo = Database::pdo();
        $stmt = $pdo->query('SELECT * FROM site_settings ORDER BY setting_key');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getMultiple(array $keys): array {
        if (empty($keys)) return [];

        $pdo = Database::pdo();
        $placeholders = str_repeat('?,', count($keys) - 1) . '?';
        $stmt = $pdo->prepare("SELECT setting_key, setting_value FROM site_settings WHERE setting_key IN ($placeholders)");
        $stmt->execute($keys);

        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[$row['setting_key']] = $row['setting_value'];
        }

        return $results;
    }

    public static function getAllAsArray(): array {
        $all = self::all();
        $result = [];
        foreach ($all as $setting) {
            $result[$setting['setting_key']] = $setting['setting_value'];
        }
        return $result;
    }
}
