<?php

namespace Servicios;

use Modelo\RememberToken;
use Modelo\Usuario;
use Random\RandomException;

class SesionServicio
{
    private RememberToken $rememberTokenModelo;
    private Usuario $usuarioModelo;

    public function __construct()
    {
        $this->rememberTokenModelo = new RememberToken();
        $this->usuarioModelo = new Usuario();
    }

    /**
     * @throws RandomException
     */
    public function guardarSesionConDatosReales(array $usuario): void
    {
        $this->cargarSesionUsuario($usuario);

        //Recordar sesión
        if (isset($_POST['rememberMe'])) {
            try {
                $token = bin2hex(random_bytes(32));
                $hash = hash('sha256', $token);
                $expiry = date('Y-m-d H:i:s', strtotime('+1 week'));

                // Borrar tokens anteriores
                $this->rememberTokenModelo->deleteByUsuarioId($usuario['id']);

                // Guardar nuevo token
                $rememberToken = [
                    'usuario_id' => $usuario['id'],
                    'token_hash' => $hash,
                    'f_caducidad' => $expiry,
                ];
                $this->rememberTokenModelo->create($rememberToken);

                // Crear cookie para guardar la sesión
                setcookie(COOKIE_REMEMBER, $token, [
                    'expires' => strtotime($expiry),
                    'path' => '/',
                    'httponly' => true,
                    // No se puede acceder a la cookie desde JavaScript
                    'samesite' => 'Strict',
                    // Evita que la cookie se envíe en peticiones externas
                    // 'secure' => true // activar si usas HTTPS
                ]);

            } catch (RandomException $e) {
                error_log('Error al generar el token: ' . $e->getMessage());
                throw $e;
            }
        }
    }

    public function cerrarSesion(): void
    {
        /*Borrar token de BD si existe cookie */
        if (isset($_COOKIE[COOKIE_REMEMBER])) {
            $token = $_COOKIE[COOKIE_REMEMBER];
            $hash = hash('sha256', $token);
            $this->rememberTokenModelo->deleteByToken($hash);

            //Borrar cookie con los mismos flags con los que se creó
            setcookie(COOKIE_REMEMBER, '', [
                'expires' => time() - 3600,
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Strict',
            ]);
        }

        //Borrar sesión
        $_SESSION = [];
        session_destroy();
    }

    public function restaurarSesionActiva(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        //si no hay sesión activa pero sí hay cookie de "recordarme", intentamos restaurar la sesión
        if (!isset($_SESSION['id']) && isset($_COOKIE[COOKIE_REMEMBER])) {
            $token = $_COOKIE[COOKIE_REMEMBER];

            //hasheamos el token para compararlo con el de la base de datos
            $hash = hash('sha256', $token);

            //buscamos el token en la bd (la consulta del modelo filtra por caducidad)
            $rememberToken = $this->rememberTokenModelo->getByToken($hash);
            if ($rememberToken) {
                //Obtenemos los datos del usuario asociado al token
                $usuario = $this->usuarioModelo->getById($rememberToken['usuario_id']);

                if ($usuario) {
                    //Restauramos la sesión con los datos del usuario
                    $this->cargarSesionUsuario($usuario);
                }
            }
        }
    }

    private function cargarSesionUsuario(array $usuario): void
    {
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['usuario'] = $usuario['usuario'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['email'] = $usuario['email'];
        $_SESSION['foto'] = $usuario['foto'] ?? null;
    }

}
