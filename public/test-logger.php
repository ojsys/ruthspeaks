<?php
// Temporary diagnostic script (remove after testing)
declare(strict_types=1);

$base = dirname(__DIR__);
$logDir = $base . '/storage/logs';
if (!is_dir($logDir)) { @mkdir($logDir, 0775, true); }
@ini_set('log_errors', '1');
@ini_set('error_log', $logDir . '/errors.log');
error_reporting(E_ALL);

error_log('test-logger.php reached at ' . date('c'));
file_put_contents($logDir . '/errors.log', '['.date('Y-m-d H:i:s')."] TEST: manual write from test-logger.php\n", FILE_APPEND | LOCK_EX);

header('Content-Type: text/plain');
echo "OK\n";
echo "error_log: ". ini_get('error_log') ."\n";
echo "logs dir: $logDir\n";
echo "writable: ". (is_writable($logDir) ? 'yes' : 'no') ."\n";

