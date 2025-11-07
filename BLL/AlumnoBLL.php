<?php
require_once __DIR__ . "/../DAL/AlumnoDAL.php";

class AlumnoBLL
{
    private AlumnosDAL $dal;

    public function __construct()
    {
        $this->dal = new AlumnosDAL();
    }

    /**
     * 🔹 Devuelve todos los alumnos de un curso
     */
    public function getAlumnosByIdCurso(int $idCurso): array
    {
        try {
            return $this->dal->getByCurso($idCurso);
        } catch (Exception $e) {
            die("❌ Error al obtener los alumnos del curso: " . $e->getMessage());
        }
    }
}
