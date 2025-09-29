<?php
session_start();

// Validamos sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

require_once("../Entidades/Usuario.php");

// Obtenemos usuario de la sesión
$raw = $_SESSION['usuario'];
$usuarioObj = is_string($raw) ? @unserialize($raw) : $raw;

$nombre = method_exists($usuarioObj, 'getNombre') ? $usuarioObj->getNombre() : '';
$apellido = method_exists($usuarioObj, 'getApellido') ? $usuarioObj->getApellido() : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Profesor</title>
    <link rel="stylesheet" href="css/profesor.css"> <!-- opcional -->
</head>
<body>
    <h2>Bienvenido Profesor <?php echo htmlspecialchars($nombre . " " . $apellido); ?></h2>
    <p>Este es tu panel de profesor.</p>

    <ul>
        <li><a href="http://localhost/controlPage-main/UI/Alumnos.php?idCurso=39">📋 Ver lista de alumnos</a></li>
        <li><a href="subirNotas.php">📚 Materias</a></li>
        <li><a href="mensajes.php">🏫 Horarios</a></li>
        <li><a href="alumno.php">🎓 Ver Perfil Del Alumno</a></li>
    </ul>

    <br>
    <a href="login.php"><button>Cerrar Sesión</button></a>
</body>
</html>
