<?php
session_start();

$_SESSION = array();


session_destroy();

// Redirigez l'utilisateur vers la page de connexion (ou une autre page)
header("Location:../pages/index.php");
exit();
?>
