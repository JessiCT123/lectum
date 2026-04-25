<?php
session_start();
include("conexion.php");

// comprobar sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: sesion.php");
    exit();
}

// recoger datos
$actual = $_POST['actual'];
$nueva = $_POST['nueva'];
$confirmar = $_POST['confirmar'];

// comprobar campos vacíos
if (empty($actual) || empty($nueva) || empty($confirmar)) {
    $_SESSION['error_message'] = "Debe completar todos los campos.";
    header("Location: perfil.php");
    exit();
}

// comprobar que no coincide con la actual
if ($actual == $nueva) {
    $_SESSION['error_message'] = "Las nueva contraseñas no debe ser igual que la actual.";
    header("Location: perfil.php");
    exit();
}

// comprobar que coinciden
if ($nueva !== $confirmar) {
    $_SESSION['error_message'] = "Las contraseñas no coinciden.";
    header("Location: perfil.php");
    exit();
}

// obtener contraseña actual
$consulta = "SELECT password FROM usuarios WHERE usuario=:usuario";
$sql = $conn->prepare($consulta);
$sql->bindParam(":usuario", $_SESSION['usuario']);
$sql->execute();

$usuario = $sql->fetch(PDO::FETCH_ASSOC);

// verificar contraseña actual
if (!password_verify($actual, $usuario['password'])) {
    $_SESSION['error_message'] = "Contraseña actual incorrecta.";
    header("Location: perfil.php");
    exit();
}

// hashear nueva contraseña para que sea segura
$nuevaHash = password_hash($nueva, PASSWORD_DEFAULT);

// actualizar en BD
$consulta = "UPDATE usuarios SET password=:password WHERE usuario=:usuario";
$sql = $conn->prepare($consulta);
$sql->bindParam(":password", $nuevaHash);
$sql->bindParam(":usuario", $_SESSION['usuario']);
$sql->execute();

// mensaje de éxito
$_SESSION['success_message'] = "Contraseña actualizada correctamente.";
header("Location: perfil.php");
exit();