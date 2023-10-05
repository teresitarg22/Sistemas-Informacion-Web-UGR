<?php
    require "../Vista/padre.php";

    $idCientifico = $_GET['cientifico'];
    
    // Eliminar los datos del usuario en la base de datos
    $stmt = $mysqli->prepare("DELETE FROM galeria WHERE id_cientifico = ?");
    $stmt->bind_param("i", $idCientifico); 
    $stmt->execute();
    $stmt = $mysqli->prepare("DELETE FROM comentarios WHERE id_cientifico = ?");
    $stmt->bind_param("i", $idCientifico); 
    $stmt->execute();
    $stmt = $mysqli->prepare("DELETE FROM enlaces WHERE id_cientifico = ?");
    $stmt->bind_param("i", $idCientifico); 
    $stmt->execute();
    $stmt = $mysqli->prepare("DELETE FROM cientificos WHERE id = ?");
    $stmt->bind_param("i", $idCientifico); 
    $stmt->execute();

    header("Location: ../Vista/portada.php");
    exit();
?>
