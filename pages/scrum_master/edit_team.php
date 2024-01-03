<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Scrum Master</title>
</head>

<body class="bg-gray-100">
    <?php
    require_once "../../includes/headers/header_scrum_master.php";

    require_once "../../config/Config.php";

    require_once "../../classes/TeamManager.php";
    require_once "../../classes/Database.php";

    // Create an instance of TeamManager
    $teamManager = new TeamManager($database);

    // Assume you have the team ID from somewhere (e.g., from the URL parameter)
    $teamId = $_GET['team_id'] ?? null;

    // Fetch team details from the database based on the team ID
    $team = $teamManager->getTeamById($teamId);

    ?>

    <div class="flex items-center justify-center h-screen">
        <div class="container mx-auto">
            <h1 class="text-center text-2xl font-semibold mb-4">Edit Team: <?php echo $team->getName(); ?></h1>
            <form action="update_team.php" method="POST" class="max-w-md mx-auto bg-white p-8 border rounded-md shadow-md">
                <input type="hidden" name="team_id" value="<?php echo $team->getId(); ?>">
                <div class="mb-4">
                    <label for="team_name" class="block text-sm font-medium text-gray-700">Team Name:</label>
                    <input type="text" id="team_name" name="team_name" class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:border-blue-500" value="<?php echo $team->getName(); ?>" required>
                </div>
                <div class="mb-4">
                    <label for="team_description" class="block text-sm font-medium text-gray-700">Description:</label>
                    <textarea id="team_description" name="team_description" class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:border-blue-500" required><?php echo $team->getDescription(); ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="scrum_master_id" class="block text-sm font-medium text-gray-700">Scrum Master ID:</label>
                    <input type="text" id="scrum_master_id" name="scrum_master_id" class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:border-blue-500" value="<?php echo $team->getScrumMasterId(); ?>" required>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">Save Changes</button>
            </form>
        </div>
    </div>
</body>

</html>
