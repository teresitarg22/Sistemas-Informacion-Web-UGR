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
    
      if (checkLogin($nick, $pass)) {
        session_start();
        
        $_SESSION['nickUsuario'] = $nick;  // guardo en la sesión el nick del usuario que se ha logueado
        header("Location: portada.php");
        exit();

      } else {
        $error = "Las credenciales de inicio de sesión son incorrectas. Por favor, inténtalo de nuevo.";
        echo $twig->render('login.html', ['error' => $error]);
        exit();
      }
    }
    
    echo $twig->render('login.html', []);
?>