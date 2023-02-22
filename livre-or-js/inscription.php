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

// Préparer la requête d'insertion
$insertQuery = "INSERT INTO utilisateurs (login, password) VALUES (?, ?)";
$insertStmt = mysqli_prepare($conn, $insertQuery);

// Récupération des données envoyées par le formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = isset($_POST['login']) ? $_POST['login'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirm-password']) ? $_POST['confirm-password'] : '';

    // Vérification que les champs ne sont pas vides
    if (empty($login) || empty($password) || empty($confirmPassword)) {
        die('Tous les champs sont obligatoires.');
    }

    // Vérification que les mots de passe sont identiques
    if ($password !== $confirmPassword) {
        die('Les mots de passe ne correspondent pas.');
    }

    // Vérification que le login n'existe pas déjà
    $query = "SELECT * FROM utilisateurs WHERE login = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $login);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        die('Un utilisateur avec ce login existe déjà.');
    }

    // Hashage du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insertion de l'utilisateur dans la base de données
    $insertStmt = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($insertStmt, "ss", $login, $hashedPassword);
    if (!mysqli_stmt_execute($insertStmt)) {
        die('Erreur lors de l\'ajout de l\'utilisateur : ' . mysqli_error($conn));
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
    <title>Inscription</title>
</head>
<header>
    <?php include 'header.php' ?>
</header>
<body>
<h1 class="titree">Inscription</h1>
<center>
    <br>
    <div class="autre"><br>

<form id="register-form" method="POST">
    <label for="login">Login:</label>
    <input type="text" name="login" id="login" required><br><br>

    <label for="password">Mot de passe:</label>
    <input type="password" name="password" id="password" required><br><br>

    <label for="confirm-password">Confirmer le mot de passe:</label>
    <input type="password" name="confirm-password" id="confirm-password" required><br><br>

    <input class="clique" type="submit" value="S'inscrire">
</form>
</div>

<script>
    // Récupération du formulaire et des champs
    const form = document.getElementById('register-form');
    const login = document.getElementById('login');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm-password');
    const message = document.getElementById('message');

    // Fonction de validation du formulaire
    function validateForm() {
        // Vérifier que les champs sont remplis
        if (!login.value || !password.value || !confirmPassword.value) {
            message.innerHTML = "Tous les champs sont obligatoires.";
            return false;
        }

        // Vérifier que les mots de passe sont identiques
        if (password.value !== confirmPassword.value) {
            message.innerHTML = "Les mots de passe ne correspondent pas.";
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
                    message.innerHTML = "Inscription réussie !";
                    form.reset();
                } else {
                    message.innerHTML = "Une erreur est survenue lors de l'inscription.";
                }
            };
            xhr.send(`login=${encodeURIComponent(login.value)}&password=${encodeURIComponent(password.value)}`);
        }
    }

    if (form) {
        // Ajout de l'écouteur d'événement sur la soumission du formulaire
        form.addEventListener('submit', submitForm);
    }
    console.log(submitForm(<object data="" type="submit"></object>))
</script>

</body>
</html>
