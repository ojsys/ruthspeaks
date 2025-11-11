<?php
define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/app/Env.php';
\App\Env::load(BASE_PATH . '/.env');

header('Content-Type: text/plain');

$adminEmail = \App\Env::get('ADMIN_EMAIL', '');
$adminPasswordHash = \App\Env::get('ADMIN_PASSWORD_HASH', '');
$expectedPassword = 'Jonah@1234';

echo "=== ADMIN LOGIN VERIFICATION ===\n\n";
echo "ADMIN_EMAIL from .env: {$adminEmail}\n";
echo "ADMIN_PASSWORD_HASH from .env: {$adminPasswordHash}\n\n";

echo "Expected password: {$expectedPassword}\n";
echo "Verifying password against hash...\n";

$isValid = password_verify($expectedPassword, $adminPasswordHash);

if ($isValid) {
    echo "\n✅ SUCCESS! Password 'Jonah@1234' matches the hash in .env\n";
    echo "\nYou should be able to login at /admin/login with:\n";
    echo "Email: {$adminEmail}\n";
    echo "Password: {$expectedPassword}\n";
} else {
    echo "\n❌ FAILED! Password 'Jonah@1234' does NOT match the hash in .env\n";
    echo "\nGenerating correct hash for 'Jonah@1234'...\n";
    $correctHash = password_hash($expectedPassword, PASSWORD_DEFAULT);
    echo "\nCorrect hash: {$correctHash}\n";
    echo "\nYou need to update your .env file with:\n";
    echo "ADMIN_PASSWORD_HASH={$correctHash}\n";
}
