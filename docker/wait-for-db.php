<?php

$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '5432';
$name = getenv('DB_DATABASE') ?: 'pff';
$user = getenv('DB_USERNAME') ?: 'pff';
$pass = getenv('DB_PASSWORD') ?: '';

$maxTries = 30;
for ($i = 0; $i < $maxTries; $i++) {
    try {
        new PDO("pgsql:host={$host};port={$port};dbname={$name}", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 3,
        ]);
        exit(0);
    } catch (Throwable $e) {
        fwrite(STDERR, "Waiting for database ({$host}:{$port})... ".$e->getMessage()."\n");
        sleep(2);
    }
}

fwrite(STDERR, "Database never became available after {$maxTries} attempts.\n");
exit(1);
