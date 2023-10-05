<?php
    require "../Vista/padre.php";

    $data = json_decode(file_get_contents("php://input"), true);
    $idComentario = $data["id_comentario"];
    $idCientifico = $data["id_cientifico"];
    $comentario = $data["nuevo_comentario"];

    // Obtener el comentario original de la base de datos
    $comentarioOriginal = obtenerComentario($idComentario);

    // Verificar si el comentario original ya contiene la marca de edición
    $marcaEdicion = "*Editado por el moderador";
    if (strpos($comentarioOriginal, $marcaEdicion) === false) {
        // Agregar la marca de edición al comentario editado con dos saltos de línea
        $comentario = $comentario . "\n\n" . $marcaEdicion;
    }

    // Actualizar los datos del comentario en la base de datos
    updateComentario($idComentario, $comentario);
    
?>