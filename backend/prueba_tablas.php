<?php
include("conexion.php");

// Mostrar todas las tablas de la base de datos
echo "<h2>Tablas en la base de datos bd_lectum</h2>";

$tablas = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_NUM);

foreach ($tablas as $tabla) {
    $tablaNombre = $tabla[0];
    echo "<h3>Tabla: $tablaNombre</h3>";

    // Mostrar contenido de la tabla
    $resultado = $conn->query("SELECT * FROM $tablaNombre")->fetchAll(PDO::FETCH_ASSOC);

    if (count($resultado) > 0) {
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr>";
        foreach (array_keys($resultado[0]) as $columna) {
            echo "<th>$columna</th>";
        }
        echo "</tr>";

    
        foreach ($resultado as $fila) {
            echo "<tr>";
            foreach ($fila as $valor) {
                echo "<td>$valor</td>";
            }
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>La tabla está vacía.</p>";
    }
}
?>