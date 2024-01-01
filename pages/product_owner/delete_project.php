<?php
require_once "../../config/Config.php";
require_once "../../classes/Database.php";
require_once "../../classes/ProjectManager.php";

// Check if the project ID is provided in the request
if (isset($_GET['id'])) {
    $projectId = $_GET['id'];

    // Assuming you have an instance of ProjectManager
    $projectManager = new ProjectManager($database);

    // Attempt to delete the project
    $result = $projectManager->deleteProject($projectId);

    // Send a JSON response indicating success or failure
    header('Content-Type: application/json');
    echo json_encode(['success' => $result === null, 'error' => $result]);
    exit;
}
