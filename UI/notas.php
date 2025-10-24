<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Opcional: evita que las warnings se muestren en HTML durante desarrollo.
// En producción es mejor registrar errores en un log en vez de mostrarlos.
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Conexión
$conexion = new mysqli("localhost", "root", "2901", "control");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

/* ───────────── AGREGAR NUEVA NOTA (con prepared statement) ───────────── */
if (isset($_POST['agregar'])) {
    // var_dump($_POST); exit; // --> descomenta para debug (verá la salida raw del formulario)

    // Sanitizar/recuperar
    $id_alumno = isset($_POST['id_alumno']) && $_POST['id_alumno'] !== '' ? (int) $_POST['id_alumno'] : null;
    $materia = isset($_POST['materia']) ? trim($_POST['materia']) : '';
    $nota = isset($_POST['nota']) && $_POST['nota'] !== '' ? (float) $_POST['nota'] : null;
    $observaciones = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : '';

    if ($id_alumno && $materia !== '' && $nota !== null) {
        $stmt = $conexion->prepare("INSERT INTO notas (id_alumno, materia, nota, observaciones, fecha) VALUES (?, ?, ?, ?, CURDATE())");
        if ($stmt) {
            $stmt->bind_param("isds", $id_alumno, $materia, $nota, $observaciones);
            if (!$stmt->execute()) {
                echo "<p class='error'>Error al insertar: " . htmlspecialchars($stmt->error) . "</p>";
            }
            $stmt->close();
        } else {
            echo "<p class='error'>Error prepare: " . htmlspecialchars($conexion->error) . "</p>";
        }
    } else {
        echo "<p class='error'>⚠️ Faltan datos o el alumno no fue seleccionado correctamente.</p>";
    }
}

/* ───────────── ACTUALIZAR NOTA (con prepared statement) ───────────── */
if (isset($_POST['actualizar'])) {
    $id_nota = isset($_POST['id_nota']) ? (int) $_POST['id_nota'] : 0;
    $nota = isset($_POST['nota']) ? (float) $_POST['nota'] : null;
    $observaciones = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : '';

    if ($id_nota > 0 && $nota !== null) {
        $stmt = $conexion->prepare("UPDATE notas SET nota = ?, observaciones = ? WHERE id_nota = ?");
        if ($stmt) {
            $stmt->bind_param("dsi", $nota, $observaciones, $id_nota);
            if (!$stmt->execute()) {
                echo "<p class='error'>Error al actualizar: " . htmlspecialchars($stmt->error) . "</p>";
            }
            $stmt->close();
        } else {
            echo "<p class='error'>Error prepare (update): " . htmlspecialchars($conexion->error) . "</p>";
        }
    }
}

/* ───────────── OBTENER ALUMNOS (cargar en array para mayor control) ───────────── */
$alumnos_result = $conexion->query("SELECT idAlumnos, nombre FROM alumnos");
$alumnos = [];
if ($alumnos_result) {
    while ($r = $alumnos_result->fetch_assoc()) {
        // Sólo almacenar filas válidas que tengan idAlumnos
        if (isset($r['idAlumnos']) && $r['idAlumnos'] !== '') {
            $alumnos[] = $r;
        }
    }
    $alumnos_result->free();
}

/* ───────────── OBTENER NOTAS ───────────── */
$sql = "SELECT n.id_nota, a.nombre AS nombre_alumno, n.materia, n.nota, n.observaciones
        FROM notas n
        JOIN alumnos a ON n.id_alumno = a.idAlumnos
        ORDER BY a.nombre ASC";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas de los alumnos</title>
    <link rel="stylesheet" href="css/notas.css">
</head>
<body>
    <div class="container">
        <h1>📚 Notas de los alumnos</h1>
   <a href="alumno.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
        <h3>Agregar nueva nota</h3>
        <div class="form-agregar">
            <form method="POST" class="inline">
                <select name="id_alumno" required>
                    <option value="">Seleccionar alumno</option>
                    <?php foreach ($alumnos as $a): ?>
                        <?php
                            // seguridad: si no existe idAlumnos o nombre, saltar
                            if (!isset($a['idAlumnos'])) continue;
                            $val = (int)$a['idAlumnos'];
                        ?>
                        <option value="<?= $val ?>"><?= htmlspecialchars($a['nombre'] ?? "Sin nombre") ?></option>
                    <?php endforeach; ?>
                </select>

                <input type="text" name="materia" placeholder="Materia" required>
                <input type="number" step="0.01" min="0" max="10" name="nota" placeholder="Nota (0-10)" required>
                <input type="text" name="observaciones" placeholder="Observaciones">
                <button type="submit" name="agregar">Agregar Nota</button>
            </form>
        </div>

        <h3>Listado de notas</h3>
        <table>
            <tr>
                <th>Alumno</th>
                <th>Materia</th>
                <th>Nota</th>
                <th>Observaciones</th>
                <th>Acción</th>
            </tr>

            <?php if ($resultado && $resultado->num_rows > 0): ?>
                <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($fila['nombre_alumno']) ?></td>
                    <td><?= htmlspecialchars($fila['materia']) ?></td>
                    <td>
                        <form method="POST" class="inline">
                            <input type="number" step="0.01" min="0" max="10" name="nota" value="<?= htmlspecialchars($fila['nota']) ?>">
                            <input type="hidden" name="id_nota" value="<?= (int)$fila['id_nota'] ?>">
                            <button type="submit" name="actualizar">Guardar</button>
                        </form>
                    </td>
                    <td><input type="text" name="observaciones" value="<?= htmlspecialchars($fila['observaciones']) ?>" form="form-<?= (int)$fila['id_nota'] ?>"></td>
                    <!-- Nota: Para observaciones, ajusta el form si es necesario; aquí asumo que se envía con el mismo form -->
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="no-notas">No hay notas cargadas aún. ¡Agrega la primera!</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>
