<?php
//Estas dos lineas permiten al servidor PHP los errores que suceden mientrasse ejecuta el programa

/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/

//Mediante estas condiciones nos encargamos de saber si el usuario esta; logueando, registrando o cerrando sesión.
if (isset($_POST["user"]) && isset($_POST["pass"])){
    registrar($_POST["user"],$_POST["pass"],$_POST["nombre"],$_POST["apellido"],$_POST["email"]);
}
if (isset($_POST["username"]) && isset($_POST["password"])) {
    comprobarLogin($_POST["username"], $_POST["password"]);
}
if (isset($_GET["cerrar"])){
    cerrarSesion();
}
//La funcion de cerrar sesión se encarga de destruir la sesión de PHP para que asi no haya nigún usuario logueado y te
//devuelve al login.
function cerrarSesion(){
    session_destroy();
    header("Location: index.php");
}

//Mediante este metodo comprobamos que el usuario que esta intentando acceder realmente exista con esas creedenciales.
function comprobarLogin($usuario, $password)
{
    //Conexión con la base de datos
    require_once "bbdd.php";
    $dbh = connect();
    //Petición a la BBDD de los datos del usuario
    $data = array( 'username' => $usuario);
    $stmt = $dbh->prepare("SELECT id_user, username, password FROM USERS where username = :username ");


    $stmt->execute($data);
    $fila = $stmt->fetch();
    //En caso de que nos devuelva una fila, comprobamos mediante el metodo password_verify que las contraseñas coinciden
    //ya que en la base de datos las contraseñas se almacenan cifradas
    if(password_verify($password, $fila['password'])) {
        //Abrimos sesion
        session_start();
        $nombreUsuario = $fila["username"];
        $idUsuario = $fila["id_user"];
        //Guardamos los datos del usuario en sesion para poder manejar las proximas paginas.
        $_SESSION["nombreUsuario"] = $nombreUsuario;
        $_SESSION["idUsuario"]=$idUsuario;
        //Cerramos la conexion con la base de datos.
        close();
        //Con este metodo nos encargamos de que el usuario que se loguea, en la BBDD actualizamos un campo para saber
        //cuando ha sido su ultima conexion
        updateLastLogin($idUsuario);
        //Esto lleva al usuario a la pagina principal tras el login
        header("location:home.php");
    } else{
        //En caso de error o que no encuentre al usuario, guardamos el error en una cookie y se refresca la pagina
        //de esta manera le saldra el mensaje de error al usuario.
        setcookie("errorLog","Nombre o contraseña incorrectos", time()+60);
        //Cerramos la conexion con la base de datos.
        close();
        header("Location: index.php");
    }



}


//Con esta funcion registramos a un usuario en el sistema almacenandolo en la BBDD.
function registrar($usuario, $password,$nombre,$apellido,$mail){
    //Conexión con la base de datos
    require_once "bbdd.php";
    $dbh = connect();
    //Mediante la contraseña que nos ha introducido el usuario, nos encargamos de cifrarla.
    $pass = password_hash($password, PASSWORD_DEFAULT);
    //Preparamos los datos para enviarselos al servidor e insertar al usuario;
    $data = array('usuario'=>$usuario, 'pass' => $pass, 'nombre'=>$nombre,'apellido'=>$apellido,'mail' =>$mail);
    $stmt = $dbh->prepare("insert into USERS (username, password, name, surname, email) values(:usuario,:pass, :nombre,:apellido,:mail)");
    $stmt->execute($data);


    $data = array('usuario'=>$usuario);
    //Una vez ejecutada la anterior sentencia nos encargamos de comprobar que el usuario ha sido correctamente
    //registrado
    $stmt = $dbh->prepare("SELECT * FROM USERS where username = :usuario ");
    $stmt->execute($data);
    //En caso de que nos devuelva una fila es que el usuario ha sido correctamente registrado.
    if($stmt->rowCount() == 1) {
        //Guardamos el mensaje de informacion para el usuario y le devolvemos al login para que proceda a logearse
        setcookie("errorLog", "El usuario ".$usuario." ha sido registrado", time()+60);

        close();
        header("Location: index.php");
    } else{
        //En caso de que no se haya insertado o no se encuentre al usuario se pondrá este mensaje de error y se le
        //enseñará al usuario
        setcookie("errorLog","Problemas con la Base de datos", time()+60);
        //Le devolvemos a la pagina de inicio.
        close();
        header("Location: index.php");

    }

}

