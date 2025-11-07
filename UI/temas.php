<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . "/../controladores/TemaController.php");
require_once(__DIR__ . "/../DAL/ProfesorDAL.php");

if (!isset($_SESSION["idProfesor"])) {
    header("Location: login.php");
    exit;
}

$idProfesor = $_SESSION["idProfesor"];
$profesorDAL = new ProfesorDAL();
$controller = new TemaController();

$profesor = $profesorDAL->getProfesorById($idProfesor);
if (!$profesor) die("Profesor no encontrado");

if (!isset($_SESSION["cursoSeleccionado"])) {
    header("Location: mis_cursos.php");
    exit;
}

// 📌 Datos del curso seleccionado
$cursoSeleccionado = $_SESSION["cursoSeleccionado"];
$idCurso = isset($cursoSeleccionado["idCursos"]) ? intval($cursoSeleccionado["idCursos"]) : null;
$anio = $cursoSeleccionado["Año"] ?? "";
$division = $cursoSeleccionado["Division"] ?? "";
$materia = $cursoSeleccionado["asignatura"] ?? "";
$anioLectivo = $cursoSeleccionado["año_lectivo"] ?? "";

// ✍️ Actualizar firma de autoridad
if (isset($_POST['accion']) && $_POST['accion'] === 'actualizar_firma') {
    $controller->actualizarFirmaAutoridad(intval($_POST['id_tema']), intval($_POST['estado']));
    echo json_encode(['success' => true]);
    exit;
}

// 🗑️ Eliminar tema
if (isset($_GET["eliminar"])) {
    $controller->eliminarTema(intval($_GET["eliminar"]));
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?deleted=1");
    exit;
}

// ➕ Agregar tema nuevo
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['titulo'])) {
    $controller->agregarTema($_POST, $idProfesor, $idCurso);
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?success=1");
    exit;
}

// 📋 Listar temas
$temas = $controller->listarTemas($idProfesor, $idCurso);
$tipos = $controller->getTiposClase();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Libro de Temas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">📘 Libro de Temas</span>
        <a href="profesor.php" class="btn btn-outline-light"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</nav>

<div class="container py-4">
    <div class="card p-4">
        <h2 class="text-center mb-3">Libro de Temas</h2>
        <h5 class="text-center text-muted mb-4">
            Profesor: <?= htmlspecialchars($profesor->getNombre()) ?> <?= htmlspecialchars($profesor->getApellido()) ?>
        </h5>
        <p class="text-center text-primary fw-bold">
            Curso: <?= htmlspecialchars("{$anio}° {$division} - {$materia} ({$anioLectivo})") ?>
        </p>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">✅ Tema agregado correctamente.</div>
        <?php elseif (isset($_GET['deleted'])): ?>
            <div class="alert alert-success">🗑️ Tema eliminado correctamente.</div>
        <?php endif; ?>

        <!-- 📝 Formulario nuevo tema -->
        <form method="POST" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Materia:</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($materia) ?>" readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label">Modalidad:</label>
                <select name="idtipoClase" class="form-select">
                    <option value="">(Sin modalidad)</option>
                    <?php foreach ($tipos as $t): ?>
                        <option value="<?= $t['idtipoClase'] ?>"><?= htmlspecialchars($t['tipoClase']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Número de clase:</label>
                <input type="number" name="numero_clase" min="1" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Título:</label>
                <input type="text" name="titulo" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Fecha:</label>
                <input type="date" name="fecha" class="form-control" value="<?= date('Y-m-d') ?>">
            </div>

            <div class="col-12">
                <label class="form-label">Descripción:</label>
                <textarea name="descripcion" class="form-control" rows="3"></textarea>
            </div>

            <div class="col-12 form-check">
                <input type="checkbox" name="firma_profesor" class="form-check-input" id="firma_profesor">
                <label for="firma_profesor" class="form-check-label">Firma del profesor</label>
            </div>

            <div class="col-12 text-end">
                <button class="btn btn-primary"><i class="bi bi-plus-circle"></i> Agregar Tema</button>
            </div>
        </form>
    </div>

    <!-- 📂 Listado de temas con colapsable -->
    <div class="card p-4 mt-4">
        <h4 class="mb-3 text-center">
            <a class="text-decoration-none" data-bs-toggle="collapse" href="#listaTemas" role="button" aria-expanded="false" aria-controls="listaTemas">
                <i class="bi bi-list-ul"></i> Listado de Temas <i class="bi bi-chevron-down"></i>
            </a>
        </h4>

        <div class="collapse show" id="listaTemas">
            <table class="table table-striped text-center">
                <thead>
                    <tr>
                        <th>ID</th><th>Título</th><th>N° Clase</th><th>Fecha</th>
                        <th>Firma Prof.</th><th>Autoridad</th><th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($temas as $f): ?>
                    <tr>
                        <td><?= $f->getId() ?></td>
                        <td><?= htmlspecialchars($f->getTitulo()) ?></td>
                        <td><?= $f->getNumeroClase() ?? '—' ?></td>
                        <td><?= $f->getFecha() ?></td>
                        <td><?= $f->isFirmaProfesor() ? '✅' : '❌' ?></td>
                        <td>
                            <input type="checkbox" class="form-check-input firmaAutoridad" data-id="<?= $f->getId() ?>" <?= $f->isFirmaAutoridad() ? 'checked' : '' ?>>
                        </td>
                        <td>
                            <a href="?eliminar=<?= $f->getId() ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este tema?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.firmaAutoridad').forEach(chk => {
    chk.addEventListener('change', () => {
        fetch('', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `accion=actualizar_firma&id_tema=${chk.dataset.id}&estado=${chk.checked ? 1 : 0}`
        });
    });
});
</script>
</body>
</html>
