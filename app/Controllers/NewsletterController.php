<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Database; use function App\getJsonInput; use function App\serverJson;

class NewsletterController {
    public static function subscribe(): void {
        $data = getJsonInput();
        $email = trim((string)($data['email'] ?? ''));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { serverJson(['ok'=>false,'message'=>'Invalid email'],400); return; }
        $pdo = Database::pdo();
        try {
            $stmt = $pdo->prepare('INSERT INTO subscribers (email) VALUES (:email)');
            $stmt->execute([':email'=>$email]);
        } catch (\PDOException $e) {
            // Ignore duplicates
        }
        serverJson(['ok'=>true]);
    }
}

