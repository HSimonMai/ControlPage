<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . "/../Controladores/AlumnosController.php";

$controller = new AlumnosController();
$data = $controller->mostrarAlumnos();

$curso = $data["curso"];
$listaAlumnos = $data["alumnos"];
$nombreProfesor = $_SESSION["nombreProfesor"] ?? "Profesor";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Alumnos - <?= htmlspecialchars($curso["asignatura"]) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand"><i class="bi bi-person-video3"></i> Panel del Profesor</span>
        <div>
            <a href="profesor.php" class="btn btn-outline-light me-2">Volver</a>
            <a href="login.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>
    </div>
</nav>

<div class="container py-4">
    <div class="card p-4">
        <div class="text-center mb-4">
            <h2><i class="bi bi-people"></i> Lista de Alumnos</h2>
            <p>Curso:
                <strong><?= htmlspecialchars($curso["Año"]) ?>° <?= htmlspecialchars($curso["Division"]) ?> - <?= htmlspecialchars($curso["asignatura"]) ?></strong>
            </p>
        </div>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Apellido</th>
                    <th>Nombre</th>
                    <th>DNI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($listaAlumnos)): $i=1; ?>
                    <?php foreach ($listaAlumnos as $alumno): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($alumno->getApellido()) ?></td>
                            <td><?= htmlspecialchars($alumno->getNombre()) ?></td>
                            <td><?= htmlspecialchars($alumno->getDni()) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center">No hay alumnos en este curso.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
