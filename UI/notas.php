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
    <style>
        /* Reset y base */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: #333;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 30px;
        }
        
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 2.5em;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        h3 {
            color: #34495e;
            margin: 25px 0 15px 0;
            font-size: 1.4em;
            border-bottom: 2px solid #3498db;
            padding-bottom: 8px;
        }
        
        /* Formulario de agregar */
        .form-agregar {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #3498db;
        }
        
        .form-agregar .inline {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: end;
            justify-content: center;
        }
        
        .form-agregar select,
        .form-agregar input[type="text"],
        .form-agregar input[type="number"] {
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 1em;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            min-width: 150px;
        }
        
        .form-agregar select:focus,
        .form-agregar input[type="text"]:focus,
        .form-agregar input[type="number"]:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        .form-agregar button {
            padding: 12px 20px;
            border: none;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            cursor: pointer;
            border-radius: 6px;
            font-size: 1em;
            font-weight: 600;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            white-space: nowrap;
        }
        
        .form-agregar button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }
        
        .form-agregar button:active {
            transform: translateY(0);
        }
        
        /* Tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            margin-top: 10px;
        }
        
        th {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9em;
        }
        
        td {
            padding: 15px 12px;
            border-bottom: 1px solid #ecf0f1;
            vertical-align: middle;
        }
        
        tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        tr:hover {
            background: #e8f5e8;
            transition: background 0.2s ease;
        }
        
        /* Formularios en tabla */
        form.inline {
            display: contents; /* Permite que los elementos fluyan en la tabla */
        }
        
        input[type="number"],
        input[type="text"] {
            padding: 8px 10px;
            border: 1.5px solid #bdc3c7;
            border-radius: 4px;
            font-size: 0.95em;
            width: 100%;
            transition: border-color 0.3s ease;
        }
        
        input[type="number"]:focus,
        input[type="text"]:focus {
            outline: none;
            border-color: #27ae60;
            box-shadow: 0 0 0 2px rgba(39, 174, 96, 0.1);
        }
        
        /* Botones en tabla */
        button {
            padding: 8px 15px;
            border: none;
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            cursor: pointer;
            border-radius: 4px;
            font-size: 0.9em;
            font-weight: 500;
            transition: all 0.2s ease;
            min-width: 80px;
        }
        
        button:hover {
            background: linear-gradient(135deg, #229954 0%, #27ae60 100%);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(39, 174, 96, 0.3);
        }
        
        /* Mensajes de error */
        .error {
            color: #e74c3c;
            background: #fdf2f2;
            border: 1px solid #e74c3c;
            padding: 10px 15px;
            border-radius: 6px;
            margin: 10px 0;
            font-weight: 500;
        }
        
        /* Responsivo */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .container {
                padding: 20px;
                margin: 10px;
            }
            
            h1 {
                font-size: 2em;
            }
            
            .form-agregar .inline {
                flex-direction: column;
                align-items: stretch;
            }
            
            .form-agregar select,
            .form-agregar input[type="text"],
            .form-agregar input[type="number"] {
                min-width: auto;
            }
            
            table {
                font-size: 0.9em;
            }
            
            th, td {
                padding: 10px 8px;
            }
        }
        
        /* No hay notas */
        .no-notas {
            text-align: center;
            color: #7f8c8d;
            font-style: italic;
            padding: 40px;
        }
    </style>
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
