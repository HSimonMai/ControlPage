<?php
require_once(__DIR__."/../BLL/AlumnoBLL.php");
require_once(__DIR__."/../BLL/TutorBLL.php");
require_once(__DIR__."/../BLL/AlumnoBLL.php");
require_once(__DIR__."/../BLL/UsuariosBLL.php");
require_once(__DIR__."/../BLL/CursoBLL.php");


if (isset($_POST["nombre"]) && !empty($_POST["nombre"])) {
    $nombre = $_POST["nombre"];
}

if (isset($_POST["apellido"]) && !empty($_POST["apellido"])) {
    $apellido = $_POST["apellido"];
}

if (isset($_POST["dni"]) && !empty($_POST["dni"])) {
    $dni = $_POST["dni"];
}


if (isset($_POST["nacionalidad"]) && !empty($_POST["nacionalidad"])) {
    $nacionalidad = $_POST["nacionalidad"];
}

if (isset($_POST["direccion"]) && !empty($_POST["direccion"])) {
    $direccion = $_POST["direccion"];
}

if (isset($_POST["fechaNacimiento"]) && !empty($_POST["fechaNacimiento"])) {
    $fechaNacimiento = $_POST["fechaNacimiento"];
}

if (isset($_POST["idComision"]) && !empty($_POST["idComision"])) {
    $idComision = $_POST["idComision"];
}



if (isset($_POST["idCurso"]) && !empty($_POST["idCurso"])) {
    $idCurso = $_POST["idCurso"];
}


if (isset($_POST["idSexo"]) && !empty($_POST["idSexo"])) {
    $idSexo = $_POST["idSexo"];
}


if (isset($_POST["tutorNombre"]) && !empty($_POST["tutorNombre"])) {
    $tutorNombre = $_POST["tutorNombre"];
}

if (isset($_POST["tutorApellido"]) && !empty($_POST["tutorApellido"])) {
    $tutorApellido = $_POST["tutorApellido"];
}


if (isset($_POST["tutorDni"]) && !empty($_POST["tutorDni"])) {
    $tutorDni = $_POST["tutorDni"];
}


if (isset($_POST["tutorEmail"]) && !empty($_POST["tutorEmail"])) {
    $tutorEmail = $_POST["tutorEmail"];
}

if (isset($_POST["tutorTelefono"]) && !empty($_POST["tutorTelefono"])) {
    $tutorTelefono = $_POST["tutorTelefono"];
}

if (isset($_POST["idPreceptor"]) && !empty($_POST["idPreceptor"])) {
    $idPreceptor = $_POST["idPreceptor"];
}

$findIdPreceptorByIdCurso=  CursoBLL::getUsuarioByIdCurso($idCurso);

$id = 0;

$idUsuario= $findIdPreceptorByIdCurso->getId();
$tutor = new Tutor(
    $id,
    $tutorNombre,
    $tutorApellido,
    $tutorDni,
    $tutorEmail,
    $tutorTelefono
);

$tutorBLL = new TutorBLL();
$idTutor = $tutorBLL->GrabarTutor($tutor);

$id = 0;
$alumno = new Alumno(
    $id,
    $dni,
    $nombre,
    $apellido,
    $idSexo,
    $nacionalidad,
    $fechaNacimiento,
    $direccion,
    $idCurso,
    $idTutor,
    $idUsuario
);

$alumnoBLL = new AlumnoBLL();

$idAlumno= $alumnoBLL->GrabarAlumno($alumno);

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;