<?php
    require "../Vista/padre.php";
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $query = $_POST['query'];

        if($usuario['tipo'] == 'gestor'){
            // Realizar la consulta en la base de datos
            $sql = "SELECT * FROM cientificos WHERE nombre LIKE '%$query%'";
        }
        else{
            // Realizar la consulta en la base de datos
            $sql = "SELECT * FROM cientificos WHERE nombre LIKE '%$query%' AND publicado = 1";
        }

        $result = $mysqli->query($sql);
        $resultados = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $resultados[] = $row;
        }
        
        echo json_encode($resultados);
    }

?>