//Funcion que se encarga, mediante un id, de devolver un array asociativo con todos los datos del usuario que se haya
//logeado
function cargarUsuario($id){
    //Conexion con la base de datos.
    require_once "bbdd.php";
    $dbh = connect();
    if (isset($_GET["id"])){

        $data = array( 'id' => $_GET["id"]);
        //Seleccionamos al usuario mediante el id que nos proporcionen.
        $stmt = $dbh->prepare("SELECT  id_user, username, name, surname, biography,email, last_login_date,profile_image  FROM USERS where id_user = :id");


        $stmt->execute($data);
        $fila = $stmt->fetch();
        //Preparamos el array asociativo;
        $persona=[
            "id"=> $fila["id_user"],
            "username"=>$fila["username"],
            "nombre"=>$fila["name"],
            "apellido"=>$fila["surname"],
            "biografia"=>$fila["biography"],
            "email"=>$fila["email"],
            "ultimoLogin"=>$fila["last_login_date"],
            "foto"=>$fila["profile_image"]
        ];
        //Mediante esta consulta contamos el número de preguntas que haya realizado el usuario.
        $stmt = $dbh->prepare("SELECT  count(*) FROM QUESTIONS where id_user = :id");


        $stmt->execute($data);
        //Introducimos el dato recibido en el array asociativo y lo devolvemos
        $count = $stmt->fetchColumn();
        $persona["preguntas"]= $count;
        close();
        return $persona;
    }else{



    $data = array( 'id' => $id);
    //Seleccionamos al usuario mediante el id que nos proporcionen.
    $stmt = $dbh->prepare("SELECT  id_user, username, name, surname, biography,email, last_login_date,profile_image  FROM USERS where id_user = :id");


    $stmt->execute($data);
    $fila = $stmt->fetch();
    //Preparamos el array asociativo;
    $persona=[
        "id"=> $fila["id_user"],
        "username"=>$fila["username"],
        "nombre"=>$fila["name"],
        "apellido"=>$fila["surname"],
        "biografia"=>$fila["biography"],
        "email"=>$fila["email"],
        "ultimoLogin"=>$fila["last_login_date"],
        "foto"=>$fila["profile_image"]
    ];
    //Mediante esta consulta contamos el número de preguntas que haya realizado el usuario.
    $stmt = $dbh->prepare("SELECT  count(*) FROM QUESTIONS where id_user = :id");


    $stmt->execute($data);
    //Introducimos el dato recibido en el array asociativo y lo devolvemos
    $count = $stmt->fetchColumn();
    $persona["preguntas"]= $count;
    close();
    return $persona;
    }

}

//Funcion que se encarga guardar el ultimo login del usuario en la base de datos.
function updateLastLogin($id){
    require_once "bbdd.php";
    $dbh = connect();

    $data = array( 'id' => $id );
    //Seleccionamos la fecha actual para guardarla en el servidor
    $stmt = $dbh->prepare("UPDATE USERS SET last_login_date = CURRENT_TIMESTAMP where id_user= :id");


    $stmt->execute($data);


    close();
}

