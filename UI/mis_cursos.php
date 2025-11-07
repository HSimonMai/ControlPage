<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . "/../BLL/CursoBLL.php";

// Verificar sesión activa
if (!isset($_SESSION["idProfesor"])) {
    header("Location: login.php");
    exit;
}

$idProfesor = $_SESSION["idProfesor"];
$nombreProfesor = $_SESSION["nombreProfesor"] ?? "Profesor";

// Obtener cursos del profesor desde la BLL
$cursos = CursoBLL::obtenerCursosYAsignaturasPorProfesor($idProfesor);

// Agrupar por año
$divisiones = [];

foreach ($cursos as $curso) {
    $anio = $curso["Año"];
    $link = '<a class="dropdown-item" href="profesor.php?idCurso=' . $curso["idCursos"] . '">' .
            $curso["Año"] . '° ' . $curso["Division"] . ' (' . $curso["asignatura"] . ')</a>';


    if (!isset($divisiones[$anio])) {
        $divisiones[$anio] = [];
    }

    $divisiones[$anio][] = $link;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Cursos</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/preceptor.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top justify-content-center">
        <div class="container-fluid">
            <span class="navbar-brand mx-auto">Bienvenido, <?php echo htmlspecialchars($nombreProfesor); ?></span>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <?php if (!empty($divisiones)): ?>
                        <?php foreach ($divisiones as $anio => $cursosPorAnio): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="dropdown<?php echo $anio; ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php echo $anio; ?>° División
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdown<?php echo $anio; ?>">
                                    <?php foreach ($cursosPorAnio as $cursoLink): ?>
                                        <li><?php echo $cursoLink; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="nav-item"><span class="nav-link disabled">No hay cursos asignados</span></li>
                    <?php endif; ?>
                </ul>

                <form method="POST" action="../Controladores/logout.control.php" class="d-flex ms-auto">
                    <button type="submit" class="btn btn-outline-danger">Cerrar sesión</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-5 pt-5">
        <div class="text-center mt-5">
            <h2>Seleccioná un curso para continuar</h2>
            <p class="text-muted">Podés elegirlo desde la barra de navegación superior</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
