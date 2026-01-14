<?php
// Վկայակոչում QR Code գրադարանին
include('phpqrcode/qrlib.php'); // Այս ֆայլը պետք է լինի qr_animaux/phpqrcode/qrlib.php

// DB կապը
$host = 'localhost';
$db   = 'qr_animaux';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Եթե DB error լինի → exception
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // associative array
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options); // Ստեղծում ենք PDO կապ
} catch (Exception $e) {
    die('Erreur DB : ' . $e->getMessage()); // Եթե DB connection fails
}

// Ստուգում ենք, որ GET id կա
if (!isset($_GET['id'])) {
    die('Animal non trouvé.');
}
$id = intval($_GET['id']); // sanitize id

// Վերցնում ենք տվյալ կենդանուն
$stmt = $pdo->prepare("SELECT * FROM animals WHERE id = ?");
$stmt->execute([$id]);
$animal = $stmt->fetch();
if (!$animal) {
    die('Animal non trouvé.');
}

// QR code-ի տվյալը → հղում դեպի animal_details.php?id=XX
$url =
    $_SERVER['REQUEST_SCHEME'] . '://' .
    $_SERVER['HTTP_HOST'] .
    '/view_animal.php?id=' . $animal['id'];
QRcode::png($url);
