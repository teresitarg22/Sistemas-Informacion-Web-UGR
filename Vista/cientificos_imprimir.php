<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";
    include("../Modelo/bd.php");

    // Abrimos una conexión con la base de datos.
    $bd = Database::getInstancia();
    $mysqli = $bd->getConexion();
    
    // Cargamos la plantilla desde Templates:
    $loader = new \Twig\Loader\FilesystemLoader('../Templates');
    $twig = new \Twig\Environment($loader);
    $twig->addGlobal('mysqli', $mysqli);

    // Comprobamos que el id del científico no es nulo y lo guardamos:
    if(isset($_GET['cientifico'])){
        $idCientifico = $_GET['cientifico'];
    }else{
        $idCientifico = -1;
    }
    
    // Consultamos el científico en nuestra base de datos y renderizamos la plantilla:
    $cientifico = getCientifico($idCientifico);
    echo $twig->render('cientificos_imprimir.html', ['cientifico' => $cientifico]);
    
?>
