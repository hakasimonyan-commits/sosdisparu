<?php
// Միացնում ենք DB կապը
require 'db.php';

// Ստուգում ենք՝ կա՞ id URL-ում
// Օրինակ՝ animal.details.php?id=3
if (!isset($_GET['id'])) {
    die("Animal introuvable");
}

// id-ն դարձնում ենք թիվ (անվտանգության համար)
$id = (int) $_GET['id'];

// Պատրաստում ենք հարցումը DB-ի համար
$stmt = $pdo->prepare("SELECT * FROM animals WHERE id = ?");
$stmt->execute([$id]);

// Վերցնում ենք կենդանու տվյալները
$animal = $stmt->fetch();

// Եթե նման կենդանի չկա
if (!$animal) {
    die("Animal introuvable");
}

// Նկարները JSON-ից դարձնում ենք array
$photos = json_decode($animal['photos'], true);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($animal['name']) ?></title>
</head>

<body>

    <h1><?= htmlspecialchars($animal['name']) ?></h1>

    <p><strong>Type :</strong> <?= htmlspecialchars($animal['type']) ?></p>
    <p><strong>Sexe :</strong> <?= htmlspecialchars($animal['gender']) ?></p>
    <p><strong>Date de naissance :</strong> <?= htmlspecialchars($animal['birth_date']) ?></p>
    <p><strong>Couleur :</strong> <?= htmlspecialchars($animal['color']) ?></p>
    <p><strong>Race :</strong> <?= htmlspecialchars($animal['breed']) ?></p>

    <?php if (!empty($animal['health_issues'])): ?>
        <p><strong>Problèmes de santé :</strong><br>
            <?= nl2br(htmlspecialchars($animal['health_issues'])) ?>
        </p>
    <?php endif; ?>

    <h3>Photos</h3>

    <?php
    if ($photos) {
        foreach ($photos as $photo) {
            echo "<img src='uploads/animals/$photo' width='200' style='margin:10px;'>";
        }
    }
    ?>

    <hr>

    <p>
        📞 <strong>Contact :</strong><br>
        +33 1 23 45 67 89<br>
        contact@qr-animaux.com
    </p>

</body>

</html>