<?php
// Connexion à la BDD
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0)
            continue;

        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            $value = trim($value, '"\'');
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}

$serveur = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? '');
$utilisateur = getenv('DB_USER') ?: ($_ENV['DB_USER'] ?? '');
$motDePasse = getenv('DB_PASSWORD') ?: ($_ENV['DB_PASSWORD'] ?? '');
$nomBdd = getenv('DB_NAME') ?: ($_ENV['DB_NAME'] ?? '');