//Con esta funcion nos encargamos de que, a la hora de meterte a un perfil, se carguen las preguntas que haya realizado
//el usuario del perfil.
function cargarPreguntas($id){
    //Conexion con la base de datos.
    require_once "bbdd.php";
    $dbh = connect();
    if(isset($_GET["id"])) {
        $data = array('id' => $_GET["id"]);
        //Seleccionamos todas las preguntas.
        $stmt = $dbh->prepare("SELECT  * FROM QUESTIONS where id_user = :id");
        //Le damos el metodo de lectura de datos.
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        //Esta funcion se encarga de que, mientras encuentre filas, las enseñe generando la estructura HTML necesaria.
        $stmt->execute($data);
        while ($fila = $stmt->fetch()) {
            $likes = cargarLikesPregunta($fila["id_question"]);
            $replys = cargarReplysPregunta($fila["id_question"]);

            $tag = cargarTag($fila["id_topic"]);
            echo "<div class='pregunta'>";
            echo "<div class='interaccion'>";
            echo "<p>Likes: ".$likes."</p><p>Replys: ".$replys."</p></div>";
            echo "<div class='titulotags'>";
            echo "<h2 class='tituloPregunta'><a href='preguntas.php?pregunta=".$fila["id_question"]."'>" . $fila["title"] . "</a></h2>";
            echo "<p>".$fila["text"]."</p></div>";
            echo "<div class='fechaPregunta'>";
            echo "<p>Creada el: " . timeAgo($fila["date"]) . "</p> <p class='labels'>".$tag."</p></div></div>";

        }
    }
    else{
        $data = array('id' => $id);
        //Seleccionamos todas las preguntas.
        $stmt = $dbh->prepare("SELECT  * FROM QUESTIONS where id_user = :id");
        //Le damos el metodo de lectura de datos.
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        //Esta funcion se encarga de que, mientras encuentre filas, las enseñe generando la estructura HTML necesaria.
        $stmt->execute($data);
        while ($fila = $stmt->fetch()) {
            $likes = cargarLikesPregunta($fila["id_question"]);
            $replys = cargarReplysPregunta($fila["id_question"]);
            if ($_SESSION["idUsuario"] === $fila["id_user"]){
                $posicionBorrar = "inline-block";
            }else{
                $posicionBorrar = "none";
            }

            $tag = cargarTag($fila["id_topic"]);
            echo "<div class='pregunta'>";
            echo "<div class='interaccion'>";
            echo "<p>Likes: ".$likes."</p><p>Replys: ".$replys."</p></div>";
            echo "<div class='titulotags'>";
            echo "<div class='titleBorrar'><h2><a href='preguntas.php?pregunta=".$fila["id_question"]."'> ".$fila["title"]."</a></h2><a href='home.php?borrar=".$fila["id_question"]."'> <i style='color:black; display:".$posicionBorrar.";' class='fa fa-trash'></i></a>
           </div> <p>".$fila["text"]."</p></div>";
            echo "<div class='fechaPregunta'>";
            echo "<p>Creada: " . timeAgo($fila["date"]) . "</p><p class='labels'> ".$tag."</p></div></div>";


            //Estructura a generar
            /*  <div class="pregunta">
                <div class="interaccion">
                    <p>LIKES</p>
                    <p>RESPUESTAS</p>
                </div>
                <div class="titulotags">
                    <h3 class="tituloPregunta"><a href="#">"TITULO PREGUNTA"</a></h3>
                    <p>tags</p>
                </div>
                <div class="fechaPregunta">
                    <p>FECHA DE PREGUNTA</p>
                </div>
            </div>
            */
        }
    }
}

//Esta funcion se encarga mediante el id de la pregunta de cargar el tag correspondiente
function cargarTag($id){
    require_once "bbdd.php";
    $dbh = connect();

    $data = array( 'id' => $id );
    $stmt = $dbh->prepare("SELECT  * FROM TOPICS where id_topic = :id");


    $stmt->execute($data);
    $fila =  $stmt->fetch();

    close();
    return $fila["name"];
}
//En esta funcion nos encargamos de, que mediante el id del usuario, se devuelva la ruta de donde esta almacenada la
//imagen del usuario en el servidor para poder enseñarla
function cargarFotoPerfil($id)
{
    require_once "bbdd.php";
    $dbh = connect();
    if (isset($_GET["id"])) {
        $data = array('id' => $_GET["id"]);
        $stmt = $dbh->prepare("SELECT  id_user, username, profile_image FROM USERS where id_user = :id");
        $stmt->execute($data);
        $fila = $stmt->fetch();
        //En caso de que no la encuentre, este if devuelve la ruta de una imagen por defecto para todos los usuarios que
        //aun no hayan subido ninguna foto de perfil.
        if ($fila['profile_image'] != null) {
            echo $fila['profile_image'];
        } else {
            echo "../images/userProfile/.default.jpg";
        }
    } else {
        $data = array('id' => $id);
        $stmt = $dbh->prepare("SELECT  id_user, username, profile_image FROM USERS where id_user = :id");
        $stmt->execute($data);
        $fila = $stmt->fetch();
        //En caso de que no la encuentre, este if devuelve la ruta de una imagen por defecto para todos los usuarios que
        //aun no hayan subido ninguna foto de perfil.
        if ($fila['profile_image'] != null) {
            echo $fila['profile_image'];
        } else {
            echo "../images/userProfile/.default.jpg";
        }
    }
}
//Funcion de la paguina principal que se encarga de cargar todas las preguntas de la BBDD.

