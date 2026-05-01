<?php

namespace Modelo;

use PDO;

class Libro extends BaseModel
{
    protected string $tabla = 'libros';
    protected string $idCol = 'id';
    protected array $columnas = [
        'titulo',
        'autor',
        'genero_id',
        'valoracion',
        'lecturas',
        'anio',
        'paginas',
        'portada',
        'descripcion',
    ];

    public function obtenerLibrosGenero(string|null $orderBy = null): array
    {
        $stmt = $this->conn->query(
            "SELECT l.*, g.nombre AS genero_nombre
             FROM libros l
             JOIN generos g ON l.genero_id = g.id" .
            ($orderBy ? " ORDER BY {$orderBy}" : '')
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerLibroGeneroPorId(int $id): array|false
    {
        $stmt = $this->conn->prepare(
            "SELECT l.*, g.nombre AS genero_nombre
             FROM libros l
             JOIN generos g ON l.genero_id = g.id
             WHERE l.id = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
