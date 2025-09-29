<?php
require_once(__DIR__ . "../../Entidades/Mail.php");

class MailDAL extends AbstractMapper
{

    public function getFaltasAlumnos($alumno,$idCurso)
    {
        $consulta = "SELECT 
    alumnos.idAlumnos,
    alumnos.Nombre,
    alumnos.Apellido,
    alumnos.Dni,
    cursos.Año,
    cursos.Division,
    tutores.Nombre AS TutorNombre,
    tutores.Apellido AS TutorApellido,
    tutores.email AS TutorEmail,
COUNT(CASE WHEN asistencias.ValorAsistencia IN (1, 0.5) THEN 1 END) AS TotalFaltas
FROM 
    asistencias
INNER JOIN alumnos ON alumnos.idAlumnos = asistencias.idAlumnos
INNER JOIN cursos ON cursos.idCursos = alumnos.idCursos
INNER JOIN tutores ON alumnos.idTutores = tutores.idTutores
WHERE 
    alumnos.idAlumnos = '$alumno' && cursos.idCursos= '$idCurso'
GROUP BY 
    alumnos.idAlumnos,
    alumnos.Nombre,
        alumnos.Dni,
    alumnos.Apellido,
    cursos.Año,
    cursos.Division,
    tutores.Nombre,
    tutores.Apellido,
    tutores.email;

        
        ";

        $this->setConsulta($consulta);
        $resultado = $this->Find();
        return $resultado;
    }



    public function doLoad($columna)
    {
        $nombre = (string) $columna["Nombre"];
        $apellido = (string) $columna["Apellido"];
        $dniAlumno= (int) $columna["Dni"];
        $cursoAno = (string) $columna["Año"];
        $cursoDivision = (string) $columna["Division"];
        $nombreTutor = (string) $columna["TutorNombre"];
        $apellidoTutor = (string) $columna["TutorApellido"];
        $emailTutor =  (string) $columna["TutorEmail"];

        $mail = new Mail(
            $nombre,
            $apellido,
            $dniAlumno,
            $cursoAno,
            $cursoDivision,
            $nombreTutor,
            $apellidoTutor,
            $emailTutor
        );
        return $mail;
    }
}
