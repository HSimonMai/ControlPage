<?php
// Mostrar errores (para depuración)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
$host = "localhost";
$user = "root";
$pass = "2901";
$db = "control";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consulta SQL: obtenemos las asistencias
$sql = "SELECT idAsistencias, FechaAsistencia, ValorAsistencia, idAlumnos, idtipoClase FROM asistencias LIMIT 15";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Asistencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #a7c7e7;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .table thead {
            background-color: #007bff;
            color: white;
        }
        .attendance-present {
            color: #198754;
            font-weight: 600;
        }
        .attendance-absent {
            color: #dc3545;
            font-weight: 600;
        }
        .no-data {
            color: #6c757d;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-brand"><i class="bi bi-calendar-check"></i> Administrador</span>
    <div>
      <a href="profesor.php" class="btn btn-outline-light">
        <i class="bi bi-arrow-left"></i> Volver
      </a>
    </div>
  </div>
</nav>

<!-- CONTENIDO -->
<div class="container py-4">
  <div class="card p-4">
    <h2 class="text-center mb-4"><i class="bi bi-check2-square"></i> Listado de Asistencias</h2>

    <table class="table table-striped table-hover align-middle text-center">
      <thead>
        <tr>
          <th>ID Asistencia</th>
          <th>Fecha</th>
          <th>Valor Asistencia</th>
          <th>ID Alumno</th>
          <th>ID Tipo Clase</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <?php 
              $valor = htmlspecialchars($row['ValorAsistencia']);
              $esPresente = ($valor == 1 || strtolower($valor) == 'presente' || strtolower($valor) == 'si');
              $claseValor = $esPresente ? 'attendance-present' : 'attendance-absent';
              $icono = $esPresente ? '<i class="bi bi-check-circle-fill"></i>' : '<i class="bi bi-x-circle-fill"></i>';
              $textoValor = $esPresente ? 'Presente' : 'Ausente';
            ?>
            <tr>
              <td class="fw-bold"><?= htmlspecialchars($row['idAsistencias']) ?></td>
              <td><?= date('d/m/Y', strtotime(htmlspecialchars($row['FechaAsistencia']))) ?></td>
              <td class="<?= $claseValor ?>"><?= $icono ?> <?= $textoValor ?></td>
              <td><?= htmlspecialchars($row['idAlumnos']) ?></td>
              <td><?= htmlspecialchars($row['idtipoClase']) ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" class="no-data text-center p-4">
              <i class="bi bi-calendar-x display-5 d-block mb-2"></i>
              No hay asistencias registradas aún.
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

<?php
$conn->close();
?>
