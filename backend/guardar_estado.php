<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'Sesión no iniciada']);
    exit;
}

$uid = $_SESSION['id'];
$lid = $_POST['libro_id'];
$estado = $_POST['estado']; // 'pendiente', 'leyendo', 'terminado'

try {
    // Verificamos si ya existe el registro
    $check = $conn->prepare("SELECT id FROM usuario_libros WHERE usuario_id = :u AND libro_id = :l");
    $check->execute([':u' => $uid, ':l' => $lid]);
    
    if ($check->rowCount() > 0) {
        // Actualizamos
        $sql = "UPDATE usuario_libros SET estado = :e WHERE usuario_id = :u AND libro_id = :l";
    } else {
        // Insertamos nuevo
        $sql = "INSERT INTO usuario_libros (usuario_id, libro_id, estado) VALUES (:u, :l, :e)";
    }
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([':u' => $uid, ':l' => $lid, ':e' => $estado]);
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}