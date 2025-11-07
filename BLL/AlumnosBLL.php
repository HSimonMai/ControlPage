<?php
require_once __DIR__ . "/../DAL/AlumnosDAL.php";


class AlumnosBLL
{
    private AlumnosDAL $alumnoDAL;

    public function __construct()
    {
        $this->alumnoDAL = new AlumnosDAL();
    }

    public function getAlumnosByIdCurso(int $idCurso): array
    {
        // ✅ Usa la sesión como caché
        if (isset($_SESSION["alumnos_curso_$idCurso"])) {
            return $_SESSION["alumnos_curso_$idCurso"];
        }

        // Si no está en sesión, los trae desde la BD
        $alumnos = $this->alumnoDAL->getByCurso($idCurso);

        // Los guarda en sesión para la próxima vez
        $_SESSION["alumnos_curso_$idCurso"] = $alumnos;

        return $alumnos;
    }
}
