<?php
require_once("AbstractMapper.php");
require_once("../Entidades/InformeAsistencia.php");

class InformeAsistenciaDAL extends AbstractMapper
{

        public function getInformeGeneral($idCurso): array
        {
            $consulta = "SELECT 
                alumnos.idAlumnos,
                alumnos.Nombre,
                alumnos.Apellido,
                tipoclase.tipoClase,
                asistencias.FechaAsistencia,
                asistencias.ValorAsistencia,
                COUNT(CASE WHEN asistencias.ValorAsistencia = 0 THEN 1 END) AS AsistenciasCompletas,

                COUNT(CASE WHEN asistencias.ValorAsistencia = 1 THEN 1 END) AS AsistenciasCompletas,
                COUNT(CASE WHEN asistencias.ValorAsistencia = 0.5 THEN 1 END) AS AsistenciasMedias,
                SUM(asistencias.ValorAsistencia) AS FaltasTotales
            FROM 
                asistencias 
            INNER JOIN 
                alumnos 
            ON 
                asistencias.idAlumnos = alumnos.idAlumnos 
            INNER JOIN
                cursos
            ON 
                cursos.idCursos = alumnos.idCursos
            INNER JOIN 
                tipoclase 
            ON 
                tipoclase.idTipoClase = asistencias.idtipoClase
            WHERE 
                cursos.idCursos = '$idCurso'
GROUP BY     
    alumnos.idAlumnos,
    alumnos.Nombre,
    alumnos.Apellido,
    tipoclase.tipoClase,
    asistencias.FechaAsistencia,
                    asistencias.ValorAsistencia";
        
            $this->setConsulta($consulta);
            return $this->FindAll();
        }
        


    public function getInfAsistencia($idAlumno): array
    {
        $consulta = "SELECT   
            alumnos.idAlumnos,
            alumnos.Nombre,
            alumnos.Apellido,
            tipoclase.tipoClase,
            asistencias.FechaAsistencia,
            asistencias.ValorAsistencia,
            SUM(asistencias.ValorAsistencia) AS FaltasTotales
            FROM asistencias 
            INNER JOIN alumnos ON asistencias.idAlumnos = alumnos.idAlumnos 
            INNER JOIN tipoclase ON asistencias.idtipoClase = tipoclase.idtipoClase
            WHERE alumnos.idAlumnos = '$idAlumno' 
            GROUP BY alumnos.idAlumnos, alumnos.Nombre, alumnos.Apellido,asistencias.FechaAsistencia,asistencias.ValorAsistencia,tipoclase.tipoClase;";

        $this->setConsulta($consulta);
        $resultado = $this->FindAll();
        return $resultado;
    }



    public function doLoad($columna): InformeAsistencia
    {
        $idAlumno = (int)$columna["idAlumnos"];
        $nombre = (string)$columna["Nombre"];
        $apellido = (string)$columna["Apellido"];
        $tipoClase = (string)$columna["tipoClase"];
        $fechaAsistencia = new DateTime($columna["FechaAsistencia"]);
        $valorAsistencia = (float) $columna["ValorAsistencia"];
        $faltasTotales = (float) $columna["FaltasTotales"];
        $informeAsistencia = new InformeAsistencia(
            $idAlumno,
            $nombre,
            $apellido,
            $tipoClase,
            $valorAsistencia,
            $fechaAsistencia,
            $faltasTotales
        );

        return $informeAsistencia;
    }
}
