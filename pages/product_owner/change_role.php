<?php
require_once "../../config/Config.php";
require_once "../../classes/Database.php";
require_once "../../classes/UserManager.php";

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['userId'])) {
    $userManager = new UserManager($database);
    $result = $userManager->changeUserRole($data['userId']);
    echo json_encode(['message' => $result]);
} else {
    echo json_encode(['message' => 'Invalid request']);
}
