<?php

require_once "../../config/Config.php";
require_once "../../includes/headers/header_scrum_master.php";
require_once "../../classes/Database.php";
require_once "../../classes/TeamManager.php";

// Vérifiez si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérez les données du formulaire
    $teamId = $_POST["team_id"];
    $teamName = $_POST["team_name"];
    $teamDescription = $_POST["team_description"];
    $scrumMasterId = $_POST["scrum_master_id"];

    // Créez une instance de TeamManager
    $teamManager = new TeamManager($database);

    // Mettez à jour l'équipe
    $updateResult = $teamManager->updateTeam($teamId, $teamName, $teamDescription, $scrumMasterId);

    // Vérifiez si la mise à jour a réussi
    if ($updateResult === null) {
        echo "Team updated successfully!";
    } else {
        echo "Error updating team: " . $updateResult;
    }
} else {
    echo "Error: Form not submitted.";
}

?>
