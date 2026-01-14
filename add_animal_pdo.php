<?php
require 'includes/auth.php';
?>
<?php
// Error reporting – ցույց է տալիս բոլոր սխալները, շատ կարևոր development-ի համար
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// DB connection setup - PDO կապ
$host = 'localhost'; // MySQL host
$db   = 'qr_animaux'; // Ձեր database-ի անունը
$user = 'root'; // MAMP-ի default օգտվող
$pass = 'root'; // MAMP-ի default password
$charset = 'utf8mb4'; // Character set

// DSN + options կարգավորում
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Սխալ → exception
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Ստանալու ենք associative array
];

// PDO connection ստեղծում
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (Exception $e) {
    die('Erreur DB : ' . $e->getMessage()); // Եթե DB error → կանգնեցրու script-ը
}

// Ստուգում ենք, որ form-ը ուղարկվել է POST method-ով
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Ստանում ենք form-ի բոլոր դաշտերը
    $name = $_POST['name'] ?? ''; // Կենդանու անուն
    $type = $_POST['type'] ?? ''; // Տիպ (chien/chat/autre)

    // Եթե օգտատերը ընտրել է "Autre" → վերցնում ենք textbox-ից արժեքը
    if ($type === 'autre') {
        $type = $_POST['type_autre'] ?? '';
        if (empty($type)) { // Ստուգում, որ textbox-ը լցված է
            echo "<p style='color:red;'>Խնդրում ենք նշել այլ տիպը:</p>";
            exit; // կանգնեցնում ենք script-ը եթե դատարկ է
        }
    }

    $gender = $_POST['gender'] ?? ''; // Արու/Մորու
    $birth_date = $_POST['birth_date'] ?? null; // Ծննդյան ամսաթիվ
    $color = $_POST['color'] ?? ''; // Գույն
    $breed = $_POST['breed'] ?? ''; // Ցեղ
    $id_chip = $_POST['id_chip'] ?? ''; // ID/Puce
    $health_issues = $_POST['health_issues'] ?? ''; // Առողջական խնդիրներ

    // Ֆայլերի upload
    $uploaded_files = []; // Արդյունք array՝ filenames պահելու համար
    if (!empty($_FILES['photos']['name'][0])) { // Ստուգում ենք, որ ֆայլ կա
        foreach ($_FILES['photos']['tmp_name'] as $index => $tmpName) {
            $filename = basename($_FILES['photos']['name'][$index]); // Ստանում ենք ֆայլի անունը
            $target = "uploads/animals/" . $filename; // Նպատակային path

            // Փորձել տեղափոխել ֆայլը
            if (move_uploaded_file($tmpName, $target)) {
                $uploaded_files[] = $filename; // Ավելացնում ենք array-ում
            } else {
                echo "<p style='color:red;'>Ֆայլը չի տեղավորվել: $filename</p>";
            }
        }
    }

    // DB insert – try/catch safety
    try {
        $stmt = $pdo->prepare("INSERT INTO animals 
            (name, type, gender, birth_date, color, breed, id_chip, health_issues, photos)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // execute PDO statement → ֆայլերի անունները պահվում են JSON array
        $stmt->execute([
            $name,
            $type,
            $gender,
            $birth_date,
            $color,
            $breed,
            $id_chip,
            $health_issues,
            json_encode($uploaded_files)
        ]);

        $animal_id = $pdo->lastInsertId();

        header("Location: produit.php?id_animal=" . $animal_id);
        exit;

        // Հաջողության հաղորդագրություն

    } catch (Exception $e) {
        echo "<p style='color:red;'>Erreur lors de l'insertion DB : " . $e->getMessage() . "</p>";
    }
}

include 'includes/footer.php';
