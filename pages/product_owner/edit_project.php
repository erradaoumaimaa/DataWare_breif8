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

    // Check if the project ID is provided in the URL
    if (isset($_GET['id'])) {
        $projectId = $_GET['id'];
    
        // Use the correct variable name here
        $projectData = $projectManager->getProjectById($projectId);
    } else {
        // Handle error if project ID is not provided
        echo "Error: Project ID not provided.";
        exit();
    }
    $startDate = date('Y-m-d', strtotime($projectData['date_start']));
    $endDate = date('Y-m-d', strtotime($projectData['date_end']));
    ?>

    <div class="flex items-center justify-center h-screen">
        <div class="container mx-auto">
            <h1 class="text-center text-2xl font-semibold mb-4">Edit Project: <?php echo $projectData['name']; ?></h1>
            
            <form action="./p.php" method="POST" class="max-w-md mx-auto bg-white p-8 border rounded-md shadow-md">
                <!-- Add form fields with existing project data -->
                <input type="hidden" name="project_id" value="<?php echo $projectId; ?>">
                

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Project Name:</label>
                        <input type="text" id="name" name="name" class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:border-blue-500" value="<?php echo $projectData['name']; ?>" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
                        <textarea id="description" name="description" class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:border-blue-500" required><?php echo $projectData['description']; ?></textarea>
                    </div>
                <div class="mb-4">
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:border-blue-500" value="<?= $startDate; ?>" required>
                </div>

                <div class="mb-4">
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date:</label>
                    <input type="date" id="end_date" name="end_date" class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:border-blue-500" value="<?= $endDate; ?>" required>
                </div>

                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status:</label>
                    <input type="text" id="status" name="status" class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:border-blue-500" value="<?php echo $projectData['status']; ?>" required>
                </div>

                <div class="mb-4">
                    <label for="scrum_master_id" class="block text-sm font-medium text-gray-700">Scrum Master's :</label>
                   
                    <input type="hidden" id="scrum_master_id" name="scrum_master_id" class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:border-blue-500" value="<?php echo $projectData['scrum_master_id']; ?>" required>
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">Save Changes</button>
            </form>
        </div>
    </div>
</body>

</html>
