<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/Vista/assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
</head>

<body>

    <div id="perfil-container">

        <h1 class="form-heading">Mi Perfil</h1>
        <p class="form-subheading">Gestiona tu cuenta</p>

        <!-- foto de perfil -->
        <form action="<?= BASE_URL ?>/perfil/subirFoto" class="perfil-img" method="post" enctype="multipart/form-data">
            <input type="file" name="foto" accept="image/*" id="fotoInput" style="display:none;"  onchange="this.form.submit()" required>
            <img src="<?= BASE_URL . '/' . FOTOS_FOLDER . '/' . ($_SESSION['foto'] ?? 'perfil.jpg'); ?>">
            <button type="button" class="submit-btn" onclick="document.getElementById('fotoInput').click()">Cambiar foto</button>
        </form>

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
            <label for="formNombre">Nombre completo</label>
            <div class="input-wrap">
                <i class="fa-solid fa-user input-icon"></i>
                <input type="text" class="form-input" id="formNombre"
                       value="<?php echo $_SESSION['nombre']; ?>" readonly>
            </div>
        </div>

        <!-- usuario -->
        <div class="input-group">
            <label for="formUsuario">Usuario</label>
            <div class="input-wrap">
                <i class="fa-solid fa-user input-icon"></i>
                <input type="text" class="form-input" id="formUsuario"
                       value="<?php echo $_SESSION['usuario']; ?>" readonly>
            </div>
        </div>

        <!-- email -->
        <div class="input-group">
            <label for="formEmail">Email</label>
            <div class="input-wrap">
                <i class="fa-solid fa-envelope input-icon"></i>
                <input type="text" class="form-input" id="formEmail"
                       value="<?php echo $_SESSION['email']; ?>" readonly>
            </div>
        </div>

        <!-- cambiar contraseña -->
        <form method="post" action="<?= BASE_URL ?>/perfil/cambiarPassword">

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
                    <input type="password" name="confirmar" class="form-input" placeholder="Confirmar contraseña"
                           required>
                </div>
            </div>

            <button type="submit" class="submit-btn">Actualizar contraseña</button>
        </form>

        <br>

        <!-- cerrar sesión -->
        <form action="<?= BASE_URL ?>/auth/logout" method="post">
            <button type="submit" class="submit-btn">Cerrar sesión</button>
        </form>

        <br>

        <!-- volver al index -->
        <a href="<?= BASE_URL ?>">
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
