<?php
if (isset($_POST["action"])){
    $action=$_POST["action"];

    switch($action){
        case "checkUsername": checkUsername();break;
        case "checkEmail": checkEmail();break;
        case "likePregunta": likePregunta();break;
        case "likeRespuesta": likeRespuesta();break;
        case "mejorRespuesta": mejorRespuesta();break;
        case "liveSearch": liveSearch();break;
    }

}
function checkUsername(){
    require_once "bbdd.php";
    $dbh = connect();

    $data = array("username" => $_POST["value"]);
    $stmt = $dbh->prepare("SELECT COUNT(*) FROM USERS WHERE username LIKE :username LIMIT 1;");

    $stmt->execute($data);
    $aviable = $stmt->fetchColumn();
    close();

    echo $aviable;
}
function checkEmail(){
    require_once "bbdd.php";
    $dbh = connect();
    $data = array("email" => $_POST["value"]);
    $stmt = $dbh->prepare("SELECT COUNT(*) FROM USERS WHERE email LIKE :email LIMIT 1;"); 

    $stmt->execute($data);
    $aviable = $stmt->fetchColumn();
    close();

    echo $aviable;
}
function likePregunta(){
    session_start();
    if(isset($_SESSION["idUsuario"])){

    require_once "bbdd.php";
    $dbh = connect();

    $data = array("idPregunta" => $_POST["value"],"idUsuario" => $_SESSION["idUsuario"]);

    //busco el like por si esta dado, si esta dado, lo quito, si no esta, lo añado
    $stmt = $dbh->prepare("SELECT COUNT(*) FROM `LIKES_QUESTIONS` WHERE `id_question` = :idPregunta AND `id_user` = :idUsuario LIMIT 1;");
    $stmt->execute($data);
    $response = $stmt->fetchColumn();

    if ($response==1){
        //el like ya esta dado, lo tenemos que deletear
        $stmt = $dbh->prepare("DELETE FROM `LIKES_QUESTIONS` WHERE `LIKES_QUESTIONS`.`id_user` = :idUsuario AND `LIKES_QUESTIONS`.`id_question` = :idPregunta;");
        $stmt->execute($data);
    } else {
        //el like no esta dado, lo tenemos que instertar
        $stmt = $dbh->prepare("INSERT INTO `LIKES_QUESTIONS` (`id_user`, `id_question`) VALUES (:idUsuario, :idPregunta);");
        $stmt->execute($data);
    }
    //vuelvo a buscar el like para saber si la operacion que se tenia que hacer se ha hecho correctamente

    $stmt = $dbh->prepare("SELECT COUNT(*) FROM `LIKES_QUESTIONS` WHERE `id_question` = :idPregunta AND `id_user` = :idUsuario LIMIT 1;");
    $stmt->execute($data);
    $response = $stmt->fetchColumn();
    close();

    echo $response;
    }
}
function likeRespuesta(){
    session_start();
    if(isset($_SESSION["idUsuario"])){
    require_once "bbdd.php";
    $dbh = connect();
    $data = array("idRespuesta" => $_POST["respuesta"], "idUsuario" => $_SESSION["idUsuario"]);

    //busco el like por si esta dado, si esta dado, lo quito, si no esta, lo añado
    $stmt = $dbh->prepare("SELECT COUNT(*) FROM `LIKES_ANSWERS` WHERE `id_answer` = :idRespuesta AND `id_user` = :idUsuario LIMIT 1;");
    $stmt->execute($data);
    $response = $stmt->fetchColumn();

    $data = array("idRespuesta" => $_POST["respuesta"], "idUsuario" => $_SESSION["idUsuario"], "idPregunta" => $_POST["pregunta"]);
    if ($response==1){
        //el like ya esta dado, lo tenemos que deletear
        $stmt = $dbh->prepare("DELETE FROM `LIKES_ANSWERS` WHERE `LIKES_ANSWERS`.`id_question` = :idPregunta AND `LIKES_ANSWERS`.`id_answer` = :idRespuesta AND `LIKES_ANSWERS`.`id_user` = :idUsuario");
        $stmt->execute($data);
    } else {
        //el like no esta dado, lo tenemos que instertar
        $stmt = $dbh->prepare("INSERT INTO `LIKES_ANSWERS` (`id_question`, `id_answer`, `id_user`) VALUES (:idPregunta, :idRespuesta, :idUsuario);");
        $stmt->execute($data);
    }
    $data = array("idRespuesta" => $_POST["respuesta"], "idUsuario" => $_SESSION["idUsuario"]);
    $stmt = $dbh->prepare("SELECT COUNT(*) FROM `LIKES_ANSWERS` WHERE `id_answer` = :idRespuesta AND `id_user` = :idUsuario LIMIT 1;");
    $stmt->execute($data);
    $response = $stmt->fetchColumn();

    close();
    echo $response;
    }
}
function mejorRespuesta(){
    session_start();
    if(isset($_SESSION["idUsuario"])){
            require_once "bbdd.php";
            $dbh = connect();
            //primero miro que la pregunta sea del usuario que esta logueado
            $data = array("idUsuario" => $_SESSION["idUsuario"],"idPregunta"=> $_POST["pregunta"]);
            $stmt = $dbh ->prepare("SELECT COUNT(*) FROM `QUESTIONS` WHERE `id_question` = :idPregunta AND `id_user` = :idUsuario LIMIT 1;");
            $stmt->execute($data);
            $response = $stmt->fetchColumn();

            //si la respuesta es 1 es que la pregunta es del usuario logeado, por lo que podra marcar una respuesta como "la mejor"
            if ($response==1){
                //antes de marcar una respuesta como la mejor tenemos que buscar si hay alguna otra respuesta como la mejor para cambiarlas
                $data = array("idPregunta"=> $_POST["pregunta"]);
                $stmt = $dbh ->prepare("SELECT `id_answer` FROM `ANSWERS` WHERE `id_question` = :idPregunta AND `best_answer` = 1 LIMIT 1;");
                $stmt->execute($data);
                $idMejorRespuesta = $stmt->fetchColumn();
                //la respuesta es el id de la mejor respuesta en caso de que exista mejor respuesta


                //si la respuesta(idMejorRespuesta) NO esta vacia significa que hay una mejor respuesta, por lo que la borramos
                if ($idMejorRespuesta!=""){
                    $data = array("idRespuesta"=> $idMejorRespuesta,"idPregunta"=> $_POST["pregunta"]);
                    $stmt = $dbh->prepare("UPDATE `ANSWERS` SET `best_answer` = '0' WHERE `ANSWERS`.`id_question` = :idPregunta AND `ANSWERS`.`id_answer` = :idRespuesta;");
                    $stmt->execute($data);
                }
                //si la mejor respuesta es la misma que la respuesta que se ha clikado no queremos añadir de nuevo que es mejor pregunta, queremos que se mantenga borrada (funcionalidad de quitar una mejor respuesta)

                if ($idMejorRespuesta!=$_POST["respuesta"]){
                //ahora hemos quitado como mejor respuesta la que habia antes, ahora toca añadir la que han clikado
                $data = array("idRespuesta"=> $_POST["respuesta"], "idPregunta"=> $_POST["pregunta"]);
                $stmt = $dbh->prepare("UPDATE `ANSWERS` SET `best_answer` = '1' WHERE `ANSWERS`.`id_question` = :idPregunta AND `ANSWERS`.`id_answer` = :idRespuesta;");
                $stmt->execute($data);
                $count = $stmt->rowCount();
                //si count es 1 significa que la update se ha realizado con exito
                }
            }
            close();
            echo $idMejorRespuesta; //esta variable es la aintigua mejor respuesta, la mando para cabiar el color del tick de la antigua mejor respuesta.
    }
}
function liveSearch(){
    require_once "bbdd.php";
    $dbh = connect();
    $data = array("busqueda" => '%'.$_POST["value"].'%');
    $stmt = $dbh ->prepare("SELECT title FROM `QUESTIONS` WHERE lower(title) LIKE :busqueda LIMIT 5");
    $stmt->setFetchMode(PDO::FETCH_OBJ);
    $stmt->execute($data);
    
    
    while($row = $stmt->fetch()) {
        echo $row->title . "%%%";   
    }
    
    close();
}