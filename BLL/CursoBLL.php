<?php

require_once(__DIR__."../../Entidades/Cursos.php");
require_once(__DIR__."../../DAL/CursoDAL.php");
require_once(__DIR__."../../Entidades/Usuario.php");

class CursoBLL
{

    public static function getAllCursos(): array
    {
        $cursoDAL = new CursoDAL();
        $lista = $cursoDAL->getAllCursos();
        return $lista;
    }

    public function cursosAsignados(): array
    {
        $usuario = unserialize($_SESSION['usuario']);
        $idPreceptor = $usuario->getId();

        $lista = $this->getCursosByIdPreceptor(idPreceptor: $idPreceptor);
        return $lista;
    }




    public static function getCursosByIdPreceptor($idPreceptor): array
    {
        $cursoDAL = new CursoDAL();
        $lista = $cursoDAL->findCursosById($idPreceptor);
        return $lista;
    }


    
    public static function findCursoByIdAlumno($idAlumnoCurso): array
    {
        $cursoDAL = new CursoDAL();
        $resultado= $cursoDAL->findCursosByAlumno($idAlumnoCurso);
        return $resultado;
    }



    public static function getUsuarioByIdCurso($idCurso)
    {
        $cursoDAL = new CursoDAL();
        $resultado= $cursoDAL->getCursoById($idCurso);
        return $resultado;
    }



    public function findCursosById($idPreceptor): array
    {
        $usuario = new CursoDAL();

        $lista = $this->getCursosByIdPreceptor(idPreceptor: $idPreceptor);
        return $lista;
    }

    public function GrabarCurso($curso)
    {
        $cursoDAl= new CursoDAL();
        $resultado= $cursoDAl->InsertarCurso($curso);
        return $resultado;
    }

    public function UpdateCurso($curso)
    {
        $cursoDAL= new CursoDAL();
        $resultado= $cursoDAL->UpdateCurso($curso);
        return $resultado;
    }

    

    public function deleteCurso($idCurso)
    {
        $cursoDAL= new CursoDAL();
        $resultado= $cursoDAL->deleteCurso($idCurso);
        return $resultado;    
    }

  
}

