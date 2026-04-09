<?php

    $servidor = "localhost";
    $usuario  = "root";
    $password = ""; 
    $base_datos = "biblioteca"; 

    $conexion = mysqli_connect($servidor, $usuario, $password, $base_datos);

    if (!$conexion) {
        die("Error de conexión a la base de datos: " . mysqli_connect_error());
    }

    // Para que acepte tildes y "ñ" correctamente
    mysqli_set_charset($conexion, "utf8");
    
?>