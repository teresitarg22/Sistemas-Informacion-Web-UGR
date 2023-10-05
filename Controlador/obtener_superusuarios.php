<?php
    require "../Vista/padre.php";

    // Consulta para contar la cantidad de usuarios superusuarios
    $sqlCountSuperusuarios = "SELECT COUNT(*) as count FROM usuarios WHERE tipo = 'superusuario'";

    // Ejecutar la consulta:
    $resultCountSuperusuarios = $mysqli->query($sqlCountSuperusuarios);
    $countSuperusuarios = $resultCountSuperusuarios->fetch_assoc()['count'];

    // Enviar la respuesta JSON
    header('Content-Type: application/json');
    echo json_encode(['count' => $countSuperusuarios]);
?>