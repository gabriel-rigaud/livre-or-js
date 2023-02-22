<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
<link rel="stylesheet" href="/css/style.css">
<header>
    <div class="topnav" id="myTopnav">
        <div class="logo"> <img src="https://cdn.dribbble.com/users/230290/screenshots/15128882/media/4175d17c66f179fea9b969bbf946820f.jpg?compress=1&resize=400x300" width="135"></div>
        <a href="javascript:void(0);" class="icon" onclick="myFunction()">
            <i class="fa fa-bars"></i>
    <?php
    // test si l'utilisateur est connecté
    if (isset($_GET['deconnexion'])){
        if($_GET['deconnexion']==true){
            session_unset();
            session_destroy();
            header('Location: index.php');
        }
    }
    else if(isset($_SESSION['login'])){
        $login = $_SESSION['login'];
        echo "<div>
<div>
<a href='.'>Accueil</a>
<a href='profil.php'>Profil</a>
<a href='livre-or.php'>Le Livre D'or</a>
<a href='commentaire.php'>Commentaire</a>
<a href='deconnexion.php'>Déconnexion</a>
</div>";
        if ($login) {}
    }
    else{
        echo "<div>
<a href='.'>Accueil</a>
<a href='livre-or.php'>Le Livre D'or</a>
<a href='connexion.php'>Connexion</a>
<a href='inscription.php'>Inscription</a>
</div>";
    }
    ?>
    </div>
</header>