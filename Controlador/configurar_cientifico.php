<?php
require "../Vista/padre.php";

$idCientifico = $_GET['cientifico'];

// Verificamos si se envió el formulario y se procesan los datos.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha'];
    $biografia = $_POST['biografia'];
    $frases = $_POST['frases'];
    $hashtags = $_POST['hashtags'];

    // Verificar si es un string y eliminar espacios en blanco y tabulaciones
    if (is_string($hashtags)) {
        $hashtags = trim($hashtags);
        $hashtags = preg_replace('/\s+/', ' ', $hashtags);
    }
    
    // Actualizar los datos del usuario en la base de datos
    $actualizado = updateCientifico($idCientifico, $nombre, $fecha, $biografia, $frases);
    $insertado = updateHashtags($idCientifico, $hashtags);
    
    // Comprobar si la actualización fue exitosa
    if ($actualizado && $insertado) {
        echo "Datos actualizados correctamente.";
    } else if (!$insertado) {
        echo "Los hashtags deben comenzar por '#'.";
    } else {
        echo "Error al actualizar los datos.";
    }
}

$cientifico = getCientifico($idCientifico);
$hashtags = getHashtags($idCientifico);
echo $twig->render('configurar_cientifico.html', ['cientifico' => $cientifico, 'hashtags' => $hashtags, 'usuario' => $usuario, 'isLogged' => true]);
?>
