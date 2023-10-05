<?php
    /*  En este fichero, hemos incluido todas las instrucciones que tienen que ver con nuestra base de datos, 
        para separar correctamente lo que sería el modelo del controlador.
    */

    class Database {
        private static $instancia = null;
        private $mysqli;
    
        // Constructor que inicia la conexión con la BD e informa en caso de error:
        private function __construct() {
            $this->mysqli = new mysqli('database', 'marioytere', 'tiger', 'SIBW');
            if ($this->mysqli->connect_errno) {
                echo ("Fallo al conectar: " . $this->mysqli->connect_error);
            }
        }
       
        // Función que devuelve una instancia (o la crea si no existe):
        public static function getInstancia() {
            if (self::$instancia == null) {
                self::$instancia = new Database();
            }
            return self::$instancia;
        }
        // Función que devuelve la conexión:
        public function getConexion() {
            return $this->mysqli;
        }
    }

    // -----------------------------------------
    // Nos conectamos a la base de datos:
        $bd = Database::getInstancia();
        $mysqli = $bd->getConexion();

    // -----------------------------------------------------------------------    
    // Función para obtener la información de un científico. 
    function getCientifico($idCientifico) {
        global $mysqli;

        // Consultamos la base de datos y almacenamos el resultado:
        $res = $mysqli->query("SELECT id, nombre, fecha, biografia, frases, publicado FROM cientificos WHERE id=" . $idCientifico);
        
        // Creamos una variable científico que esté vacía (o por defecto) por si se da el caso de que el id sea incorrecto.
        $cientifico = array('id' => '-1', 'nombre' => 'XXX', 'fecha' => 'YYY', 'biografia' => 'ZZZ', 'frases' => 'AAA');
        
        // Si se han encontrado resultados en la consulta, se los pasamos científico:
        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $cientifico = array(
                'id' => $row['id'], 
                'nombre' => $row['nombre'], 
                'fecha' => $row['fecha'], 
                'biografia' => $row['biografia'], 
                'frases' => $row['frases'],
                'publicado' => $row['publicado']
            );
        }
        
        // Devolvemos el array asociativo de científico.
        return $cientifico;
    }

    // -----------------------------------------------------------------------   
    // Función para obtener la galería de fotos de un científico.
    function getGaleria($idCientifico) {
        global $mysqli;

        // Consultamos la base de datos y almacenamos los datos de las imágenes:
        $res = $mysqli->query("SELECT * FROM galeria WHERE id_cientifico=" . $idCientifico);

        // Creamos un array para guardar los datos:
        $fotos = array();

        // Si se han encontrado resultados en la consulta, guardamos la info de cada foto de la galeria:
        if ($res->num_rows > 0) {
            while($row = $res->fetch_assoc()){
                $foto = array(
                    'id' => $row['id'], 
                    'id_cientifico' => $row['id_cientifico'], 
                    'foto' => $row['foto'], 
                    'pieFoto' => $row['pieFoto']
                );

                // Guardamos la foto en el array de fotos:
                array_push($fotos, $foto);
            }
        }

        // Devolvemos el array asociativo de las imágenes.
        return $fotos;
    }

    // ------------------------------------------------------------
    // Función para obtener los enlaces extras de un científico.
    function getEnlaces($idCientifico) {
        global $mysqli;

        // Consultamos la base de datos y almacenamos los datos de los enlaces:
        $res = $mysqli->query("SELECT * FROM enlaces WHERE id_cientifico=" . $idCientifico);

        // Creamos un array para guardar los datos:
        $enlaces = array();

        // Si se han encontrado resultados en la consulta, guardamos la info de cada enlace:
        if ($res->num_rows > 0) {
            $enlaces = array();
            while($row = $res->fetch_assoc()){
                $enlace = array(
                    'id' => $row['id'],
                    'id_cientifico' => $row['id_cientifico'],
                    'enlace' => $row['enlace'], 
                    'infoEnlace' => $row['infoEnlace']
                );
                
                array_push($enlaces, $enlace);
            }
        }

        // Devolvemos el array asociativo de los enlaces.
        return $enlaces;
    }

    // ------------------------------------------------------------
    // Función para obtener los comentarios extras de un científico.
    function getComentarios($idCientifico) {
        global $mysqli;
        
        // Consultamos la base de datos y almacenamos los datos de los comentarios:
        $resultado = $mysqli->query("SELECT * FROM comentarios WHERE id_cientifico = $idCientifico ORDER BY fecha ASC, hora ASC");
        
        // Creamos un array para guardar los datos:
        $comentarios = array();

        // Si se han encontrado resultados en la consulta, guardamos la info de cada comentario:
        if($resultado->num_rows > 0){
            while($fila = $resultado->fetch_assoc()){
                $comentario = array(
                    'id' => $fila['id'],
                    'nombre' => $fila['nombre'],
                    'email' => $fila['email'],
                    'comentario' => $fila['comentario'],
                    'fecha' => $fila['fecha'],
                    'hora' => $fila['hora']
                );

                array_push($comentarios, $comentario);
            }
        }
        
        // Devolvemos el array asociativo de los comentarios.
        return $comentarios;
    }

    function obtenerComentario($idComentario){
        global $mysqli;

        $sql = "SELECT comentario FROM comentarios WHERE id = ?";
        $stmt = $mysqli->prepare($sql);

        $stmt->bind_param("i", $idComentario);
        $stmt->execute();
        $stmt->bind_result($comentario);

        // Obtener el resultado de la consulta
        if ($stmt->fetch()) {
            return $comentario;
        } 
        else{
            return null; // Valor predeterminado cuando no se encuentra ningún comentario
        }
    }

    // ---------------------------------------------------------
    function updateComentario($id, $comentario){
        global $mysqli;

        // Construye la consulta SQL de actualización
        $query = "UPDATE comentarios SET comentario = '$comentario' WHERE id = '$id'";
        
        // Ejecuta la consulta
        $mysqli->query($query);
    }

    // ------------------------------------------------------------
    // Función para obtener los hashtags de un científico.
    function getHashtags($idCientifico) {
        global $mysqli;
    
        // Consultamos la base de datos y almacenamos los datos de los enlaces:
        $res = $mysqli->query("SELECT * FROM hashtags WHERE id_cientifico=" . $idCientifico);
    
        // Verificar si ocurrió un error en la consulta
        if (!$res) {
            // Manejar el error de la consulta
            echo "Error en la consulta: " . $mysqli->error;
            return array(); // Devolver un array vacío en caso de error
        }
    
        // Creamos un array para guardar los datos:
        $hashtags = array();
    
        // Si se han encontrado resultados en la consulta, guardamos la info de cada enlace:
        if ($res->num_rows > 0) {
            while($row = $res->fetch_assoc()) {
                $hashtag = array(
                    'id_cientifico' => $row['id_cientifico'],
                    'hashtag' => $row['hashtag']
                );
                array_push($hashtags, $hashtag);
            }
        }
    
        // Devolvemos el array asociativo de los enlaces.
        return $hashtags;
    }

    // ----------------------------------------------------
    function updateHashtags($id, $hashtags){
        global $mysqli;
    
        // Separar por cada espacio o salto de línea:
        $palabras = preg_split("/[\s,\t]+/", $hashtags); // Dividir la cadena en palabras
    
        $hashtags = array(); // Arreglo para almacenar las palabras que comienzan con "#"
    
        foreach ($palabras as $palabra) {
            if (substr($palabra, 0, 1) === "#") {
                $hashtags[] = $palabra; // Agregar la palabra al arreglo de hashtags
            } else return false;
        }
    
        // Borra el contenido actual
        $sql = "DELETE FROM hashtags";
        $resultado = $mysqli->query($sql);
    
        // Insertar los hashtags:
        foreach ($hashtags as $hashtag) {
            $comp = $mysqli->prepare("INSERT INTO hashtags (id_cientifico, hashtag) VALUES (?, ?)");
            $comp->bind_param("is", $id, $hashtag);
            
            // Verificar si la actualización fue exitosa
            if (!$comp->execute()) {
                return false; // Retorna false si hubo un error en la actualización
            }
        }
    
        return true;
    }

    // ---------------------------------------------------------------------------
    // Función para insertar la ruta de la imagen en la tabla "galeria"
    function insertarImagenGaleria($idCientifico, $rutaImagen) {
        global $mysqli;
        $stmt = $mysqli->prepare("INSERT INTO galeria (id_cientifico, foto, pieFoto) VALUES (?, ?, '')");
        $stmt->bind_param("is", $idCientifico, $rutaImagen);
        
        if ($stmt->execute() === TRUE) 
            return true;
        else
            return false;
    }

    // ----------------------------------------------------------------------------
    function obtenerInformacionImagen($idImagen){
        global $mysqli;
        
        // Consultamos la base de datos y almacenamos los datos de los comentarios:
        $resultado = $mysqli->query("SELECT * FROM galeria WHERE id = $idImagen");

        // Si se han encontrado resultados en la consulta, guardamos la info de cada comentario:
        if($resultado->num_rows > 0){
            $fila = $resultado->fetch_assoc();
            $imagen = array(
                'id' => $fila['id'],
                'id_cientifico' => $fila['id_cientifico'],
                'foto' => $fila['foto'],
                'pieFoto' => $fila['pieFoto']
            );
        }
        
        // Devolvemos el array asociativo de los comentarios.
        return $imagen;
    }

    // -------------------------------------------------------------------
    function eliminarImagenGaleria($idImagen) {
        global $mysqli;
        $comp = $mysqli->prepare("DELETE FROM galeria WHERE id = ?");
        $comp->bind_param("i", $idImagen);
    
        // Ejecutamos la consulta:
        if ($comp->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
?>