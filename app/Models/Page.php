<?php
declare(strict_types=1);

namespace App\Models;

use App\Database;
use PDO;

class Page {
    public static function all(): array {
        $pdo = Database::pdo();
        $stmt = $pdo->query('SELECT * FROM pages ORDER BY created_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById(int $id): ?array {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare('SELECT * FROM pages WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public static function findBySlug(string $slug): ?array {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare('SELECT * FROM pages WHERE slug = :slug AND is_published = 1');
        $stmt->execute([':slug' => $slug]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public static function create(array $data): int {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare('
            INSERT INTO pages (slug, title, content, excerpt, featured_image, is_published)
            VALUES (:slug, :title, :content, :excerpt, :image, :published)
        ');
        $stmt->execute([
            ':slug' => $data['slug'],
            ':title' => $data['title'],
            ':content' => $data['content'],
            ':excerpt' => $data['excerpt'] ?? null,
            ':image' => $data['featured_image'] ?? null,
            ':published' => $data['is_published'] ?? 1
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare('
            UPDATE pages
            SET slug = :slug, title = :title, content = :content, excerpt = :excerpt,
                featured_image = :image, is_published = :published
            WHERE id = :id
        ');
        $stmt->execute([
            ':id' => $id,
            ':slug' => $data['slug'],
            ':title' => $data['title'],
            ':content' => $data['content'],
            ':excerpt' => $data['excerpt'] ?? null,
            ':image' => $data['featured_image'] ?? null,
            ':published' => $data['is_published'] ?? 1
        ]);
    }

    public static function delete(int $id): void {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare('DELETE FROM pages WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}
