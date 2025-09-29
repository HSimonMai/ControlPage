<?php

session_start();


// ---- 1) Obtener el dato de sesión de forma segura ----
$sessionKey = null;
if (isset($_SESSION['usuario'])) {
    $sessionKey = 'usuario';
} elseif (isset($_SESSION['usuarios'])) { 
    $sessionKey = 'usuarios';
} else {
    header("Location: login.php");
    exit;
}
    
require_once("../Entidades/Usuario.php");

// Puede venir serializado (string) o ya como objeto
$raw = $_SESSION[$sessionKey];
$usuarioObj = is_string($raw) ? @unserialize($raw) : $raw;

// Validación mínima
if (!$usuarioObj || !is_object($usuarioObj)) {
    session_unset();
    session_destroy();
    header("Location: login.php?e=sess");
    exit;
}

// ---- 2) Extraer datos del usuario ----
$nombre = method_exists($usuarioObj, 'getNombre') ? $usuarioObj->getNombre() : '';
$apellido = method_exists($usuarioObj, 'getApellido') ? $usuarioObj->getApellido() : '';
$idTipo = (int)(method_exists($usuarioObj, 'getIdTiposUsuarios') ? $usuarioObj->getIdTiposUsuarios() : 0);


?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Seleccionar Perfil</title>
  <link rel="stylesheet" href="css/selecPerfil.css">
</head>
<body>
  <h2>Bienvenido/a, <?php echo htmlspecialchars(trim("$nombre $apellido")); ?></h2>
  <p>Selecciona el perfil con el que deseas ingresar:</p>

  <?php if ($idTipo === 1): ?>
      <a href="preceptor.php"><button>Ingresar como Preceptor</button></a>

  <?php elseif ($idTipo === 2): ?>
      <a href="SuperPreceptor.php"><button>Ingresar como Super Preceptor</button></a>
      <br><br>
      <a href="preceptor.php"><button>Ingresar como Preceptor</button></a>

  <?php elseif ($idTipo === 3): ?>
      <a href="administrador.php"><button>Ingresar como Administrador</button></a>

  <?php elseif ($idTipo === 4): ?>
      <a href="profesor.php"><button>Ingresar como Profesor</button></a>

  <?php elseif ($idTipo === 5): ?>
      <a href="alumno.php"><button>Ingresar como Alumno</button></a>

  <?php else: ?>
      <p>No tienes un rol asignado.</p>
  <?php endif; ?>

  <br><br>
  <a href="login.php"><button>Cerrar Sesión</button></a>
</body>
</html>
