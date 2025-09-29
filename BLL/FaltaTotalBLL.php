<?php
require_once("../DAL/FaltaTotalDAL.php");

class FaltaTotalBLL
{
    public static function getFaltasTotales($idAlumno)
    {
        $faltasTotalesDAL = new FaltaTotalDAL();
        $resultado = $faltasTotalesDAL->faltasTotales($idAlumno);
        return $resultado;
    }


    public static function getInformeCurso($idCurso)
    {
        $informeGeneral = new FaltaTotalDAL();
        $resultado = $informeGeneral->informeCurso($idCurso);
        return $resultado;
    }
}
