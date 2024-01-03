<?php
// Importe la classe Database
require_once(dirname(__FILE__) . "/../classes/Database.php");

// Paramètres connexion Data base :
$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "db_dw";

// instance de la classe Database
$database = new Database($dbHost, $dbUser, $dbPassword, $dbName);

// Récupérer la connexion PDO
$con = $database->getConnection();
?>
