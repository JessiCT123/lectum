<?php

namespace Servicios;

class FicherosService
{
    public function validarArchivo(array $archivo, int $maxBytes, array $extensionesValidas): bool
    {
        $valido = true;

        //Comprobamos que el archivo se subió sin errores
        if ($archivo['error'] !== UPLOAD_ERR_OK) {
            $valido = false;
        }

        //Comprobamos el tamaño
        if ($valido && $archivo['size'] > $maxBytes) {
            $valido = false;
        }

        if ($valido) {
            //Comprobamos la extensión
            $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
            if (!in_array($extension, $extensionesValidas, true)) {
                $valido = false;
            }
        }

        return $valido;
    }

    public function guardar(array $archivo, string $carpeta, string|null $sufijoNombre = null): string|false
    {
        $sufijo = $sufijoNombre ?: $archivo['name'];
        //Si el sufijo no tiene extensión, le ponemos la del archivo original
        if (!pathinfo($sufijo, PATHINFO_EXTENSION)) {
            $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
            $sufijo .= '.' . $extension;
        }

        $nombre = uniqid('', true) . '_' . $sufijo;
        $filePath = $carpeta . $nombre;

        if (move_uploaded_file($archivo['tmp_name'], $filePath)) {
            return $nombre;
        }
        return false;
    }

    public function borrar(string $ruta): bool
    {
        if (file_exists($ruta)) {
            return unlink($ruta);
        }
        return false;
    }

}
