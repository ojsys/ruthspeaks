<?php
declare(strict_types=1);

namespace App;

require_once __DIR__ . '/Logger.php';
require_once __DIR__ . '/Env.php';

function setup_error_handlers(): void {
    Env::load(defined('BASE_PATH') ? BASE_PATH . '/.env' : null);
    $debug = Env::bool('APP_DEBUG', false);
    if ($debug) {
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);
    } else {
        ini_set('display_errors', '0');
        error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
    }

    set_error_handler(function($errno, $errstr, $errfile, $errline) use ($debug) {
        if (!(error_reporting() & $errno)) return false;
        Logger::error("$errstr", ['file'=>$errfile, 'line'=>$errline, 'errno'=>$errno]);
        if ($debug) {
            echo "<pre>PHP Error [$errno] $errstr in $errfile:$errline</pre>";
        }
        return true;
    });

    set_exception_handler(function($ex) use ($debug) {
        $msg = $ex->getMessage();
        $trace = $ex->getTraceAsString();
        Logger::error("Uncaught exception: $msg", ['trace'=>$trace]);
        if ($debug) {
            echo '<pre>Uncaught exception: ' . htmlspecialchars($msg) . "\n\n" . htmlspecialchars($trace) . '</pre>';
        } else {
            http_response_code(500);
            echo 'An unexpected error occurred.';
        }
    });

    register_shutdown_function(function(){
        $e = error_get_last();
        if ($e && in_array($e['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            Logger::error('Fatal error: ' . $e['message'], ['file'=>$e['file'], 'line'=>$e['line']]);
            http_response_code(500);
            echo 'A fatal error occurred.';
        }
    });
}

