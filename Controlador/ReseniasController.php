<?php

namespace Controlador;

use Modelo\Libro;
use Modelo\Resenia;

class ReseniasController extends BaseController
{
    private Libro $libroModelo;
    private Resenia $reseniaModelo;

    public function __construct()
    {
        $this->libroModelo = new Libro();
        $this->reseniaModelo = new Resenia();
    }

    public function index(): void
    {
        $libros = $this->libroModelo->getAll();
        $resenias = $this->reseniaModelo->obtenerReseniasLibros();
        $idLibro= $this->requestGet("id");
        $this->render('pestanias/resenias', compact('libros', 'resenias','idLibro'));
    }

    public function publicar()
    {
        //Si la petición no es POST lanza 404
        $this->postOr404();

        $resenia = [
            'usuario_id' => $this->getSesion('id'),
            'libro_id' => $this->requestPost('libro_id'),
            'valoracion' => $this->requestPost('valoracion'),
            'texto' => $this->requestPost('texto'),
        ];
        $this->reseniaModelo->create($resenia);

        $this->redirect('resenias', ['id' => $resenia['libro_id']]);
    }

    public function eliminar()
    {
        //Si la petición no es POST lanza 404
        $this->postOr404();

        $libroId = $this->requestPost('libro_id');
        $usuarioId = $this->getSesion('id');
        $this->reseniaModelo->deleteByUsuarioIdAndLibroId($usuarioId, $libroId);

        $this->redirect('resenias');
    }

}
