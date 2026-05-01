<?php

namespace Controlador;

use JetBrains\PhpStorm\NoReturn;
use JsonException;

abstract class BaseController
{
    /**
     * Carga una vista pasándole variables.
     * $vista es relativa a Vista/, sin extensión.
     * Ejemplo: $this->render('pestanias/explorar', compact('libros'))
     */
    protected function render(string $vista, array $datos = []): void
    {
        extract($datos);
        include_once dirname(__DIR__) . '/Vista/' . $vista . '.php';
    }

    protected function redirect(string $ruta, array $params = []): void
    {
        $query = $params ? '?' . http_build_query($params) : '';
        header('Location: ' . BASE_URL . '/' . ltrim($ruta, '/') . $query);
        exit;
    }

    protected function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    protected function postOr404(): void
    {
        if ($this->getMethod() !== 'POST') {
            die('404 — ruta ' . $this->getMethod() . ' ' . $_SERVER['REQUEST_URI'] . ' no encontrada');
        }
    }

    protected function sesionActivaOrRedirect(string $ruta = ''): void
    {
        if (!isset($_SESSION['id'])) {
            $this->redirect($ruta);
        }
    }

    /**
     * @throws JsonException
     */
    protected function postOr404ApiResponse(): void
    {
        if ($this->getMethod() !== 'POST') {
            $message = 'Ruta ' . $this->getMethod() . ' ' . $_SERVER['REQUEST_URI'] . ' no encontrada';
            $this->apiResponse(404, $message);
        }
    }

    /**
     * @throws JsonException
     */
    protected function sesionActivaOr401ApiResponse(): void
    {
        if (!isset($_SESSION['id'])) {
            $this->apiResponse(401, 'Sesión no iniciada, iniciar sesión primero.');
        }
    }

    /**
     * @throws JsonException
     */
    #[NoReturn]
    protected function apiResponse(int $code, string $message, $data = null): void
    {
        header('Content-Type: application/json');
        http_response_code($code);
        echo json_encode([
            'message' => $message,
            'data' => $data,
        ], JSON_THROW_ON_ERROR);
        exit;
    }

    protected function requestHasFile(string $file): bool
    {
        return isset($_FILES[$file]);
    }

    protected function requestGetFile(string $file)
    {
        return $_FILES[$file];
    }

    protected function requestPost(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    protected function requestGet(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    protected function setSessionErrorMessage(string $mensaje): void
    {
        $_SESSION['error_message'] = $mensaje;
    }

    protected function setSessionSuccessMessage(string $mensaje): void
    {
        $_SESSION['success_message'] = $mensaje;
    }

    protected function getSesion(string $clave, mixed $default = null): mixed
    {
        return $_SESSION[$clave] ?? $default;
    }

    protected function setSesion(string $clave, mixed $valor): void
    {
        $_SESSION[$clave] = $valor;
    }

}
