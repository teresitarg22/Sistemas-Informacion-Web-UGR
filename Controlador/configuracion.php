<?php
    require "../Vista/padre.php";
    
    // Verificamos si se envió el formulario y se procesan los datos.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener los datos del formulario
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $correo = $_POST['correo'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $ciudad = $_POST['ciudad'];
        $pais = $_POST['pais'];
        $cp = $_POST['cp'];
        
        // Actualizar los datos del usuario en la base de datos
        $actualizado = updateUser($nick, $nombre, $apellidos, $correo, $telefono, $direccion, $ciudad, $pais, $cp);
        
        // Comprobar si la actualización fue exitosa
        if ($actualizado) {
            echo "Datos actualizados correctamente.";
            $usuario = getUser($nick);
            echo $twig->render('configuracion.html', ['usuario' => $usuario, 'isLogged' => true]);
            exit();
        } else {
            echo "Error al actualizar los datos.";
        }
    }

    
    echo $twig->render('configuracion.html', ['usuario' => $usuario, 'isLogged' => true]);
?>
