<?php

namespace Servicios;

class ValidacionesService
{
    private const PREG_PASS = '/[0-9!@#$%^&*()\-_]/';

    public function validarRegistro(array $user): bool
    {
        $nombre    = $user['nombre'];
        $usuario   = $user['usuario'];
        $email     = $user['email'];
        $password  = $user['password'];
        $password1 = $user['password1'];

        if (empty($nombre) || empty($usuario) || empty($email) || empty($password) || empty($password1)) {
            $_SESSION['error_message'] = 'Todos los campos son obligatorios.';
            return false;
        }

        $validado = $this->validarUsuario($usuario);
        $validado = $this->validarEmail($email, $validado);
        $validado = $this->validarPassword($password, $validado);
        $validado = $this->validarPasswordConfirm($password1, $password, $validado);

        return $validado;
    }

    public function validarUsuario(string $usuario, bool $validado = true): bool
    {
        if (!preg_match('/[A-Z]/', $usuario)) {
            $_SESSION['error_message'] .= 'El usuario debe contener al menos una letra mayúscula.<br>';
            $validado = false;
        }
        if (!preg_match( self::PREG_PASS, $usuario)) {
            $_SESSION['error_message'] .= 'El usuario debe contener al menos un número o símbolo (!@#$%^&*), no se acepta ":".<br>';
            $validado = false;
        }

        return $validado;
    }

    public function validarEmail(string $email, bool $validado = true): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_message'] .= 'El email no es válido.<br>';
            $validado = false;
        }

        return $validado;
    }

    public function validarPassword(string $password, bool $validado = true): bool
    {
        if (strlen($password) < 8) {
            $_SESSION['error_message'] .= 'La contraseña debe tener al menos 8 caracteres.<br>';
            $validado = false;
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $_SESSION['error_message'] .= 'La contraseña debe contener al menos una letra mayúscula.<br>';
            $validado = false;
        }
        if (!preg_match( self::PREG_PASS, $password)) {
            $_SESSION['error_message'] .= 'La contraseña debe contener al menos un número o símbolo (!@#$%^&*).<br>';
            $validado = false;
        }

        return $validado;
    }

    public function validarPasswordConfirm(string $password1, string $password, bool $validado = true): bool
    {
        if ($password !== $password1) {
            $_SESSION['error_message'] .= 'Las contraseñas no coinciden.<br>';
            $validado = false;
        }
        return $validado;
    }

    public function validarRegTerms(bool $aceptado, bool $validado = true): bool
    {
        if (!$aceptado) {
            $_SESSION['error_message'] .= 'Debes aceptar los términos y condiciones.<br>';
            $validado = false;
        }
        return $validado;
    }

}
