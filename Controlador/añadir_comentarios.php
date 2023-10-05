<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";
    include("../Modelo/bd.php");

    $bd = Database::getInstancia();
    $mysqli = $bd->getConexion();
    
    $loader = new \Twig\Loader\FilesystemLoader('../Templates');
    $twig = new \Twig\Environment($loader);
    $twig->addGlobal('mysqli', $mysqli);

    $data = json_decode(file_get_contents("php://input"), true);

    $idCientifico = $data["id_cientifico"];
    $nombre = $data["nombre"];
    $email = $data["email"];
    $comentario = $data["comentario"];
    $fecha = $data["fecha"];
    $hora = $data["hora"];

    // Compruebo que no haya sentencias maliciosas:
    $comp = $mysqli->prepare("INSERT INTO comentarios (id_cientifico, nombre, email, comentario, fecha, hora) VALUES (?, ?, ?, ?, ?, ?);");
    $comp->bind_param("isssss", $idCientifico, $nombre, $email, $comentario, $fecha, $hora);
    $comp->execute();  
?>
