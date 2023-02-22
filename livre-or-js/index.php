<?php
session_start();
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<header>
    <?php include 'header.php'?>
</header>
<body>
<?php echo '<p style="text-align: center;color: red;font-size: 40px;margin-top: 60px">Bienvenue, ' . $_SESSION['login'];?>
</body>
</html>