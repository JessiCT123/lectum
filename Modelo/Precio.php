<?php

namespace Modelo;

use PDO;

class Precio extends BaseModel
{
    protected string $tabla = 'precios';
    protected string $idCol = 'id';
    protected array $columnas = [
        'libro_id',
        'tienda_id',
        'precio',
        'url',
    ];

    public function obtenerPreciosTiendas(): array
    {
        $stmt = $this->conn->query(
            "SELECT p.*, t.nombre AS tienda_nombre, t.icono AS tienda_icono, t.estrellas, t.envio
             FROM precios p JOIN tiendas t ON p.tienda_id = t.id"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
