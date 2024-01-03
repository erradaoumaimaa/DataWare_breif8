<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Product Owner</title>
</head>

<body class="bg-gray-100">
<?php
 require_once(dirname(__FILE__) . "/../../config/Config.php");
 require_once "../../includes/headers/header_product_owner.php";
 require_once "../../classes/Database.php";
 require_once "../../classes/ProjectManager.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $projectId = $_POST["project_id"];
$name = $_POST["name"];
$description = $_POST["description"];
$startDate = $_POST["start_date"];
$endDate = $_POST["end_date"];
$status = $_POST["status"];
$scrumMasterId = isset($_POST["scrum_master_id"]) ? $_POST["scrum_master_id"] : null;
error_log("Scrum Master ID: " . $scrumMasterId);
var_dump($_POST);

    // Create an instance of ProjectManager
    $projectManager = new ProjectManager($database);

    // Update project using the ProjectManager class method
    $updateResult = $projectManager->updateProject($projectId, $name, $description, $endDate, $status, $scrumMasterId);
    var_dump($updateResult);

    if ($updateResult === null) {
        echo "Project updated successfully!";
    } else {
        echo "Error updating project: " . $updateResult;
    }
} else {
   
    echo "Error: Form not submitted.";
}
?>
