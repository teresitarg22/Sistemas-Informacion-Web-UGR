<?php
    require "padre.php";

    // Comprobamos que el id del científico no es nulo y lo guardamos:
    if(isset($_GET['cientifico'])){
        $idCientifico = $_GET['cientifico'];
    }else{
        $idCientifico = -1;
    }
    
    // Consultamos el científico en nuestra base de datos y renderizamos la plantilla:
    $cientifico = getCientifico($idCientifico);
    $enlaces = getEnlaces($idCientifico);
    $galeria = getGaleria($idCientifico);
    $comentarios = getComentarios($idCientifico);
    $hashtags = getHashtags($idCientifico);

    if($usuario === "")
        echo $twig->render('cientificos.html', ['cientifico' => $cientifico, 'enlaces' => $enlaces, 'galeria' => $galeria, 'comentarios' => $comentarios, 'hashtags' => $hashtags, 'isLogged' => false]);
    else
        echo $twig->render('cientificos.html', ['cientifico' => $cientifico, 'enlaces' => $enlaces, 'galeria' => $galeria, 'comentarios' => $comentarios, 'usuario' => $usuario, 'hashtags' => $hashtags, 'isLogged' => true]);
?>
