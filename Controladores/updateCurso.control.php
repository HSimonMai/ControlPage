<?php
require_once("../Entidades/Cursos.php");
require_once("../BLL/CursoBLL.php");

if(isset($_POST["idCurso"]) && !empty($_POST["idCurso"]))
{
    $idCurso= (int) $_POST["idCurso"];
}
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
    $idCurso,
    $ano,
    $division,
    $idPreceptor
);

$cursoBLL= new CursoBLL();
$resultado= $cursoBLL->UpdateCurso($curso);

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;