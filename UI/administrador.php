<?php
session_start();
require_once("../Entidades/Usuario.php");
require_once("../BLL/CursoBLL.php");
require_once("../BLL/UsuariosBLL.php");
require_once("../BLL/TiposUsuariosBLL.php");
require_once("../UI/components/layout.template.php");
require_once("../UI/components/navbar.template.php");
require_once("../UI/components/mainAdministrativo.template.php");


// Verificar si el usuario está autenticado y tiene el rol adecuado
$usuario = unserialize($_SESSION["usuario"]);
$idUsuario = (int) $usuario->getIdTiposUsuarios();
if ($idUsuario === 1 || $idUsuario === 2) {
    header('Location: ../UI/login.php');
    exit();
}

$navbar = new Navbar_template($usuario);
$layout = new Layout_template(navbar: $navbar);

$usuariosBLL = new UsuariosBLL();
$listaUsuarios = $usuariosBLL->ListaAlumnos();
$tiposBLL = new TiposUsuariosBLL();
$listaTipos = $tiposBLL->ListaTiposUsuarios();
$main = new Main_template($listaUsuarios, $listaTipos);

$layout->render();
$main->render();

?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<!-- //Guardar en el objeto usuario para iniciar sesion -->