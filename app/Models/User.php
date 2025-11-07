<?php
declare(strict_types=1);

namespace App\Models;

use App\Database;
use PDO;

class User {
    public static function findByEmail(string $email): ?array {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public static function findByUsername(string $username): ?array {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
        $stmt->execute([':username' => $username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public static function findById(int $id): ?array {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public static function create(array $data): int {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare('
            INSERT INTO users (email, username, password_hash, name, role, is_active, avatar, bio)
            VALUES (:email, :username, :password, :name, :role, :active, :avatar, :bio)
        ');
        $stmt->execute([
            ':email' => $data['email'],
            ':username' => $data['username'] ?? null,
            ':password' => $data['password_hash'],
            ':name' => $data['name'] ?? '',
            ':role' => $data['role'] ?? 'editor',
            ':active' => $data['is_active'] ?? 1,
            ':avatar' => $data['avatar'] ?? null,
            ':bio' => $data['bio'] ?? null
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void {
        $pdo = Database::pdo();

        $fields = [];
        $params = [':id' => $id];

        if (isset($data['name'])) {
            $fields[] = 'name = :name';
            $params[':name'] = $data['name'];
        }
        if (isset($data['email'])) {
            $fields[] = 'email = :email';
            $params[':email'] = $data['email'];
        }
        if (isset($data['username'])) {
            $fields[] = 'username = :username';
            $params[':username'] = $data['username'];
        }
        if (isset($data['bio'])) {
            $fields[] = 'bio = :bio';
            $params[':bio'] = $data['bio'];
        }
        if (isset($data['avatar'])) {
            $fields[] = 'avatar = :avatar';
            $params[':avatar'] = $data['avatar'];
        }
        if (isset($data['password_hash'])) {
            $fields[] = 'password_hash = :password';
            $params[':password'] = $data['password_hash'];
        }
        if (isset($data['role'])) {
            $fields[] = 'role = :role';
            $params[':role'] = $data['role'];
        }
        if (isset($data['is_active'])) {
            $fields[] = 'is_active = :is_active';
            $params[':is_active'] = $data['is_active'];
        }

        if (empty($fields)) return;

        $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }

    public static function updateLastLogin(int $id): void {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare('UPDATE users SET last_login_at = NOW() WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }

    public static function all(): array {
        $pdo = Database::pdo();
        $stmt = $pdo->query('SELECT id, email, username, name, role, is_active, created_at, last_login_at FROM users ORDER BY created_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function delete(int $id): void {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}
