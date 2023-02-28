<?php
include('connect.php');
try
{
//configuration des erreurs et enlever l'emulation des requetes préparées
    $options =
        [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

    $PDO = new PDO($DB_DSN, $DB_USER, $DB_PASS, $options);
    $request = $PDO->prepare("SELECT commentaires.date, utilisateurs.login, commentaires.commentaire FROM commentaires INNER JOIN utilisateurs ON commentaires.id_utilisateur = utilisateurs.id ORDER BY date DESC ");
    $request -> setFetchMode(PDO::FETCH_ASSOC);
    $request->execute();
}
catch(PDOException $pe)
{
    echo 'ERREUR : '.$pe->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>Livre D'or</title>
    <link rel="icon" href="https://cdn.dribbble.com/users/230290/screenshots/15128882/media/4175d17c66f179fea9b969bbf946820f.jpg?compress=1&resize=400x300" type="image/x-icon">
</head>
<header>
    <?php include('header.php'); ?>
</header>
<body id="livreor">
<h1 class="titree">Le Livre D'or</h1>
<br><br>
<?php
echo "<table id='com'>";
while($resultat = $request->fetch())
{
    echo "<div class='com'><div class='com-base'> <center> <h3 class='com-login'> par : $resultat[login]</h3>  <h3 class='com-poste'> Posté le :  $resultat[date] </h3> <h3 class='com-comentaire'> $resultat[commentaire]</h3></center> </div></div>" ;
}
echo "</table>";
if(isset($_SESSION['login']))
{
    echo "<br><a style='text-decoration: none' href='commentaire.php'><button class='clique' id='laisse'>Laisse un commentaire</button></a><br>";
}
?>
</body>
<footer>
    <?php include('footer.php') ?>
</footer>
</html>