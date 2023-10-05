<?php
    require "../Vista/padre.php";

    // Comprobamos que el id del científico no es nulo y lo guardamos:
    if(isset($_GET['cientifico'])){
        $idCientifico = $_GET['cientifico'];
    }else{
        $idCientifico = -1;
    }
    
    $resultado = $mysqli->query("SELECT publicado FROM cientificos WHERE id = '$idCientifico'");
    
    // Si se han encontrado resultados en la consulta, guardamos la info de cada comentario:
    if($resultado->num_rows > 0){
        $fila = $resultado->fetch_assoc();
        $publicado = $fila['publicado'];
    }

    // Construye la consulta SQL de actualización y la ejecutamos:
    if($publicado == 0)
        $query = "UPDATE cientificos SET publicado = 1 WHERE id = '$idCientifico'";
    else
        $query = "UPDATE cientificos SET publicado = 0 WHERE id = '$idCientifico'";

    $mysqli->query($query);

    header("Location: ../Vista/portada.php");
    exit();
?>