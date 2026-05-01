<?php

namespace Modelo;

class Genero extends BaseModel
{
    protected string $tabla = 'generos';
    protected string $idCol = 'id';
    protected array $columnas = ['nombre'];

}
