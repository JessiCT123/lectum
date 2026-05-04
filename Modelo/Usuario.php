<?php

namespace Modelo;

use PDO;

class Usuario extends BaseModel
{
    protected string $tabla = 'usuarios';
    protected string $idCol = 'id';
    protected array $columnas = [
        'nombre',
        'usuario',
        'email',
        'password',
        'foto',
    ];

    public function getByUsuarioOrEmail(string $usuario): array|false
    {
        $stmt = $this->conn
            ->prepare("
                        SELECT *
                        FROM $this->tabla
                        WHERE usuario = :usuario OR email = :email
            ");
        $stmt->execute([':usuario' => $usuario, ':email' => $usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function existsByUsuarioOrEmail(string $usuario, string $email): bool
    {
        $stmt = $this->conn
            ->prepare("
                        SELECT COUNT(*)
                        FROM $this->tabla
                        WHERE usuario = :usuario OR email = :email
            ");
        $stmt->execute([
            ':usuario' => $usuario,
            ':email' => $email,
        ]);
        return (bool)$stmt->fetchColumn();
    }

    public function getByUsuario(string $usuario): array|false
    {
        return $this->getByColumn('usuario', $usuario);
    }

}
