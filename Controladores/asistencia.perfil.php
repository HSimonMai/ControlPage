<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../DAL/AsistenciaDAL.php");

$mapper = new AsistenciaDAL();


// --- Mostrar resultados ---
if (!empty($asistencias)) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Fecha</th><th>Asistencia</th></tr>";

    foreach ($asistencias as $row) {
        echo "<tr>";
        echo "<td>" . $row['FechaAsistencia'] . "</td>";
        echo "<td>" . $row['ValorAsistencia'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No hay registros de asistencia.";
}
?>
