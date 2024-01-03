<?php
require_once(dirname(__FILE__) . "/../../config/Config.php");
require_once "../../includes/headers/header_scrum_master.php";
require_once "../../classes/Database.php";
require_once(dirname(__FILE__) . "/../../classes/Team.php");
$database = new Database($dbHost, $dbUser, $dbPassword, $dbName);

$teams = [];
try {
    $query = "SELECT teams.*, users.username AS scrum_master_name FROM teams LEFT JOIN users ON teams.scrum_master_id = users.id";
    $stmt = $database->getConnection()->prepare($query);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $team = new Team($row['id'], $row['name'], $row['created_at'], $row['description'], $row['scrum_master_name']);
      $teams[] = $team;
  }
} catch (PDOException $e) {
    die("Erreur lors de la récupération des équipes : " . $e->getMessage());
} finally {
    $database->disconnect();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Scrum Master</title>
</head>
<div class="container max-w-7xl mx-auto mt-8">
    <div class="mb-4">
        <h1 class="font-serif text-3xl font-bold underline decoration-gray-400"> List of Teams</h1>
        <div class="flex justify-end">
            <button class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800" onclick="redirectToCreateTeam()">
                <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                    Add Teams
                </span>
            </button>
        </div>
    </div>

    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                Title
                            </th>
                            <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                Description
                            </th>
                            <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                Scrum master
                            </th>
                            <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                Created_At
                            </th>
                            <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                Teams Members
                            </th>
                            <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                Project
                            </th>
                            <th class="px-6 py-3 text-sm text-left text-gray-500 border-b border-gray-200 bg-gray-50" colspan="3">
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        <?php foreach ($teams as $team): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="flex items-center">
                                        <?php echo $team->getName(); ?>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900"><?php echo $team->getDescription(); ?></div>
                                </td>

                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <p><?php echo $team->getScrumMasterName(); ?></p>
                                </td>

                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <p><?php echo $team->getCreatedAt(); ?></p>
                                </td>
                               
                                <!-- Actions -->
                                <td class="text-sm font-medium leading-5 whitespace-no-wrap border-b border-gray-200 ">
                                    <!-- éditer l'équipe -->
                                    <a href="edit_team.php?team_id=<?php echo $team->getId(); ?>" class="text-indigo-600 hover:text-indigo-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    <!-- supprimer l'équipe -->
                                    <a href="delete_team.php?team_id=<?php echo $team->getId(); ?>" class="text-red-600 hover:text-red-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </a>
                                    
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
        function redirectToCreateTeam() {
            window.location.href = '../scrum_master/create_team.php';
        }
        
        function editProject(projectId) {
      
      window.location.href = `./edit_team.php`;
  }
    </script>

</body>
</html>