<?php
require_once(__DIR__."/../DAL/profesorCurso.php");

class ProfesorCursoBLL
{
    private $profesorCursoDAL;

    public function __construct()
    {
        $this->profesorCursoDAL = new ProfesorCursoDAL();
    }

    public function getCursosByProfesor($idProfesor)
    {
        return $this->profesorCursoDAL->getCursosByProfesor($idProfesor);
    }

    public function getProfesoresByCurso($idCurso)
    {
        return $this->profesorCursoDAL->getProfesoresByCurso($idCurso);
    }

    public function asignarProfesorCurso($profesorId, $cursoId, $asignatura)
    {
        return $this->profesorCursoDAL->asignarProfesorCurso($profesorId, $cursoId, $asignatura);
    }

    public function eliminarAsignacion($idAsignacion)
    {
        return $this->profesorCursoDAL->eliminarAsignacion($idAsignacion);
    }

    public function eliminarCurso($idCurso){
        return $this->profesorCursoDAL->eliminarCurso($idCurso);
    }
}
?>