function cargarTodasPreguntas()
{
    require_once "bbdd.php";
    $dbh = connect();

    //Seleccionamos los datos a recoger.
    $stmt = $dbh->prepare("SELECT  * FROM QUESTIONS order by id_question desc");
    //Seleccionamos como vamos a leer los datos.
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    $stmt->execute();
    $contador = 0;
    if(isset($_SESSION["idUsuario"])){
        $posicion = "inline-block";
    }else{
        $posicion = "none";
    }

    //Mientras se encuentren preguntas, esta repetitiva se encarga de generar la estructura HTML necesaria.
    while ($fila = $stmt->fetch()) {
        if ($_SESSION["idUsuario"] === $fila["id_user"]){
            $posicionBorrar = "inline-block";
        }else{
            $posicionBorrar = "none";
        }

        
        $tag = cargarTag($fila["id_topic"]);
        $usuario = cargarCreadorPregunta($fila["id_user"]);
        $likes = cargarLikesPregunta($fila["id_question"]);
        $replys = cargarReplysPregunta($fila["id_question"]);

        echo "<div class='preguntas'>";
        echo "<p> Likes: <span id='contLikes".$fila["id_question"]."'>".$likes."</span><br>Replys: <span id='contReplys".$fila["id_question"]."'>".$replys."</span></p>";

        echo "<div class='iconos'>";
        echo "<button id='".$fila["id_question"]."' style='display:$posicion;' class='like'><i class='fas fa-heart' style='font-size:36px;color:".buscarLike($fila["id_question"])."'  value='".$contador."' id='like".$fila["id_question"]."' style='font-size:36px'></i></button>
                   <a href='preguntas.php?pregunta=".$fila["id_question"]."' ><i class='fas fa-eye' style='font-size:36px'></i></a></div>";
        echo "<div class='info'>";
        $contador = $contador +1;
        echo "<div class='titleBorrar'><h4><a href='preguntas.php?pregunta=".$fila["id_question"]."'> ".$fila["title"]."</a></h4><a href='home.php?borrar=".$fila["id_question"]."'> <i style='color:black; display:".$posicionBorrar.";' class='fa fa-trash'></i></a></div>";
        echo "<a href='user.php?id=".$fila["id_user"]."'><h5>$usuario</h2></a>";
        echo "<p>".$fila["text"]."</p></div>";
        echo "<div class='fechaTag'><span class='fecha'>".timeAgo($fila["date"])."</span><span class='labels'>".$tag."</span></div></div>";



        //Esta seria la estructura a generar
        /*<div class="preguntas">
                    <p>Likes</p>
                    <div class="iconos">

                        <button class="like"><i class='far fa-thumbs-up' style='font-size:36px'></i></button>
                        <button><i class='fas fa-eye' style='font-size:36px'></i></button>
                    </div>
                    <div class="info">
                        <h4>Titulo pregunta</h4>
                        <h5>Usuario que responde</h5>

                        <p>Li Europan lingues es membres del sam familie. Lor separat existentie es un myth.
                            Por scientie, musica, sport etc, litot Europa usa li sam vocabular.</p>
                    </div>

                    <span class="fecha">Nov 16 . 8 min read</span>

                </div>*/
    }
    close();
}



//Mediante esta funcion nos enecargamos de saber quien es el creador de la pregunta para poder enseñarlo
function cargarCreadorPregunta($id){
    require_once "bbdd.php";
    $dbh = connect();

    $data = array( 'id' => $id );
    $stmt = $dbh->prepare("SELECT  * FROM USERS where id_user = :id");


    $stmt->execute($data);
    $fila =  $stmt->fetch();

    close();
    //Devolvemos el nombre y el apellido
    return $fila["name"]." ".$fila["surname"];
}

//Con esta función nos encargamos de cargar las etiquetas que hay en la BBDD.
function cargarEtiquetas(){

    require_once "bbdd.php";
    $dbh = connect();
    $stmt = $dbh->prepare("SELECT  * FROM TOPICS");
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    $stmt->execute();
    while ($fila = $stmt->fetch()) {
       echo  " <button class='labels'  value='<".$fila["name"].">' type='button' > <a href='home.php?tag=".$fila["id_topic"]."'>".$fila["name"]."</a></button>";

    }
    close();
}
//Mediante esta funcion nos encargamos de, mediante el id de la pregunta poder generar el número de likes que tenga esa
//pregunta
function cargarLikesPregunta($id){
    require_once "bbdd.php";
    $dbh = connect();

    $data = array( 'id' => $id);
    $stmt = $dbh->prepare("SELECT  count(*) FROM LIKES_QUESTIONS where id_question = :id");


    $stmt->execute($data);
    $count = $stmt->fetchColumn();
    close();


    return $count;

}



