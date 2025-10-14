<?php
// Mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
$host = "localhost";
$user = "root"; // Cambiá si tenés otro usuario
$pass = "2901";     // Poné tu contraseña si corresponde
$db = "control"; // Cambiá por el nombre real de tu base

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consulta SQL: muestra algunos alumnos
$sql = "SELECT idAlumnos, DNI, Nombre, Apellido, Genero, Nacionalidad, FechaNacimiento, Direccion 
        FROM alumnos 
        LIMIT 10";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Alumnos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/panelAlumno.css">
</head>
<body class="p-4">

    <div class="container">
        <div class="main-card">
            <div class="header-section d-flex justify-content-between align-items-center">
                <h2 class="m-0">
                    <i class="fas fa-users me-2"></i>Listado de Alumnos
                </h2>
                <a href="profesor.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>

            <div class="table-container">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>DNI</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Género</th>
                            <th>Nacionalidad</th>
                            <th>Fecha de Nacimiento</th>
                            <th>Dirección</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= htmlspecialchars($row['idAlumnos']) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($row['DNI']) ?></td>
                                    <td><?= htmlspecialchars($row['Nombre']) ?></td>
                                    <td><?= htmlspecialchars($row['Apellido']) ?></td>
                                    <td class="text-center">
                                        <span class="gender-icon <?= strtolower(htmlspecialchars($row['Genero'])) ?>"></span>
                                        <?= htmlspecialchars($row['Genero']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['Nacionalidad']) ?></td>
                                    <td class="text-center"><?= date('d/m/Y', strtotime(htmlspecialchars($row['FechaNacimiento']))) ?></td>
                                    <td><?= htmlspecialchars($row['Direccion']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="no-data text-center">
                                    <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                    No hay alumnos registrados aún.
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
