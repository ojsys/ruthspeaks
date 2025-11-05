<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Read;
use App\Models\Post;
use App\Models\Giveaway;
use function App\getJsonInput;
use function App\serverJson;
use function App\estimateMinTimeMs;
use function App\e;
use const App\GIVEAWAY_PROGRESS_THRESHOLD;

class ApiController {
    public static function track(): void {
        $data = getJsonInput();
        $postId = (int)($data['postId'] ?? 0);
        $anonId = (string)($data['anonId'] ?? '');
        $progress = max(0, min(100, (int)($data['progress'] ?? 0)));
        $timeMs = max(0, (int)($data['timeSpentMs'] ?? 0));
        if ($postId <= 0 || !$anonId) { serverJson(['ok'=>false], 400); return; }
        Read::upsert($postId, $anonId, $progress, $timeMs);
        serverJson(['ok'=>true]);
    }

    public static function unlock(): void {
        $data = getJsonInput();
        $postId = (int)($data['postId'] ?? 0);
        $anonId = trim((string)($data['anonId'] ?? ''));
        $email = trim((string)($data['email'] ?? ''));
        $keyword = trim((string)($data['keyword'] ?? ''));
        $timeMs = max(0, (int)($data['timeSpentMs'] ?? 0));
        $progress = max(0, min(100, (int)($data['progress'] ?? 0)));

        if ($postId <= 0 || !$anonId || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            serverJson(['success'=>false, 'message'=>'Invalid request.'], 400); return;
        }

        // Fetch post by id (minimal internal helper)
        $post = self::getPostById($postId);
        if (!$post) { serverJson(['success'=>false,'message'=>'Post not found.'],404); return; }

        $giveaway = Giveaway::activeForPost($postId);
        if (!$giveaway) { serverJson(['success'=>false,'message'=>'No active giveaway.'],404); return; }

        $minTimeMs = estimateMinTimeMs((int)($post['estimated_read_minutes'] ?? 3));
        if ($progress < GIVEAWAY_PROGRESS_THRESHOLD || $timeMs < $minTimeMs) {
            serverJson(['success'=>false, 'message'=>'Keep reading a little more to unlock.']); return;
        }

        // Optional keyword check
        $rules = json_decode($giveaway['rules_json'] ?? 'null', true);
        if (is_array($rules) && !empty($rules['keyword'])) {
            if (strcasecmp(trim($rules['keyword']), $keyword) !== 0) {
                serverJson(['success'=>false, 'message'=>'Answer incorrect.']); return;
            }
        }

        // Limit by winners
        $used = Giveaway::claimsCount((int)$giveaway['id']);
        $max = (int)($giveaway['max_winners'] ?? 0);
        if ($max > 0 && $used >= $max) {
            serverJson(['success'=>false, 'message'=>'Giveaway exhausted.']); return;
        }

        // Prevent double-claim by same email
        if (Giveaway::hasClaimByEmail((int)$giveaway['id'], $email)) {
            serverJson(['success'=>false, 'message'=>'You have already claimed.']); return;
        }

        $code = self::genCode(10);
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $ok = Giveaway::createClaim((int)$giveaway['id'], $email, $code, $ip);
        if ($ok) {
            serverJson(['success'=>true, 'code'=>$code]);
        } else {
            serverJson(['success'=>false, 'message'=>'Unable to create claim.'], 500);
        }
    }

    private static function genCode(int $len = 8): string {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $out = '';
        for ($i=0; $i<$len; $i++) { $out .= $chars[random_int(0, strlen($chars)-1)]; }
        return $out;
    }

    private static function getPostById(int $id): ?array {
        $pdo = \App\Database::pdo();
        $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = :id LIMIT 1');
        $stmt->execute([':id'=>$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}