function cargarPregunta(){
    require_once "bbdd.php";
    $dbh = connect();
    $data = array( 'id' => $_GET["pregunta"]);
    if(isset($_SESSION["idUsuario"])){
        $posicion = "inline-block";
    }else{
        $posicion = "none";
    }

    //Seleccionamos los datos a recoger.
    $stmt = $dbh->prepare("SELECT  * FROM QUESTIONS where id_question = :id");
    //Seleccionamos como vamos a leer los datos.
    $contador = 0;
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $stmt->execute($data);
        $fila = $stmt->fetch();
        $tag = cargarTag($fila["id_topic"]);
        $usuario = cargarCreadorPregunta($fila["id_user"]);
        $likes = cargarLikesPregunta($fila["id_question"]);
        $replys = cargarReplysPregunta($fila["id_question"]);
        if (isset($fila["questionImage"]))
        {
            $posicion = "block";
        }else{
            $posicion = "none";
        }
    if ($_SESSION["idUsuario"] === $fila["id_user"]){
        $posicionBorrar = "inline-block";
    }else{
        $posicionBorrar = "none";
    }

    if (isset($_SESSION["idUsuario"])){
        $like = "block";
    }else{
        $like="none";
    }

    echo "<div class='iconos'>";
        echo "<p> Likes: <span id='contLikes".$fila["id_question"]."'>".$likes."</span><br>Replys: <span id='contReplys".$fila["id_question"]."'>".$replys."</span></p>";
        echo "<button  id='".$fila["id_question"]."' style='display:$posicion;' class='like'><i class='fas fa-heart' value='".$contador."' id='like".$fila["id_question"]."' style='display:".$like.";font-size:36px;color:".buscarLike($fila["id_question"])."'></i></button>
          </div>";
            $contador = $contador +1;
        echo "<div class='info'>
            <div id='titleBorrar'>
            <h1>".$fila["title"]."</h1> <i style='color:black; display:".$posicionBorrar.";' class='fa fa-trash'></i></div>
            <p>".$fila["text"]."</p>
            <img alt='' style='display:".$posicion.";' src='".$fila["questionImage"]."'>
        </div>";
        echo "<div id='etiquetas'>
        <button class='labels' ><a href='home.php?tag=".$fila["id_topic"]."' >".$tag."</a></button>
        <a class='labels' style='background: black;color: white' href='newquestions.php?reply=".$fila["id_question"]."'>RESPONDER</a>
        </div>";
        echo "
        <div class='des1'>
            <h2 class='usu'>".$usuario."</h2>
            <span class='fecha'>".timeAgo($fila["date"])."</span>
        </div>";


        /*
         *      <div class="iconos">

            <button class="like"><i class='far fa-thumbs-up' style='font-size:36px'></i></button>
            <button class="reply"><i class="fa fa-reply" style='font-size:36px'></i></button>
        </div>
        <div class="info">
            <h1>Titulo pregunta</h1>

            <p>Li Europan lingues es membres del sam familie. Lor separat existentie es un myth.
                Por scientie, musica, sport etc, litot Europa usa li sam vocabular.</p>
        </div>


        <div id="etiquetas">
        <button class="labels" >Etiquetas</button>
        <button class="labels" >Etiquetas</button>
        <button class="labels" >Etiquetas</button>
        </div>

        <div class="des1">
        <h2 class="usu">Usuario</h2>
        <span class="fecha">Nov 16 . 8 min read</span>
        </div>*/


    close();
}


