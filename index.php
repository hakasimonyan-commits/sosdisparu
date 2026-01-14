<?php
// Սկսում ենք session-ը
// Սա անհրաժեշտ է, որպեսզի իմանանք՝ user-ը login եղե՞լ է, թե ոչ
session_start();

// Ներմուծում ենք header.php ֆայլը
// Header-ի մեջ կա HTML-ի սկիզբը (<html>, <head>, <body>) և մենյուն
include 'includes/header.php';
?>

<?php
// Ստուգում ենք՝ արդյոք user-ը login է եղել
// Եթե $_SESSION['user_id'] կա, նշանակում է login OK է
if (isset($_SESSION['user_id'])):
?>
    <!-- եթե user-ը login ա արած → ցույց տուր email եթե չէ → լռի ու error չտա -->
    <p>
        <?php if (isset($_SESSION['email'])): ?>
            Bienvenue <?= htmlspecialchars($_SESSION['email']) ?>
        <?php else: ?>
            Bienvenue
        <?php endif; ?>
        <!-- htmlspecialchars → անվտանգության համար (XSS պաշտպանություն) -->
    </p>
<?php
// Փակում ենք if-ը
endif;
?>

<!-- Էջի հիմնական բովանդակությունը -->
<p>
    Retrouvez votre compagnon grâce au QR code.
</p>

<?php
// Ներմուծում ենք footer.php ֆայլը
// Footer-ի մեջ փակվում են </body> և </html>
include 'includes/footer.php';
?>