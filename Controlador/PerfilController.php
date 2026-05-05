<?php

namespace Controlador;

use JsonException;
use Modelo\Libro;
use Modelo\Resenia;
use Modelo\Usuario;
use Modelo\UsuarioLibro;
use Servicios\FicherosService;
use Servicios\SesionServicio;
use Servicios\ValidacionesService;

class PerfilController extends BaseController
{
    private Libro $libroModelo;
    private Resenia $reseniaModelo;
    private Usuario $usuarioModelo;
    private UsuarioLibro $usuarioLibroModelo;
    private SesionServicio $sesionServicio;
    private ValidacionesService $validacionesService;
    private FicherosService $ficherosService;

    public function __construct()
    {
        $this->libroModelo = new Libro();
        $this->reseniaModelo = new Resenia();
        $this->usuarioModelo = new Usuario();
        $this->usuarioLibroModelo = new UsuarioLibro();
        $this->sesionServicio = new SesionServicio();
        $this->validacionesService = new ValidacionesService();
        $this->ficherosService = new FicherosService();
    }

    public function index(): void
    {
        $this->sesionActivaOrRedirect('auth');

        $libros = $this->libroModelo->getAll();
        $resenias = $this->reseniaModelo->obtenerReseniasLibros();

        $this->render('perfil/perfil', compact('libros', 'resenias'));
    }

    public function cambiarPassword(): void
    {
        $this->postOr404();

        $actual = $this->requestPost('actual');
        $nueva = $this->requestPost('nueva');
        $confirmar = $this->requestPost('confirmar');

        if (empty($actual) || empty($nueva) || empty($confirmar)) {
            $this->setSessionErrorMessage('Todos los campos son obligatorios.');
            $this->redirect('perfil');
        }

        //1. Obtener el id del usuario de la sesión
        $usuarioId = $this->getSesion('id');

        //2. Verificar que el usuario exista en la base de datos
        if (!$usuario = $this->usuarioModelo->getById($usuarioId)) {
            $this->setSessionErrorMessage('El usuario no existe en nuestra base de datos.');
            $this->sesionServicio->cerrarSesion();
            $this->redirect('');
        }

        //3. Comprobar que la contraseña actual es correcta
        if (!password_verify($actual, $usuario['password'])) {
            $this->setSessionErrorMessage('La contraseña actual no es correcta.');
            $this->redirect('perfil');
        }

        //4. Validar la nueva contraseña con las mismas reglas del registro
        $this->setSesion('error_message', '');
        $validado = $this->validacionesService->validarPassword($nueva);
        $validado = $this->validacionesService->validarPasswordConfirm($confirmar, $nueva, $validado);

        if (!$validado) {
            $this->redirect('perfil');
        }

        //5. Hashear y guardar
        $usuario['password'] = password_hash($nueva, PASSWORD_DEFAULT);
        $this->usuarioModelo->update($usuarioId, $usuario);

        $this->setSessionSuccessMessage('Contraseña actualizada correctamente.');
        $this->redirect('perfil');
    }

    /**
     * Función API para guardar el estado de un libro en la base de datos
     * @return void
     * @throws JsonException
     */
    public function guardarEstadoLibro(): void
    {
        $this->postOr404ApiResponse();
        $this->sesionActivaOr401ApiResponse();

        $uid = $this->getSesion('id');
        $lid = $this->requestPost('libro_id');
        $estado = $this->requestPost('estado'); // 'pendiente', 'leyendo', 'terminado'

        // Verificamos si ya existe el registro
        $usuarioLibro = $this->usuarioLibroModelo->getByUsuarioIdAndLibroId($uid, $lid);

        if ($usuarioLibro) {
            //Si existe actualizamos
            $usuarioLibro['estado'] = $estado;
            $this->usuarioLibroModelo->update($usuarioLibro['id'], $usuarioLibro);
        } else {
            //Si no existe insertamos
            $this->usuarioLibroModelo->create([
                'usuario_id' => $uid,
                'libro_id' => $lid,
                'estado' => $estado,
            ]);
        }

        $this->apiResponse(200, 'Libro guardado correctamente.');
    }

    public function subirFoto(): void
    {
        //1. Comprobamos si se recibe una petición post y si la sesión está activa
        $this->postOr404();
        $this->sesionActivaOrRedirect('auth');

        //2. Comprobamos que la request venga con el archivo 'foto'
        if ($this->requestHasFile('foto')) {
            //3. Obtenemos el archivo 'foto' del request
            $archivo = $this->requestGetFile('foto');
            //3.1 Validamos que el fichero venga sin errores
            if (!$this->ficherosService->validarArchivo($archivo, MAX_FOTO_BYTES, EXTENSIONES_FOTOS_PERFIL)) {
                $this->setSessionErrorMessage('Error al cargar la foto.</br>' .
                    'Compruebe que no supere los 2MB y que sea una imagen con formato ' . implode(', ', EXTENSIONES_FOTOS_PERFIL) . '.');
                $this->redirect('perfil');
            }

            //4. Obtenemos el usuario de BD a partir de la sesión y si
            // ya existe foto para ese usuario se borra.
            $usuario = $this->usuarioModelo->getById($this->getSesion('id'));
            if (!empty($usuario['foto']) && !$usuario['foto'] !== 'perfil.jpg') {
                $filepath = DIR_BASE . FOTOS_FOLDER . $usuario['foto'];
                $this->ficherosService->borrar($filepath);
            }

            //5. Guardamos la nueva foto y actualizamos el usuario
            $nombreArchivo = $this->ficherosService->guardar(
                $archivo,
                FOTOS_FOLDER,
                $usuario['usuario'],
            );
            if ($nombreArchivo) {
                $usuario['foto'] = $nombreArchivo;
                $this->usuarioModelo->update($usuario['id'], $usuario);

                $this->setSesion('foto', $nombreArchivo);
            }

            $this->redirect('perfil');
        }
    }

}
