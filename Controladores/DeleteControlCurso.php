<?php
session_start();
require_once(__DIR__ . "/../BLL/ProfesorCursoBLL.php");
require_once(__DIR__ . "/../BLL/ProfesorBLL.php");
require_once(__DIR__ . "/../BLL/CursoBLL.php");
require_once("../UI/components/layout.template.php");
require_once("../UI/components/navbar.template.php");

// Verificar que se pasó el profesor_id
if (!isset($_GET['profesor_id'])) {
    header('Location: gestion_profesores.php');
    exit;
}

$profesorId = (int)$_GET['profesor_id'];

$usuario = unserialize($_SESSION["usuario"]);
$idTipoUsuario = (int) $usuario->getIdTiposUsuarios();
if ($idTipoUsuario === 1 || $idTipoUsuario === 4) {
    header('Location: ../UI/login.php');
    exit;
}

$navbar = new Navbar_template($usuario);
$layout = new Layout_template($navbar);
$layout->render();

// Obtener datos del profesor y sus cursos
$profesorBLL = new ProfesorBLL();
$profesorCursoBLL = new ProfesorCursoBLL();
$cursoBLL = new CursoBLL(); // <- FALTABA ESTA LÍNEA

$profesor = $profesorBLL->getProfesorById($profesorId);
$cursosAsignados = $profesorCursoBLL->getCursosByProfesor($profesorId);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos del Profesor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Cursos de <?= htmlspecialchars($profesor->getNombre()) ?> <?= htmlspecialchars($profesor->getApellido()) ?></h1>
        <a href="gestion_profesores.php" class="btn btn-secondary">← Volver</a>
    </div>

    <?php if (empty($cursosAsignados)): ?>
        <div class="alert alert-info">
            Este profesor no tiene cursos asignados.
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Cursos Asignados</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Curso</th>
                                <th>Asignatura</th>
                                <th>Año Lectivo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cursosAsignados as $index => $curso): ?>
                                <?php  
                                $cursoId = $curso->getCursoId();
                                $cursoCompleto = $cursoBLL->getUsuarioByIdCurso ($cursoId);
                                ?>

                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($cursoCompleto->getAno())?>° <?= htmlspecialchars($cursoCompleto->getDivision())?></td>
                                    <td><?= htmlspecialchars($curso->getAsignatura()) ?></td>
                                    <td><?= htmlspecialchars($curso->getAñoLectivo()) ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal<?= $curso->getId() ?>">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php
    // Función para generar modales
    function generateModalDelete($curso, $profesorId, $cursoBLL) {
        $cursoId = $curso->getCursoId();
        $cursoCompleto = $cursoBLL->getUsuarioByIdCurso($cursoId); 
        
        return '
        <div class="modal fade" id="deleteModal' . $curso->getId() . '" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Eliminar Asignación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        ¿Está seguro de que desea eliminar la asignación de <strong>' . htmlspecialchars($curso->getAsignatura()) . '</strong> 
                        del curso <strong>' .  htmlspecialchars($cursoCompleto->getAno()) . '° ' . htmlspecialchars($cursoCompleto->getDivision()) . '</strong>?
                    </div>
                    <div class="modal-footer">
                        <form method="POST" action="../Controladores/delete.controlCurso.php">
                            <input type="hidden" name="id" value="' . $curso->getId() . '">
                            <input type="hidden" name="profesor_id" value="' . $profesorId . '">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>';
    }

    // Generar modales para cada curso
    foreach ($cursosAsignados as $curso) {
        echo generateModalDelete($curso, $profesorId, $cursoBLL);
    }
    ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>