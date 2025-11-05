<?php
declare(strict_types=1);

namespace App\Models;

use App\Database; use PDO;

class Category {
    public static function all(): array {
        $pdo = Database::pdo();
        $stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll();
    }
    public static function create(string $name, string $slug): int {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (:name, :slug)");
        $stmt->execute([':name'=>$name, ':slug'=>$slug]);
        return (int)$pdo->lastInsertId();
    }
    public static function update(int $id, string $name, string $slug): bool {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("UPDATE categories SET name=:name, slug=:slug WHERE id=:id");
        return $stmt->execute([':name'=>$name, ':slug'=>$slug, ':id'=>$id]);
    }
    public static function delete(int $id): bool {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id=:id");
        return $stmt->execute([':id'=>$id]);
    }
}

