<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db.php';

$errors = [];
$first_name = $last_name = $phone = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // տվյալներ
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $phone      = trim($_POST['phone'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $password   = $_POST['password'] ?? '';
    $confirm    = $_POST['password_confirm'] ?? '';

    // validations
    if ($first_name === '') $errors[] = "Le prénom est obligatoire.";
    if ($last_name === '')  $errors[] = "Le nom est obligatoire.";

    $country_code = $_POST['country_code'] ?? '';
    $phone = $_POST['phone'] ?? '';

    if ($country_code === '') {
        $errors[] = "Veuillez choisir un code pays.";
    }

    if ($phone === '') {
        $errors[] = "Le numéro de téléphone est obligatoire.";
    } elseif (!preg_match('/^[0-9]{6,10}$/', $phone)) {
        $errors[] = "Numéro de téléphone invalide.";
    }

    if ($email === '') {
        $errors[] = "L’email est obligatoire.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide.";
    }

    if ($password === '') {
        $errors[] = "Veuillez saisir un mot de passe.";
    } elseif (
        !preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/', $password)
    ) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères,
    avec 1 majuscule, 1 minuscule et 1 chiffre.";
    }

    if ($password !== $confirm) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    // insert
    if (empty($errors)) {
        $stmt = $pdo->prepare(
            "INSERT INTO users (first_name, last_name, phone, email, password)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $first_name,
            $last_name,
            $phone,
            $email,
            password_hash($password, PASSWORD_DEFAULT)
        ]);

        header('Location: login.php');
        exit;
    }
}


/*************************************************
 * VIEW
 *************************************************/
include 'includes/header.php';
?>

<main class="container">
    <h2>Créer un compte</h2>

    <?php if ($errors): ?>
        <div class="errors">
            <?php foreach ($errors as $e): ?>
                <p><?= htmlspecialchars($e) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <input type="text" name="first_name" placeholder="Prénom"
            value="<?= htmlspecialchars($first_name) ?>">

        <input type="text" name="last_name" placeholder="Nom"
            value="<?= htmlspecialchars($last_name) ?>">

        <div class="phone-group">

            <select name="country_code" id="country_code" required>
                <option value="">Code</option>
                <option value="+33">🇫🇷 +33</option>
                <option value="+32">🇧🇪 +32</option>
                <option value="+49">🇩🇪 +49</option>
                <option value="+34">🇪🇸 +34</option>
                <option value="+39">🇮🇹 +39</option>
            </select>

            <input
                type="tel" name="phone" id="phone" placeholder="Numéro de téléphone" inputmode="numeric">

        </div>



        <input type="email" name="email" placeholder="Email"
            value="<?= htmlspecialchars($email) ?>">

        <div class="password-wrapper">
            <input type="password" name="password" id="password" placeholder="Mot de passe " required>
            <span class="toggle-password" onclick="togglePassword('password', this)">🙈</span>
            <small class="help">
                Mot de passe : minimum 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre
            </small>
        </div>

        <div class="password-wrapper">
            <input type="password" name="password_confirm" id="password_confirm" placeholder="Confirmation">
            <span class="toggle-password" onclick="togglePassword('password_confirm', this)">🙈</span>
        </div>

        <button type="submit">Créer le compte</button>
    </form>
</main>

<?php include 'includes/footer.php'; ?>