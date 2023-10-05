<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";
    require_once "../Modelo/bdUsuarios.php";

    // Abrimos una conexión con la base de datos.
    $bd = Database::getInstancia();
    $mysqli = $bd->getConexion();

    // Cargamos la plantilla desde Templates:
    $loader = new \Twig\Loader\FilesystemLoader('../Templates');
    $twig = new \Twig\Environment($loader);
    $twig->addGlobal('mysqli', $mysqli);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $nick = $_POST['nick'];
      $pass = $_POST['contraseña'];
      $nombre = $_POST['nombre'];
      $apellidos = $_POST['apellidos'];
      $correo = $_POST['correo'];
      $telefono = $_POST['telefono'];
      $direccion = $_POST['direccion'];
      $ciudad = $_POST['ciudad'];
      $pais = $_POST['pais'];
      $cp = $_POST['cp'];
    
      if (registro($nick, $pass, $nombre, $apellidos, $correo, $telefono, $direccion, $ciudad, $pais, $cp)) {
        session_start();
        
        $_SESSION['nickUsuario'] = $nick;  // guardo en la sesión el nick del usuario que se ha logueado
        header("Location: portada.php");
        exit();

      } else {
        $error = "El usuario ya ha sido registrado anteriormente. Pruebe iniciando sesión.";
        echo $twig->render('login.html', ['error' => $error]);
        exit();
      }
    }
    
    echo $twig->render('registro.html', []);
?>
