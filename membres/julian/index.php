<?php 

require_once "config.php";
require_once "../../model/Recipe.php";






try {
    $pdo = new PDO(DB_TYPE . ":host=" . DB_HOST . ";dbname=" . DB_NAME . ";port=" . DB_PORT . ";charset=" . DB_CHARSET, DB_LOGIN, DB_PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}



require_once "polishcake.php";



/* var_dump(Recipe::getRecipeById($pdo, 1)); */