<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../Controladores/TutorController.php');

if (!isset($_SESSION["idCursoSeleccionado"])) {
    echo "<div style='margin:20px; color:red; font-weight:bold;'>⚠️ No hay curso seleccionado.</div>";
    exit;
}

$idCurso = $_SESSION["idCursoSeleccionado"];

$controller = new TutorController();
$tutores = $controller->listarPorCurso($idCurso);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tutores</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="css/tutores.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand"><i class="bi bi-people-fill"></i> Tutores</span>
        <a href="profesor.php" class="btn btn-outline-light">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</nav>

<div class="container py-4">
    <div class="card p-4 shadow-sm">
        <h2 class="text-center mb-4">
            <i class="bi bi-journal-check"></i> Tutores del curso seleccionado
        </h2>

        <table class="table table-striped table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>DNI</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Alumnos a su cargo</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($tutores) > 0): ?>
                    <?php foreach ($tutores as $t): ?>
                        <tr>
                            <td><?= htmlspecialchars($t->nombre) ?></td>
                            <td><?= htmlspecialchars($t->apellido) ?></td>
                            <td><?= htmlspecialchars($t->dni) ?></td>
                            <td><?= htmlspecialchars($t->email) ?></td>
                            <td><?= htmlspecialchars($t->telefono) ?></td>
                            <td>
                                <ul class="list-unstyled mb-0">
                                    <?php if (count($t->alumnos) > 0): ?>
                                        <?php foreach ($t->alumnos as $al): ?>
                                            <li>👨‍🎓 <?= htmlspecialchars($al['nombre'] . ' ' . $al['apellido'] . ' (' . $al['dni'] . ')') ?></li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="text-muted">Sin alumnos asignados</li>
                                    <?php endif; ?>
                                </ul>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-muted py-4">
                            <i class="bi bi-person-x display-6 d-block mb-2"></i>
                            No hay tutores registrados para este curso.
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
