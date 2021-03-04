<?php
session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style/login.css">
    <link rel="icon" type="image/png" href="media/shortlogo.png">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

</head>
<body>


<p>hola <a href="user.php">

 <?=$_SESSION["nombreUsuario"]?></a>
<?= $_SESSION["idUsuario"]?></p>
</body>
</html>