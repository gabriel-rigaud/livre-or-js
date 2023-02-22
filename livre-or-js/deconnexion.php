<?php
session_start();

// Si l'utilisateur est connecté, on le déconnecte en supprimant les variables de session
if(isset($_SESSION['login'])) {
    session_unset();
    session_destroy();
}

// Redirection vers la page d'accueil
header('Location: index.php');
exit();
?>
