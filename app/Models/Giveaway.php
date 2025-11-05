<?php
declare(strict_types=1);

namespace App\Models;

use App\Database; use PDO;

class Giveaway {
    public static function activeForPost(int $postId): ?array {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT * FROM giveaways WHERE post_id = :pid AND (start_at IS NULL OR start_at <= NOW()) AND (end_at IS NULL OR end_at >= NOW()) LIMIT 1");
        $stmt->execute([':pid' => $postId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function claimsCount(int $giveawayId): int {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT COUNT(*) as c FROM claims WHERE giveaway_id = :gid AND status = 'won'");
        $stmt->execute([':gid' => $giveawayId]);
        $row = $stmt->fetch();
        return (int)($row['c'] ?? 0);
    }

    public static function hasClaimByEmail(int $giveawayId, string $email): bool {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("SELECT id FROM claims WHERE giveaway_id = :gid AND email = :email LIMIT 1");
        $stmt->execute([':gid' => $giveawayId, ':email' => $email]);
        return (bool)$stmt->fetch();
    }

    public static function createClaim(int $giveawayId, string $email, string $code, string $ip): bool {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("INSERT INTO claims (giveaway_id, email, code, status, ip_addr, created_at) VALUES (:gid, :email, :code, 'won', :ip, NOW())");
        return $stmt->execute([':gid'=>$giveawayId, ':email'=>$email, ':code'=>$code, ':ip'=>$ip]);
    }
}

