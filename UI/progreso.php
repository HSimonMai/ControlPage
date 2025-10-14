<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conexion = new mysqli("localhost", "root", "2901", "control");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener todos los alumnos
$alumnos = $conexion->query("SELECT idAlumnos, nombre, apellido FROM alumnos");

// Verificar si se seleccionó un alumno
$id_alumno = isset($_POST['id_alumno']) ? intval($_POST['id_alumno']) : 0;
$promedio = $aprobadas = $desaprobadas = 0;

if ($id_alumno > 0) {
    // Calcular promedio y materias aprobadas/desaprobadas desde la tabla notas
    $sql = "SELECT 
                ROUND(AVG(nota), 2) AS promedio_general,
                SUM(CASE WHEN nota >= 6 THEN 1 ELSE 0 END) AS aprobadas,
                SUM(CASE WHEN nota < 6 THEN 1 ELSE 0 END) AS desaprobadas
            FROM notas
            WHERE id_alumno = $id_alumno";

    $resultado = $conexion->query($sql);
    $datos = $resultado->fetch_assoc();

    $promedio = $datos['promedio_general'] ?? 0;
    $aprobadas = $datos['aprobadas'] ?? 0;
    $desaprobadas = $datos['desaprobadas'] ?? 0;

    // Insertar o actualizar el progreso
    $check = $conexion->query("SELECT id_progreso FROM progreso_academico WHERE id_alumno = $id_alumno");

    if ($check->num_rows > 0) {
        $conexion->query("UPDATE progreso_academico 
                          SET promedio_general = $promedio,
                              materias_aprobadas = $aprobadas,
                              materias_desaprobadas = $desaprobadas,
                              fecha_actualizacion = CURRENT_TIMESTAMP
                          WHERE id_alumno = $id_alumno");
    } else {
        $conexion->query("INSERT INTO progreso_academico (id_alumno, promedio_general, materias_aprobadas, materias_desaprobadas)
                          VALUES ($id_alumno, $promedio, $aprobadas, $desaprobadas)");
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Progreso Académico</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background: #f4f6f8;
        }
        .container {
            max-width: 500px;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            margin: auto;
        }
        h2 { text-align: center; }
        select, button {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        button {
            background: #007bff;
            color: white;
            cursor: pointer;
        }
        button:hover { background: #0056b3; }
        .barra {
            width: 100%;
            height: 25px;
            background: #ddd;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 10px;
        }
        .barra-progreso {
            height: 25px;
            background: #007bff;
            width: <?php echo ($promedio * 10); ?>%;}
        .resultado {
            margin-top: 15px;
            text-align: center;
        }
        .mensaje {
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
  <a href="alumno.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
<div class="container">
    <h2>📚 Progreso Académico</h2>

    <form method="POST">
        <label for="id_alumno">Seleccionar Alumno:</label>
        <select name="id_alumno" id="id_alumno" required>
            <option value="">-- Elegir alumno --</option>
            <?php while ($fila = $alumnos->fetch_assoc()) { ?>
                <option value="<?php echo $fila['idAlumnos']; ?>" 
                    <?php if ($fila['idAlumnos'] == $id_alumno) echo 'selected'; ?>>
                    <?php echo $fila['apellido'] . ", " . $fila['nombre']; ?>
                </option>
            <?php } ?>
        </select>
        <button type="submit">Ver Progreso</button>
    </form>

    <?php if ($id_alumno > 0) { ?>
        <div class="resultado">
            <div class="barra">
                <div class="barra-progreso"></div>
            </div>
            <p><strong>Promedio general:</strong> <?php echo $promedio; ?></p>
            <p><strong>Materias aprobadas:</strong> <?php echo $aprobadas; ?></p>
            <p><strong>Materias desaprobadas:</strong> <?php echo $desaprobadas; ?></p>
            <p><strong>Última actualización:</strong> <?php echo date("d/m/Y H:i"); ?></p>

            <div class="mensaje">
                <?php
                if ($promedio >= 8) {
                    echo "<span style='color:green;'>Excelente desempeño ✅</span>";
                } elseif ($promedio >= 6) {
                    echo "<span style='color:orange;'>Buen desempeño 👍</span>";
                } else {
                    echo "<span style='color:red;'>Necesita mejorar ⚠️</span>";
                }
                ?>
            </div>
        </div>
    <?php } ?>
</div>

</body>
</html>
