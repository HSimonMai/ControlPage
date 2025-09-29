<?php
require_once("../Entidades/Asistencia.php");
require_once("AbstractMapper.php");
class AsistenciaDAL extends AbstractMapper
{


    //metodo que recibe los parametros de una asistencia y los inserta en la base de datos 
    public function insertarAsistencia($asistencia, $idAlumno,$fechaActual,$tipo)
    {
        $consulta = "INSERT INTO Asistencias (FechaAsistencia, ValorAsistencia, idAlumnos,idTipoClase)
                    VALUES (
                    '$fechaActual',
                    '$asistencia',
                    '$idAlumno',
                    '$tipo'
                    )";

        $this->setConsulta($consulta);
        $id = $this->Execute();
        return $id;
    }


    public function getAsistenciasByIdAlumno($idAlumno)
    {
        $consulta= "SELECT * FROM Asistencias WHERE idAlumnos= '$idAlumno'  ";
        $this->setConsulta($consulta);
        $resultado= $this->FindAll();
        return $resultado;
    }

    public function deleteAsistencias($idAlumno)
    {
        $consulta= "DELETE FROM  asistencias WHERE idAlumnos= '$idAlumno' ";
        $this->setConsulta($consulta);
        $resultado= $this->Execute();
        return $resultado;
    }


    public function doLoad($columna)
    {
        $id = (int) $columna[""];
        $fechaAsistencia = (string)$columna["FechaAsistencia"];
        $valorAsistencia = (int)$columna["ValorAsistencia"];
        $idTipoClase= (int) $columna["idtipoClase"];

        $asistencia = new Asistencia(
            $id,
            $fechaAsistencia,
            $valorAsistencia,
            $idTipoClase
        );
        return $asistencia;
    }
}
