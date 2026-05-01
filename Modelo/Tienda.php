<?php

namespace Modelo;

class Tienda extends BaseModel
{
    protected string $tabla = 'tiendas';
    protected string $idCol = 'id';
    protected array $columnas = [
        'nombre',
        'icono',
        'estrellas',
        'envio',
    ];

}