function cargarRespuestas(){
    require_once "bbdd.php";
    $dbh = connect();
    $data = array( 'id' => $_GET["pregunta"]);

    //Seleccionamos los datos a recoger.
    $stmt = $dbh->prepare("SELECT  * FROM ANSWERS where id_question = :id");
    //Seleccionamos como vamos a leer los datos.
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    $stmt->execute($data);
    if(isset($_SESSION["idUsuario"])){
        $posicion = "inline-block";
    }else{
        $posicion = "none";
    }

    $contador = 0;
    echo "<h1 class='respu'>RESPUESTAS</h1> <article id='replys'>";

    while ($fila = $stmt->fetch()) {
        $usuario = cargarCreadorPregunta($fila["id_user"]);
        $idRespuesta = $fila["id_answer"];
        $likes = contarLikesRespuesta($fila["id_answer"]);
        $contador = $contador +1;
        echo "<div class='respuesta'>";
        echo "<div class='respIzq'><h2>RESPUESTA ".$contador."</h2>";
        echo "<h2>Likes: ".$likes." </h2>";
        echo "<button  id='".$idRespuesta."-".$fila["id_question"]."'  class='likeRespuesta'>
        <i class='fas fa-heart' value='".$contador."' id='likeRespuesta".$idRespuesta."' style='display:$posicion;color:".buscarLikeRespuesta($idRespuesta)."'></i></button>";//;color:".buscarLikeRespuesta($fila["id_question"])."
        echo "<button id='".$idRespuesta."-".$fila["id_question"]."-"."mejorRespuesta"."' class='mejorRespuesta'>
        <i class='fas fa-check' style='color:".buscarMejorRespuesta($idRespuesta,$fila["id_question"])."'></i></button>";
        echo "<div class='des2'>
            <a href='user.php?id=".$fila["id_user"]."'><h2 class='usu'>$usuario</h2></a>
            <span class='fecha'>".timeAgo($fila["date"])."</span></div>
        </div>";
        echo "<div class='respDer'> <p>".$fila["text"]."</p><img alt='' src='".$fila["answerImage"]."'></div>";
        echo "</div>";
    }
    echo "</article>";

    /*
      <h2>RESPUESTA 1</h2>
        <p>Li Europan lingues es membres del sam familie. Lor separat existentie es un myth.
            Por scientie, musica, sport etc, litot Europa usa li sam voca Europan lingues es membres del sam familie. Lor separat existentie es un myth.
            Por scientie, musica, sport etc, litot Europa usa li sam voc Europan lingues es membres del sam familie. Lor separat existentie es un myth.
            Por scientie, musica, sport etc, litot Europa usa li sam voc Europan lingues es membres del sam familie. Lor separat existentie es un myth.
            Por scientie, musica, sport etc, litot Europa usa li sam voc </p>
        <div class="des2">
            <h2 class="usu">Usuario</h2>
            <span class="fecha">Nov 16 . 8 min read</span>
        </div>
    */
}


function cargarUsuarioMenu($id){
    require_once "bbdd.php";
    $dbh = connect();

    $data = array( 'id' => $id);
    //Seleccionamos al usuario mediante el id que nos proporcionen.
    $stmt = $dbh->prepare("SELECT  id_user, username, name, surname, biography,email, last_login_date,profile_image  FROM USERS where id_user = :id");


    $stmt->execute($data);
    $fila = $stmt->fetch();
    //Preparamos el array asociativo;
    $persona=[
        "id"=> $fila["id_user"],
        "username"=>$fila["username"],
        "nombre"=>$fila["name"],
        "apellido"=>$fila["surname"],
        "biografia"=>$fila["biography"],
        "email"=>$fila["email"],
        "ultimoLogin"=>timeAgo($fila["last_login_date"]),
        "foto"=>$fila["profile_image"]
    ];
    //Mediante esta consulta contamos el número de preguntas que haya realizado el usuario.
    $stmt = $dbh->prepare("SELECT  count(*) FROM QUESTIONS where id_user = :id");


    $stmt->execute($data);
    //Introducimos el dato recibido en el array asociativo y lo devolvemos
    $count = $stmt->fetchColumn();
    $persona["preguntas"]= $count;
    close();
    return $persona;
}
function cargarFotoPerfilMenu($id){
    require_once "bbdd.php";
    $dbh = connect();

    $data = array('id' => $id);
    $stmt = $dbh->prepare("SELECT  id_user, username, profile_image FROM USERS where id_user = :id");
    $stmt->execute($data);
    $fila = $stmt->fetch();
    //En caso de que no la encuentre, este if devuelve la ruta de una imagen por defecto para todos los usuarios que
    //aun no hayan subido ninguna foto de perfil.
    if ($fila['profile_image'] != null) {
        echo $fila['profile_image'];
    }else {
        echo "../images/userProfile/.default.jpg";
    }
}

