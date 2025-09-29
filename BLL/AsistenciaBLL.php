<?php
require_once("../DAL/AsistenciaDAL.php");
require_once("../DAL/AlumnoDAL.php");
class AsistenciaBLL
{
    public function getFechaActual()
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fechaHoraActual = new DateTime();
    
        // Formato 'Y-m-d H:i:s' (AAA-MM-DD HH:MM:SS)
        return $fechaHoraActual->format('Y-m-d H:i:s'); 
    
    }

    public function grabarAsistencia($valor, $idAlumno, $tipo)
    {
        $fechaActual = $this->getFechaActual();
        $asistenciaDAL = new AsistenciaDAL();
        $asistencia = $asistenciaDAL->insertarAsistencia($valor, $idAlumno, $fechaActual, $tipo);
        return $asistencia;
    }


    public static function getAsistenciasByAlumno($idAlumno): array
    {
        $asistenciaDAL= new AsistenciaDAL();
        $resultado= $asistenciaDAL->getAsistenciasByIdAlumno($idAlumno);
        return $resultado;
    }

    public static function deleteAsistencias($idAlumno)
    {
        $asistenciaDAL= new AsistenciaDAL();
        $resultado= $asistenciaDAL->deleteAsistencias($idAlumno);
        return $resultado;
    }
}
