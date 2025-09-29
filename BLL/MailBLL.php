<?php
require_once(__DIR__ . '/../vendor/autoload.php');
require_once("../DAL/MailDAL.php");
require_once("../Mail/mail.php");

class MailBLL
{
    // Método estático que actúa como puente
    public static function getFaltas($idAlumno,$idCurso)
    {
        $mailDAL = new MailDAL();
        $alumnoData = $mailDAL->getFaltasAlumnos($idAlumno,$idCurso);

            // Pasamos los datos al procesador de correos
            MailProcessor::procesarAlumnosConInasistencias($alumnoData);
    
    }
}
?>
