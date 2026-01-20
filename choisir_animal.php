<?php
session_start();
require 'db.php';

/* ===============================
   AUTH — պետք է login
================================ */
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

/* ===============================
   Բերում ենք օգտատիրոջ կենդանիները
================================ */

$stmt = $pdo->query("SELECT id, name, photos FROM animals ORDER BY created_at DESC");
$animals = $stmt->fetchAll(PDO::FETCH_ASSOC);
/* ===============================
    UX
   Եթե կա միայն 1 կենդանի → auto redirect
================================ */
if (count($animals) === 1) {
    header('Location: commander_pendentif.php?id_animal=' . $animals[0]['id']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Choisir mon animal</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <main class="page-content">

        <h1>Choisissez l’animal pour le pendentif</h1>

        <?php if (empty($animals)): ?>
            <!-- ❌ Aucun animal -->
            <div class="empty-state">
                <p>Vous n’avez encore ajouté aucun animal.</p>
                <a href="add_animal.php" class="btn-primary">
                    Ajouter un animal
                </a>
            </div>

        <?php else: ?>
            <!--  Plusieurs animaux -->
            <div class="animals-grid">

                <?php foreach ($animals as $animal): ?>
                    <?php
                    $photos = [];
                    if (!empty($animal['photos'])) {
                        $photos = json_decode($animal['photos'], true);
                    }
                    ?>

                    <div class="animal-card">

                        <?php if (!empty($photos[0])): ?>
                            <img
                                src="uploads/animals/<?= htmlspecialchars($photos[0]) ?>"
                                alt="<?= htmlspecialchars($animal['name']) ?>">
                        <?php else: ?>
                            <div class="no-photo">🐾</div>
                        <?php endif; ?>

                        <h3><?= htmlspecialchars($animal['name']) ?></h3>

                        <a
                            href="commander_pendentif.php?id_animal=<?= $animal['id'] ?>"
                            class="btn-secondary">
                            Choisir
                        </a>

                    </div>
                <?php endforeach; ?>

            </div>
        <?php endif; ?>

    </main>

    <?php include 'includes/footer.php'; ?>

</body>

</html>