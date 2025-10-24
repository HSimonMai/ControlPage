<?php
require_once(__DIR__ . "/../DAL/ProfesorDAL.php");

class ProfesorBLL
{
    private $profesorDAL;

    public function __construct()
    {
        $this->profesorDAL = new ProfesorDAL();
    }

    public function getAllProfesores()
    {
        return $this->profesorDAL->getAllProfesores();
    }

    public function getProfesorById($id)
    {
        return $this->profesorDAL->getProfesorById($id);
    }

    public function getProfesorByDni($dni)
    {
        return $this->profesorDAL->getProfesorByDni($dni);
    }

    public function getProfesorByUsuarioId($usuarioId)
    {
        return $this->profesorDAL->getProfesorByUsuarioId($usuarioId);
    }

    //error en insertar, ver cambios
     public function insertarProfesor($dni, $nombre, $apellido, $email, $usuario_id)
    {
        // Verificar si ya existe un profesor con el mismo DNI
        // $existente = $this->profesorDAL->getProfesorByDni($profesor->getDni());
        // if ($existente) {
        //     throw new Exception("Ya existe un profesor con el DNI: " . $profesor->getDni());
        // }

        return $this->profesorDAL->InsertarProfesor($dni, $nombre, $apellido, $email, $usuario_id);
    }


    public function actualizarProfesor($profesor)
    {
        return $this->profesorDAL->UpdateProfesor($profesor);
    }

    public function eliminarProfesor($id)
    {
        return $this->profesorDAL->deleteProfesor($id);
    }


public function asignarCursoAProfesor($profesorId, $cursoId, $asignatura) {
    return $this->profesorDAL->asignarCursoAProfesor($profesorId, $cursoId, $asignatura);
}

public function getCursosDelProfesor($profesorId) {
    return $this->profesorDAL->getCursosDelProfesor($profesorId);
}

public function eliminarCursoDelProfesor($idAsignacion) {
    return $this->profesorDAL->eliminarCursoDelProfesor($idAsignacion);
}
    
}
?><?php
require_once(__DIR__ . "/../DAL/ProfesorDAL.php");
