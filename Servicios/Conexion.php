<?php

namespace Servicios;

use PDO;
use PDOException;

class Conexion
{
    private static ?PDO $conn = null;

    public static function get(): PDO
    {
        if (self::$conn === null) {
            $host = DB_HOST;
            $dbname = DB_NAME;
            $username = DB_USER;
            $password = DB_PASS;

            try {
                self::$conn = new PDO(
                    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                    $username,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            } catch (PDOException $e) {
                die("Error de conexión a la base de datos.".$e);
            }
        }

        return self::$conn;
    }
}
