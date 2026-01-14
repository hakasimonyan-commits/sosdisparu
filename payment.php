<?php
require 'includes/auth.php';
include 'includes/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Paiement simulé
    header('Location: qr_code.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Paiement</title>
</head>

<body>

    <h1>Paiement</h1>

    <form method="POST">

        <label>Numéro de carte</label><br>
        <input type="text" placeholder="4242 4242 4242 4242" required><br><br>

        <label>Date d’expiration</label><br>
        <input type="text" placeholder="MM/AA" required><br><br>

        <label>CVV</label><br>
        <input type="text" placeholder="123" required><br><br>

        <button type="submit">Payer</button>

    </form>

</body>

</html>