<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION["idProfesor"])) {
    header("Location: login.php");
    exit;
}

$idProfesor = $_SESSION["idProfesor"];

$conexion = new mysqli("localhost", "root", "2901", "control");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$query = "
    SELECT 
        pc.idProfesorCurso, 
        pc.asignatura
    FROM profesor_curso pc
    WHERE pc.profesor_id = ?
    ORDER BY pc.asignatura ASC
";

$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $idProfesor);
$stmt->execute();
$result = $stmt->get_result();

$cursos = [];
while ($row = $result->fetch_assoc()) {
    $cursos[] = $row;
}

$stmt->close();
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Asignaturas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">Asignaturas del Profesor</h4>
        </div>

        <div class="card-body">
            <?php if (count($cursos) > 0): ?>
                <table class="table table-hover align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>Asignatura</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cursos as $curso): ?>
                            <tr>
                                <td><?= htmlspecialchars($curso['asignatura']) ?></td>
                                <td>
                                    <a href="profesor.php?id=<?= urlencode($curso['idProfesorCurso']) ?>" 
                                       class="btn btn-success btn-sm">
                                       <i class="bi bi-box-arrow-in-right"></i> Ir al Panel
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-warning text-center">
                    No tienes asignaturas asignadas por el momento.
                </div>
            <?php endif; ?>
        </div>

        <div class="card-footer text-center">
            <a href="login.php" class="btn btn-danger">
                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
