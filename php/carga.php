<?php

session_start();
if (isset($_GET["reply"])){

    require_once "bbdd.php";
    $dbh = connect();
    $idUsuario = $_SESSION["idUsuario"];

    $data = array( 'desc' => $_POST["description"], 'usuario'=>$idUsuario, 'idPregunta'=>$_GET["reply"]);

    $stmt = $dbh->prepare("insert into ANSWERS (text,id_question, id_user) values(:desc, :idPregunta,:usuario)");
    $stmt->execute($data);
    $lastId = $dbh->lastInsertId();


    $nombre_img = $_FILES['imagen']['name'];
    $tipo = $_FILES['imagen']['type'];
    $tamano = $_FILES['imagen']['size'];
    $extension = pathinfo($nombre_img, PATHINFO_EXTENSION);
    $directorio = "";



    if (($_FILES["imagen"]["type"] == "image/gif")
        || ($_FILES["imagen"]["type"] == "image/jpeg")
        || ($_FILES["imagen"]["type"] == "image/jpg")
        || ($_FILES["imagen"]["type"] == "image/png")) {
        // Ruta donde se guardarán las imágenes que subamos
        $directorio = '../images/answerMedia/';
        // Muevo la imagen desde el directorio temporal a nuestra ruta indicada anteriormente
        move_uploaded_file($_FILES['imagen']['tmp_name'], $directorio .$_GET["reply"]. $lastId. "." . $extension);

    } else {
        //si no cumple con el formato
        echo "No se puede subir una imagen con ese formato ";
        header("Location: index.php");
    }
    $ruta=$directorio.$_GET["reply"].$lastId.".".$extension;

    $data = array('id'=>$lastId, 'ruta'=>$ruta, 'idPregunta'=>$_GET["reply"]);
    $stmt = $dbh->prepare("UPDATE ANSWERS SET answerImage = :ruta WHERE id_question=:idPregunta AND id_answer=:id");
    $stmt->execute($data);

    close();

    header("Location: preguntas.php?pregunta=".$_GET["reply"]);
}elseif (isset($_GET["insertar"]))
{
    //Como el elemento es un arreglos utilizamos foreach para extraer todos los valores

    require_once "bbdd.php";
    $dbh = connect();
    $idUsuario = $_SESSION["idUsuario"];
    $eti = sacarIdEtiqueta($_POST["tags"]);
    $data = array('titulo'=>$_POST["title"], 'desc' => $_POST["description"], 'usuario'=>$idUsuario,'etiqueta'=>$eti);
    $stmt = $dbh->prepare("insert into QUESTIONS (title, text, id_user, id_topic) values(:titulo,:desc, :usuario,:etiqueta)");
    $stmt->execute($data);

    $lastId = $dbh->lastInsertId();


    // Recibo los datos de la imagen
    $nombre_img = $_FILES['imagen']['name'];
    $tipo = $_FILES['imagen']['type'];
    $tamano = $_FILES['imagen']['size'];
    $extension = pathinfo($nombre_img, PATHINFO_EXTENSION);
    $directorio = "";

//Si existe imagen y tiene un tamaño correcto

    //indicamos los formatos que permitimos subir a nuestro servidor
    if (($_FILES["imagen"]["type"] == "image/gif")
        || ($_FILES["imagen"]["type"] == "image/jpeg")
        || ($_FILES["imagen"]["type"] == "image/jpg")
        || ($_FILES["imagen"]["type"] == "image/png")) {
        // Ruta donde se guardarán las imágenes que subamos
        $directorio = '../images/questionMedia/';
        // Muevo la imagen desde el directorio temporal a nuestra ruta indicada anteriormente
        move_uploaded_file($_FILES['imagen']['tmp_name'], $directorio . $lastId. "." . $extension);

    } else {
        //si no cumple con el formato
        echo "No se puede subir una imagen con ese formato ";
        header("Location: index.php");
    }
        $ruta=$directorio.$lastId.".".$extension;

        $data = array('id'=>$lastId, 'ruta'=>$ruta);
        $stmt = $dbh->prepare("UPDATE QUESTIONS SET questionImage = :ruta WHERE id_question=:id");
        $stmt->execute($data);
    close();
    header("Location: home.php");
}else {
// Recibo los datos de la imagen
    $nombre_img = $_FILES['imagen']['name'];
    $tipo = $_FILES['imagen']['type'];
    $tamano = $_FILES['imagen']['size'];
    $extension = pathinfo($nombre_img, PATHINFO_EXTENSION);
    $directorio = "";

//Si existe imagen y tiene un tamaño correcto

    //indicamos los formatos que permitimos subir a nuestro servidor
    if (($_FILES["imagen"]["type"] == "image/gif")
        || ($_FILES["imagen"]["type"] == "image/jpeg")
        || ($_FILES["imagen"]["type"] == "image/jpg")
        || ($_FILES["imagen"]["type"] == "image/png")) {
        // Ruta donde se guardarán las imágenes que subamos
        $directorio = /*$_SERVER['DOCUMENT_ROOT'].*/
            '../images/userProfile/';
        // Muevo la imagen desde el directorio temporal a nuestra ruta indicada anteriormente
        move_uploaded_file($_FILES['imagen']['tmp_name'], $directorio . $_SESSION["nombreUsuario"] . "." . $extension);

    } else {
        //si no cumple con el formato
        echo "No se puede subir una imagen con ese formato ";
    }


    require_once "bbdd.php";
    $dbh = connect();
//En caso que detecte que la contraseña tambien va a ser modificada se encarga de encriptarla para actualizarla
//en la base de datos
    if (isset($_POST["pass"])) {
        $pass = password_hash($_POST["pass"], PASSWORD_DEFAULT);

        $data = array('id' => $_SESSION["idUsuario"], 'pass' => $pass,);
        $stmt = $dbh->prepare("UPDATE USERS SET password = :pass where id_user= :id");


        $stmt->execute($data);
        close();

        header("Location: user.php");
    } else {
        //En caso de que detecte que hay una imagen, se recogeran los datos previamente preparados para guardar la ruta de
        //la nueva imagen
        if ($tamano > 0) {

            //Preparamos los datos con los proporcionados por el usuario.
            $data = array(
                'ruta' => "../images/userProfile/" . $_SESSION["nombreUsuario"] . "." . $extension,
                'id' => $_SESSION["idUsuario"],
                'username' => $_POST["user"],
                'name' => $_POST["nombre"],
                'ape' => $_POST["apellido"],
                'email' => $_POST["email"],
                'bio' => $_POST["bio"]);

            $stmt = $dbh->prepare("UPDATE USERS SET username = :username, name = :name, surname = :ape, email = :email, profile_image = :ruta, biography = :bio where id_user= :id");


            $stmt->execute($data);
            close();

            header("Location: user.php");
        } else {
            //Esto se ejecutará en caso de que no haya subido ninguna imagen. Se le quedara la imagen que ya tenia.
            $data = array(
                'id' => $_SESSION["idUsuario"],
                'username' => $_POST["user"],
                'name' => $_POST["nombre"],
                'ape' => $_POST["apellido"],
                'email' => $_POST["email"],
                'bio' => $_POST["bio"]);

            $stmt = $dbh->prepare("UPDATE USERS SET username = :username, name = :name, surname = :ape, email = :email,  biography = :bio where id_user= :id");


            $stmt->execute($data);
            close();

            header("Location: user.php");
        }

    }
}



function sacarIdEtiqueta($etiqueta){
    require_once "bbdd.php";
    $dbh = connect();

    $data = array('etiqueta'=>$etiqueta);
    $stmt = $dbh->prepare("SELECT id_topic, name from TOPICS where name = :etiqueta");
    $stmt->execute($data);
    close();
    $fila = $stmt->fetch();

    return $fila["id_topic"];
}



