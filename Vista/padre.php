<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";
    include("../Modelo/bdUsuarios.php");
    
    // Abrimos una conexión con la base de datos.
    $bd = Database::getInstancia();
    $mysqli = $bd->getConexion();
    
    // Cargamos la plantilla desde Templates:
    $loader = new \Twig\Loader\FilesystemLoader('../Templates');
    $twig = new \Twig\Environment($loader);
    $twig->addGlobal('mysqli', $mysqli);
    
    // Consultamos el usuario actual en nuestra base de datos y renderizamos la plantilla:
    session_start();
    
    if (isset($_SESSION['nickUsuario'])) {
        $nick = $_SESSION['nickUsuario'];
        $usuario = getUser($nick);
    } else {
        $usuario = "";
    }
?>
