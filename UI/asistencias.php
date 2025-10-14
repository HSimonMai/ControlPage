<?php
// Mostrar errores (opcional para depurar)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
$host = "localhost";
$user = "root"; // Cambiá si tu usuario es distinto
$pass = "2901";     // Poné tu contraseña si corresponde
$db = "control"; // Cambiá por el nombre real de tu base de datos

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consulta SQL: obtenemos las asistencias
$sql = "SELECT idAsistencias, FechaAsistencia, ValorAsistencia, idAlumnos, idtipoClase FROM asistencias LIMIT 15";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Asistencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/asistencia.css">
</head>
<body class="p-4">

    <div class="container">
        <div class="main-card">
            <div class="header-section d-flex justify-content-between align-items-center">
                <h2 class="m-0">
                    <i class="fas fa-calendar-check me-2"></i>Listado de Asistencias
                </h2>
                <a href="profesor.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>

            <div class="table-container">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID Asistencia</th>
                            <th>Fecha</th>
                            <th>Valor Asistencia</th>
                            <th>ID Alumno</th>
                            <th>ID Tipo Clase</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <?php 
                                    $valor = htmlspecialchars($row['ValorAsistencia']);
                                    $esPresente = ($valor == 1 || strtolower($valor) == 'presente' || strtolower($valor) == 'si');
                                    $claseValor = $esPresente ? 'attendance-present' : 'attendance-absent';
                                    $icono = $esPresente ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-times-circle"></i>';
                                    $textoValor = $esPresente ? 'Presente' : 'Ausente';
                                ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= htmlspecialchars($row['idAsistencias']) ?></td>
                                    <td class="text-center"><?= date('d/m/Y', strtotime(htmlspecialchars($row['FechaAsistencia']))) ?></td>
                                    <td class="text-center">
                                        <span class="attendance-value <?= $claseValor ?>">
                                            <?= $icono ?> <?= $textoValor ?>
                                        </span>
                                    </td>
                                    <td class="text-center"><?= htmlspecialchars($row['idAlumnos']) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($row['idtipoClase']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="no-data text-center">
                                    <i class="fas fa-calendar-times fa-3x mb-3"></i><br>
                                    No hay asistencias registradas aún.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
