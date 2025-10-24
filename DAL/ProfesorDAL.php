<?php 

require_once(__DIR__."/../Entidades/Profesor.php");
require_once("AbstractMapper.php");

// <?php
// require_once(__DIR__ . "/../../Entidades/Profesor.php");
// require_once("AbstractMapper.php");

class ProfesorDAL extends AbstractMapper
{
    
    public function getAllProfesores(): array
    {
        $consulta = "SELECT * FROM profesores WHERE activo = 1 ORDER BY apellido, nombre";
        $this->setConsulta($consulta);
        $lista = $this->FindAll();
        return $lista;
    }


    public function getProfesorById($idProfesor)
    {
        $consulta = "SELECT * FROM profesores WHERE idProfesores = '$idProfesor'";
        $this->setConsulta($consulta);
        $resultado = $this->Find();
        return $resultado;
    }

    public function getProfesorByDni($dni)
    {
        $consulta = "SELECT * FROM profesores WHERE dni = '$dni'";
        $this->setConsulta($consulta);
        $resultado = $this->Find();
        return $resultado;
    }

    
    public function getProfesorByUsuarioId($usuarioId)
    {
        $consulta = "SELECT * FROM profesores WHERE usuario_id = '$usuarioId'";
        $this->setConsulta($consulta);
        $resultado = $this->Find();
        return $resultado;
    }


    public function InsertarProfesor($dni, $nombre, $apellido, $email, $usuario_id)
{
    $sql = "INSERT INTO profesores (dni, nombre, apellido, email, usuario_id, activo, created_at) 
            VALUES ('$dni', '$nombre', '$apellido', '$email', $usuario_id, 1, NOW())";
        $this->setConsulta($sql);
        return $this->Execute();
    
}

    
    public function UpdateProfesor($profesor)
    {
        $consulta = "UPDATE profesores 
        SET 
            dni='" . $profesor->getDni() . "',
            nombre='" . $profesor->getNombre() . "',
            apellido='" . $profesor->getApellido() . "',
            email='" . $profesor->getEmail() . "',
            telefono='" . $profesor->getTelefono() . "'
        WHERE idProfesores='" . $profesor->getId() . "'";

        $this->setConsulta($consulta);
        $id = $this->Execute();
        return $id;
    }

   
    public function deleteProfesor($id)
    {
        $consulta = "UPDATE profesores SET activo = 0 WHERE idProfesores = '$id'";
        $this->setConsulta($consulta);
        $resultado = $this->Execute();
        return $resultado;
    }


    public function EliminarPorUsuarioId($usuario_id)
{
    $sql = "DELETE FROM profesores WHERE usuario_id = $usuario_id";
    $this->setConsulta($sql);
    return $this->Execute();
}

    //eliminar profesor

// public function deleteProfesor($idProfesor){

//     $consulta="DELETE * FROM profesores WHERE idProfesores = '$idProfesor'";
//     $this->setConsulta($consulta);
//     $resultado = $this->Execute();
//     return $resultado;
// }

public function asignarCursoAProfesor(){
    

}

public function getCursosDelProfesor(){

}

public function eliminarCursoDelProfesor(){

}





    public function doLoad($columna)
    {
        $id = (int) $columna['idProfesores'];
        $dni = (string) $columna['dni'];
        $nombre = (string) $columna['nombre'];
        $apellido = (string) $columna['apellido'];
        $email = $columna['email'] ? (string) $columna['email'] : null;
        $telefono = $columna['telefono'] ? (string) $columna['telefono'] : null;
        $activo = (bool) $columna['activo'];
        $usuario_id = isset($columna['usuario_id']) ? (int) $columna['usuario_id'] : null;

        $profesor = new Profesor(
            $id,
            $dni,
            $nombre,
            $apellido,
            $email,
            $telefono,
            $activo,
            $usuario_id
        );
        return $profesor;
    }
    private $idProfesor;

}

?>