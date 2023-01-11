<?php
$pdo = require_once __DIR__ . '/database/database.php';
$authDB = include_once __DIR__ . '/database/security.php';

$sessionId = $_COOKIE['session'];
if ($sessionId) {
    $authDB->logout($sessionId, $user);
    header('Location: /index.php');
}
