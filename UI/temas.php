<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$conexion = new mysqli("localhost", "root", "2901", "control");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_profesor = $_POST["id_profesor"];
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $fecha = $_POST["fecha"];

    $sql = "INSERT INTO temas (id_profesor, titulo, descripcion, fecha) 
            VALUES ('$id_profesor', '$titulo', '$descripcion', '$fecha')";

    if ($conexion->query($sql) === TRUE) {
        echo "<p style='color: green;'>Tema agregado correctamente.</p>";
    } else {
        echo "<p style='color: red;'>Error al agregar tema: " . $conexion->error . "</p>";
    }
}

// Obtener profesores para el select
$resultado_profesores = $conexion->query("SELECT idProfesores, nombre FROM profesores");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Tema</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { max-width: 400px; margin: auto; background: #f4f4f4; padding: 20px; border-radius: 10px; }
        label { display: block; margin-top: 10px; }
        input, textarea, select { width: 100%; padding: 8px; margin-top: 5px; }
        button { margin-top: 15px; padding: 10px; width: 100%; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
<h2>  <a href="profesor.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Volver
                </a></h2>
<h2>Agregar nuevo tema</h2>

<form method="POST" action="">
    <label for="id_profesor">Profesor:</label>
    <select name="id_profesor" required>
        <option value="">Seleccionar...</option>
        <?php while ($prof = $resultado_profesores->fetch_assoc()): ?>
            <option value="<?php echo $prof['id_profesor']; ?>">
                <?php echo $prof['nombre']; ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label for="titulo">Título del tema:</label>
    <input type="text" name="titulo" required>

    <label for="descripcion">Descripción:</label>
    <textarea name="descripcion" rows="4"></textarea>

    <label for="fecha">Fecha:</label>
    <input type="date" name="fecha">

    <button type="submit">Agregar Tema</button>
</form>

</body>
</html>
