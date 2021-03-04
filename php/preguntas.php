<?php
session_start();
?>
<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../style/menu.css">
    <link rel="icon" type="image/png" href="../media/shortlogoVectorizado.png">
    <script src="../js/jquery-3.5.1.js"></script>
    <script src="../js/likes.js"></script>
    <link rel="stylesheet" href="../style/preguntas.css">
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>


    <title>Preguntas</title>
</head>
<?php require_once "code.php"?>

<body>
<header>
    <?php require_once "menu.php" ?>
</header>
<main>
    <section>

      <?php cargarPregunta() ?>

    </section>
    <?php cargarRespuestas()?>

</main>

<footer>
    <?php require_once "footer.php" ?>
</footer>

</body>
</html>
