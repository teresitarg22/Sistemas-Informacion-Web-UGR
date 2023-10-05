<?php
    require "../Vista/padre.php";
    
    // Obtener el valor del campo de texto
    $nombre = $_GET['nombre'];

    // Realizar la búsqueda en la base de datos
    // Aquí debes agregar tu lógica para obtener los nombres de científicos que coincidan con la búsqueda

    // Mostrar los resultados de la búsqueda
    if (isset($nombresCientificos) && !empty($nombresCientificos)) {
        echo '<ul>';
        foreach ($nombresCientificos as $nombreCientifico) {
            echo '<li>' . $nombreCientifico . '</li>';
        }
        echo '</ul>';
    } else {
        echo 'No se encontraron resultados.';
}
?>
