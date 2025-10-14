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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Alumno</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/alumno.css">
</head>
<body>

    <div class="container">
        <div class="main-card">
            <!-- Sección de bienvenida -->
            <div class="welcome-section">
                <h2>
                    <i class="fas fa-user-graduate me-3"></i>
                    Bienvenido, <?= htmlspecialchars($nombre . ' ' . $apellido) ?>!
                </h2>
                <p>Este es tu panel personal de alumno. Accede a tus notas, asistencias y más.</p>
            </div>

            <!-- Sección de menú -->
            <div class="menu-section">
                <h3 class="menu-title">
                    <i class="fas fa-list me-2"></i>Menú Principal
                </h3>
                <ul class="menu-list">
                    <li>
                        <a href="notas.php" class="menu-item">
                            <i class="fa-solid fa-graduation-cap"></i>
                            Notas
                        </a>
                    </li>
                    <li>
                        <a href="progreso.php" class="menu-item">
                            <i class="fa-solid fa-clipboard-user"></i>
                            Progreso Academico
                        </a>
                    </li>
                    <li>
                        <a href="tutores.php"" class="menu-item">
                            <i class="fa-solid fa-address-card"></i>
                            Contacto Con El Tutor
                        </a>
                    </li>
                    <li>
                        <a href="profesor.php" class="menu-item">
                            <i class="fa-solid fa-chalkboard-user"></i>
                            Ir a Perfil del Profesor
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Sección de logout -->
            <div class="logout-section">
                <a href="login.php" class="btn-logout">
                    <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
