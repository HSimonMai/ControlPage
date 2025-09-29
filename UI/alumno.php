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
    <title>Panel Alumno</title>
    <link rel="stylesheet" href="css/alumno.css"> <!-- opcional -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body>
    <h2>Bienvenido Alumno Al Perfil Del Alumno</h2>
    <p>Este es el panel de alumno.</p>
    

    <ul>
        <li><a href="asistencia.php"> <i class="fa-solid fa-graduation-cap" /></i> Notas</a></li>
        <li><a href="materiales.php"> </a><i class="fa-solid fa-clipboard-user"></i> Asistencias</li>
        <li><a href="mensajes.php"><i class="fa-solid fa-address-card"></i> Contacto</a></li>
        <li><a href="profesor.php"><i class="fa-solid fa-chalkboard-user"></i> Ir a Perfil Del Profesor</a></li>
    </ul>

    <br>
    <a href="login.php"><button>Cerrar Sesión</button></a>
</body>
</html>
