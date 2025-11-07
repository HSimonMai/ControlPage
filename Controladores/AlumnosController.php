<?php
session_start();
require_once __DIR__ . "/../BLL/AlumnoBLL.php";

class AlumnosController {
    private AlumnoBLL $alumnoBLL;

    public function __construct() {
        $this->alumnoBLL = new AlumnoBLL();
    }

    public function mostrarAlumnos() {
        // Verificar sesión del profesor
        if (!isset($_SESSION["idProfesor"])) {
            header("Location: login.php");
            exit;
        }

        // Si viene un curso nuevo por GET, actualizar la sesión
        if (isset($_GET["idCurso"])) {
            $_SESSION["idCursoSeleccionado"] = (int) $_GET["idCurso"];
        }

        $idCurso = $_SESSION["idCursoSeleccionado"] ?? null;
        if (!$idCurso) {
            header("Location: mis_cursos.php");
            exit;
        }

        $cursoSeleccionado = $_SESSION["cursoSeleccionado"] ?? [
            "Año" => "",
            "Division" => "",
            "asignatura" => ""
        ];

        // Obtener alumnos desde la BLL (usa sesión internamente)
        $listaAlumnos = $this->alumnoBLL->getAlumnosByIdCurso($idCurso);

        return [
            "curso" => $cursoSeleccionado,
            "alumnos" => $listaAlumnos
        ];
    }
}
