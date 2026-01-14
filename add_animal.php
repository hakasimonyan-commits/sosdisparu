<?php
include 'includes/header.php';
require 'includes/auth.php';

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un animal</title>
    <script>
        // Fonction qui affiche ou cache le champ "Précisez si autre" selon la sélection
        function checkType(select) {
            const otherField = document.getElementById('otherTypeField'); // On prend le div du champ texte

            if (select.value === 'autre') { // Si "Autre" est choisi
                otherField.style.display = 'block'; // Affiche le champ
                otherField.querySelector('input').required = true; // Rend le champ obligatoire
            } else { // Sinon
                otherField.style.display = 'none'; // Cache le champ
                otherField.querySelector('input').required = false; // Champ non obligatoire
            }
        }
    </script>
</head>

<body>
    <h1>Ajouter un animal</h1>

    <!-- Formulaire qui envoie les données à add_animal_pdo.php via POST -->
    <form action="add_animal_pdo.php" method="post" enctype="multipart/form-data">

        Nom:<br>
        <input type="text" name="name" required> <!-- Nom de l'animal, champ obligatoire -->
        <br><br>

        Type:<br>
        <select name="type" required onchange="checkType(this)"> <!-- Choix du type d'animal -->
            <option value="chien">Chien</option> <!-- Option chien -->
            <option value="chat">Chat</option> <!-- Option chat -->
            <option value="autre">Autre</option> <!-- Option autre déclenche le champ texte -->
        </select>
        <br><br>

        <!-- Champ texte qui apparaît si "Autre" est sélectionné -->
        <div id="otherTypeField" style="display:none;">
            Précisez si autre:<br>
            <input type="text" name="type_autre"> <!-- Permet de préciser le type si "Autre" -->
            <br><br>
        </div>

        Sexe:<br>
        <select name="gender" required> <!-- Sélection du sexe de l'animal -->
            <option value="male">Mâle</option>
            <option value="female">Femelle</option>
        </select>
        <br><br>

        Date de naissance:<br>
        <input type="date" name="birth_date"> <!-- Date de naissance de l'animal -->
        <br><br>

        Couleur:<br>
        <input type="text" name="color"> <!-- Couleur de l'animal -->
        <br><br>

        Race:<br>
        <input type="text" name="breed"> <!-- Race de l'animal -->
        <br><br>

        Puce / ID:<br>
        <input type="text" name="id_chip"> <!-- Numéro de puce ou ID de l'animal -->
        <br><br>

        Problèmes de santé / Message:<br>
        <textarea name="health_issues"></textarea> <!-- Champ pour notes santé ou message -->
        <br><br>

        Photos (1 à 3):<br>
        <input type="file" name="photos[]" multiple> <!-- Upload de plusieurs photos -->
        <br><br>

        <button type="submit">Ajouter</button> <!-- Bouton pour envoyer le formulaire -->

    </form>
</body>

</html>

<?php include 'includes/footer.php'; ?>