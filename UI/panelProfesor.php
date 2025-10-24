<?php

session_start();

if (!isset($_SESSION['profesor_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_POST['curso_id']) && !isset($_SESSION['curso_id'])) {
    header("Location: seleccionar_curso.php");
    exit();
}

if (isset($_POST['curso_id'])) {
    $_SESSION['curso_id'] = $_POST['curso_id']; // guardamos el curso seleccionado
}

$curso_id = $_SESSION['curso_id'];

if (!isset($_SESSION['profesor_id'])) {
    header("Location: login.php");
    exit();
}

$conexion = new mysqli("localhost", "root", "2901", "control");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$profesor_id = $_SESSION['profesor_id'];

$sql = "SELECT c.idProfesorCurso, c.curso_id, c.asignatura, c.año_lectivo
        FROM profesorcurso c
        WHERE c.profesor_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $profesor_id);
$stmt->execute();
$resultado = $stmt->get_result();

echo "<h2>Bienvenido, " . htmlspecialchars($_SESSION['nombre_profesor']) . "</h2>";
echo "<h3>Mis cursos:</h3>";

if ($resultado->num_rows > 0) {
    echo "<table border='1' cellpadding='6' cellspacing='0'>";
    echo "<tr><th>ID Curso</th><th>Asignatura</th><th>Año lectivo</th></tr>";
    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($fila['curso_id']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['asignatura']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['año_lectivo']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No tenés cursos asignados todavía.</p>";
}

$stmt->close();
$conexion->close();
?>
