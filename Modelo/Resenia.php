<?php

namespace Modelo;

use PDO;

class Resenia extends BaseModel
{
    protected string $tabla = 'resenias';
    protected string $idCol = 'id';
    protected array $columnas = [
        'libro_id',
        'usuario_id',
        'valoracion',
        'texto',
    ];

    public function obtenerReseniasLibros(int|null $limit = null): array
    {
        $stmt = $this->conn->query(
            "SELECT r.*, l.titulo AS titulo_libro, u.usuario AS nombre_usuario, u.nombre
             FROM resenias r
             JOIN libros l ON r.libro_id = l.id
             JOIN usuarios u ON r.usuario_id = u.id
             ORDER BY r.fecha DESC " . ($limit ? "LIMIT {$limit}" : '')
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteByUsuarioIdAndLibroId(int $usuarioId, int $libroId): bool
    {
        $stmt = $this->conn
            ->prepare("
                        DELETE FROM {$this->tabla}
                        WHERE usuario_id = :usuarioId AND libro_id = :libroId
            ");
        return $stmt->execute([
            ':usuarioId' => $usuarioId,
            ':libroId' => $libroId,
        ]);
    }

}
