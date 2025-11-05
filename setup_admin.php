<?php
/**
 * Admin Setup Script
 * This script will update your .env file with the correct admin credentials
 * DELETE THIS FILE after setup for security!
 */

$envPath = __DIR__ . '/.env';
$email = 'onahjonah@gmail.com';
$password = 'Jonah@1234';
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

echo "<!DOCTYPE html><html><head><title>Admin Setup</title>";
echo "<style>body{font-family:sans-serif;max-width:800px;margin:50px auto;padding:20px;}";
echo ".success{background:#d1fae5;color:#065f46;padding:15px;border-radius:8px;margin:10px 0;}";
echo ".error{background:#fee2e2;color:#991b1b;padding:15px;border-radius:8px;margin:10px 0;}";
echo ".warning{background:#fef3c7;color:#92400e;padding:15px;border-radius:8px;margin:10px 0;}";
echo "code{background:#f3f4f6;padding:2px 6px;border-radius:4px;font-family:monospace;}</style>";
echo "</head><body>";
echo "<h1>🔧 Admin Setup</h1>";

// Check if .env file exists
if (!file_exists($envPath)) {
    echo "<div class='error'><strong>❌ ERROR:</strong> .env file not found at: " . htmlspecialchars($envPath) . "</div>";
    echo "<p>Please create a .env file by copying .env.example</p>";
    exit;
}

// Read current .env file
$envContent = file_get_contents($envPath);

// Update admin credentials
$updated = false;

// Update email
if (preg_match('/^ADMIN_EMAIL=.*/m', $envContent)) {
    $envContent = preg_replace('/^ADMIN_EMAIL=.*/m', 'ADMIN_EMAIL=' . $email, $envContent);
    $updated = true;
} else {
    echo "<div class='warning'>⚠️ ADMIN_EMAIL not found in .env file</div>";
}

// Update password hash
if (preg_match('/^ADMIN_PASSWORD_HASH=.*/m', $envContent)) {
    $envContent = preg_replace('/^ADMIN_PASSWORD_HASH=.*/m', 'ADMIN_PASSWORD_HASH=' . $passwordHash, $envContent);
    $updated = true;
} else {
    echo "<div class='warning'>⚠️ ADMIN_PASSWORD_HASH not found in .env file</div>";
}

// Write back to file
if ($updated) {
    if (file_put_contents($envPath, $envContent)) {
        echo "<div class='success'><strong>✅ SUCCESS!</strong> Admin credentials have been updated.</div>";
        echo "<h2>Your Admin Credentials:</h2>";
        echo "<div class='success'>";
        echo "<strong>Login URL:</strong> <code>/admin/login</code><br>";
        echo "<strong>Email:</strong> <code>" . htmlspecialchars($email) . "</code><br>";
        echo "<strong>Password:</strong> <code>" . htmlspecialchars($password) . "</code><br>";
        echo "<strong>Password Hash:</strong> <code>" . htmlspecialchars(substr($passwordHash, 0, 40)) . "...</code>";
        echo "</div>";

        echo "<h3>Next Steps:</h3>";
        echo "<ol>";
        echo "<li>Try logging in at <code>/admin/login</code></li>";
        echo "<li><strong>DELETE this file (setup_admin.php) immediately for security!</strong></li>";
        echo "<li>Also delete <code>verify_admin.php</code> if it exists</li>";
        echo "</ol>";
    } else {
        echo "<div class='error'><strong>❌ ERROR:</strong> Could not write to .env file. Check file permissions.</div>";
        echo "<p>You may need to manually update the .env file with these values:</p>";
        echo "<code style='display:block;padding:10px;background:#f3f4f6;margin:10px 0;'>";
        echo "ADMIN_EMAIL=" . htmlspecialchars($email) . "<br>";
        echo "ADMIN_PASSWORD_HASH=" . htmlspecialchars($passwordHash);
        echo "</code>";
    }
} else {
    echo "<div class='error'><strong>❌ ERROR:</strong> Could not find admin credential fields in .env file</div>";
}

echo "<hr>";
echo "<div class='error'><strong>🚨 SECURITY WARNING:</strong> Delete this file immediately after use!</div>";
echo "</body></html>";
