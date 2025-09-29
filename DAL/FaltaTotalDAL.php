<?php

require_once("../Entidades/FaltaTotal.php");
require_once("AbstractMapper.php");

class FaltaTotalDAL extends AbstractMapper
{


    public function faltasTotales ($idAlumno): array
    {
        $consulta= '

        SELECT 
        alumnos.idAlumnos,
        alumnos.DNI,
        alumnos.nombre,
        alumnos.apellido,
        -- Total de asistencias completas
        SUM(CASE WHEN asistencias.ValorAsistencia = 0 THEN 1 ELSE 0 END) AS TotalAsistenciasCompletas, 
        -- Total de medias faltas
        SUM(CASE WHEN asistencias.ValorAsistencia = 0.5 THEN 0.5 ELSE 0 END) AS MediasFaltas, 
        -- Total de faltas
        SUM(CASE 
                WHEN asistencias.ValorAsistencia = 1 THEN 1       -- Faltas completas suman 1
                WHEN asistencias.ValorAsistencia = 0.5 THEN 0.5  -- Medias faltas suman 0.5
                ELSE 0                                           -- Aseguramos que otros casos no afecten
            END) AS FaltasTotales
    FROM 
        asistencias 
    INNER JOIN 
        alumnos 
    ON 
        asistencias.idAlumnos = alumnos.idAlumnos 
    INNER JOIN
        tipoclase
    ON 
        asistencias.idtipoClase = tipoclase.idtipoClase
    INNER JOIN
        cursos
    ON 
        cursos.idCursos = alumnos.idCursos
    WHERE 
        alumnos.idAlumnos = '.$idAlumno.'
    GROUP BY     
        alumnos.idAlumnos,
        alumnos.DNI,
        alumnos.nombre,
        alumnos.apellido;    
 ';

        $this->setConsulta($consulta);
        $resultado= $this->FindAll();
        return $resultado;

    }


    public function informeCurso($idCurso)
    {
        $consulta = '

SELECT 
alumnos.idAlumnos,
alumnos.DNI,
alumnos.nombre,
alumnos.apellido,
-- Total de asistencias completas
SUM(CASE WHEN asistencias.ValorAsistencia = 0 THEN 1 ELSE 0 END) AS TotalAsistenciasCompletas, 
-- Total de medias faltas
SUM(CASE WHEN asistencias.ValorAsistencia = 0.5 THEN 0.5 ELSE 0 END) AS MediasFaltas, 
-- Total de faltas
SUM(CASE 
        WHEN asistencias.ValorAsistencia = 1 THEN 1       -- Faltas completas suman 1
        WHEN asistencias.ValorAsistencia = 0.5 THEN 0.5  -- Medias faltas suman 0.5
        ELSE 0                                           -- Aseguramos que otros casos no afecten
    END) AS FaltasTotales
FROM 
asistencias 
INNER JOIN 
alumnos 
ON 
asistencias.idAlumnos = alumnos.idAlumnos 
INNER JOIN
tipoclase
ON 
asistencias.idtipoClase = tipoclase.idtipoClase
INNER JOIN
cursos
ON 
cursos.idCursos = alumnos.idCursos
WHERE 
cursos.idCursos = '.$idCurso.'
GROUP BY     
alumnos.idAlumnos,
alumnos.DNI,
alumnos.nombre,
alumnos.apellido;

        ';
    
        $this->setConsulta($consulta);
        $resultado = $this->FindAll();
        return $resultado;
    }
    

    public function doLoad($columna)
    {
        $id= (int) $columna["idAlumnos"];
        $dni= (int) $columna["DNI"];
        $nombre= (string) $columna["nombre"];
        $apellido= (string) $columna["apellido"];
        $asistencia= (float) $columna["TotalAsistenciasCompletas"];
        $mediasFaltas= (float) $columna["MediasFaltas"];
        $faltasTotals = (float) $columna["FaltasTotales"];


        $faltaTotal= new FaltaTotal(
            $id,
            $dni,
            $nombre,
            $apellido,
            $asistencia,
            $mediasFaltas,
            $faltasTotals,
        );

        return $faltaTotal;
    }
}
