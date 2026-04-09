<?php
include 'conexion.php';

// Recibimos los datos 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Encriptamos la contraseña
    $pass_encriptada = password_hash($pass, PASSWORD_DEFAULT);

    // Insertamos en la tabla 'usuarios'
    $sql = "INSERT INTO usuarios (username, password) VALUES ('$user', '$pass_encriptada')";

    if (mysqli_query($conexion, $sql)) {
        echo "Usuario registrado con éxito";
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?>