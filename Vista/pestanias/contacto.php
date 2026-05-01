<?php
include_once dirname(__DIR__) . '/header.php'
?>

<div class="texto-centro">
    <p class="frase">
        “No hay mayor magia que la que ocurre cuando abres un libro y, sin darte cuenta, comienzas a vivir dentro de
        otra vida que solo existe en tu imaginación.”
    </p>
    <p class="autor">— Antoine de Saint-Exupéry, <i>El Principito</i></p>
</div>

<div id="contacto" class="seccion">
    <h2> contacto</h2>
    <p><b>Si tienes preguntas sobre nuestros servicios, no dudes en ponerte en contacto con nosotros.</b></p>
    <div class="contacto-contenedor">
        <div class=" imgContacto">

            <img src="<?= BASE_URL ?>/Vista/assets/img/logo.png" alt="imgContacto" class="imgContacto">
            <pre>
<u><b>Horario de atención telefónica para soporte e incidencias técnicas:</b></u>
        Lunes a Viernes:    09:30 a 20:30
        Sábados:            10:30 a 14:00
        Domingos:             CERRADO

    <i class="fa-solid fa-phone"></i></i> Teléfono: 963 258 741
    <i class="fa-regular fa-envelope-open"></i> Email: lectum.info@gmail.com
    </pre>
        </div>

       
    </div>
</div>

<!--Devolvemos a la carpeta actual y cambiamos de nivel entre carpetas-->
<?php include_once dirname(__DIR__) . '/footer.php'; ?>
