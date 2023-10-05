<?php
    require "../Vista/padre.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idImagen'])) {
        $idImagen = $_POST['idImagen'];
    
        // Obtener la información de la imagen desde la base de datos
        $imagen = obtenerInformacionImagen($idImagen);
    
        if ($imagen) {
            // Eliminar la imagen de la carpeta de imágenes
            $rutaImagen = $imagen['foto'];
            if (file_exists($rutaImagen)) {
                unlink($rutaImagen);
            }
    
            // Eliminar la entrada de la imagen de la base de datos
            eliminarImagenGaleria($idImagen);
    
            echo "Imagen eliminada correctamente.";
        } else {
            echo "La imagen no existe.";
        }
    } else {
        echo "Solicitud inválida.";
    }
    
?>
