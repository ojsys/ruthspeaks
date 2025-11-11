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

    echo "\n=== CREATING TEST USER ===\n";
    require_once BASE_PATH . '/app/Models/User.php';

    $testEmail = 'test_' . time() . '@example.com';
    $testUsername = 'testuser_' . time();
    $testPassword = 'password123';
    $testPasswordHash = password_hash($testPassword, PASSWORD_DEFAULT);

    echo "Creating test user...\n";
    echo "Email: {$testEmail}\n";
    echo "Username: {$testUsername}\n";
    echo "Password: {$testPassword}\n";
    echo "Hash: " . substr($testPasswordHash, 0, 30) . "...\n";

    try {
        $newUserId = \App\Models\User::create([
            'name' => 'Test User',
            'email' => $testEmail,
            'username' => $testUsername,
            'password_hash' => $testPasswordHash,
            'role' => 'editor',
            'is_active' => 1
        ]);

        echo "User created with ID: {$newUserId}\n";

        // Now try to fetch and verify
        echo "\n--- Testing Login Process ---\n";
        $fetchedUser = \App\Models\User::findByEmail($testEmail);

        if ($fetchedUser) {
            echo "User fetched successfully\n";
            echo "Fetched password_hash: " . substr($fetchedUser['password_hash'], 0, 30) . "...\n";
            echo "Hash matches what we created: " . ($fetchedUser['password_hash'] === $testPasswordHash ? 'YES ✓' : 'NO ✗') . "\n";

            $verifyResult = password_verify($testPassword, $fetchedUser['password_hash']);
            echo "Password verification: " . ($verifyResult ? "SUCCESS ✓" : "FAILED ✗") . "\n";

            if (!$verifyResult) {
                echo "\nDEBUG INFO:\n";
                echo "Original hash length: " . strlen($testPasswordHash) . "\n";
                echo "Fetched hash length: " . strlen($fetchedUser['password_hash']) . "\n";
                echo "Original hash: {$testPasswordHash}\n";
                echo "Fetched hash: {$fetchedUser['password_hash']}\n";
            }
        } else {
            echo "ERROR: Could not fetch user after creation!\n";
        }

        // Try with findByUsername too
        echo "\n--- Testing with findByUsername ---\n";
        $fetchedByUsername = \App\Models\User::findByUsername($testUsername);
        if ($fetchedByUsername) {
            echo "User fetched by username successfully\n";
            $verifyResult2 = password_verify($testPassword, $fetchedByUsername['password_hash']);
            echo "Password verification: " . ($verifyResult2 ? "SUCCESS ✓" : "FAILED ✗") . "\n";
        }

    } catch (Exception $e) {
        echo "ERROR creating test user: " . $e->getMessage() . "\n";
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
