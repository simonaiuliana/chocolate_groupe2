<?php
// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['calculer'])) {
    // Connexion à la base de données (remplacez par vos informations de connexion)
    $servername = "localhost";
    $username = "username";
    $password = "password";
    $dbname = "nom_de_la_base_de_données";

    // Établissement de la connexion
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérification de la connexion
    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    // Récupération du nombre de personnes saisi
    $nr_personnes = $_POST['nr_personnes'];

    // Requête pour obtenir les ingrédients depuis la base de données
    $sql = "SELECT ingredient, cantitate FROM retete_ingredient WHERE id_reteta = 1"; // Remplacez id_reteta par l'identifiant de votre recette ou tout autre critère d'identification

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Création d'une variable pour stocker le résultat
        $output = "<ul>";
        // Affichage de chaque ingrédient calculé pour le nombre de personnes saisi
        while($row = $result->fetch_assoc()) {
            $ingredient = $row["ingredient"];
            $cantitate = $row["cantitate"] * $nr_personnes;
            $output .= "<li>$cantitate $ingredient</li>";
        }
        $output .= "</ul>";
        
        // Affichage du résultat dans le div avec l'id "resultat" et appel de la fonction JavaScript pour l'afficher
        echo "<script>document.getElementById('resultat').innerHTML = '$output'; afiseazaRezultat();</script>";
    } else {
        echo "Aucun résultat trouvé.";
    }

    // Fermeture de la connexion à la base de données
    $conn->close();
}

