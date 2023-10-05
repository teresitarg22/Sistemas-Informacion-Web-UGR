<?php
    require "../Vista/padre.php";
    
    // Verificamos si se envió el formulario y se procesan los datos.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener los datos del formulario
        $nombre = $_POST['nombre'];
        $fecha = $_POST['fecha'];
        $biografia = $_POST['biografia'];
        $frases = $_POST['frases'];
        
        // Actualizar los datos del usuario en la base de datos
        $insertado = insertCientifico($nombre, $fecha, $biografia, $frases);
        
        // Comprobar si la actualización fue exitosa
        if ($insertado) {
            echo "Datos insertados correctamente.";
        } else {
            echo "Error al insertar los datos.";
        }
    }

    echo $twig->render('añadir_cientifico.html', ['usuario' => $usuario, 'isLogged' => true]);
?>
