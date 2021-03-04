<?php
function connect()
{
    //Datos para la conexion con la base de datos
    $host = "localhost";
    $dbname = "aergibide";
    $user = "jefe";
    $pass = "Jm12345";
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    try {

        //Establecer la conexion con la base de datos<
        $dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    //Devolvemos la conexion
    return $dbh;
}
//Funcion para cerrar la conexion con la base de datos

function close(){
    $dbh = null;
}