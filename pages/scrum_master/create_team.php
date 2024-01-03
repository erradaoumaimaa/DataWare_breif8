<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Create Team</title>
</head>

<body class="bg-gray-100">
    <?php
    require_once(dirname(__FILE__) . "/../../config/Config.php");
    require_once "../../includes/headers/header_scrum_master.php";
    require_once "../../classes/Database.php";
    require_once "../../classes/TeamManager.php";

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $name = $_POST["name"];
        $description = $_POST["description"];
        $scrumMasterId = isset($_POST["scrum_master_id"]) ? $_POST["scrum_master_id"] : null;

        // Create an instance of TeamManager
        $teamManager = new TeamManager($database);

        // Create team using the TeamManager class method
        $createResult = $teamManager->createTeam($name, $description, $scrumMasterId);

        if ($createResult === null) {
            echo "Team created successfully!";
        } else {
            echo "Error creating team: " . $createResult;
        }
    }
    ?>
    <div class="container max-w-2xl mx-auto mt-8">
        <h1 class="font-serif text-3xl font-bold underline decoration-gray-400"> Create Team</h1>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <!-- Add your form fields here, e.g., team name, description, and scrum master -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Team Name:</label>
                <input type="text" id="name" name="name" class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:border-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
                <textarea id="description" name="description" class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:border-blue-500" required></textarea>
            </div>

            <div class="mb-4">
                <label for="scrum_master_id" class="block text-sm font-medium text-gray-700">Scrum Master's ID:</label>
                <input type="text" id="scrum_master_id" name="scrum_master_id" class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:border-blue-500" required>
            </div>

            <div class="mb-4">
                <button type="submit" class="bg-blue-500 text-white p-2 rounded-md">Create Team</button>
            </div>
        </form>
    </div>
</body>

</html>
