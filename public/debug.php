<?php
session_start();
define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/app/Env.php';
\App\Env::load(BASE_PATH . '/.env');
require_once BASE_PATH . '/app/Config.php';
require_once BASE_PATH . '/app/Database.php';

header('Content-Type: text/plain');

try {
    $pdo = \App\Database::pdo();

    echo "=== CHECKING USERS TABLE STRUCTURE ===\n";
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    foreach ($columns as $col) {
        echo "Column: {$col['Field']} | Type: {$col['Type']}\n";
    }

    echo "\n=== CHECKING USER DATA (Last 3 users) ===\n";
    $stmt = $pdo->query("SELECT id, email, username, SUBSTRING(password_hash, 1, 20) as pass_preview, LENGTH(password_hash) as hash_length, is_active FROM users ORDER BY id DESC LIMIT 3");
    $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    foreach ($users as $user) {
        echo "ID: {$user['id']}\n";
        echo "Email: {$user['email']}\n";
        echo "Username: " . ($user['username'] ?? 'NULL') . "\n";
        echo "Password hash (first 20 chars): {$user['pass_preview']}...\n";
        echo "Hash length: {$user['hash_length']}\n";
        echo "Is active: {$user['is_active']}\n";
        echo "---\n";
    }

    echo "\n=== TESTING PASSWORD HASH GENERATION ===\n";
    $testPassword = 'password123';
    $testHash = password_hash($testPassword, PASSWORD_DEFAULT);
    echo "Test password: {$testPassword}\n";
    echo "Generated hash length: " . strlen($testHash) . "\n";
    echo "Verify test: " . (password_verify($testPassword, $testHash) ? "SUCCESS" : "FAILED") . "\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
