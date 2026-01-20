<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>QR Animaux</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>

    <header>
        <div class="logo">
            <img src="/assets/logo.png" alt="QR Animaux Logo">
        </div>

        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="produits.php">Produits</a></li>
                    <li><a href="list_animal.php">Animaux</a></li>
                    <li><a href="#" id="logoutBtn">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="register.php">Créer un compte</a></li>
                    <li><a href="login.php">Se connecter</a></li>




                <?php endif; ?>
            </ul>
        </nav>
    </header>


    <div class="modal" id="logoutModal">
        <div class="modal-content">
            <p>Voulez-vous vraiment vous déconnecter ?</p>

            <div class="modal-actions">
                <a href="deconnexion.php" class="btn danger">
                    Oui, se déconnecter
                </a>

                <button id="cancelLogout" class="btn">
                    Annuler
                </button>
            </div>
        </div>
    </div>