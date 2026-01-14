<?php
/* =========================================================
   🔧 DEV — ՍԽԱԼՆԵՐԻ ՑՈՒՑԱԴՐՈՒՄ
   (միայն ծրագրավորման ժամանակ)
   ========================================================= */
ini_set('display_errors', 1);
error_reporting(E_ALL);

/* =========================================================
   🗄️ ՄԻԱՑՈՒՄ ԲԱԶԱՅԻՆ (MySQL)
   ========================================================= */
$pdo = new PDO(
    "mysql:host=localhost;dbname=qr_animaux;charset=utf8mb4",
    "root",
    "root",
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);

/* =========================================================
   🐾 QR-ից ստանում ենք կենդանու ID-ն
   Օրինակ → view_animal.php?id=7
   ========================================================= */
if (!isset($_GET['id'])) {
    die("Animal introuvable");
}

$animal_id = (int)$_GET['id'];

/* =========================================================
   📋 ԿԵՆԴԱՆՈՒ ՏՎՅԱԼՆԵՐԸ ԲԱԶԱՅԻՑ
   ========================================================= */
$stmt = $pdo->prepare("SELECT * FROM animals WHERE id = ?");
$stmt->execute([$animal_id]);
$animal = $stmt->fetch();

if (!$animal) {
    die("Animal introuvable");
}

/* =========================================================
   📨 POST → GPS + MESSAGE + SMS (Twilio)
   Երբ գտնողը սեղմում է «Գտել եմ կենդանուն»
   ========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alert'])) {

    // 📍 Գեոլոկացիա
    $lat = $_POST['lat'] ?? '';
    $lng = $_POST['lng'] ?? '';

    // 📞 Գտնողի հեռախոս (ոչ պարտադիր)
    $finder_phone = trim($_POST['finder_phone'] ?? '');

    // 💬 Գտնողի հաղորդագրություն
    $finder_message = trim($_POST['finder_message'] ?? '');

    /* =====================================================
       📲 TWILIO ԿԱՐԳԱՎՈՐՈՒՄ
       ===================================================== */
    //utilisation des variables d'environnement

    /* =====================================================
       ✉️SMS ՏԵՔՍՏ — ՖՐԱՆՍԵՐԵՆ
       ===================================================== */
    $message  = "🐾 Bonne nouvelle !\n";
    $message .= "Votre animal a été retrouvé.\n\n";

    if ($finder_message !== '') {
        $message .= "💬 Message du trouveur :\n$finder_message\n\n";
    }

    $message .= "📍 Localisation :\nhttps://maps.google.com/?q=$lat,$lng\n\n";

    if ($finder_phone !== '') {
        $message .= "📞 Téléphone du trouveur : $finder_phone\n";
    } else {
        $message .= "📞 Le trouveur n’a pas laissé de numéro\n";
    }

    /* =====================================================
       SMS ՈՒՂԱՐԿՈՒՄ
       ===================================================== */
    $data = http_build_query([
        'From' => $from,
        'To'   => $to,
        'Body' => $message
    ]);

    $ch = curl_init("https://api.twilio.com/2010-04-01/Accounts/$sid/Messages.json");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$sid:$token");
    curl_exec($ch);
    curl_close($ch);

    echo "OK";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($animal['name']) ?> – Animal retrouvé</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <main class="container">

        <!-- 🐾 ԿԵՆԴԱՆՈՒ ՏՎՅԱԼՆԵՐ -->
        <h1><?= htmlspecialchars($animal['name']) ?></h1>
        <a class="btn call" href="tel:<?= htmlspecialchars($animal['owner_phone']) ?>">
            📞 Contacter le propriétaire
        </a>
        <?php

        if ($animal['type'] === 'chat') {
            $icon = '🐱';
            $label = 'Chat';
        } else {
            $icon = '🐶';
            $label = 'Chien';
        }
        ?>
        <p><strong>Type :</strong> <?= $icon ?> <?= $label ?></p>
        <p><strong> Sex :</strong> <?= htmlspecialchars($animal['gender']) ?></p>
        <p><strong>ID :</strong> <?= htmlspecialchars($animal['id_chip']) ?></p>
        <p><strong>Santé :</strong> <?= htmlspecialchars($animal['health_issues'] ?: 'Aucun problème connu') ?></p>

        <p style="color:red;">⚠️ Cet animal est déclaré perdu</p>

        <!--  ԼՈՒՍԱՆԿԱՐՆԵՐ -->
        <div class="photos">
            <?php
            $photos = json_decode($animal['photos'] ?? '', true);
            if ($photos) {
                foreach ($photos as $p) {
                    echo "<img src='uploads/animals/$p' style='max-width:200px;margin:5px;'>";
                }
            }
            ?>
        </div>

        <!-- 📞 ԿՈՃԱԿՆԵՐ -->
        <a class="btn call" href="tel:<?= htmlspecialchars($animal['owner_phone']) ?>">
            📞 Appeler le propriétaire
        </a><br><br>

        <a class="btn vet" target="_blank"
            href="https://www.google.com/maps/search/vétérinaire+autour+de+moi/">
            🏥 Vétérinaire à proximité
        </a><br><br>

        <!--  ԳՏՆԵԼ ԵՄ -->
        <button class="btn found" id="foundBtn">
            📍 J’ai trouvé cet animal
        </button>

        <!-- 📨 ՀԱՂՈՐԴԱԳՐՈՒԹՅԱՆ ՁԵՎ -->
        <div id="alertBox" style="display:none;margin-top:15px;">
            <textarea id="finder_message"
                placeholder="Décrivez où se trouve l’animal et son état"
                rows="4" style="width:100%;"></textarea>

            <input type="tel" id="finder_phone" placeholder="Votre téléphone (facultatif)">
            <br><br>
            <button class="btn send" id="sendAlert">📨 Envoyer le message</button>
        </div>

    </main>

    <script>
        let lat = '',
            lng = '';
        const foundBtn = document.getElementById('foundBtn');
        const alertBox = document.getElementById('alertBox');

        foundBtn.onclick = function() {

            if (!navigator.geolocation) {
                alert("❌ La géolocalisation n’est pas supportée");
                return;
            }

            alert("📍 Récupération de la position…");

            navigator.geolocation.getCurrentPosition(
                function(pos) {
                    lat = pos.coords.latitude;
                    lng = pos.coords.longitude;
                    alert("✅ Position obtenue");
                    alertBox.style.display = 'block';
                },
                function(err) {
                    alert("❌ Erreur GPS : " + err.message);
                }
            );
        };

        document.getElementById('sendAlert').onclick = () => {
            fetch("", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "alert=1" +
                        "&lat=" + lat +
                        "&lng=" + lng +
                        "&finder_phone=" + encodeURIComponent(document.getElementById('finder_phone').value) +
                        "&finder_message=" + encodeURIComponent(document.getElementById('finder_message').value)
                })
                .then(() => alertBox.innerHTML = "✅ Message envoyé avec succès");
        };
    </script>

</body>

</html>