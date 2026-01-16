<?php
require 'includes/auth.php';
session_start();

$_SESSION['panier'] ??= [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['panier'][] = [
        'nom'  => trim($_POST['nom']),
        'prix' => (float) $_POST['prix']
    ];
}

$total = 0;

foreach ($_SESSION['panier'] as $item) {
    echo htmlspecialchars($item['nom']) . " - "
        . number_format($item['prix'], 2) . " €<br>";
    $total += $item['prix'];
}

echo "<br><strong>Total HT : " . number_format($total, 2) . " €</strong>";

include 'includes/footer.php';
