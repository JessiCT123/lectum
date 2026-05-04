<?php

namespace Controlador;

use Modelo\Genero;
use Modelo\Libro;
use Modelo\Precio;
use Modelo\Resenia;
use Modelo\Tienda;
use Modelo\UsuarioLibro;

class InicioController extends BaseController
{
    private Libro $libroModelo;
    private Precio $precioModelo;
    private Tienda $tiendaModelo;
    private Genero $generoModelo;
    private Resenia $reseniaModelo;

    public function __construct()
    {
        $this->libroModelo = new Libro();
        $this->precioModelo = new Precio();
        $this->tiendaModelo = new Tienda();
        $this->generoModelo = new Genero();
        $this->reseniaModelo = new Resenia();
    }

    public function index(): void
    {
        $usuarioLibroModelo = new UsuarioLibro();

        $libros = $this->libroModelo->obtenerLibrosGenero('l.id');
        $tienda = $this->tiendaModelo->getAll('id');
        $precios = $this->precioModelo->obtenerPreciosTiendas();
        $resenias = $this->reseniaModelo->obtenerReseniasLibros(20);
        $generos = $this->generoModelo->getAll('nombre');

        $coleccionUsuario = [];
        if ($this->getSesion('id')) {
            $coleccionUsuario = $usuarioLibroModelo->getAllByUsuarioId($this->getSesion('id'));
        }

        $this->render('index', compact('libros', 'tienda', 'precios', 'resenias', 'generos', 'coleccionUsuario'));
    }

    public function contacto(): void
    {
        $libros = $this->libroModelo->obtenerLibrosGenero('l.titulo');
        $precios = $this->precioModelo->obtenerPreciosTiendas();
        $idLibro = $this->requestGet('id', '');

        $this->render('pestanias/contacto', compact('libros', 'precios', 'idLibro'));
    }

    public function compararPrecios(): void
    {
        $libros = $this->libroModelo->obtenerLibrosGenero('l.titulo');
        $precios = $this->precioModelo->obtenerPreciosTiendas();
        $idLibro = $this->requestGet('id', '');

        $this->render('pestanias/compararPrecios', compact('libros', 'precios', 'idLibro'));
    }

    public function explorar(): void
    {
        $libros = $this->libroModelo->obtenerLibrosGenero('l.id');
        $generos = $this->generoModelo->getAll('nombre');
        $tiendas = $this->tiendaModelo->getAll('id');
        $precios = $this->precioModelo->getAll();
        $resenias = $this->reseniaModelo->obtenerReseniasLibros(20);

        $this->render('pestanias/explorar', compact('libros', 'generos', 'tiendas', 'precios', 'resenias'));
    }

    public function misLibros(): void
    {
        $usuarioLibroModelo = new UsuarioLibro();

        $librosBase = $this->libroModelo->obtenerLibrosGenero();

        $coleccionUsuario = [];
        if ($this->getSesion('id')) {
            $coleccionUsuario = $usuarioLibroModelo->getAllByUsuarioId($this->getSesion('id'));
        }

        $this->render('pestanias/misLibros', compact('librosBase', 'coleccionUsuario'));
    }
}
