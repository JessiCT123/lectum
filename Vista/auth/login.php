<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/Vista/assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
</head>

<body>

    <div id="formLogin" style="display:block">
        <h1 class="form-heading">Bienvenido de nuevo</h1>
        <p class="form-subheading">Inicia sesión para acceder a tu biblioteca.</p>
        <form id="loginForm" method="post" action="<?= BASE_URL ?>/auth/login" novalidate>

            <div class="input-group">
                <label for="loginUser">Usuario o Email</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-user input-icon"></i>
                    <input type="text" id="loginUser" class="form-input" placeholder="Ingrese su usuario o email"
                           autocomplete="username" name="usuario"/>
                </div>
            </div>

            <div class="input-group">
                <label for="loginPassword">Contraseña</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" id="loginPassword" class="form-input" placeholder="Ingresa tu contraseña"
                           autocomplete="current-password" name="password"/>
                    <button type="button" class="pw-toggle" onclick="togglePw('loginPassword',this)"
                            aria-label="Mostrar contraseña">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; font-size:13px;">
                <label style="display:flex; align-items:center; gap:6px; color:#666; cursor:pointer;">
                    <input type="checkbox" name="rememberMe" style="accent-color:#caa64b;"/>
                    Recordarme
                </label>
            </div>

            <?php
            /*Si no se ha encontrado el usuario ni contraseña se mostrará el mensaje de error. */
            if (isset($_SESSION['error_message'])) {
                echo '<p style="color:red;">' . $_SESSION['error_message'] . "</p>";
                unset($_SESSION['error_message']);
            }
            ?>

            <button type="submit" class="submit-btn">
                Iniciar sesión
            </button>
        </form>

        <br>
        <p>¿No tienes cuenta?</p>

        <a href="<?= BASE_URL ?>/auth/registro">
            <button class="submit-btn">Registrarse</button>
        </a>

        <br><br>

        <a href="<?= BASE_URL ?>">
            <button type="submit" name="volver" class="submit-btn">Volver</button>
        </a>

        <script src="<?= BASE_URL ?>/Vista/assets/js/func.js"></script>
        <footer>
            <p class="text-sm text-[#a8a5a0] text-center md:text-left">
                <span class="font-display text-[#f4a261]">Lectum</span> © 2025/2026 · Proyecto fin de grado
            </p>

        </footer>

</body>

</html>
