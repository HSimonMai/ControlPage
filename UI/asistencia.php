<?php

include '../DAL/config.php'; // conexión a la BD

// Recibimos el ID del alumno (ej: ?idAlumno=95)
$idAlumno = isset($_GET['idAlumno']) ? intval($_GET['idAlumno']) : 0;

if ($idAlumno > 0) {
    $sql = "SELECT FechaAsistencia, ValorAsistencia 
            FROM asistencias 
            WHERE idAlumnos = $idAlumno 
            ORDER BY FechaAsistencia DESC";
    
    $result = $conn->query($sql);

    echo "<h2>Asistencias del Alumno #$idAlumno</h2>";
    echo "<table border='1' cellpadding='8' cellspacing='0'>
            <tr>
                <th>Fecha</th>
                <th>Estado</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        // Interpretamos el valor
        $estado = "";
        if ($row['ValorAsistencia'] == 1.00) $estado = "Presente";
        elseif ($row['ValorAsistencia'] == 0.50) $estado = "Media falta";
        else $estado = "Ausente";

        echo "<tr>
                <td>".$row['FechaAsistencia']."</td>
                <td>".$estado."</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<p>No se encontró el alumno.</p>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <style>
.presente {
    color: green;
    font-weight: bold;
}

.media-falta {
    color: orange;
    font-weight: bold;
}

.ausente {
    color: red;
    font-weight: bold;
}


    </style>
</body>
</html>