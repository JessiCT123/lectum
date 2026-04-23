<?php
session_start();
include("conexion.php");

try {

    if (isset($_POST['nuevo'])) {
        $nombre = $_POST['nombre'];
        $usuario = $_POST["usuario"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $password1 = $_POST["password1"];

        if (!empty($nombre) && !empty($usuario) && !empty($email) && !empty($password) && !empty($password1)) {/*comprobamos que todos los datos estén llenos*/

            if (!preg_match('/[A-Z]/', $usuario)) {
                $_SESSION['error_message'] = 'El usuario debe contener al menos una mayúscula.';
                header("Location: registros.php");
                exit();
            }

            if (!preg_match('/[0-9!@#$%^&*)(-_]/', $usuario)) {
                $_SESSION['error_message'] = 'El usuario debe contener al menos un número o símbolo (!@#$%^&*).';
                header("Location: registros.php");
                exit();
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error_message'] = 'El email no es válido.';
                header("Location: registros.php");
                exit();
            }

            if (strlen($password) < 8) {
                $_SESSION['error_message'] = 'La contraseña debe tener al menos 8 caracteres.';
                header("Location: registros.php");
                exit();
            }

            if ($password !== $password1) {
                $_SESSION['error_message'] = 'Las contraseñas no coinciden.';
                header("Location: registros.php");
                exit();
            }

            if (!isset($_POST['regTerms'])) {
                $_SESSION['error_message'] = 'Debes aceptar los términos y condiciones.';
                header("Location: registros.php");
                exit();
            }

            /*Realizamos la consulta en la tabla de usuarios para ver si ya existe un usuario o email igual*/
            $consulta = "SELECT * FROM usuarios WHERE usuario = :usuario OR email = :email";
            $sql = $conn->prepare($consulta);
            $sql->bindParam(":usuario", $usuario);
            $sql->bindParam(":email", $email);
            $sql->execute();

            if ($sql->rowCount() > 0) {
                $_SESSION['error_message'] = 'El usuario o email ya existen.';
                header("Location: registros.php");
                exit();
            } else {

                // hasheamos la contraseña antes de guardarla en la bd
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                //Realizamos la inserción en la tabla de usuarios en caso de que no hay un usuario con el mismo usuario 
                $consulta = "INSERT INTO usuarios (nombre, usuario, email, password) VALUES(:nombre, :usuario, :email, :password)";
                $sql = $conn->prepare($consulta);
                $sql->bindParam(":nombre", $nombre);
                $sql->bindParam(":usuario", $usuario);
                $sql->bindParam(":email", $email);
                $sql->bindParam(":password", $passwordHash);
                $sql->execute();

                $_SESSION['success_message'] = 'ok';
                header("Location: registros.php");
                exit();
            }
        } else {
            $_SESSION['error_message'] = 'Debe ingresar todos los datos.';
            header("Location: registros.php");
            exit();
        }
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

</head>

<body>

    <div id="formRegister">
        <h1 class="form-heading">Bienvenido a Lectum</h1>
        <p class="form-subheading">Crea tu cuenta gratis</p>

        <form method="post" action="registros.php">
            <div class="input-group">
                <label>Nombre completo</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-user input-icon"></i>
                    <input type="text" class="form-input" placeholder="Ingresa tu nombre completo" name="nombre" autocomplete="name" />
                </div>
            </div>

            <div class="input-group">
                <label>Usuario</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-user input-icon"></i>
                    <input type="text" class="form-input" placeholder="Ingrese su usuario" autocomplete="usuario" name="usuario" />
                </div>
                <ul style="color:#999; font-size:0.78rem; margin-top:0.4rem; padding-left:1.2rem;">
                    <li>Debe contener al menos una mayúscula.</li>
                    <li>Debe contener al menos un número o símbolo (!@#$%^&*()-_).</li>
                </ul>
            </div>

            <div class="input-group">
                <label>Email</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-envelope input-icon"></i>
                    <input type="email" class="form-input" placeholder="Ingresa tu email" name="email" autocomplete="email" />
                </div>
            </div>

            <div class="input-group">
                <label>Contraseña</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" id="regPassword" class="form-input" placeholder="Mínimo 8 caracteres" name="password" />
                    <button type="button" class="pw-toggle" onclick="togglePw('regPassword',this)">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
                <div class="strength-wrap">
                    <ul style="color:#999; font-size:0.78rem; margin-top:0.4rem; padding-left:1.2rem;">
                        <li>Debe contener al menos 8 caracteres.</li>
                    </ul>
                </div>
            </div>

            <div class="input-group">
                <label>Confirmar contraseña</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" class="form-input" placeholder="Repite tu contraseña" name="password1" />
                </div>
            </div>

            <!--<div class="check-group">
                <input type="checkbox" id="regTerms" name="regTerms" />
                <label for="regTerms">
                    Acepto los <a href="#">términos y condiciones</a> y la
                    <a href="#">política de privacidad</a>.
                </label>
            </div>-->

            <?php
            /*Si no se ha encontrado el usuario ni contraseña se mostrará el mensaje de error. */
            if (isset($_SESSION['error_message'])) {
                echo '<p style="color:red">' . $_SESSION['error_message'] . "</p>";
                unset($_SESSION['error_message']);
            }
            ?>
            <?php if (isset($_SESSION['success_message'])): ?>
                <p style="color:green">✅ Usuario creado</p>
                <br>
                <a href="sesion.php"><button class="submit-btn">Iniciar sesión</button></a>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <br>

            <button type="submit" class="submit-btn" name="nuevo">
                Crear cuenta
            </button>
        </form>

        <br>

        <a href="/index.php"><button class="submit-btn">Volver</button></a>

    </div>

    <script>
        function togglePw(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>

</body>

<footer style="margin-top:40px; text-align:center; padding:15px; color:#a8a5a0; font-size:13px;">
    <p>
        <span style="color:#f4a261; font-weight:bold;">Lectum</span>
        © 2025/2026 · Proyecto fin de grado
    </p>
</footer>

</html>