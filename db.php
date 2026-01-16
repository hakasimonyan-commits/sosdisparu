<?php
// db.php - Ստեղծում ենք PDO կապը DB-ի հետ

$env = parse_ini_file(__DIR__ . '/.env');

$host = $env['DB_HOST'];
$db   = $env['DB_NAME'];
$user = $env['DB_USER'];
$pass = $env['DB_PASS'];
$charset = 'utf8mb4';


$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Եթե DB error լինի → exception
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Ստանալու ենք associative array
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options); // Ստեղծում ենք PDO connection
} catch (Exception $e) {
    die('Erreur DB : ' . $e->getMessage()); // Եթե սխալ → կանգնեցնում ենք execution
}
