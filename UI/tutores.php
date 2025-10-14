<?php
$conexion = new mysqli("localhost", "root", "2901", "control");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$sql = "SELECT * FROM tutores";
$resultado = $conexion->query($sql);

// Supongamos que $faltas es el número de faltas del alumno
$faltas = 4; // Podés cambiar esto dinámicamente según el alumno
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutores</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 30px;
        }
        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .alerta {
            width: 80%;
            margin: 20px auto;
            background-color: #ffcccc;
            color: #a00;
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            box-shadow: 0 2px 10px rgba(255, 0, 0, 0.2);
        }
    </style>
</head>
<body>

<?php if ($faltas > 3): ?>
    <div class="alerta">
        ⚠️ Si el alumno tiene más de 3 faltas. Se notificará a los tutores.
    </div>
<?php endif; ?>
<h2>  <a href="alumno.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Volver
                </a></h2>
<h2 style="text-align:center;">Lista de Tutores</h2>

<table>
    <tr>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>DNI</th>
        <th>Email</th>
        <th>Teléfono</th>
    </tr>

    <?php while($fila = $resultado->fetch_assoc()): ?>
    <tr>
        <td><?= $fila['Nombre'] ?></td>
        <td><?= $fila['Apellido'] ?></td>
        <td><?= $fila['Dni'] ?></td>
        <td><?= $fila['Email'] ?></td>
        <td><?= $fila['Telefono'] ?></td>
    </tr>
    <?php endwhile; ?>

</table>

</body>
</html>
