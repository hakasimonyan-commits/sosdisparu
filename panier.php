<?php
require 'includes/auth.php';

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

$_SESSION['panier'][] = [
    'nom' => $_POST['produit'],
    'prix' => $_POST['prix']
];

$total = 0;
foreach ($_SESSION['panier'] as $item) {
    echo $item['nom'] . " - " . $item['prix'] . " €<br>";
    $total += $item['prix'];
}

echo "<br>Total HT : " . $total . " €";
