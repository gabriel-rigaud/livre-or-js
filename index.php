<?php
session_start();
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
</head>
<header>
    <?php include 'header.php' ?>
</header>
<body><br>
<center>
    <div class="autre">
        <h1>Hello Bienvenue Sur Le Livre D'or Js</h1>
        <?php
        if (!isset($_SESSION['login'])) {
            // utilisateur non connecté, afficher le bouton de connexion
            echo '<button class="clique" id="btn-home"><a class="clique" href="connexion.php">Connexion</a></button>';
            echo '<button class="clique" id="btn-home"><a class="clique" href="inscription.php">Inscription</a></button>';
        }
        ?>
        <?php if (isset($_SESSION['login'])): ?>
            <?php echo '<p style="text-align: center;color: red;font-size: 40px;margin-top: 30px">Bienvenue, ' . $_SESSION['login'];?>
        <br>
            <button class="clique" id="btn-home"><a class="clique" href="todolist.php">MyToDoListe !</a></button>
            <button class="clique" id="btn-home"><a class="clique" href="profil.php">Mon profil</a></button>
        <?php endif; ?>
    </div>
</center>
</body>
</html>