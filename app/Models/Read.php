<?php
declare(strict_types=1);

namespace App\Models;

use App\Database; use PDO;

class Read {
    public static function upsert(int $postId, string $anonId, int $progress, int $timeMs): void {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("INSERT INTO reads (post_id, anon_id, progress, time_spent_ms, created_at, updated_at)
            VALUES (:pid, :aid, :progress, :timeMs, NOW(), NOW())
            ON DUPLICATE KEY UPDATE progress = GREATEST(progress, VALUES(progress)), time_spent_ms = GREATEST(time_spent_ms, VALUES(time_spent_ms)), updated_at = NOW()");
        $stmt->execute([':pid'=>$postId, ':aid'=>$anonId, ':progress'=>$progress, ':timeMs'=>$timeMs]);
    }

    public static function get(int $postId, string $anonId): ?array {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT * FROM reads WHERE post_id = :pid AND anon_id = :aid LIMIT 1");
        $stmt->execute([':pid'=>$postId, ':aid'=>$anonId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}

