<?php

session_start();

// comprobamos si el usuario ha iniciado sesión, si no, lo mandamos al login
if (!isset($_SESSION['usuario'])) {
    header("Location: sesion.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>

<body>

    <div id="perfil-container">

        <h1 class="form-heading">Mi Perfil</h1>
        <p class="form-subheading">Gestiona tu cuenta</p>

        <!-- foto de perfil -->
        <div class="perfil-img">
            <img src="perfil.jpg">
            <br><br>
            <button class="submit-btn">Cambiar foto</button>
        </div>

        <?php
        if (isset($_SESSION['error_message'])) {
            echo '<p style="color:red; text-align:center;">' . $_SESSION['error_message'] . '</p>';
            unset($_SESSION['error_message']);
        }

        if (isset($_SESSION['success_message'])) {
            echo '<p style="color:green; text-align:center;">' . $_SESSION['success_message'] . '</p>';
            unset($_SESSION['success_message']);
        }
        ?>

        <!-- nombre -->
        <div class="input-group">
            <label>Nombre completo</label>
            <div class="input-wrap">
                <i class="fa-solid fa-user input-icon"></i>
                <input type="text" class="form-input"
                    value="<?php echo $_SESSION['nombre']; ?>" readonly>
            </div>
        </div>

        <!-- usuario -->
        <div class="input-group">
            <label>Usuario</label>
            <div class="input-wrap">
                <i class="fa-solid fa-user input-icon"></i>
                <input type="text" class="form-input"
                    value="<?php echo $_SESSION['usuario']; ?>" readonly>
            </div>
        </div>

        <!-- email -->
        <div class="input-group">
            <label>Email</label>
            <div class="input-wrap">
                <i class="fa-solid fa-envelope input-icon"></i>
                <input type="text" class="form-input"
                    value="<?php echo $_SESSION['email']; ?>" readonly>
            </div>
        </div>

        <!-- cambiar contraseña -->
        <form method="post" action="cambiar_password.php">

            <h3 class="section-title">Cambiar contraseña</h3>

            <div class="input-group">
                <div class="input-wrap">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" name="actual" class="form-input" placeholder="Contraseña actual" required>
                </div>
            </div>

            <div class="input-group">
                <div class="input-wrap">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" name="nueva" class="form-input" placeholder="Nueva contraseña" required>
                </div>
            </div>

            <div class="input-group">
                <div class="input-wrap">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" name="confirmar" class="form-input" placeholder="Confirmar contraseña" required>
                </div>
            </div>

            <button type="submit" class="submit-btn">Actualizar contraseña</button>
        </form>

        <br>

        <!-- cerrar sesión -->
        <form action="cerrarSesion.php" method="post">
            <button type="submit" class="submit-btn">Cerrar sesión</button>
        </form>

        <br>

        <!-- volver al index -->
        <a href="index.php">
            <button class="submit-btn">Volver</button>
        </a>

    </div>

</body>


<footer style="margin-top:40px; text-align:center; padding:15px; color:#a8a5a0; font-size:13px;">
    <p>
        <span style="color:#f4a261; font-weight:bold;">Lectum</span>
        © 2025/2026 · Proyecto fin de grado
    </p>
</footer>

</html>