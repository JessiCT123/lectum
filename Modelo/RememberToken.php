<?php

namespace Modelo;

use PDO;

class RememberToken extends BaseModel
{
    protected string $tabla = 'remember_tokens';
    protected string $idCol = 'id';
    protected array $columnas = [
        'usuario_id',
        'token_hash',
        'f_caducidad',
        'f_creacion',
    ];

    public function getByToken(string $token, bool $caducidad = true): array|false
    {
        $stmt = $this->conn
            ->prepare("
                        SELECT *
                        FROM {$this->tabla}
                        WHERE token_hash = :token
                        " . ($caducidad ? "AND f_caducidad > NOW()" : '') . "
            ");
        $stmt->execute([':token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteByUsuarioId(int $usuarioId): bool
    {
        return $this->deleteByColumn('usuario_id', $usuarioId);
    }

    public function deleteByToken(string $token): bool
    {
        return $this->deleteByColumn('token_hash', $token);
    }

}
