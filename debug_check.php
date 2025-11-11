<?php
define('BASE_PATH', __DIR__);
require_once __DIR__ . '/app/Env.php';
\App\Env::load(__DIR__ . '/.env');

require_once __DIR__ . '/app/Config.php';
require_once __DIR__ . '/app/Database.php';

// Check database structure
$pdo = \App\Database::pdo();

echo "=== CHECKING USERS TABLE STRUCTURE ===\n";
$stmt = $pdo->query("DESCRIBE users");
$columns = $stmt->fetchAll(\PDO::FETCH_ASSOC);
foreach ($columns as $col) {
    echo "Column: {$col['Field']} | Type: {$col['Type']} | Null: {$col['Null']} | Key: {$col['Key']}\n";
}

echo "\n=== CHECKING USER DATA ===\n";
$stmt = $pdo->query("SELECT id, email, username, password_hash, LENGTH(password_hash) as hash_length, is_active FROM users ORDER BY id DESC LIMIT 3");
$users = $stmt->fetchAll(\PDO::FETCH_ASSOC);

foreach ($users as $user) {
    echo "ID: {$user['id']}\n";
    echo "Email: {$user['email']}\n";
    echo "Username: {$user['username']}\n";
    echo "Password hash: {$user['password_hash']}\n";
    echo "Hash length: {$user['hash_length']}\n";
    echo "Is active: {$user['is_active']}\n";

    // Test password verification
    $testPassword = 'password123';
    $verifyResult = password_verify($testPassword, $user['password_hash']);
    echo "Verify 'password123': " . ($verifyResult ? "SUCCESS" : "FAILED") . "\n";
    echo "---\n";
}

echo "\n=== TESTING PASSWORD HASH ===\n";
$testPassword = 'password123';
$testHash = password_hash($testPassword, PASSWORD_DEFAULT);
echo "Test password: {$testPassword}\n";
echo "Test hash: {$testHash}\n";
echo "Test hash length: " . strlen($testHash) . "\n";
echo "Verify test: " . (password_verify($testPassword, $testHash) ? "SUCCESS" : "FAILED") . "\n";