function cargarReplysPregunta($id){
    require_once "bbdd.php";
    $dbh = connect();

    $data = array( 'id' => $id);
    $stmt = $dbh->prepare("SELECT  count(*) FROM ANSWERS where id_question = :id");


    $stmt->execute($data);
    $count = $stmt->fetchColumn();
    close();


    return $count;

}
function buscarLike($idPregunta){
    require_once "bbdd.php";
    $dbh = connect();
    $data = array("idPregunta"=>$idPregunta,"idUsuario"=>$_SESSION["idUsuario"]);
    $stmt = $dbh->prepare("SELECT COUNT(*) FROM LIKES_QUESTIONS WHERE id_user = :idUsuario AND id_question = :idPregunta LIMIT 1;");
    $stmt->execute($data);
    $respuesta = $stmt->fetchColumn();
    close();

    if ($respuesta==1){
        return "red";
    }else {
        return "black";
    }
}
function buscarLikeRespuesta($idAnswer){
    require_once "bbdd.php";
    $dbh = connect();
    $data = array("idRespuesta"=>$idAnswer,"idUsuario"=>$_SESSION["idUsuario"]);
    $stmt = $dbh->prepare("SELECT COUNT(*) FROM LIKES_ANSWERS WHERE id_user = :idUsuario AND id_answer = :idRespuesta LIMIT 1;");
    $stmt->execute($data);
    $respuesta = $stmt->fetchColumn();
    close();

    if ($respuesta==1){
        return "red";
    }else {
        return "black";
    }
}
function buscarMejorRespuesta($idRespuesta,$idPregunta){
    require_once "bbdd.php";
    $dbh = connect();
    $data = array("idPregunta"=>$idPregunta,"idRespuesta"=>$idRespuesta);
    $stmt = $dbh->prepare("SELECT COUNT(*) FROM ANSWERS WHERE id_answer = :idRespuesta AND id_question = :idPregunta AND best_answer=1;");
    $stmt->execute($data);
    $respuesta = $stmt->fetchColumn();
    close();

    if ($respuesta==1){
        return "green";
    } else {
        return "black";
    }

}
function timeAgo($date){
	   $timestamp = strtotime($date);	
	   
	   $strTime = array("second", "minute", "hour", "day", "month", "year");
	   $length = array("60","60","24","30","12","10");

	   $currentTime = time();
	   if($currentTime >= $timestamp) {
			$diff     = time()- $timestamp;
			for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
			$diff = $diff / $length[$i];
			}

			$diff = round($diff);
			return $diff . " " . $strTime[$i] . "(s) ago ";
	   }	
}

function cargarPreguntasSinRespuesta(){
    require_once "bbdd.php";
    $dbh = connect();

    $stmt = $dbh->prepare("SELECT QUESTIONS.* FROM QUESTIONS LEFT JOIN ANSWERS ON QUESTIONS.id_question = ANSWERS.id_question WHERE ANSWERS.id_question is null order by id_question desc ");
    $stmt->execute();
    $contador = 0;
    //Mientras se encuentren preguntas, esta repetitiva se encarga de generar la estructura HTML necesaria.
    while ($fila = $stmt->fetch()) {
        if ($_SESSION["idUsuario"] === $fila["id_user"]){
            $posicionBorrar = "inline-block";
        }else{
            $posicionBorrar = "none";
        }

        $tag = cargarTag($fila["id_topic"]);
        $usuario = cargarCreadorPregunta($fila["id_user"]);
        $likes = cargarLikesPregunta($fila["id_question"]);
        $replys = cargarReplysPregunta($fila["id_question"]);

        echo "<div class='preguntas'>";
        echo "<p> Likes: <span id='contLikes" . $fila["id_question"] . "'>" . $likes . "</span><br>Replys: <span id='contReplys" . $fila["id_question"] . "'>" . $replys . "</span></p>";

        echo "<div class='iconos'>";
        echo "<button id='" . $fila["id_question"] . "'  class='like'><i class='fas fa-heart' style='font-size:36px;color:" . buscarLike($fila["id_question"]) . "'  value='" . $contador . "' id='like" . $fila["id_question"] . "' style='font-size:36px'></i></button>
                   <a href='preguntas.php?pregunta=" . $fila["id_question"] . "' ><i class='fas fa-eye' style='font-size:36px'></i></a></div>";
        echo "<div class='info'>";
        $contador = $contador + 1;
        echo "<div class='titleBorrar'><h4><a href='preguntas.php?pregunta=".$fila["id_question"]."'> ".$fila["title"]."</a></h4><a href='home.php?borrar=".$fila["id_question"]."'> <i style='color:black; display:".$posicionBorrar.";' class='fa fa-trash'></i></a></div>";
        echo "<a href='user.php?id=" . $fila["id_user"] . "'><h5>$usuario</h2></a>";
        echo "<p>" . $fila["text"] . "</p></div>";
        echo "<div class='fechaTag'><span class='fecha'>" . timeAgo($fila["date"]) . "</span><span class='labels'>" . $tag . "</span></div></div>";

    }
        close();
}

