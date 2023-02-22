<?php
include('connect.php');
if (isset($_SESSION['login'])) {
} else {
    // rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: connexion.php');
    exit();
}

try
{
//configuration des erreurs et enlever l'emulation des requetes préparées
$options =
[
  PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_EMULATE_PREPARES => false
];
      //ici on verifie que le boutton submit est utilisé
      if(isset($_POST['submit']))
      {
      $commentaire = $_POST['com'];
      $iduser = $_SESSION['connexion'];

          //ici on verifie que tous les champs sont remplis
          if($commentaire)
          {               
              //on connecte la base de donnée et on lance la requete préparée pour verifier que le pseudo est disponible
              $PDO = new PDO($DB_DSN, $DB_USER, $DB_PASS, $options);
              $request = $PDO->prepare('INSERT INTO commentaires(commentaire,id_utilisateur,date) VALUES (?,? ,NOW())');        
              $request->bindValue(1, $commentaire);
              $request->bindValue(2, $iduser);
              $request->execute();
              header('location: livre-or.php');
                          
          }
      } 
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
    <title>Commentaire</title>
    <link rel="icon" href="https://cdn.dribbble.com/users/230290/screenshots/15128882/media/4175d17c66f179fea9b969bbf946820f.jpg?compress=1&resize=400x300" type="image/x-icon">
</head>
<header>
    <?php include('header.php') ?>
</header>
<body id="commentaire">
<h1 class="titree">Commentaire</h1>
<br>
    <center>
        <div class="autre">
            <form action="commentaire.php" method="post">
                <h2 style="text-align: center">Laisse un commentaire</h2><br>
                <div style="text-align: center">
                    <textarea rows="3" name="com" class="post" placeholder="Écrie ton commentaire"></textarea>
                    <br>
                    <button type="submit" name="submit" class="clique">Envoyé</button>
                </div>
            </form>
        </div>
    </center>

<script src="/js/app.js"></script>
</body>

<footer>
    <?php include('footer.php') ?>
</footer>
</html>