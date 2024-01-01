<?php

require_once(dirname(__FILE__) . "/../../config/Config.php");
require_once "../../classes/Database.php";
require_once "../../classes/UserManager.php";


session_start();
$productOwnerId = 37; // Replace this with the actual Product Owner's user ID
$userManager = new UserManager($database);
$users = $userManager->getUsersExceptProductOwner($productOwnerId);

function getUserAvatarColorClass($userId)
{
   
    $colors = ["bg-red-500", "bg-blue-500", "bg-green-500", "bg-yellow-500"];
    $colorIndex = $userId % count($colors);
    return $colors[$colorIndex];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>User Management</title>
</head>

<body class="bg-gray-100">
    <?php require_once "../../includes/headers/header_product_owner.php"; ?>

    <div class="container max-w-7xl mx-auto mt-8">
        <div class="mb-4">
            <h1 class="font-serif text-3xl font-bold underline decoration-gray-400"> User Management</h1>
        </div>
    </div>

    <!-- Ajoutez une classe de marge à cet élément -->
    <div class="container max-w-7xl mx-auto mt-20"> 
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            <?php foreach ($users as $user) : ?>
                <?php if ($user['role'] !== 'po') : ?>
                    <div class="max-w-sm rounded overflow-hidden shadow-lg bg-white mb-4">
                        <div class="px-6 py-4">
                            <!-- Avatar circulaire avec les deux premières lettres du nom d'utilisateur -->
                            <div class="avatar <?= getUserAvatarColorClass($user['id']) ?> rounded-full overflow-hidden h-12 w-12 flex items-center justify-center">
                                <span class="text-white text-lg font-bold"><?= strtoupper(substr($user['username'], 0, 2)) ?></span>
                            </div>

                            <!-- Informations utilisateur -->
                            <div class="font-bold text-xl mb-2"><?= htmlspecialchars($user['username']) ?></div>

                            <!-- Badge pour le rôle de l'utilisateur -->
                            <span class="inline-block bg-blue-500 text-white rounded-full px-3 py-1 text-sm font-semibold"><?= htmlspecialchars($user['role']) ?></span>

                            <!-- Option pour changer le rôle de l'utilisateur -->
                            <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center mt-2" onclick="changeUserRole(<?= htmlspecialchars($user['id']) ?>)">
                                <span>Change Role</span>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
       function changeUserRole(userId) {
        // Demande de confirmation
        if (confirm("Voulez-vous vraiment changer le rôle de cet utilisateur?")) {
            // Requête AJAX
            fetch('change_role.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    userId: userId,
                }),
            })
            .then(response => response.json())
            .then(data => {
                // Affiche la réponse du serveur
                alert(data.message);
                // Recharge la page après la mise à jour du rôle
                location.reload();
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }
    }
    </script>

</body>

</html>
