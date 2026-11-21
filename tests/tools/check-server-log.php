<?php
require __DIR__.'/../Support/ServerLog.php';
if (count($argv) !== 2 || !is_readable($argv[1])) {
    fwrite(STDERR, "Usage: php tests/tools/check-server-log.php LOG_FILE\n");
    exit(2);
}
$errors = \Tests\Support\ServerLog::errors(file_get_contents($argv[1]));
foreach ($errors as $line) fwrite(STDERR, $line."\n");
exit($errors ? 1 : 0);
