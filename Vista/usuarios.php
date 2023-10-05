<?php
    require "padre.php";

    // Compruebo si está loggeado:
    if ($usuario == "") {
        header("Location: login.php");
        exit();
    } 

    if($usuario === "")
        echo $twig->render('usuarios.html', ['usuario' => $usuario, 'isLogged' => false]);
    else
        echo $twig->render('usuarios.html', ['usuario' => $usuario, 'isLogged' => true]);
    
?>
