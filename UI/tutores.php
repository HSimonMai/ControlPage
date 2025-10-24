<?php
$conexion = new mysqli("localhost", "root", "2901", "control");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$sql = "SELECT * FROM tutores";
$resultado = $conexion->query($sql);

// Supongamos que $faltas es el número de faltas del alumno
$faltas = 4; // Podés cambiar esto dinámicamente según el alumno
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tutores</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    background-color: #a7c7e7;
}
.card {
    border-radius: 1rem;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}
.alerta {
    background-color: #ffc107;
    color: #000;
    padding: 15px;
    text-align: center;
    border-radius: 8px;
    margin-bottom: 20px;
    font-weight: bold;
}
.table thead {
    background-color: #007bff;
    color: white;
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand"><i class="bi bi-people-fill"></i> Tutores</span>
        <a href="profesor.php" class="btn btn-outline-light">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</nav>

<div class="container py-4">
    <!-- Alerta -->
    <?php if ($faltas > 3): ?>
        <div class="alerta">
            ⚠️ Si el alumno tiene más de 3 faltas, se notificará a los tutores.
        </div>
    <?php endif; ?>

    <div class="card p-4">
        <h2 class="text-center mb-4">
            <i class="bi bi-journal-check"></i> Lista de Tutores
        </h2>

        <table class="table table-striped table-hover align-middle text-center">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>DNI</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <?php while($fila = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($fila['Nombre']) ?></td>
                        <td><?= htmlspecialchars($fila['Apellido']) ?></td>
                        <td><?= htmlspecialchars($fila['Dni']) ?></td>
                        <td><?= htmlspecialchars($fila['Email']) ?></td>
                        <td><?= htmlspecialchars($fila['Telefono']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            <i class="bi bi-person-x display-6 d-block mb-2"></i>
                            No hay tutores registrados.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conexion->close(); ?>
