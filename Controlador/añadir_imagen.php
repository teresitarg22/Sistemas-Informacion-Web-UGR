<?php
    require "../Vista/padre.php";

    $idCientifico = $_GET['cientifico'];
    $cientifico = getCientifico($idCientifico);

    // Verificamos si se envió el formulario y se procesan los datos.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_FILES['imagen'])){
            $errors = array();
            $file_name = $_FILES['imagen']['name'];
            $file_size = $_FILES['imagen']['size'];
            $file_tmp = $_FILES['imagen']['tmp_name'];
            $file_type = $_FILES['imagen']['type'];
            $file_ext_parts = explode('.', $_FILES['imagen']['name']);
            $file_ext = strtolower(end($file_ext_parts));

            $extensions = array("jpeg", "jpg", "png");

            if (in_array($file_ext, $extensions) === false){
                $errors[] = "Extensión no permitida, elige una imagen JPEG o PNG.";
            }

            if ($file_size > 2097152){
                $errors[] = 'Tamaño del fichero demasiado grande';
            }

            if (empty($errors) == true) {
                $destination_folder = "../Img/Cientificos";

                $destination_path = $destination_folder . "/" . $file_name;

                move_uploaded_file($file_tmp, $destination_path);

                // Insertar la ruta de la imagen en la tabla "galeria"
                insertarImagenGaleria($idCientifico, $destination_path);

                $varsParaTwig['imagen'] = $destination_path;
            }

            if (sizeof($errors) > 0) {
                $varsParaTwig['errores'] = $errors;
            }
        }
    }

    $cientifico = getCientifico($idCientifico);
    $hashtags = getHashtags($idCientifico);
    echo $twig->render('configurar_cientifico.html', ['cientifico' => $cientifico, 'hashtags' => $hashtags, 'usuario' => $usuario, 'isLogged' => true]);
?>
