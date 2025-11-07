<?php
require_once(__DIR__ . "/../Entidades/Cursos.php");
require_once(__DIR__ . "/../DAL/CursoDAL.php");
require_once(__DIR__ . "/../Entidades/Usuario.php");

class CursoBLL
{
    public static function getAllCursos(): array
    {
        $cursoDAL = new CursoDAL();
        return $cursoDAL->getAllCursos();
    }

    public function cursosAsignados(): array
    {
        $usuario = unserialize($_SESSION['usuario']);
        $idPreceptor = $usuario->getId();

        return $this->getCursosByIdPreceptor($idPreceptor);
    }

    public static function getCursosByIdPreceptor($idPreceptor): array
    {
        $cursoDAL = new CursoDAL();
        return $cursoDAL->findCursosById($idPreceptor);
    }

    public static function findCursoByIdAlumno($idAlumnoCurso): array
    {
        $cursoDAL = new CursoDAL();
        return $cursoDAL->findCursosByAlumno($idAlumnoCurso);
    }

    public static function getUsuarioByIdCurso($idCurso)
    {
        $cursoDAL = new CursoDAL();
        return $cursoDAL->getCursoById($idCurso);
    }

    public function findCursosById($idPreceptor): array
    {
        return $this->getCursosByIdPreceptor($idPreceptor);
    }

    public function GrabarCurso($curso)
    {
        $cursoDAL = new CursoDAL();
        return $cursoDAL->InsertarCurso($curso);
    }

    public function UpdateCurso($curso)
    {
        $cursoDAL = new CursoDAL();
        return $cursoDAL->UpdateCurso($curso);
    }

    public function deleteCurso($idCurso)
    {
        $cursoDAL = new CursoDAL();
        return $cursoDAL->deleteCurso($idCurso);
    }

    // 🔹 Cursos por profesor
    public static function getCursosByProfesor($idProfesor): array
    {
        $cursoDAL = new CursoDAL();
        return $cursoDAL->findCursosByProfesor($idProfesor);
    }

    // 🔹 Alias para compatibilidad con UsuariosBLL
    public static function obtenerCursosPorProfesor($idProfesor): array
    {
        return self::getCursosByProfesor($idProfesor);
    }

    // ✅ Método limpio: la BLL pide a la DAL los datos
    public static function obtenerCursosYAsignaturasPorProfesor(int $idProfesor): array
    {
        $cursoDAL = new CursoDAL();
        return $cursoDAL->getCursosYAsignaturasPorProfesor($idProfesor);
    }
    public static function obtenerCursoPorProfesorYId(int $idProfesor, int $idCurso): ?array
{
    $cursoDAL = new CursoDAL();
    return $cursoDAL->findCursoPorProfesorYId($idProfesor, $idCurso);
}

}
