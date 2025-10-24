<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar sesión
if (!isset($_SESSION["idProfesor"])) {
    header("Location: login.php");
    exit;
}

$idProfesor = $_SESSION["idProfesor"];

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "2901", "control");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener nombre del profesor
$stmt = $conexion->prepare("SELECT nombre FROM profesores WHERE idProfesores = ?");
$stmt->bind_param("i", $idProfesor);
$stmt->execute();
$stmt->bind_result($nombreProfesor);
$stmt->fetch();
$stmt->close();

//Firma del Jefe de área
if (isset($_POST['accion']) && $_POST['accion'] === 'actualizar_firma') {
    $id_tema = intval($_POST['id_tema']);
    $nuevo_estado = intval($_POST['estado']);

    $stmt = $conexion->prepare("UPDATE temas SET firma_autoridad = ? WHERE id_tema = ?");
    $stmt->bind_param("ii", $nuevo_estado, $id_tema);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}

// Eliminar Tema
if (isset($_GET["eliminar"])) {
    $id_tema = intval($_GET["eliminar"]);
    $stmt = $conexion->prepare("DELETE FROM temas WHERE id_tema = ?");
    $stmt->bind_param("i", $id_tema);

    if ($stmt->execute()) {
        header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?deleted=1");
        exit;
    } else {
        header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?error=" . urlencode($stmt->error));
        exit;
    }
}

//Agregar Nuevo Tema
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['titulo'])) {
    $id_profesor = intval($_POST["id_profesor"]);
    $idtipoClase = !empty($_POST["idtipoClase"]) ? intval($_POST["idtipoClase"]) : null;
    $numero_clase = !empty($_POST["numero_clase"]) ? intval($_POST["numero_clase"]) : null;
    $titulo = trim($_POST["titulo"]);
    $descripcion = trim($_POST["descripcion"]);
    $fecha = $_POST["fecha"];
    $firma_profesor = isset($_POST["firma_profesor"]) ? 1 : 0;
    $firma_autoridad = 0;

    if (!empty($id_profesor) && !empty($titulo) && !empty($fecha)) {
        $stmt = $conexion->prepare("
            INSERT INTO temas (id_profesor, idtipoClase, numero_clase, titulo, descripcion, fecha, firma_profesor, firma_autoridad)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iiisssii", $id_profesor, $idtipoClase, $numero_clase, $titulo, $descripcion, $fecha, $firma_profesor, $firma_autoridad);
        $stmt->execute();
        header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?success=1");
        exit;
    } else {
        header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?error=" . urlencode("Complete los campos requeridos"));
        exit;
    }
}

// Tipos de clase
$tipos = $conexion->query("SELECT idtipoClase, tipoClase FROM tipoclase ORDER BY tipoClase");

// Listado de temas
$temas = $conexion->query("
    SELECT t.id_tema, t.titulo, t.descripcion, t.fecha, t.numero_clase,
        t.firma_profesor, t.firma_autoridad,
        p.nombre AS profesor, tc.tipoClase AS modalidad
    FROM temas t
    INNER JOIN profesores p ON t.id_profesor = p.idProfesores
    LEFT JOIN tipoclase tc ON t.idtipoClase = tc.idtipoClase
    WHERE t.id_profesor = $idProfesor
    ORDER BY t.id_tema DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Libro de Temas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="css/temas.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark">
<div class="container-fluid">
    <span class="navbar-brand">Libro De Temas</span>
    <div>
        <a href="profesor.php" class="btn btn-outline-light"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>
</nav>

<div class="container py-4">
    <div class="card p-4">
        <h2 class="mb-2 text-center"><i class="bi bi-journal-text"></i> Libro de Temas</h2>
        <h5 class="text-center text-muted mb-4">Profesor: <?= htmlspecialchars($nombreProfesor) ?></h5>

        <!-- Mensajes -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">✅ Tema agregado correctamente.</div>
        <?php elseif (isset($_GET['deleted'])): ?>
            <div class="alert alert-success">🗑️ Tema eliminado correctamente.</div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-danger">❌ <?= htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <!-- FORMULARIO -->
        <form method="POST" class="row g-3">
            <input type="hidden" name="id_profesor" value="<?= $idProfesor ?>">

            <div class="col-md-4">
                <label class="form-label">Profesor:</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($nombreProfesor) ?>" readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label">Modalidad:</label>
                <select name="idtipoClase" class="form-select">
                    <option value="">(Sin modalidad)</option>
                    <?php while ($t = $tipos->fetch_assoc()): ?>
                        <option value="<?= $t['idtipoClase'] ?>"><?= htmlspecialchars($t['tipoClase']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Número de clase:</label>
                <input type="number" name="numero_clase" min="1" class="form-control" placeholder="Ej: 1, 2, 3...">
            </div>

            <div class="col-md-6">
                <label class="form-label">Título:</label>
                <input type="text" name="titulo" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Fecha:</label>
                <input type="date" name="fecha" class="form-control" required value="<?= date('Y-m-d') ?>">
            </div>

            <div class="col-12">
                <label class="form-label">Descripción:</label>
                <textarea name="descripcion" class="form-control" rows="3" placeholder="Descripción del tema..."></textarea>
            </div>

            <div class="col-12 form-check">
                <input type="checkbox" name="firma_profesor" id="firma_profesor" class="form-check-input">
                <label for="firma_profesor" class="form-check-label">Firma del profesor</label>
            </div>

            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Agregar Tema
                </button>
            </div>
        </form>
    </div>

    <!-- LISTADO DESPLEGABLE -->
    <div class="card p-4 mt-4">
        <h3 class="mb-3 text-center">
            <button class="btn btn-link text-decoration-none text-dark fw-bold" 
                    data-bs-toggle="collapse" data-bs-target="#listadoTemas" 
                    aria-expanded="false" aria-controls="listadoTemas">
                <i class="bi bi-caret-down-square"></i> Listado de Temas
            </button>
        </h3>

        <div class="collapse" id="listadoTemas">
            <table class="table table-striped table-hover align-middle text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Profesor</th>
                        <th>Modalidad</th>
                        <th>N° Clase</th>
                        <th>Título</th>
                        <th>Fecha</th>
                        <th>Firma Prof.</th>
                        <th>Firma Autoridad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($f = $temas->fetch_assoc()): ?>
                    <tr>
                        <td><?= $f['id_tema'] ?></td>
                        <td><?= htmlspecialchars($f['profesor']) ?></td>
                        <td><?= htmlspecialchars($f['modalidad'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($f['numero_clase'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($f['titulo']) ?></td>
                        <td><?= htmlspecialchars($f['fecha']) ?></td>
                        <td><?= $f['firma_profesor'] ? '✅' : '⬜' ?></td>
                        <td>
                            <input type="checkbox" class="form-check-input firmaAutoridad"
                                data-id="<?= $f['id_tema'] ?>"
                                <?= $f['firma_autoridad'] ? 'checked' : '' ?>>
                        </td>
                        <td>
                            <a href="?eliminar=<?= $f['id_tema'] ?>" 
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('¿Eliminar este tema?')">
                            <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Cambiar firma de autoridad (CheckBox)
document.querySelectorAll('.firmaAutoridad').forEach(chk => {
    chk.addEventListener('change', () => {
        const id_tema = chk.dataset.id;
        const estado = chk.checked ? 1 : 0;

        fetch('', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `accion=actualizar_firma&id_tema=${id_tema}&estado=${estado}`
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conexion->close(); ?>
