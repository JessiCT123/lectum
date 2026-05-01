<?php

namespace Controlador;

use Modelo\Usuario;
use Random\RandomException;
use Servicios\SesionServicio;
use Servicios\ValidacionesService;

class AuthController extends BaseController
{
    private Usuario $usuarioModelo;
    private SesionServicio $sesionServicio;
    private ValidacionesService $validacionesService;

    public function __construct()
    {
        $this->usuarioModelo = new Usuario();
        $this->sesionServicio = new SesionServicio();
        $this->validacionesService = new ValidacionesService();
    }

    public function index(): void
    {
        $this->render('auth/login');
    }

    public function login(): void
    {
        //Si la petición no es POST lanza 404
        $this->postOr404();

        $usuario = $this->requestPost('usuario');
        $password = $this->requestPost('password');

        if (!empty($usuario) && !empty($password)) {
            $usuario = $this->usuarioModelo->getByUsuarioOrEmail($usuario);
            if ($usuario && password_verify($password, $usuario['password'])) {
                try {
                    $this->sesionServicio->guardarSesionConDatosReales($usuario);
                    $this->redirect('');
                } catch (RandomException $e) {
                    $this->setSessionErrorMessage('Error al iniciar sesión, inténtelo de nuevo.');
                    $this->redirect('auth');
                }
            } else {
                $this->setSessionErrorMessage('Usuario/email o contraseña incorrectos.');
                $this->redirect('auth');
            }
        } else {
            $this->setSessionErrorMessage('Debe ingresar todos los datos.');
            $this->redirect('auth');
        }
    }

    public function logout(): void
    {
        $this->sesionServicio->cerrarSesion();
        $this->redirect('');
    }

    public function registro(): void
    {
        $this->render('auth/registro'); //Solo muestra la vista de registro
    }

    public function registrar(): void //Realiza el registro del usuario
    {
        $this->postOr404();

        $user = [
            'nombre' => $this->requestPost('nombre'),
            'usuario' => $this->requestPost('usuario'),
            'email' => $this->requestPost('email'),
            'password' => $this->requestPost('password'),
            'password1' => $this->requestPost('password1'),
        ];

        if (!$this->validacionesService->validarRegistro($user)) {
            $this->redirect('auth/registro');
        }

        /*Realizamos la consulta en la tabla de usuarios para ver si ya existe un usuario o email igual*/
        if ($this->usuarioModelo->existsByUsuarioOrEmail($user['usuario'], $user['email'])) {
            $this->setSessionErrorMessage('El usuario o email ya existe.');
        } else {
            // hasheamos la contraseña antes de guardarla en la bd
            $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);

            $this->usuarioModelo->create($user);
            $this->setSessionSuccessMessage('Usuario registrado correctamente.');
        }

        $this->redirect('auth');
    }
}
