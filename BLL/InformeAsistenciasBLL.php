<?php
require_once("../Entidades/InformeAsistencia.php");
require_once("../DAL/InformeAsistenciaDAL.php");
class InformeAsistenciasBLL
{

    public static function getInformeGeneral($idCurso): array
    {
        $InformeAsistenciaDAL= new InformeAsistenciaDAL();
        $resultado= $InformeAsistenciaDAL->getInformeGeneral($idCurso);
        return $resultado;
    }
    

    public static function informeAsistencia($idAlumno): array
    {
        $InformeAsistenciaDAL= new InformeAsistenciaDAL();
        $resultado= $InformeAsistenciaDAL->getInfAsistencia($idAlumno);
        return $resultado;
    }
}