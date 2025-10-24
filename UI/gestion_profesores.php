<?php
session_start();
require_once(__DIR__ . "/../BLL/ProfesorBLL.php");
require_once(__DIR__ . "/../BLL/CursoBLL.php");
require_once(__DIR__."/../BLL/ProfesorCursoBLL.php");

require_once("../UI/components/layout.template.php");
require_once("../UI/components/navbar.template.php");

require_once("../UI/components/mainCursos.template.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['profesor_id'])) {
    $profesorId = (int)$_POST['profesor_id'];
    $cursoId = (int)$_POST['curso_id'];
    $asignatura = trim($_POST['asignatura']);
    
    try {
        $profesorCursoBLL = new ProfesorCursoBLL();
        $resultado = $profesorCursoBLL->asignarProfesorCurso($profesorId, $cursoId, $asignatura);
        
        if ($resultado) {
            $_SESSION['mensaje'] = "success|Curso asignado correctamente al profesor";
        }
    } catch (Exception $e) {
        $_SESSION['mensaje'] = "error|" . $e->getMessage();
    }
    
    // Redirigir para evitar reenvío
    header("Location: gestion_profesores.php");
    exit;
}



$usuario = unserialize($_SESSION["usuario"]);
$idTipoUsuario = (int) $usuario->getIdTiposUsuarios();
if ($idTipoUsuario === 1 || $idTipoUsuario === 4) {
    header('Location: ../UI/login.php');
}
$navbar=new Navbar_template($usuario);
$layout= new Layout_template($navbar);

$layout->render();



$profesorBLL = new ProfesorBLL();
$cursoBLL = new CursoBLL();

$profesores = $profesorBLL->getAllProfesores();
$cursos = $cursoBLL->getAllCursos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Profesores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Edición de Profesores</h1>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Lista de Profesores</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>DNI</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Email</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($profesores as $index => $profesor): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($profesor->getDni()) ?></td>
                                        <td><?= htmlspecialchars($profesor->getNombre()) ?></td>
                                        <td><?= htmlspecialchars($profesor->getApellido()) ?></td>
                                        <td><?= htmlspecialchars($profesor->getEmail()) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" onclick="asignarCursos(<?= $profesor->getId() ?>)">
                                                Asignar Cursos
                                            </button>
                                            <button class="btn btn-sm btn-info" onclick="verCursos(<?= $profesor->getId() ?>)">
                                                Ver Cursos
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- //modal para asignarCursos -->
    <div class="modal fade" id="modalAsignarCursos">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Asignar Cursos al Profesor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formAsignarCurso" method="POST" action="gestion_profesores.php">
                        <input type="hidden" id="profesorId" name="profesor_id">
                        <div class="mb-3">
                            <label for="curso_id" class="form-label">Curso</label>
                            <select class="form-control" id="curso_id" name="curso_id" required>
                                <option value="">Seleccionar curso</option>
                                <?php foreach ($cursos as $curso): ?>
                                <option value="<?= $curso->getId() ?>">
                                    <?= $curso->getAno() ?>° <?= $curso->getDivision() ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="asignatura" class="form-label">Asignatura</label>
                            <input type="text" class="form-control" id="asignatura" name="asignatura" required>
                        </div>
                        <button type="submit" class="btn btn-success">Asignar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function asignarCursos(profesorId) {
        document.getElementById('profesorId').value = profesorId;
        new bootstrap.Modal(document.getElementById('modalAsignarCursos')).show();
    }
    
    function verCursos(profesorId) {
        window.location.href = 'cursos_profesor.php?profesor_id=' + profesorId;
    }
    </script>
</body>
</html>