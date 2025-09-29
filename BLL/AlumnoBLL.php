<?php
require_once("../DAL/AlumnoDAL.php");

class AlumnoBLL
{

    public function GrabarAlumno($alumno)
    {
        $alumnoDAL = new AlumnoDAL();
        $id = $alumnoDAL->InsertarAlumno($alumno);
        return $id;
    }
    public function getIdAlumnoDni($dni)
    {
        $alumnoDAL = new AlumnoDAL();
        $id = $alumnoDAL->findId($dni);
        return $id;
    }

    public static function getAlumnoById($idAlumno)
    {

        $alumnoDAL = new AlumnoDAL();
        $id = $alumnoDAL->findId($idAlumno);
        return $id;
    }

    public static function getAlumnosByIdCurso($idCurso)
    {
        try {
            if ($idCurso <= 0) {
                throw new InvalidArgumentException("El ID del curso debe ser mayor a cero.");
            }

            $alumnoDAL = new AlumnoDAL();
            $lista = $alumnoDAL->findAlumnosByIdCurso($idCurso);

            if ($lista === null || empty($lista)) {
                return null;
            }

            return $lista;
        } catch (Exception $e) {
            // Log del error
            error_log("Error en getAlumnosByIdCurso: " . $e->getMessage());
            return null;
        }
    }

    public static function listaAlumnos(): array
    {
        $AlumnoDAL = new AlumnoDAL();
        $lista = $AlumnoDAL->getAllAlumnos();
        return $lista;
    }

    public function deleteAlumno($id)
    {
        $alumnoDAL = new AlumnoDAL();
        $resultado = $alumnoDAL->deleteAlumno($id);
        return $resultado;
    }

    public function UpdateAlumno($alumno)
    {
        $alumnoDAL = new AlumnoDAL();
        $resultado = $alumnoDAL->UpdateAlumno($alumno);
        return $resultado;
    }
}
