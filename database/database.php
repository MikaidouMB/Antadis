<?php

$dns = 'mysql:host=127.0.0.1;dbname=mikaidoumbo_lVh35';
$username = 'root';
$pwd = '';

try {
    $pdo = new PDO($dns, $username, $pwd, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    throw new Exception($e->getMessage());
}

return $pdo;