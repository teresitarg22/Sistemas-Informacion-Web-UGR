<?php
    require "../Vista/padre.php";

    $data = json_decode(file_get_contents("php://input"), true);
    $idComentario = $data["id_comentario"];
    
    // Eliminar el comentario de la base de datos:
    $stmt = $mysqli->prepare("DELETE FROM comentarios WHERE id = ?");
    $stmt->bind_param("i", $idComentario); 
    $stmt->execute();

    header("Location: ../Vista/portada.php");
    exit();
?>