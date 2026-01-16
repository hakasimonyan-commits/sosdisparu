<?php
require 'db.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/vendor/autoload.php';


if (!isset($_GET['id'])) {
    die("Animal introuvable");
}

$animal_id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM animals WHERE id = ?");
$stmt->execute([$animal_id]);
$animal = $stmt->fetch();

if (!$animal) {
    die("Animal introuvable");
}

$photos = json_decode($animal['photos'] ?? '[]', true);
?>

<?php include 'includes/header.php'; ?>

<main class="container">

    <h1><?= htmlspecialchars($animal['name']) ?></h1>
    <p><strong>Type :</strong> <?= htmlspecialchars($animal['type']) ?></p>
    <p><strong>Race :</strong> <?= htmlspecialchars($animal['breed']) ?></p>

    <?php if ($photos): ?>
        <div class="photos">
            <?php foreach ($photos as $photo): ?>
                <img src="uploads/animals/<?= htmlspecialchars($photo) ?>" alt="animal">
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- 📍 Bouton principal -->
    <button id="foundBtn" class="btn-found">
        📍 J’ai trouvé cet animal
    </button>

    <!-- 📨 Formulaire caché -->
    <form id="alertForm" method="POST" action="send_sms.php" style="display:none;">
        <input type="hidden" name="animal_id" value="<?= $animal['id'] ?>">
        <input type="hidden" name="lat" id="lat">
        <input type="hidden" name="lng" id="lng">


        <textarea name="finder_message"
            placeholder="Où se trouve l’animal ? État, détails…"></textarea>

        <input type="tel" name="finder_phone"
            placeholder="Votre téléphone (facultatif)">

        <button type="submit">📨 Envoyer le message</button>
    </form>

    <a target="_blank"
        href="https://www.google.com/maps/search/vétérinaire+autour+de+moi/"
        class="vet-link">
        🏥 Vétérinaire à proximité
    </a>

</main>

<script src="/assets/view_animal.js"></script>
<?php include 'includes/footer.php'; ?>