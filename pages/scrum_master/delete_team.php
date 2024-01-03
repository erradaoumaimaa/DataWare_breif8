<?php
require_once "../../config/Config.php";
require_once "../../includes/headers/header_scrum_master.php";
require_once "../../classes/Database.php";
require_once "../../classes/TeamManager.php";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["team_id"])) {
    $teamId = $_GET["team_id"];

    // Créez une instance de TeamManager
    $teamManager = new TeamManager($database);

    // Supprimez l'équipe en utilisant la méthode deleteTeam de TeamManager
    $deleteResult = $teamManager->deleteTeam($teamId);

    if ($deleteResult === null) {
        echo "L'équipe a été supprimée avec succès!";
    } else {
        echo "Erreur lors de la suppression de l'équipe : " . $deleteResult;
    }
} else {
    echo "Erreur : ID de l'équipe non fourni.";
}
?>