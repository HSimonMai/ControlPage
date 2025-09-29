<?php
require_once("../Entidades/Usuario.php");
require_once("../BLL/UsuariosBLL.php");


if (isset($_POST["nombre"])) {
    $nombre = (string) $_POST["nombre"];
}

if (isset($_POST["apellido"])) {
    $apellido = (string) $_POST["apellido"];
}

if (isset($_POST["email"])) {
    $email = (string) $_POST["email"];
}

if (isset($_POST["dni"])) {
    $dni = (string) $_POST["dni"];
}

if (isset($_POST["contrasena"])) {
    $contrasena = (string) $_POST["contrasena"];
}


if (isset($_POST["idTipo"])) {
    $idTipoUsuario = (int) $_POST["idTipo"];
}
$id= 0;
$usuario = new Usuario(
    $id,
    $dni,
    $email,
    $contrasena,
    $nombre,
    $apellido,
    $idTipoUsuario
);
$usuarioBLL = new UsuariosBLL();

$añadir = $usuarioBLL->GrabarUsuario($usuario);
header('Location: ../UI/administrador.php');
