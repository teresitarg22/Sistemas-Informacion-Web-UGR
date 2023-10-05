<?php
  // Este fichero es para simular una supuesta base de datos en donde se almacenan datos de usuarios registrados en nuestra aplicación
  // Cada usuario se supone que tiene 3 campos: nick, pass y super:
  //  - nick: apodo del usuario
  //  - pass: un hash del password (lo que se almacena en la base de datos)
  //  - super: un boolean que indica si el usuario es superusuario (o no)

  // Lo que aparece en el "hash" se ha obtenido de:
  // password_hash('passwordSuperSeguro', PASSWORD_DEFAULT)  ---->  $2y$10$mGwJK76zo6rjkZL3j6YU6uKmjNtV51jmMy8zSUUFt/uuPmzfZeQ0O
  // password_hash('otroPassword', PASSWORD_DEFAULT)  ---->  $2y$10$XfxLjcJB.54YreU8SOr1y.vEeRMnuu6izd0xAZwSeuQQZGyJ1TT.y 

  // Más info sobre hashes para password en PHP: https://www.sitepoint.com/hashing-passwords-php-5-5-password-hashing-api/

  // $usuarios = [ ['nick' => 'Zerjillo', 'pass' => '$2y$10$mGwJK76zo6rjkZL3j6YU6uKmjNtV51jmMy8zSUUFt/uuPmzfZeQ0O', 'super' => true],
  //               ['nick' => 'Pepe', 'pass' => '$2y$10$XfxLjcJB.54YreU8SOr1y.vEeRMnuu6izd0xAZwSeuQQZGyJ1TT.y', 'super' => false]
  //             ];

  require_once "/usr/local/lib/php/vendor/autoload.php";
  include("../Modelo/bd.php");

  $bd = Database::getInstancia();
  $mysqli = $bd->getConexion();
  
  $usuarios = $mysqli->query("SELECT * FROM usuarios")->fetch_all(MYSQLI_ASSOC);  
  
  // --------------------------------------------------------------------
  // Nosotros vamos a suponer que, por defecto, el sistema cuenta con un superusuario y con un moderador.
  // Comprobamos si ya existe un usuario superusuario
  $consultaSuperUsuario = "SELECT * FROM usuarios WHERE tipo = 'superusuario'";
  $resultadoSuperUsuario = $mysqli->query($consultaSuperUsuario);

  // Comprobamos si ya existe un usuario moderador
  $consultaModerador = "SELECT * FROM usuarios WHERE tipo = 'moderador'";
  $resultadoModerador = $mysqli->query($consultaModerador);

  if ($resultadoSuperUsuario->num_rows == 0 && $resultadoModerador->num_rows == 0) {
    // No existen usuarios superusuario ni moderador, procedemos a crearlos

    // ------------- SUPERUSUARIO -------------
    $nickSuperUsuario = 'super';
    $contraseñaSuperUsuario = 'root'; // Contraseña para el superusuario

    // Cifrar la contraseña
    $hashSuperUsuario = password_hash($contraseñaSuperUsuario, PASSWORD_DEFAULT);

    // Insertar el superusuario en la tabla de usuarios
    $consultaInsertSuperUsuario = "INSERT INTO usuarios (nombre_usuario, contraseña, tipo, nombre, apellidos, correo, telefono, direccion, ciudad, pais, cp) VALUES ('$nickSuperUsuario', '$hashSuperUsuario', 'superusuario', '', '', '', 0, '', '', '', 0)";
    $resultadoInsertSuperUsuario = $mysqli->query($consultaInsertSuperUsuario);

    // ------------- MODERADOR -------------
    $nickModerador = 'mod';
    $contraseñaModerador = 'root'; // Contraseña para el moderador

    // Cifrar la contraseña
    $hashModerador = password_hash($contraseñaModerador, PASSWORD_DEFAULT);

    // Insertar el moderador en la tabla de usuarios
    $consultaInsertModerador = "INSERT INTO usuarios (nombre_usuario, contraseña, tipo,  nombre, apellidos, correo, telefono, direccion, ciudad, pais, cp) VALUES ('$nickModerador', '$hashModerador', 'moderador', '', '', '', 0, '', '', '', 0)";
    $resultadoInsertModerador = $mysqli->query($consultaInsertModerador);
  }

  // ---------------------------------------------
  // Registrar usuario nuevo:
  function registro($nick, $contraseña, $nombre, $apellidos, $correo, $telefono, $direccion, $ciudad, $pais, $cp) {
    global $usuarios;
    global $mysqli;
    
    // Comprobamos si ya existe:
    $consulta = "SELECT nombre_usuario FROM usuarios WHERE nombre_usuario = '$nick'";
    $resultados = $mysqli->query($consulta);

    if ($resultados->num_rows > 0) {
      return false;
    }

    // Ciframos la contraseña:
    $hash = password_hash($contraseña, PASSWORD_DEFAULT);

    // Insertamos
    $comp = $mysqli->prepare("INSERT INTO usuarios VALUES (?, ?, 'registrado', ?, ?, ?, ?, ?, ?, ?, ?)");
    $comp->bind_param("sssssisssi", $nick, $hash, $nombre, $apellidos, $correo, $telefono, $direccion, $ciudad, $pais, $cp);
      

    // Ejecutamos la consulta:
    if ($comp->execute() === TRUE) 
      return true;
    else
      return false;
    
  }
  
  // ---------------------------------------------
  // Devuelve true si existe un usuario con esa contraseña
  function checkLogin($nick, $contraseña) {
    global $usuarios;
    
    for ($i = 0 ; $i < sizeof($usuarios) ; $i++) {
      if ($usuarios[$i]['nombre_usuario'] === $nick) {
      
        if (password_verify($contraseña, $usuarios[$i]['contraseña'] )) {
          return true;
        }
      }
    }
    
    return false;
  }
    
  // --------------------------------------------------------------------
  // Devuelve la información de un usuario a partir de su nombre 
  function getUser($nombre) {
    global $usuarios;
    
    for ($i = 0 ; $i < sizeof($usuarios) ; $i++) {
      if ($usuarios[$i]['nombre_usuario'] === $nombre) {
        return $usuarios[$i];
      }
    }
    
    return 0;
  }

  // --------------------------------------------------------------------
  // Función para actualizar los datos del usuario:
  function updateUser($nick, $nombre, $apellidos, $correo, $telefono, $direccion, $ciudad, $pais, $cp) {
    global $mysqli;
    
    // Construye la consulta SQL de actualización
    $query = "UPDATE usuarios SET nombre = '$nombre', apellidos = '$apellidos', correo = '$correo', telefono = '$telefono', direccion = '$direccion', ciudad = '$ciudad', pais = '$pais', cp = '$cp' WHERE nombre_usuario = '$nick'";
    
    // Ejecuta la consulta
    $resultado = $mysqli->query($query);
    
    // Verifica si la actualización fue exitosa
    if ($resultado) 
      return true; // Retorna true si se actualizó correctamente
    else 
      return false; // Retorna false si hubo un error en la actualización
  }

  // --------------------------------------------------------------------
  // Función para actualizar el rol del usuario:
  function updateRolUsuario($usuario, $tipo) {
    global $mysqli;
    
    // Construye la consulta SQL de actualización
    $query = "UPDATE usuarios SET tipo = '$tipo' WHERE nombre_usuario = '$usuario'";
    
    // Ejecuta la consulta
    $resultado = $mysqli->query($query);
    
    // Verifica si la actualización fue exitosa
    if ($resultado) 
      return true; // Retorna true si se actualizó correctamente
    else 
      return false; // Retorna false si hubo un error en la actualización
  }

  // --------------------------------------------------------------------
  // Función para actualizar los datos del científico:
  function updateCientifico($id, $nombre, $fecha, $biografia, $frases) {
    global $mysqli;
    
    // Construye la consulta SQL de actualización
    $query = "UPDATE cientificos SET nombre = '$nombre', fecha = '$fecha', biografia = '$biografia', frases = '$frases' WHERE id = '$id'";
    
    // Ejecuta la consulta
    $resultado = $mysqli->query($query);
    
    // Verifica si la actualización fue exitosa
    if ($resultado) 
      return true; // Retorna true si se actualizó correctamente
    else
      return false; // Retorna false si hubo un error en la actualización
  }

  // --------------------------------------------------------------------
  // Función para insertar los datos del científico:
  function insertCientifico($nombre, $fecha, $biografia, $frases) {
    global $mysqli;

    // Comprobamos si ya existe:
    $consulta = "SELECT nombre FROM cientificos WHERE nombre = ?";
    $stmt = $mysqli->prepare($consulta);
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $resultados = $stmt->get_result();

    if ($resultados->num_rows > 0) {
      return false;
    }

    // Insertamos
    $comp = $mysqli->prepare("INSERT INTO cientificos (nombre, fecha, biografia, frases) VALUES (?, ?, ?, ?)");
    $comp->bind_param("ssss", $nombre, $fecha, $biografia, $frases);

    // Ejecutamos la consulta:
    if ($comp->execute())
      return true;
    else
      return false;
}

?>
