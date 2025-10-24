<?php 

require_once("../Entidades/ProfesorCurso.php");
require_once("AbstractMapper.php");

class ProfesorCursoDal extends AbstractMapper{

    //obtiene los cursos de un profesor
    public function getCursosByProfesor($idProfesor):array{

        $consulta = "SELECT pc.*,c.Año,c.Division
                     FROM profesor_curso pc
                     INNER JOIN cursos c ON pc.curso_id = c.idCursos WHERE pc.profesor_id = '$idProfesor'
                     ORDER BY c.Año, c.Division";
            $this->setConsulta($consulta);
            $lista = $this->FindAll();
            return $lista;
    }

    //obtener profesores de un curso

    public function getProfesoresByCurso($idCurso):array{
        $consulta = "SELECT pc.*,p.nombre,p.apellido
        FROM profesor_curso pc
        INNER JOIN profesores p ON pc.profesor_id = p.idProfesores WHERE pc.curso_id = '$idCurso' AND p.activo =1";
        $this->setConsulta($consulta);
        $lista = $this->FindAll();
        return $lista;
    }

    //asignar profesor a curso


public function asignarProfesorCurso($profesorId, $cursoId, $asignatura)
{
    $añoLectivo = date('Y'); // Año actuañll
    
    $consulta = "INSERT INTO profesor_curso(profesor_id, curso_id, asignatura, año_lectivo) VALUES(
        $profesorId,
        $cursoId,
        '$asignatura',
        $añoLectivo
    )"; 
    
    $this->setConsulta($consulta);
    $id = $this->Execute();
    return $id;
}

    //eliminar asignacion profesor-curso

    public function eliminarAsignacion($idAsignacion){

        $consulta = "DELETE FROM profesor_curso WHERE idProfesorCurso ='$idAsignacion'";
        $this->setConsulta($consulta);
        $resultado = $this->Execute();
        return $resultado;
    }


    public function eliminarCurso($idCurso){
        $consulta = "DELETE  FROM profesor_curso WHERE idProfesorCurso= '$idCurso'";
        $this->setConsulta($consulta);
        $resultado=$this->Execute();
        return $resultado;
    }



    //cargar objeto desde BD

    public function doLoad($columna){
        $id = (int) $columna['idProfesorCurso'];
        $profesor_id = (int) $columna['profesor_id'];
        $curso_id = (int) $columna['curso_id'];
        $asignatura = (string) $columna['asignatura'];
        $año_lectivo = $columna['año_lectivo'] ? (int) $columna['año_lectivo']:date('Y');

        $profesorCurso = new ProfesorCurso(
            $id,
            $profesor_id,
            $curso_id,
            $asignatura,
            $año_lectivo
        );
        return $profesorCurso;
    }


}




?>