<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";
    include("../Modelo/bd.php");

    $bd = Database::getInstancia();
    $mysqli = $bd->getConexion();
    
    $resultado = $mysqli->query("SELECT * FROM palabras_prohibidas");
    $palabras = array();

    if($resultado->num_rows > 0){
        while($fila = $resultado->fetch_assoc()){
            array_push($palabras, $fila['nombre']);
        }
    }
    
    echo json_encode($palabras);
    
?>
