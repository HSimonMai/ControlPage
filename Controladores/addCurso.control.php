<?php
require_once("../Entidades/Cursos.php");
require_once("../BLL/CursoBLL.php");

$id=0;

if(isset($_POST["ano"]) && !empty($_POST["ano"]))
{
    $ano= (int) $_POST["ano"];
}

if(isset($_POST["division"]) && !empty($_POST["division"]))
{
    $division= (string) $_POST["division"];

}

if(isset($_POST["idPreceptor"]) && !empty($_POST["idPreceptor"]))
{
    $idPreceptor= (int) $_POST["idPreceptor"];
}

$curso= new Cursos(
    $id,
    $ano,
    $division,
    $idPreceptor
);

$cursoBLL= new CursoBLL();
$resultado= $cursoBLL->GrabarCurso($curso);

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;