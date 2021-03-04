<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../style/login.css">
    <link rel="icon" type="image/png" href="../media/shortlogoVectorizado.png">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <meta name="google-signin-client_id" content="664586227547-s57mbe513n8ene86tmeemnt9iil6b1kf.apps.googleusercontent.com">
    <script src="../js/jquery-3.5.1.js"></script>
    <script src="../js/login.js"></script>

</head>

<body>
    <div id="color">
        <img src="../media/shortlogoVectorizado.png" alt="Logo" id="imgPcs">
        <h2 id="nombreEmpresa">Aergibide</h2>
    </div>
    <div id="login">
        <div class="btncambiar">
            <input type="button" value="Log in" class="btnSignin active">
            <input type="button" value="Sign up" class="btnSignup unactive changeForm">
        </div>
        <img src="../media/shortlogoVectorizado.png" alt="Logo" class="imgMovil">
        <form action="./code.php" method="post">
            <h1>Log in</h1>
            <i class="fa fa-user-circle">&nbsp;
                <input type="text" name="username" class="username" placeholder="Username"></i>
            <i class="fa fa-lock">&nbsp;
                <input type="password" name="password" class="password" placeholder="Password"></i>
            <div id="errorLog" style="color: #ffb600;margin-bottom: 12px;">
                <?php
                //En caso de que se necesite esta cookie mostrará un mensaje informativo al usuario
                if (isset($_COOKIE["errorLog"])) {
                ?>
                    <p id="errorLog"> <?= $_COOKIE["errorLog"] ?> </p>

                <?php setcookie("errorLog", null, -1);
                } ?>
            </div>

            <input type="submit" value="Log in" onclick="return validateForm('login')">
            <input type="button" value="Sign up" class="btnSignmbl changeForm">
            <!-- <div class="g-signin2" data-onsuccess="onSignIn"></div>
            <a href="#" onclick="signOut();">Sign out</a>
            <script>
                function onSignIn(googleUser) {
                    // Useful data for your client-side scripts:
                    var profile = googleUser.getBasicProfile();
                    console.log("ID: " + profile.getId()); // Don't send this directly to your server!
                    console.log('Full Name: ' + profile.getName());
                    console.log('Given Name: ' + profile.getGivenName());
                    console.log('Family Name: ' + profile.getFamilyName());
                    console.log("Image URL: " + profile.getImageUrl());
                    console.log("Email: " + profile.getEmail());

                    // The ID token you need to pass to your backend:
                    var id_token = googleUser.getAuthResponse().id_token;
                    console.log("ID Token: " + id_token);
                }

                function signOut() {
                    var auth2 = gapi.auth2.getAuthInstance();
                    auth2.signOut().then(function() {
                        console.log('User signed out.');
                    });
                }
            </script> -->
        </form>
        <p class="newaccount"><a href="home.php">Dont have an account?  Enter as a guest</a></p>
    </div>
    <div id="signup">
        <div class="btncambiar">
            <input type="button" value="Log in" class="btnSignin unactive changeForm">
            <input type="button" value="Sign up" class="btnSignup active">
        </div>
        <img src="../media/shortlogoVectorizado.png" alt="Logo" class="imgMovil">
        <form action="./code.php" method="post">
            <h1>Sign up</h1>
            <i class="fa fa-user-circle">&nbsp;
                <input type="text" required name="user" class="username" placeholder="Username"></i>
            <p id="errorUsername"></p>
            <i class="fa fa-lock">&nbsp;
                <input type="password" required name="pass" class="password" placeholder="Password (Jm12345$)"></i>
            <p id="errorPassword"></p>
            <i class="fa fa-lock">&nbsp;
                <input type="password" required name="pass" id="password2" placeholder="Repeat the password"></i>
            <p id="errorPassword2"></p>
            <i class='far fa-address-card'>&nbsp;
                <input type="text" name="nombre" id="nombre" required placeholder="Nombre"></i>
            <p id="errorNombre"></p>
            <i class='fas fa-address-card'>&nbsp;
                <input type="text" name="apellido" id="apellido" required placeholder="Apellido"></i>
            <p id="errorApellido"></p>
            <i class="fa fa-at">&nbsp;
                <input type="email" name="email" id="email" required placeholder="Correo electronico"></i>
            <p id="errorEmail"></p>
            <!-- <i class="fa fa-file-image-o" id="fotoperfil">&nbsp;
                <input type="file" name="fotoperfil" class="fotoperfil" value="foto de perfil"></i> -->
            <input type="submit" value="Sign up" onclick="return validateForm('signup')">
            <input type="button" value="Log in" class="btnSignmbl changeForm">
        </form>
        <p class="newaccount"><a href="home.php">Dont have an account?  Enter as a guest</a></p>
    </div>
</body>

</html>