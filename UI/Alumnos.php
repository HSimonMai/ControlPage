<?php
session_start();
require_once("../Entidades/Usuario.php");
require_once("../BLL/CursoBLL.php");
require_once("../BLL/AlumnoBLL.php");
require_once("../BLL/TutorBLL.php");

require_once("../UI/components/layout.template.php");
require_once("../UI/components/navbar.template.php");
require_once("../UI/components/mainAlumno.php");

// Verificar si el usuario está autenticado y tiene el rol adecuado
$usuario = unserialize($_SESSION["usuario"]);
$idUsuario = (int) $usuario->getIdTiposUsuarios();
if ($idUsuario === 1 && $idUsuario === 2) {
    header('Location: ../UI/login.php');
    exit();
}



$navbar= new Navbar_template($usuario);
$layout= new Layout_template($navbar);




$listaAlumnos= AlumnoBLL::listaAlumnos();

$listaCursos= CursoBLL::getAllCursos();

$listaTutores= TutorBLL::getAllTutor();
$layout->render();

$main= new Main_template($listaAlumnos, $listaTutores);
$main->render()

?>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


