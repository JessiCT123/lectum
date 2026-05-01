<?php

namespace Modelo;

use Servicios\Conexion;
use PDO;

abstract class BaseModel
{
    protected PDO $conn;

    protected string $tabla;
    protected string $idCol;
    /**
     * @var list<string>
     */
    protected array $columnas = [];

    public function __construct()
    {
        $this->conn = Conexion::get();
    }

    public function getAll(string|null $orderBy = null): array
    {
        return $this->conn
            ->query("SELECT * FROM {$this->tabla}" . ($orderBy ? " ORDER BY {$orderBy}" : ''))
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->conn
            ->prepare("SELECT * FROM {$this->tabla} WHERE {$this->idCol} = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByColumn(string $column, string|int $value): array|false
    {
        $stmt = $this->conn
            ->prepare("
                        SELECT *
                        FROM {$this->tabla}
                        WHERE {$column} = :value
            ");
        $stmt->execute([':value' => $value]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllByColumn(string $column, string|int $value): array
    {
        $stmt = $this->conn
            ->prepare("
                        SELECT *
                        FROM {$this->tabla}
                        WHERE {$column} = :value
            ");
        $stmt->execute([':value' => $value]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteByColumn(string $column, string|int $value): bool
    {
        $stmt = $this->conn
            ->prepare("
                        DELETE FROM {$this->tabla}
                        WHERE {$column} = :value
            ");
        return $stmt->execute([':value' => $value]);
    }

    public function create(array $datos): int
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->tabla} (" .
            implode(', ', $this->columnas) .
            ") VALUES (" .
            implode(', ', array_fill(0, count($this->columnas), '?')) .
            ")"
        );

        // Reordenar datos según el orden de columnas
        $valores = array_map(fn($c) => $datos[$c], $this->columnas);
        $stmt->execute(array_values($valores));
        return (int)$this->conn->lastInsertId();
    }

    public function update(int $id, array $datos): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE {$this->tabla} SET " .
            implode(', ', array_map(static fn($col) => "{$col} = ?", $this->columnas)) .
            " WHERE {$this->idCol} = ?"
        );

        //Reordenar datos según el orden de columnas
        $valores = array_map(static fn($col) => $datos[$col], $this->columnas);
        return $stmt->execute(array_merge(array_values($valores), [$id]));
    }

    public function delete(int $id): bool
    {
        return $this->conn
            ->prepare("DELETE FROM {$this->tabla} WHERE {$this->idCol} = :id")
            ->execute([':id' => $id]);
    }

}
