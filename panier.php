<?php
session_start();
require_once 'includes/auth.php';

// ==========================
// Initialisation du panier
// ==========================
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// ==========================
// Ajout au panier (POST)
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        isset($_POST['nom'], $_POST['prix']) &&
        $_POST['nom'] !== '' &&
        is_numeric($_POST['prix'])
    ) {
        $_SESSION['panier'][] = [
            'nom'  => trim($_POST['nom']),
            'prix' => (float) $_POST['prix']
        ];
    }
}

// ==========================
// Calcul du total
// ==========================
$total = array_sum(array_column($_SESSION['panier'], 'prix'));
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Panier</title>
    <link rel="stylesheet" href="assets/css/panier.css">
</head>

<body>

    <h1>🛒 Mon panier</h1>

    <?php if (empty($_SESSION['panier'])): ?>
        <p>Votre panier est vide.</p>
    <?php else: ?>

        <ul>
            <?php foreach ($_SESSION['panier'] as $item): ?>
                <li>
                    <?= htmlspecialchars($item['nom']) ?>
                    — <?= number_format($item['prix'], 2, ',', ' ') ?> €
                </li>
            <?php endforeach; ?>
        </ul>

        <p><strong>Total HT :</strong>
            <?= number_format($total, 2, ',', ' ') ?> €
        </p>

    <?php endif; ?>

    <?php include 'includes/footer.php'; ?>

</body>

</html>