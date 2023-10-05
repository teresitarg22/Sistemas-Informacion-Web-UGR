<?php
    require "padre.php";

    $resultado = $mysqli->query("SELECT id, nombre, publicado FROM cientificos");
    $cientifico = array();

    if($resultado->num_rows > 0){
        while($fila = $resultado->fetch_assoc()){
            $cient = array('id' => $fila['id'], 'nombre' => $fila['nombre'], 'publicado' => $fila['publicado']);

            // Obtenemos la primera foto de la galería del científico:
            $fotos = getGaleria($fila['id']);
            
            if (!empty($fotos)) {
                $primera_foto = $fotos[0];
                $cient['foto'] = $primera_foto['foto'];
            }

            array_push($cientifico, $cient);
        }
    }

    if(isset($_GET['publicado'])){
        $registrado = $_GET['publicado'];        
    }
    else{
        $registrado = 1;
    }

    if($usuario === "")
        echo $twig->render('portada.html', ['cientifico' => $cientifico, 'isLogged' => false, 'publicado' => $registrado]);
    else
        echo $twig->render('portada.html', ['cientifico' => $cientifico, 'usuario' => $usuario, 'isLogged' => true, 'publicado' => $registrado]);

?>
