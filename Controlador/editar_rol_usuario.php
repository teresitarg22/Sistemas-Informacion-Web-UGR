<?php
    require "../Vista/padre.php";

    $data = json_decode(file_get_contents("php://input"), true);
    $usuario = $data["usuario"];
    $tipo = $data["tipo"];

    // Consulta para contar la cantidad de usuarios superusuarios
    $sqlCountSuperusuarios = "SELECT COUNT(*) as count FROM usuarios WHERE tipo = 'superusuario'";

    // Ejecutar la consulta:
    $resultCountSuperusuarios = $mysqli->query($conexion, $sqlCountSuperusuarios);
    $countSuperusuarios = $resultCountSuperusuarios->fetch_assoc()['count'];

    if ($countSuperusuarios <= 1 && $tipo == "superusuario") {
        // No permitir cambiar el rol y devolver una respuesta JSON con un mensaje de error:
        header('Content-Type: application/json');
        echo json_encode(['error' => 'No se puede cambiar el rol del único usuario superusuario.']);
        exit;
    }
    else {
        // Actualizar los datos del usuario en la base de datos:
        updateRolUsuario($usuario, $tipo);
    }
?>


