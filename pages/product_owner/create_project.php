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
   
    $projectManager = new ProjectManager($database);
    ?>

    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white p-8 rounded w-96 shadow-md max-w-md rounded-2xl">

            <h2 class="text-2xl text-center mb-6">Create Project</h2>

            <form action="create_project.php" method="POST" class="space-y-4">

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Project Name:</label>
                    <input type="text" id="name" name="name"
                        class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:border-blue-500" required>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
                    <textarea id="description" name="description"
                        class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:border-blue-500"
                        required></textarea>
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">Deadline:</label>
                    <input type="date" id="end_date" name="end_date"
                        class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:border-blue-500" required>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status:</label>
                    <input type="text" id="status" name="status"
                        class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:border-blue-500" required>
                </div>

                <div>
                    <label for="scrum_master" class="block text-sm font-medium text-gray-700">Scrum Master:</label>
                    <select id="scrum_master" name="scrum_master"
                        class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:border-blue-500" required>
                        <?php
                        $scrumMasters = $projectManager->getScrumMasters();

                        foreach ($scrumMasters as $scrumMaster) {
                            echo "<option value='{$scrumMaster['email']}'>{$scrumMaster['email']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <button type="submit"
                    class="w-full text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">Create
                    Project</button>

            </form>
        </div>
    </div>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST["name"];
    $description = $_POST["description"];
    $endDate = $_POST["end_date"];
    $status = $_POST["status"];
    $scrumMasterEmail = $_POST["scrum_master"];

    // Assuming you have a variable for the Product Owner's user ID, replace $productOwnerId with your actual variable
    $productOwnerId = 37; // Replace this with the actual Product Owner's user ID

    // Create the project
    $result = $projectManager->createProject($name, $description, $endDate, $status, $productOwnerId, $scrumMasterEmail);

    if ($result === null) {
        // Success
        echo "Redirecting..."; // Add this line for debugging
        header("Location: /DataWare_breif8/pages/product_owner/index.php");
        exit();
    
    } else {
        // Handle the error
        echo "Error: " . $result;
    }
}

?>