function cargarPreguntasPorTag(){
    require_once "bbdd.php";
    $dbh = connect();
    $data = array('id'=>$_GET["tag"]);
    //Seleccionamos los datos a recoger.
    $stmt = $dbh->prepare("SELECT  * FROM QUESTIONS where id_topic = :id order by id_question desc");
    //Seleccionamos como vamos a leer los datos.
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    $stmt->execute($data);
    $contador = 0;
    if(isset($_SESSION["idUsuario"])){
        $posicion = "inline-block";
    }else{
        $posicion = "none";
    }


    //Mientras se encuentren preguntas, esta repetitiva se encarga de generar la estructura HTML necesaria.
    while ($fila = $stmt->fetch()) {

        if ($_SESSION["idUsuario"] === $fila["id_user"]){
            $posicionBorrar = "inline-block";
        }else{
            $posicionBorrar = "none";
        }

        $tag = cargarTag($fila["id_topic"]);
        $usuario = cargarCreadorPregunta($fila["id_user"]);
        $likes = cargarLikesPregunta($fila["id_question"]);
        $replys = cargarReplysPregunta($fila["id_question"]);

        echo "<div class='preguntas'>";
        echo "<p> Likes: <span id='contLikes".$fila["id_question"]."'>".$likes."</span><br>Replys: <span id='contReplys".$fila["id_question"]."'>".$replys."</span></p>";

        echo "<div class='iconos'>";
        echo "<button id='".$fila["id_question"]."' style='display:$posicion;' class='like'><i class='fas fa-heart' style='font-size:36px;color:".buscarLike($fila["id_question"])."'  value='".$contador."' id='like".$fila["id_question"]."' style='font-size:36px'></i></button>
                   <a href='preguntas.php?pregunta=".$fila["id_question"]."' ><i class='fas fa-eye' style='font-size:36px'></i></a></div>";
        echo "<div class='info'>";
        $contador = $contador +1;
        echo "<div class='titleBorrar'><h4><a href='preguntas.php?pregunta=".$fila["id_question"]."'> ".$fila["title"]."</a></h4><a href='home.php?borrar=".$fila["id_question"]."' <i style='color:black; display:".$posicionBorrar.";' class='fa fa-trash'></i></div>";
        echo "<a href='user.php?id=".$fila["id_user"]."'><h5>$usuario</h2></a>";
        echo "<p>".$fila["text"]."</p></div>";
        echo "<div class='fechaTag'><span class='fecha'>".timeAgo($fila["date"])."</span><span class='labels'>".$tag."</span></div></div>";


    }
    close();
}

function cargarEtiquetasSinEnlace(){
    require_once "bbdd.php";
    $dbh = connect();
    $stmt = $dbh->prepare("SELECT  * FROM TOPICS");
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    $stmt->execute();
    while ($fila = $stmt->fetch()) {
        echo  " <button class='labels'  value='".$fila["name"]."' type='button' >".$fila["name"]."</button>";

    }
    close();
}

function borrarPregunta(){
    require_once "bbdd.php";
    $dbh = connect();
    $data = array('id'=>$_GET["borrar"]);
    $stmt = $dbh->prepare("DELETE FROM QUESTIONS WHERE id_question = :id");
    
    $stmt->execute($data);
     echo  '<script type="text/javascript">';
     echo 'window.location.href = "home.php"';
     echo '</script>';

close();


}

function contarLikesRespuesta($id){
    require_once "bbdd.php";
    $dbh = connect();

    $data = array( 'id' => $id);
    $stmt = $dbh->prepare("SELECT  count(*) FROM LIKES_ANSWERS where id_answer = :id");


    $stmt->execute($data);
    $count = $stmt->fetchColumn();
    close();


    return $count;
}
