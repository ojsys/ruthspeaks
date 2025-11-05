<?php
declare(strict_types=1);

namespace App\Models;

use App\Database; use PDO;

class Post {
    public static function recent(int $limit = 10): array {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT p.id, p.slug, p.title, p.excerpt, p.cover_image, p.published_at, p.estimated_read_minutes FROM posts p WHERE p.published_at IS NOT NULL ORDER BY p.published_at DESC LIMIT :lim");
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function findBySlug(string $slug): ?array {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE slug = :slug AND (published_at IS NOT NULL OR :is_admin = 1) LIMIT 1");
        $isAdmin = isset($_SESSION['admin']) ? 1 : 0;
        $stmt->execute([':slug' => $slug, ':is_admin' => $isAdmin]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function related(int $postId, int $limit = 3): array {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT id, slug, title, excerpt, cover_image, published_at FROM posts WHERE id <> :id AND published_at IS NOT NULL ORDER BY published_at DESC LIMIT :lim");
        $stmt->bindValue(':id', $postId, PDO::PARAM_INT);
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id LIMIT 1");
        $stmt->execute([':id'=>$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function all(int $limit = 100, int $offset = 0): array {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT p.* FROM posts p ORDER BY p.created_at DESC LIMIT :lim OFFSET :off");
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function latest(array $opts = []): array {
        $limit = isset($opts['limit']) ? (int)$opts['limit'] : 12;
        $offset = isset($opts['offset']) ? (int)$opts['offset'] : 0;
        $cat = isset($opts['category_id']) && $opts['category_id'] !== '' ? (int)$opts['category_id'] : null;
        $q = isset($opts['q']) ? trim((string)$opts['q']) : '';
        $pdo = Database::pdo();
        $where = ['(p.published_at IS NOT NULL)'];
        $params = [];
        if ($cat) { $where[] = 'p.category_id = :cat'; $params[':cat'] = $cat; }
        if ($q !== '') {
            $where[] = '(p.title LIKE :q OR p.excerpt LIKE :q OR p.content LIKE :q)';
            $params[':q'] = '%' . $q . '%';
        }
        $sql = 'SELECT p.*, c.name as category_name FROM posts p LEFT JOIN categories c ON p.category_id = c.id';
        if ($where) { $sql .= ' WHERE ' . implode(' AND ', $where); }
        $sql .= ' ORDER BY p.published_at DESC, p.created_at DESC LIMIT :lim OFFSET :off';
        $stmt = $pdo->prepare($sql);
        foreach ($params as $k=>$v) { $stmt->bindValue($k, $v); }
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function create(array $data): int {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("INSERT INTO posts (slug, title, excerpt, content, cover_image, category_id, author_id, estimated_read_minutes, published_at) VALUES (:slug, :title, :excerpt, :content, :cover, :cat, :author, :erm, :pub)");
        $stmt->execute([
            ':slug'=>$data['slug'], ':title'=>$data['title'], ':excerpt'=>$data['excerpt'] ?? null,
            ':content'=>$data['content'], ':cover'=>$data['cover_image'] ?? null,
            ':cat'=>$data['category_id'] ?? null, ':author'=>$data['author_id'] ?? null,
            ':erm'=>$data['estimated_read_minutes'] ?? 4, ':pub'=>$data['published_at'] ?? null
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): bool {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("UPDATE posts SET slug=:slug, title=:title, excerpt=:excerpt, content=:content, cover_image=:cover, category_id=:cat, author_id=:author, estimated_read_minutes=:erm, published_at=:pub WHERE id=:id");
        return $stmt->execute([
            ':slug'=>$data['slug'], ':title'=>$data['title'], ':excerpt'=>$data['excerpt'] ?? null,
            ':content'=>$data['content'], ':cover'=>$data['cover_image'] ?? null,
            ':cat'=>$data['category_id'] ?? null, ':author'=>$data['author_id'] ?? null,
            ':erm'=>$data['estimated_read_minutes'] ?? 4, ':pub'=>$data['published_at'] ?? null, ':id'=>$id
        ]);
    }

    public static function delete(int $id): bool {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id=:id");
        return $stmt->execute([':id'=>$id]);
    }

    public static function tagsFor(int $postId): array {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT t.name FROM tags t JOIN post_tags pt ON pt.tag_id=t.id WHERE pt.post_id=:id ORDER BY t.name");
        $stmt->execute([':id'=>$postId]);
        return array_map(fn($r)=>$r['name'],$stmt->fetchAll());
    }

    public static function setTags(int $postId, array $names): void {
        $pdo = Database::pdo();
        $names = array_values(array_unique(array_filter(array_map(fn($s)=>trim($s), $names))));
        $pdo->prepare('DELETE FROM post_tags WHERE post_id=:id')->execute([':id'=>$postId]);
        if (!$names) return;
        $tagIds = [];
        foreach ($names as $n) {
            // find or create tag
            $stmt = $pdo->prepare('SELECT id FROM tags WHERE name=:n LIMIT 1');
            $stmt->execute([':n'=>$n]);
            $row = $stmt->fetch();
            if ($row) { $tagIds[] = (int)$row['id']; continue; }
            $slug = \App\slugify($n);
            $pdo->prepare('INSERT INTO tags (name, slug) VALUES (:name,:slug)')->execute([':name'=>$n, ':slug'=>$slug]);
            $tagIds[] = (int)$pdo->lastInsertId();
        }
        $stmt = $pdo->prepare('INSERT INTO post_tags (post_id, tag_id) VALUES (:pid, :tid)');
        foreach ($tagIds as $tid) { $stmt->execute([':pid'=>$postId, ':tid'=>$tid]); }
    }
}
