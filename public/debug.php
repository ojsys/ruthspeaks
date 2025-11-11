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

    echo "\n=== SIMULATING LOGIN FOR LATEST USER ===\n";
    if (!empty($users)) {
        $latestUser = $users[0];
        echo "Testing with User ID: {$latestUser['id']}\n";
        echo "Email: {$latestUser['email']}\n";

        // Test with password123
        $testPass = 'password123';
        echo "\nAttempting to verify password: '{$testPass}'\n";

        // Get full user record
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $latestUser['id']]);
        $fullUser = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($fullUser) {
            echo "Password hash from DB: " . substr($fullUser['password_hash'], 0, 30) . "...\n";
            echo "Password hash length: " . strlen($fullUser['password_hash']) . "\n";
            echo "Hash type: " . (strpos($fullUser['password_hash'], '$2y$') === 0 ? 'bcrypt' : 'unknown') . "\n";

            $verifyResult = password_verify($testPass, $fullUser['password_hash']);
            echo "Verification result: " . ($verifyResult ? "SUCCESS ✓" : "FAILED ✗") . "\n";

            // Try to simulate exact login process
            echo "\n--- Simulating findByEmail ---\n";
            require_once BASE_PATH . '/app/Models/User.php';
            $userByEmail = \App\Models\User::findByEmail($latestUser['email']);
            if ($userByEmail) {
                echo "User found by email: YES\n";
                echo "Has password_hash key: " . (isset($userByEmail['password_hash']) ? 'YES' : 'NO') . "\n";
                if (isset($userByEmail['password_hash'])) {
                    echo "password_hash value: " . substr($userByEmail['password_hash'], 0, 30) . "...\n";
                    $loginVerify = password_verify($testPass, $userByEmail['password_hash']);
                    echo "Login verification would: " . ($loginVerify ? "SUCCESS ✓" : "FAIL ✗") . "\n";
                }
            }
        }
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
