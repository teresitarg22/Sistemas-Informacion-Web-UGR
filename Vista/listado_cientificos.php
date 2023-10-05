<?php
    require "padre.php";

    $resultado = $mysqli->query("SELECT id, nombre FROM cientificos");
    $cientificos = array();

    if($resultado->num_rows > 0){
        while($fila = $resultado->fetch_assoc()){
            $cient = array('id' => $fila['id'], 'nombre' => $fila['nombre']);
            array_push($cientificos, $cient);
        }
    }

    if($usuario === "")
        echo $twig->render('listado_cientificos.html', ['cientificos' => $cientificos, 'isLogged' => false]);
    else
        echo $twig->render('listado_cientificos.html', ['cientificos' => $cientificos, 'usuario' => $usuario, 'isLogged' => true]);
?>
