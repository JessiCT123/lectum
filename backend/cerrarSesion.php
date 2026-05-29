<?php
session_start();
include 'conexion.php';
require_once __DIR__ . '/../config/config.php';


/*Borrar token de BD si existe cookie */
if (isset($_COOKIE['remember_token'])) {

    $token = $_COOKIE['remember_token'];
    $hash = hash('sha256', $token);

    $stmt = $conn->prepare("DELETE FROM remember_tokens WHERE token_hash = :hash");
    $stmt->bindParam(":hash", $hash);
    $stmt->execute();

    /*Borrar cookie */
    setcookie("remember_token", "", time() - 3600, "/");
}

/*Borrar sesión */
$_SESSION = [];
session_destroy();

header("Location: " . BASE_URL . "/index.php");
exit();

?>