<?php
require_once("../BLL/AlumnoBLL.php");
require_once("../BLL/TutorBLL.php");

if (isset($_POST["idAlumno"]) && !empty($_POST["idAlumno"])) {
    $idAlumno = $_POST["idAlumno"];
}

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



if (isset($_POST["idCurso"]) && !empty($_POST["idCurso"])) {
    $idCurso = $_POST["idCurso"];
}


if (isset($_POST["idSexo"]) && !empty($_POST["idSexo"])) {
    $genero = $_POST["idSexo"];
}



if (isset($_POST["idTutor"]) && !empty($_POST["idTutor"])) {
    $idTutor = $_POST["idTutor"];
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



$tutor = new Tutor(
    $idTutor,
    $tutorNombre,
    $tutorApellido,
    $tutorDni,
    $tutorEmail,
    $tutorTelefono
);

$tutorBLL = new TutorBLL();
$idTutor = $tutorBLL->UpdateTutor($tutor);

$alumno = new Alumno(
    $idAlumno,
    $dni,
    $nombre,
    $apellido,
    $genero,
    $nacionalidad,
    $fechaNacimiento,
    $direccion,
    $idCurso,
    $idTutor,
    $idPreceptor=0
);

$alumnoBLL = new AlumnoBLL();

$idAlumno= $alumnoBLL->UpdateAlumno($alumno);

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;