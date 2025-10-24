<?php
// Mostrar errores
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

// Consulta SQL: muestra algunos alumnos
$sql = "SELECT idAlumnos, DNI, Nombre, Apellido, Genero, Nacionalidad, FechaNacimiento, Direccion 
        FROM alumnos 
        LIMIT 10";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Listado de Alumnos</title>
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
    .no-data {
      color: #6c757d;
      font-size: 1.1rem;
    }
    .gender-icon {
      font-size: 1.2rem;
      margin-right: 4px;
    }
    .male {
      color: #007bff;
    }
    .female {
      color: #e83e8c;
    }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-brand"><i class="bi bi-people-fill"></i> Administrador</span>
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
    <h2 class="text-center mb-4"><i class="bi bi-person-lines-fill"></i> Listado de Alumnos</h2>

    <table class="table table-striped table-hover align-middle text-center">
      <thead>
        <tr>
          <th>ID</th>
          <th>DNI</th>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Género</th>
          <th>Nacionalidad</th>
          <th>Fecha de Nacimiento</th>
          <th>Dirección</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <?php 
              $genero = strtolower(htmlspecialchars($row['Genero']));
              $iconoGenero = ($genero === 'masculino' || $genero === 'm') 
                  ? '<i class="bi bi-gender-male male gender-icon"></i>'
                  : (($genero === 'femenino' || $genero === 'f') 
                      ? '<i class="bi bi-gender-female female gender-icon"></i>'
                      : '<i class="bi bi-gender-ambiguous text-secondary gender-icon"></i>');
            ?>
            <tr>
              <td class="fw-bold"><?= htmlspecialchars($row['idAlumnos']) ?></td>
              <td><?= htmlspecialchars($row['DNI']) ?></td>
              <td><?= htmlspecialchars($row['Nombre']) ?></td>
              <td><?= htmlspecialchars($row['Apellido']) ?></td>
              <td><?= $iconoGenero ?> <?= ucfirst($genero) ?></td>
              <td><?= htmlspecialchars($row['Nacionalidad']) ?></td>
              <td><?= date('d/m/Y', strtotime(htmlspecialchars($row['FechaNacimiento']))) ?></td>
              <td><?= htmlspecialchars($row['Direccion']) ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="8" class="no-data text-center p-4">
              <i class="bi bi-person-x display-5 d-block mb-2"></i>
              No hay alumnos registrados aún.
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
    