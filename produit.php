<?php


session_start();
// Սկսում ենք սեսիան (օգտագործողի տվյալների համար)

require 'db.php';
// Միացնում ենք տվյալների բազան (PDO կապ)


// =======================
// 1️⃣ Ստուգում ենք՝ կա՞ id_animal URL-ում
// =======================
if (!isset($_GET['id_animal'])) {
    // Եթե URL-ում չկա id_animal → կանգնեցնում ենք էջը
    die("Animal non trouvé.");
}


// =======================
// 2️⃣ Ստանում ենք կենդանու ID-ն URL-ից
// produit.php?id_animal=7 → $id_animal = 7
// =======================
$id_animal = (int) $_GET['id_animal'];


// =======================
// 3️⃣ Բազայից վերցնում ենք տվյալ կենդանուն
// animals = աղյուսակ
// id = կենդանու ID
// =======================
$stmt = $pdo->prepare("SELECT * FROM animals WHERE id = ?");
$stmt->execute([$id_animal]);
$animal = $stmt->fetch();


// =======================
// 4️⃣ Եթե կենդանին գոյություն չունի → կանգ
// =======================
if (!$animal) {
    die("Animal introuvable.");
}

// =======================
// PRODUCT — pendentif
// =======================
$stmtProd = $pdo->prepare("SELECT * FROM products WHERE id = 1");
$stmtProd->execute();
$product = $stmtProd->fetch();

if (!$product) {
    die("Produit introuvable.");
}

?>


// =======================
// 5️⃣ Միացնում ենք QR code գրադարանը
// (հիմա կարելի է, քանի որ $animal արդեն կա)
// =======================

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Pendentif QR anti-perte</title>

    <!-- Միացնում ենք CSS ֆայլը -->
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include 'includes/header.php'; ?>


    <main class="page-content">
        <div class="produit">

            <?php
            // =======================
            // 9️⃣ Կենդանու նկարների JSON-ը դարձնում ենք array
            // =======================
            $photos = [];
            if (!empty($animal['photos'])) {
                $photos = json_decode($animal['photos'], true);
            }
            ?>

            <!-- ԿԵՆԴԱՆՈՒ ՏՎՅԱԼՆԵՐ -->
            <div class="animal-preview">
                <!-- Կենդանու անունը -->
                <h2><?php echo htmlspecialchars($animal['name']); ?></h2>

                <!-- Առաջին նկարը, եթե կա -->
                <?php if (!empty($photos[0])): ?>
                    <img
                        src="uploads/animals/<?php echo htmlspecialchars($photos[0]); ?>"
                        alt="Photo de <?php echo htmlspecialchars($animal['name']); ?>"
                        style="max-width:200px;border-radius:8px;">
                <?php else: ?>
                    <p>Aucune photo disponible.</p>
                <?php endif; ?>
            </div>

            <!-- QR կոդ -->
            <div class="qr-preview">
                <h3>QR կոդ</h3>
                <img src="qr_code.php?id=<?= $animal['id'] ?>" alt="QR code">
            </div>
            <!-- ՊԵՆԴԵՆՏԻՖԻ ՆԿԱՐ -->
            <div class="img-box">
                <img src="images/chien_pendentif.jpg" alt="Pendentif QR chien">
            </div>

            <!-- ՊԵՆԴԵՆՏԻՖԻ ՏՎՅԱԼՆԵՐ -->
            <div class="info-box">

                <h1>Pendentif QR anti-perte pour chien et chat</h1>

                <p class="prix">Prix : 8,88 €</p>

                <!-- Պատվերի ձև -->
                <form method="post" action="panier.php">

                    <label>Type de QR</label>
                    <select name="type_qr">
                        <option value="gravure_laser">Gravure laser</option>
                        <option value="imprime">QR imprimé</option>
                    </select>

                    <label>Personnalisation</label>
                    <select name="personnalisation">
                        <option value="qr_seul">QR + photo + nom + téléphone</option>
                        <option value="qr_seul">QR + nom + téléphone</option>
                        <option value="qr_photo_tel">QR seul</option>
                    </select>

                    <!-- Թաքնված տվյալներ -->
                    <input type="hidden" name="animal_id" value="<?php echo $animal['id']; ?>">
                    <input type="hidden" name="produit" value="pendentif_qr">
                    <input type="hidden" name="prix" value="8.88">

                    <button type="submit">Ajouter au panier</button>
                    <button type="submit">Acheter maintenant</button>
                </form>

                <p>
                    Si votre animal est trouvé, le QR permet de contacter immédiatement le propriétaire.
                </p>

            </div>

        </div>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>

</html>