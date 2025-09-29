<?php

// Conectar
$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consulta
$sql = "SELECT FechaAsistencia, ValorAsistencia FROM asistencias";
$result = $conn->query($sql);

// Mostrar resultados en tabla
if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Fecha</th><th>Asistencia</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['FechaAsistencia'] . "</td>";
        echo "<td>" . $row['ValorAsistencia'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No hay registros de asistencia.";
}

$conn->close();
?>
