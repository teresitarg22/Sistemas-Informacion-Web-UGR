<?php
    require "padre.php";

    $resultado = $mysqli->query("SELECT nombre_usuario, tipo FROM usuarios");
    $usuarios = array();

    if($resultado->num_rows > 0){
        while($fila = $resultado->fetch_assoc()){
            $user = array('nombre' => $fila['nombre_usuario'], 'tipo' => $fila['tipo']);
            array_push($usuarios, $user);
        }
    }

    if($usuario === "")
        echo $twig->render('listado_usuarios.html', ['usuarios' => $usuarios, 'isLogged' => false]);
    else
        echo $twig->render('listado_usuarios.html', ['usuarios' => $usuarios, 'usuario' => $usuario, 'isLogged' => true]);
?>