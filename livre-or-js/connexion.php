<?php
// Informations de connexion à la base de données
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'livreor';

// Connexion à la base de données
$conn = mysqli_connect($host, $username, $password, $dbname);

// Vérifier la connexion
if (!$conn) {
    die("Connexion échouée : " . mysqli_connect_error());
}

// Récupération des données envoyées par le formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = isset($_POST['login']) ? $_POST['login'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Vérification que les champs ne sont pas vides
    if (empty($login) || empty($password)) {
        die('Tous les champs sont obligatoires.');
    }

    // Vérification que l'utilisateur existe dans la base de données
    $query = "SELECT * FROM utilisateurs WHERE login = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $login);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$row = mysqli_fetch_assoc($result)) {
        die('Cet utilisateur n\'existe pas.');
    }

    // Vérification que le mot de passe est correct
    if (!password_verify($password, $row['password'])) {
        die('Le mot de passe est incorrect.');
    }

    // Connexion réussie : création de la session et redirection vers la page d'accueil
    session_start();
    $_SESSION['login'] = $login;
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<header>
    <?php include 'header.php' ?>
</header>
<body>
<h1 class="titree">Connexion</h1>
<center>
    <br>
    <div class="autre"><br>
        <form id="login-form" method="POST">
        <label for="login">Login:</label>
        <input type="text" name="login" id="login" required><br><br>

        <label for="password">Mot de passe:</label>
        <input type="password" name="password" id="password" required><br><br>

        <input class="clique" type="submit" value="Se connecter">
    </form>
    </div>
</body>
<script>
    // Récupération du formulaire et des champs
    const form = document.getElementById('login-form');
    const username = document.getElementById('username');
    const password = document.getElementById('password');
    const message = document.getElementById('message');

    // Fonction de validation du formulaire
    function validateForm() {
        // Vérifier que les champs sont remplis
        if (!username.value || !password.value) {
            message.innerHTML = "Tous les champs sont obligatoires.";
            return false;
        }

        return true;
    }

    // Fonction d'envoi du formulaire
    function submitForm(event) {
        event.preventDefault(); // Empêcher la soumission du formulaire par défaut

        if (validateForm()) {
            // Envoi des données via AJAX
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        message.innerHTML = "Connexion réussie !";
                        window.location.href = "index.php";
                    } else {
                        message.innerHTML = "Nom d'utilisateur ou mot de passe incorrect.";
                    }
                } else {
                    message.innerHTML = "Une erreur est survenue lors de la connexion.";
                }
            };
            xhr.send(`username=${encodeURIComponent(username.value)}&password=${encodeURIComponent(password.value)}`);
        }
    }

    if (form) {
        // Ajout de l'écouteur d'événement sur la soumission du formulaire
        form.addEventListener('submit', submitForm);
    }
    console.log(submitForm(<object data="" type="submit"></object>))
</script>
</html>
