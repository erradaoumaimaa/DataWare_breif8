<?php
session_start();

require_once "../../includes/headers/header_member.php";
require_once "../../config/config.php";
require_once "../../classes/Database.php";

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    // Redirect to the login page or handle the case where the user is not logged in
    header("Location: ../index.php");
    exit();
}

// Fetch user data from the database
$query = "SELECT id, username FROM users";
$stmt = $database->getConnection()->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Function to generate avatar initials
function getAvatarInitials($username)
{
    $initials = strtoupper(substr($username, 0, 2));
    return $initials;
}

// Function to generate a random color
function getRandomColor()
{
    $letters = '0123456789ABCDEF';
    $color = '#';
    for ($i = 0; $i < 6; $i++) {
        $color .= $letters[rand(0, 15)];
    }
    return $color;
}

// Fetch the currently logged-in user's information
$loggedInUser = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Membre</title>
</head>

<body>
    <h1>Hello <?= $users['username'] ?></h1>

    <div class="grid grid-cols-3 gap-4">
        <?php foreach ($users as $user) : ?>
            <div class="text-center">
                <h2><?= $user['username'] ?></h2>
                <div style="background-color: <?= getRandomColor() ?>; color: white; width: 50px; height: 50px; line-height: 50px; border-radius: 50%; margin: 0 auto;"><?= getAvatarInitials($user['username']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>
