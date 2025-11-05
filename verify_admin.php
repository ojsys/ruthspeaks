<?php
/**
 * Admin Credentials Verification Script
 * Upload this to your server and access it via browser to verify admin credentials
 * DELETE THIS FILE after verification for security!
 */

require_once __DIR__ . '/app/Env.php';
App\Env::load(__DIR__ . '/.env');

$expectedEmail = 'onahjonah@gmail.com';
$expectedPassword = 'Jonah@1234';

echo "<!DOCTYPE html><html><head><title>Admin Verification</title>";
echo "<style>body{font-family:sans-serif;max-width:800px;margin:50px auto;padding:20px;}";
echo ".success{background:#d1fae5;color:#065f46;padding:15px;border-radius:8px;margin:10px 0;}";
echo ".error{background:#fee2e2;color:#991b1b;padding:15px;border-radius:8px;margin:10px 0;}";
echo ".info{background:#e0e7ff;color:#1e3a8a;padding:15px;border-radius:8px;margin:10px 0;}";
echo "code{background:#f3f4f6;padding:2px 6px;border-radius:4px;font-family:monospace;}</style>";
echo "</head><body>";
echo "<h1>🔐 Admin Credentials Verification</h1>";

// Check if .env file exists
if (!file_exists(__DIR__ . '/.env')) {
    echo "<div class='error'><strong>❌ ERROR:</strong> .env file not found!</div>";
    echo "<p>Please create a .env file in the root directory.</p>";
    exit;
}

echo "<div class='success'><strong>✅ .env file found</strong></div>";

// Get credentials from environment
$envEmail = App\Env::get('ADMIN_EMAIL', '');
$envPasswordHash = App\Env::get('ADMIN_PASSWORD_HASH', '');

echo "<h2>Current Configuration:</h2>";
echo "<div class='info'>";
echo "<strong>ADMIN_EMAIL:</strong> <code>" . htmlspecialchars($envEmail) . "</code><br>";
echo "<strong>ADMIN_PASSWORD_HASH:</strong> <code>" . substr($envPasswordHash, 0, 30) . "...</code>";
echo "</div>";

// Verify email
if ($envEmail === $expectedEmail) {
    echo "<div class='success'><strong>✅ Email matches:</strong> " . htmlspecialchars($expectedEmail) . "</div>";
} else {
    echo "<div class='error'><strong>❌ Email does NOT match</strong><br>";
    echo "Expected: <code>" . htmlspecialchars($expectedEmail) . "</code><br>";
    echo "Found: <code>" . htmlspecialchars($envEmail) . "</code></div>";
}

// Verify password
if (password_verify($expectedPassword, $envPasswordHash)) {
    echo "<div class='success'><strong>✅ Password hash is correct!</strong></div>";
    echo "<h2>✨ Your Admin Credentials:</h2>";
    echo "<div class='info'>";
    echo "<strong>Login URL:</strong> <code>/admin/login</code><br>";
    echo "<strong>Email:</strong> <code>" . htmlspecialchars($expectedEmail) . "</code><br>";
    echo "<strong>Password:</strong> <code>" . htmlspecialchars($expectedPassword) . "</code>";
    echo "</div>";
} else {
    echo "<div class='error'><strong>❌ Password hash is INCORRECT</strong></div>";
    echo "<p>The password hash in your .env file doesn't match the expected password.</p>";
    echo "<h3>To fix this:</h3>";
    echo "<p>Update your .env file with this hash:</p>";
    echo "<code style='display:block;padding:10px;background:#f3f4f6;'>ADMIN_PASSWORD_HASH=" .
         htmlspecialchars(password_hash($expectedPassword, PASSWORD_DEFAULT)) . "</code>";
}

echo "<hr>";
echo "<div class='error'><strong>⚠️ IMPORTANT:</strong> Delete this file (verify_admin.php) after verification for security!</div>";
echo "</body></html>";
