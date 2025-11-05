<?php
declare(strict_types=1);

spl_autoload_register(function(string $class) {
    // Only handle our namespace
    if (strpos($class, 'App\\') !== 0) return;
    $relative = str_replace('\\', '/', $class); // App/Controllers/HomeController
    $relative = 'app/' . substr($relative, 4);   // app/Controllers/HomeController
    $path = defined('BASE_PATH') ? BASE_PATH . '/' . $relative . '.php' : __DIR__ . '/../' . $relative . '.php';
    if (is_file($path)) {
        require_once $path;
    }
});

