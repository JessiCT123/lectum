<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/Vista/assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

</head>

<body>

    <div id="formRegister">
        <h1 class="form-heading">Bienvenido a Lectum</h1>
        <p class="form-subheading">Crea tu cuenta gratis</p>

        <?php
        if (isset($_SESSION['error_message'])) {
            echo '<p style="color:red">' . $_SESSION['error_message'] . "</p>";
            unset($_SESSION['error_message']);
        }
        ?>

        <form method="post" action="<?= BASE_URL ?>/auth/registrar">
            <div class="input-group">
                <label for="formNombre">Nombre completo</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-user input-icon"></i>
                    <input type="text" class="form-input" id="formNombre" placeholder="Ingresa tu nombre completo"
                        name="nombre" autocomplete="name" />
                </div>
            </div>

            <div class="input-group">
                <label for="formUsuario">Usuario</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-user input-icon"></i>
                    <input type="text" class="form-input" id="formUsuario" placeholder="Ingrese su usuario"
                        autocomplete="usuario" name="usuario" />
                </div>
                <ul style="color:#999; font-size:0.78rem; margin-top:0.4rem; padding-left:1.2rem;">
                    <li id="mayus">Debe contener al menos una mayúscula.</li>
                    <li id="numero">Debe contener al menos un número o símbolo (!@#$%^&*()-_).</li>
                </ul>
            </div>

            <div class="input-group">
                <label for="formEmail">Email</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-envelope input-icon"></i>
                    <input type="email" id="formEmail" class="form-input" placeholder="Ingresa tu email" name="email"
                        autocomplete="email" />
                </div>
            </div>

            <div class="input-group">
                <label for="regPassword">Contraseña</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" id="regPassword" class="form-input" placeholder="Mínimo 8 caracteres"
                        name="password" />
                    <button type="button" class="pw-toggle" onclick="togglePw('regPassword',this)">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
                <div class="strength-wrap">
                    <ul style="color:#999; font-size:0.78rem; margin-top:0.4rem; padding-left:1.2rem;">
                        <li id="length">Debe contener al menos 8 caracteres.</li>
                        <li id="mayus">Debe contener al menos una mayúscula.</li>
                    </ul>
                </div>
            </div>

            <div class="input-group">
                <label for="repeatPassword">Confirmar contraseña</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" class="form-input" id="repeatPassword" placeholder="Repite tu contraseña"
                        name="password1" />
                </div>
            </div>

            <!--<div class="check-group">
                <input type="checkbox" id="regTerms" name="regTerms" />
                <label for="regTerms">
                    Acepto los <a href="#">términos y condiciones</a> y la
                    <a href="#">política de privacidad</a>.
                </label>
            </div>-->

            <?php if (isset($_SESSION['success_message'])): ?>
                <p style="color:green">✅ Usuario creado</p>
                <br>
                <a href="<?= BASE_URL ?>/auth">
                    <button class="submit-btn">Iniciar sesión</button>
                </a>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <br>

            <button type="submit" class="submit-btn" name="nuevo">
                Crear cuenta
            </button>
        </form>

        <br>

        <a href="<?= BASE_URL ?>">
            <button class="submit-btn">Volver</button>
        </a>

    </div>

    <script src="func.js"></script>

</body>

<footer style="margin-top:40px; text-align:center; padding:15px; color:#a8a5a0; font-size:13px;">
    <p>
        <span style="color:#f4a261; font-weight:bold;">Lectum</span>
        © 2025/2026 · Proyecto fin de grado
    </p>
</footer>

</html>