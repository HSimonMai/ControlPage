<?php
session_start();

require_once __DIR__ . '/../BLL/CursoBLL.php';

// Verificar sesión activa
if (!isset($_SESSION['profesor_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar curso seleccionado o guardado
if (!isset($_POST['curso_id']) && !isset($_SESSION['curso_id'])) {
    header("Location: seleccionar_curso.php");
    exit();
}

// Guardar curso seleccionado si viene por POST
if (isset($_POST['curso_id'])) {
    $_SESSION['curso_id'] = $_POST['curso_id'];
}

$profesor_id = $_SESSION['profesor_id'];
$nombreProfesor = $_SESSION['nombre_profesor'] ?? "Profesor";

// Obtener cursos mediante la capa BLL (sin tocar la BD directamente)
$cursos = CursoBLL::obtenerCursosYAsignaturasPorProfesor($profesor_id);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Cursos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <h2>Bienvenido, <?= htmlspecialchars($nombreProfesor) ?></h2>
        <h3>Mis cursos:</h3>

        <?php if (!empty($cursos)): ?>
            <table class="table table-bordered table-striped mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>ID Curso</th>
                        <th>Asignatura</th>
                        <th>Año lectivo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cursos as $curso): ?>
                        <tr>
                            <td><?= htmlspecialchars($curso['curso_id']) ?></td>
                            <td><?= htmlspecialchars($curso['asignatura']) ?></td>
                            <td><?= htmlspecialchars($curso['año_lectivo']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No tenés cursos asignados todavía.</p>
        <?php endif; ?>
    </div>
</body>
</html>
