<?php
require 'includes/auth.php'; //  ստուգում է՝ user-ը login է, թե ոչ
require 'db.php';            //  PDO connection (մեկ անգամ, կենտրոնացված)
include 'includes/header.php';
// Վերցնում ենք բոլոր կենդանիները DB-ից
$stmt = $pdo->query("SELECT * FROM animals ORDER BY created_at DESC");
$animals = $stmt->fetchAll(); // Բոլոր rows array-ում
?>


<main class="container">
    <h2>Liste des animaux</h2>
    <p><a href="add_animal.php">Ajouter un nouvel animal</a></p>

    <?php if (count($animals) === 0): ?>
        <p>Aucun animal ajouté pour le moment.</p>
    <?php else: ?>
        <div class="table-wrapper">
            <table border="1" cellpadding="5" cellspacing="0">
                <tr>
                    <th>Nom</th>
                    <th>Type</th>
                    <th>Sexe</th>
                    <th>Date de naissance</th>
                    <th>Couleur</th>
                    <th>Race</th>
                    <th>ID / Puce</th>
                    <th>Problèmes de santé</th>
                    <th>Photos</th>
                    <th>QR Code</th> <!-- նոր սյուն QR կոդի համար -->
                </tr>
                <?php foreach ($animals as $animal): ?>
                    <tr>
                        <td><?= htmlspecialchars($animal['name']) ?></td>
                        <td><?= htmlspecialchars($animal['type']) ?></td>
                        <td><?= htmlspecialchars($animal['gender']) ?></td>
                        <td><?= htmlspecialchars($animal['birth_date']) ?></td>
                        <td><?= htmlspecialchars($animal['color']) ?></td>
                        <td><?= htmlspecialchars($animal['breed']) ?></td>
                        <td><?= htmlspecialchars($animal['id_chip']) ?></td>
                        <td><?= nl2br(htmlspecialchars($animal['health_issues'])) ?></td>
                        <td class="photos-cell">
                            <?php
                            $photos = json_decode($animal['photos'], true);
                            if ($photos) {
                                foreach ($photos as $photo) {
                                    echo "<img src='uploads/animals/$photo'>";
                                }
                            }
                            ?>
                        </td>
                        <td>
                            <img src="qr_code.php?id=<?= $animal['id'] ?>" width="100"> <!-- QR Code պատկեր -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>
</main>


<?php
include 'includes/footer.php';
?>