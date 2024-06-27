<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // validation nom
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    if (empty($name)) {
        die("Le nom este obligatoire.");
    }
    if (!preg_match("/^[a-zA-ZÀ-ÿ\s\-']*$/u", $name)) {
        die("Le nom peut contenir seulement des lettres, espaces, tirets et apostrophes.");
    }
    

    // validation email
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    if (empty($email)) {
        die("Le email est obligatoire.");
    }
    // verification format email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Le format d'email est invalid.");
    }

    // validation commentaire
    $comment = isset($_POST['comment']) ? $_POST['comment'] : '';
    if (empty($comment)) {
        die("Inserez un commentaire!");
    }

    // validation raiting
    $rating = isset($_POST['rating']) ? $_POST['rating'] : '';
    if (empty($rating) || !is_numeric($rating) || $rating < 1 || $rating > 5) {
        die("Choisissez le nombre d’étoiles");
    }

    
    // Conexiune bd
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=database', 'username', 'password');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepared statement pour inserer dans la  baza de date
        $stmt = $pdo->prepare("INSERT INTO comments (name, email, comment, rating) VALUES (:name, :email, :comment, :rating)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':comment', $comment);
        $stmt->bindParam(':rating', $rating);

        $stmt->execute();

        echo "Commentaire ajouté avec succès!";
    } catch (PDOException $e) {
        echo "Erreur lors de l'insertion dans la base de données: " . $e->getMessage();
    }
}

