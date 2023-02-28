<?php

include('connect.php');
try {
    // Vérification de l'existence de la session
    if(!isset($_SESSION['login']))
    {
        //Autrement on redirige vers connexion
        header('location: connexion.php');
        exit();
    }

    // Stockage de l'identifiant de l'utilisateur connecté dans $idverify
    $idverify = $_SESSION['login'];



    // Récupération des données envoyées en AJAX
    if(isset($_POST['login']) && !empty($_POST['login']) && isset($_POST['password']) && !empty($_POST['password']) && isset($_POST['password2']) && !empty($_POST['password2'])) {
        $new_login = $_POST['login'];
        $new_password = $_POST['password'];
        $confirm_password = $_POST['password2'];

        // Vérification que le nouveau login n'est pas déjà utilisé
        $connexion = mysqli_connect("localhost","root","","livreor") or die('erreur');
        $reget = ("SELECT * FROM utilisateurs WHERE login='$new_login' ");
        $regetx = mysqli_query($connexion, $reget);
        $row = mysqli_num_rows($regetx);

        if($row == 0) {
            // Vérification que les mots de passe sont identiques
            if($new_password === $confirm_password) {
                // Modification du login et du mot de passe dans la base de données
                $requete = ("UPDATE `utilisateurs` SET login = '$new_login', password = '$new_password' WHERE login = '$idverify' ");
                $query = mysqli_query($connexion, $requete);

                if($query) {
                    // Si la modification s'est bien déroulée, on retourne "success" en JSON
                    echo json_encode(array("status" => "success", "message" => "Votre login et votre mot de passe ont été modifiés avec succès !"));
                    $_SESSION['login'] = $new_login; // On met à jour le login dans la session
                }
                else {
                    // Si la modification a échoué, on retourne "error" en JSON
                    echo json_encode(array("status" => "error", "message" => "Une erreur est survenue lors de la modification de votre login et de votre mot de passe."));
                }
            }
            else {
                // Si les mots de passe ne correspondent pas, on retourne "error" en JSON
                echo json_encode(array("status" => "error", "message" => "Les mots de passe ne correspondent pas."));
            }
        }
        else {
            // Si le login est déjà utilisé, on retourne "error" en JSON
            echo json_encode(array("status" => "error", "message" => "Ce login est déjà utilisé."));
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
    <title>Profil</title>
    <link rel="icon" href="https://cdn.dribbble.com/users/230290/screenshots/15128882/media/4175d17c66f179fea9b969bbf946820f.jpg?compress=1&resize=400x300" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" /></html>
</head>

<header>
<?php include('header.php') ?>
</header>

<body id="profil">
<section id="contact">
    <center>
        <div class="container">
            <div class="titre">
                <h1>Profil</h1>
                <p>Modifié vos informations !</p>
            </div>
            <form action="profil.php" method="post">
                <div>
                    <label for="login">Votre Login :</label>
                    <input type="text" id="login" name="login" placeholder="Entrer votre login" required>
                </div>

                <div>
                    <label for="mdp">Password&nbsp;: </label>
                    <input type="password" id="mdp" name="password" placeholder="Entrer votre password" required>
                </div>

                <div>
                    <label for="confmdp">Confirmé&nbsp;:</label>
                    <input type="password" id="confmdp" name="password2" placeholder="Re rentrer password" required>
                </div>

                <div>
                    <br>
                    <button class="clique" type="submit" name="submit">Valider</button>
                    <button class="clique"><a class="clique" href="deconnexion.php">Deconnexion</a></button>
                </div>
    </center>
</section>


<script>
    const form = document.querySelector('#profil-form');
    const loginField = form.querySelector('#login');
    const passwordField = form.querySelector('#mdp');
    const confirmPasswordField = form.querySelector('#confmdp');

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        const login = loginField.value;
        const password = passwordField.value;
        const confirmPassword = confirmPasswordField.value;

        if(login.trim() === '' || password.trim() === '' || confirmPassword.trim() === '') {
            alert('Veuillez remplir tous les champs.');
            return;
        }

        if(password !== confirmPassword) {
            alert('Les mots de passe ne correspondent pas.');
            return;
        }

        const data = new FormData(form);

        fetch('update-profile.php', {
            method: 'POST',
            body: data
        })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    alert(data.message);
                    loginField.value = '';
                    passwordField.value = '';
                    confirmPasswordField.value = '';
                }
                else {
                    alert(data.message);
                }
            })
            .catch(error => console.error(error));
    });
</script>
<footer>
    <?php include('footer.php') ?>
</footer>
</body>
</html>