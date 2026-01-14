<?php
// Connexion DB + session
require 'db.php';
session_start();

// Tableau des erreurs
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Données du formulaire
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validation email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Adresse email invalide.";
    }

    // Si pas d'erreurs → vérification DB
    if (empty($errors)) {

        // Recherche de l'utilisateur
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Vérification email + mot de passe
        if (!$user || !password_verify($password, $user['password'])) {
            $errors[] = "Email ou mot de passe incorrect.";
        } else {
            // Connexion réussie → session
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['first_name'] = $user['first_name']; // ← անունը
            $_SESSION['last_name']  = $user['last_name'];  // ← ազգանունը (ըստ ցանկության)

            // Redirection vers l'accueil
            header('Location: index.php');
            exit;
        }
    }
}
?>

<?php include 'includes/header.php'; ?>



<h2>Se connecter</h2>

<?php
// Affichage des erreurs
foreach ($errors as $error) {
    echo "<p style='color:red;'>$error</p>";
}
?>

<form action="login.php" method="post">

    <label>Email :</label><br>
    <input type="email" name="email" value="haka.simonyan@gmail.com" required>
    <br><br>

    <div class="password-wrapper">
        <input type="password" id="login_password" name="password" value="12345#Aa" required>
        <span class="toggle-password" onclick="togglePassword('login_password', this)">🙈</span>
    </div>


    <button type="submit">Se connecter</button>
</form>

<p>
    <a href="register.php">Créer un compte</a>
</p>
<script src="script.js"></script>


<?php include 'includes/footer.php'; ?>