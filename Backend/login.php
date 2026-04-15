<?php
session_start();
include 'conexion.php'; // tu conexión a la BD

try {

    if (!empty($_POST['usuario']) && !empty($_POST['password'])) { /*comprobamos que los datos de usuario y password estén llenos*/
        $usuario = $_POST["usuario"];
        $password = $_POST["password"];

        /*Realizamos la consulta en la tabla de usuarios para ver si existe el usuario ingresado */
        $consulta = "SELECT * FROM usuarios WHERE usuario = :usuario OR email = :usuario";
        $sql = $conn->prepare($consulta);
        $sql->bindParam(":usuario", $usuario);
        $sql->execute();

        /*Obtienemos el resultado de la consulta SQL */
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        // Verificar usuario y contraseña
        if ($row && password_verify($password, $row['password'])) {

            // Guardar sesión con datos reales
            $_SESSION['usuario'] = $row['usuario'];
            $_SESSION['id'] = $row['id'];

            // RECORDAR SESIÓN
            if (isset($_POST['rememberMe'])) {

                $token = bin2hex(random_bytes(32));
                $hash = hash('sha256', $token);
                $expiry = date("Y-m-d H:i:s", time() + (86400 * 15)); // 15 días

                // Borrar tokens anteriores
                $delete = $conn->prepare("DELETE FROM remember_tokens WHERE usuario_id = :id");
                $delete->bindParam(":id", $row['id']);
                $delete->execute();

                // Guardar nuevo token
                $consulta = "INSERT INTO remember_tokens (usuario_id, token_hash, f_caducidad) 
                             VALUES(:id, :hash, :expiry)";
                $stmt = $conn->prepare($consulta);
                $stmt->bindParam(":id", $row['id']);
                $stmt->bindParam(":hash", $hash);
                $stmt->bindParam(":expiry", $expiry);
                $stmt->execute();

                // Crear cookie
                setcookie("remember_token", $token, [
                    'expires' => time() + (86400 * 15),
                    'path' => '/',
                    'httponly' => true,
                    'samesite' => 'Strict'
                    // 'secure' => true // activar si usas HTTPS
                ]);
            }

            header("Location: index.html");
            exit();
        } else {
            $_SESSION['error_message'] = 'Usuario/email o contraseña incorrectos.';
            header("Location: sesion.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = 'Debe ingresar todos los datos.';
        header("Location: sesion.php");
        exit();
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
