<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Pendentif QR anti-perte</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <main class="page-content">

        <section class="produit-hero">
            <div class="img-box">
                <img src="image/chien_pendentif.jpg" alt="Pendentif QR chien">
            </div>

            <div class="info-box">
                <h1>Pendentif QR anti-perte pour chien et chat</h1>

                <p class="description">
                    Un pendentif intelligent qui permet à toute personne
                    de vous contacter immédiatement si votre animal est retrouvé.
                </p>

                <p class="prix">Prix : <strong>8,88 €</strong></p>

                <ul class="benefices">
                    <li>✔ QR code unique</li>
                    <li>✔ Sans abonnement</li>
                    <li>✔ Résistant à l’eau</li>
                    <li>✔ Fonctionne partout</li>
                </ul>

                <a href="choisir_animal.php" class="btn-primary">
                    Choisir mon animal
                </a>
            </div>
        </section>

    </main>

    <?php include 'includes/footer.php'; ?>

</body>

</html>