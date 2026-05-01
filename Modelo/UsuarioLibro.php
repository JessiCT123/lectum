<?php

namespace Modelo;

use PDO;

class UsuarioLibro extends BaseModel
{
    protected string $tabla = 'usuario_libros';
    protected string $idCol = 'id';
    protected array $columnas = [
        'usuario_id',
        'libro_id',
        'estado',
    ];

    public function getAllByUsuarioId(int $id): array
    {
        return $this->getAllByColumn('usuario_id', $id);
    }

    public function getByUsuarioIdAndLibroId(int $usuarioId, int $libroId): array|false
    {
        $stmt = $this->conn->prepare("
            SELECT *
            FROM {$this->tabla}
            WHERE usuario_id = :usuarioId AND libro_id = :libroId
        ");
        $stmt->execute([
            ':usuarioId' => $usuarioId,
            ':libroId' => $libroId,
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
