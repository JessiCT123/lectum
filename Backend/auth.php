<?php
include 'conexion.php';

//iniciamos sesión solo si no está inicada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//si no hay sesión activa pero sí hay cookie de "recordarme", intentamos restaurar la sesión
if (!isset($_SESSION['id']) && isset($_COOKIE['remember_token'])) {

    $token = $_COOKIE['remember_token'];
    //hasheamos el token para compararlo con el de la base de datos
    $hash = hash('sha256', $token);

    //buscamos el token en la bd y comprobamos que no haya caducado
    $sql = $conn->prepare("SELECT * FROM remember_tokens 
                           WHERE token_hash = :hash 
                           AND f_caducidad > NOW()");
    $sql->bindParam(":hash", $hash);
    $sql->execute();

    $row = $sql->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        //Obtener usuario asociado al token
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->bindParam(":id", $row['usuario_id']);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            //restauramos la sesión con los datos del usuario
            $_SESSION['id'] = $user['id'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['usuario'] = $user['usuario'];
            $_SESSION['email'] = $user['email'];
        }
    }
}
