<?php
require_once("../BLL/UsuariosBLL.php");
require_once("../Entidades/Usuario.php");



if(isset($_POST["id"]))
{
    $id= $_POST["id"];
}
if(isset($_POST["nombre"]))
{
    $nombre= $_POST["nombre"];
}

if($_POST["apellido"])
{
    $apellido= $_POST["apellido"];
}

if($_POST["email"])
{
    $email= $_POST["email"];
}

if($_POST["contrasena"])
{
    $contrasena= $_POST["contrasena"];
}

if($_POST["dni"])
{
    $dni= $_POST["dni"];
}


if (isset($_POST["idTipo"])) {
    $idTipoUsuario = (int) $_POST["idTipo"];
}

$usuario= new Usuario(
    $id,
    $dni,
    $email,
    $contrasena,
    $nombre,
    $apellido,
    $idTipoUsuario
);

$usuarioBLL= new UsuariosBLL();
$update= $usuarioBLL->UpdateUsuario($usuario);

    header('Location: ../UI/administrador.php');
