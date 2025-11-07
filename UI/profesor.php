<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../BLL/AlumnoBLL.php");

// Verificar sesión de profesor
if (!isset($_SESSION["idProfesor"])) {
    header("Location: login.php");
    exit;
}

// Verificar si se seleccionó un curso
if (isset($_GET["idCurso"])) {
    $idCurso = (int) $_GET["idCurso"];

    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "2901", "control");
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    $sql = "
        SELECT 
            c.idCursos,
            c.Año,
            c.Division,
            pc.asignatura,
            pc.año_lectivo
        FROM profesor_curso pc
        INNER JOIN cursos c ON pc.curso_id = c.idCursos
        WHERE pc.curso_id = ? AND pc.profesor_id = ?
    ";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $idCurso, $_SESSION["idProfesor"]);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $curso = $resultado->fetch_assoc();

    if ($curso) {
        $_SESSION["cursoSeleccionado"] = $curso;
        $_SESSION["idCursoSeleccionado"] = $idCurso; // Guardar ID para BLL
    } else {
        header("Location: mis_cursos.php");
        exit;
    }
}

// Recuperar curso de la sesión
$cursoSeleccionado = $_SESSION["cursoSeleccionado"] ?? null;
if (!$cursoSeleccionado) {
    header("Location: mis_cursos.php");
    exit;
}

$nombreProfesor = $_SESSION["nombreProfesor"] ?? "Profesor";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Profesor - <?= htmlspecialchars($cursoSeleccionado["asignatura"]) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #a7c7e7;
            min-height: 100vh;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            background-color: white;
        }
        .menu-section {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .menu-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            background-color: #f8f9fa;
            border-radius: 10px;
            text-decoration: none;
            color: #212529;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .menu-item:hover {
            background-color: #007bff;
            color: white;
            transform: scale(1.03);
        }
        .menu-title {
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand"><i class="bi bi-person-video3"></i> Panel del Profesor</span>
        <div>
            <a href="mis_cursos.php" class="btn btn-outline-light me-2">
                <i class="bi bi-arrow-left"></i> Cambiar Curso
            </a>
            <a href="login.php" class="btn btn-danger">
                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
            </a>
        </div>
    </div>
</nav>

<div class="container py-4">
    <div class="card p-4">
        <!-- Sección de bienvenida -->
        <div class="text-center mb-4">
            <h2>
                <i class="bi bi-person-badge"></i>
                Bienvenido, <?= htmlspecialchars($nombreProfesor) ?>!
            </h2>
            <p>Estás gestionando el curso:
                <strong class="text-primary">
                    <?= htmlspecialchars($cursoSeleccionado["Año"]) ?>° <?= htmlspecialchars($cursoSeleccionado["Division"]) ?> - 
                    <?= htmlspecialchars($cursoSeleccionado["asignatura"]) ?> (<?= htmlspecialchars($cursoSeleccionado["año_lectivo"]) ?>)
                </strong>
            </p>
        </div>

        <!-- Menú vertical -->
        <div class="menu-section mx-auto" style="max-width: 400px;">
            <h3 class="menu-title text-center"><i class="bi bi-list-task"></i> Menú Principal</h3>

            <!-- Botón para ver lista de alumnos -->
            <a href="listaAlumnos.php" class="menu-item">
                <i class="bi bi-people"></i> Ver lista de alumnos
            </a>

            <!-- Mantener los demás botones como estaban, sin funcionalidad aún -->
            <a href="temas.php" class="menu-item">
                <i class="bi bi-book"></i> Libro de Temas
            </a>

            <a href="tutores.php" class="menu-item">
                <i class="bi bi-person-lines-fill"></i> Contacto con el Tutor
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
