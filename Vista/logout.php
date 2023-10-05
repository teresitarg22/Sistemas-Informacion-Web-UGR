<?php
    // Abrimos la sesión:
    session_start();
        
    // Destruimos la sesión:
    session_destroy();
    
    header("Location: portada.php");
      
    exit();
?>
