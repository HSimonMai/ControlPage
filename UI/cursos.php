<?php
session_start();
require_once("../Entidades/Cursos.php");
require_once("../Entidades/Usuario.php");

require_once("../BLL/CursoBLL.php");
require_once("../BLL/UsuariosBLL.php");


require_once("../UI/components/layout.template.php");
require_once("../UI/components/navbar.template.php");

require_once("../UI/components/mainCursos.template.php");

$usuario = unserialize($_SESSION["usuario"]);
$idTipoUsuario = (int) $usuario->getIdTiposUsuarios();
if ($idTipoUsuario === 1 || $idTipoUsuario === 2) {
    header('Location: ../UI/login.php');
}

$navbar=new Navbar_template($usuario);
$layout= new Layout_template($navbar);

$layout->render();


$main= new Main_templateCursos();
$main->render();

?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
