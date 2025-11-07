<?php
declare(strict_types=1);

/**
 * Simple Migration Runner
 * Usage: php migrate.php [migration_file.sql]
 */

require_once __DIR__ . '/app/Config.php';
require_once __DIR__ . '/app/Database.php';

if ($argc < 2) {
    echo "Usage: php migrate.php <migration_file.sql>\n";
    echo "Example: php migrate.php migrations/006_user_authentication.sql\n";
    exit(1);
}

$migrationFile = $argv[1];

// Check if file exists
if (!file_exists($migrationFile)) {
    echo "Error: Migration file not found: {$migrationFile}\n";
    exit(1);
}

echo "Running migration: {$migrationFile}\n";

try {
    $pdo = \App\Database::pdo();
    $sql = file_get_contents($migrationFile);

    // Split by semicolons and execute each statement
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        fn($stmt) => !empty($stmt) && !preg_match('/^--/', $stmt)
    );

    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $pdo->exec($statement);
        }
    }

    echo "✓ Migration completed successfully!\n";
} catch (PDOException $e) {
    echo "✗ Migration failed: {$e->getMessage()}\n";
    exit(1);
}
