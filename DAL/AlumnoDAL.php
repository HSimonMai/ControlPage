<?php
require_once("../Entidades/Alumno.php");
require_once("AbstractMapper.php");

class AlumnoDAL  extends AbstractMapper
{

    public function InsertarAlumno($alumno)
    {
        $consulta = "INSERT INTO alumnos (DNI, Nombre, Apellido, Genero, Nacionalidad, FechaNacimiento, Direccion, idCursos, idUsuarios, idTutores) VALUES ('" . $alumno->getDni() . "', '" . $alumno->getNombre() . "', '" . $alumno->getApellido() . "', '" . $alumno->getGenero() . "', '" . $alumno->getNacionalidad() . "', '" . $alumno->getFechaNacimiento() . "', '" . $alumno->getDireccion() . "','" . $alumno->getIdCurso() . "', '" . $alumno->getIdPreceptor() . "', '" . $alumno->getIdTutor() . "')";

        $this->setConsulta($consulta);
        $id = $this->Execute();
        return $id;
    }

    public function deleteAlumno($idAlumno)
    {
        $consulta = "DELETE FROM alumnos WHERE idAlumnos = '$idAlumno'";
        $this->setConsulta($consulta);
        $resultado = $this->Execute();
        return $resultado;
    }

    public function findAlumnoById($idAlumno)
    {
        $query= "SELECT * FROM alumnos WHERE idAlumnos= '$idAlumno' ";
        $this->setConsulta($query);
        $alumno= $this->Find();
        return $alumno;
    }

    //Metodo que mediante la id del curso busca los alumnos que esten en ese curso 
    public function findAlumnosByIdCurso($idCurso): array
    {
        $consulta = "SELECT * FROM alumnos WHERE idCursos ='$idCurso'";
        $this->setConsulta($consulta);
        return $this->FindAll();  // Devuelve un array de objetos Alumno
    }
    //Busca los alumnos mediante el dni y retorna un objeto 
    public function findId(string $idAlumnos)
    {
        $consulta =  "SELECT * FROM alumnos WHERE idAlumnos = '$idAlumnos'";
        $this->setConsulta($consulta);
        $resultado = $this->Find();;
        return $resultado;
    }

    //Recupera un array de todos los objetos tipo alumnos
    public function getAllAlumnos()
    {
        $consulta = "SELECT * FROM alumnos";
        $this->setConsulta($consulta);
        $lista = $this->FindAll();
        return $lista;
    }

    public function UpdateAlumno($alumno)
    {
        $consulta = "UPDATE alumnos 
        SET DNI='" . $alumno->getDni() . "',
            Nombre='" . $alumno->getNombre() . "',
            Apellido='" . $alumno->getApellido() . "',
            Genero='" . $alumno->getGenero() . "',
            Nacionalidad='" . $alumno->getNacionalidad() . "',
            FechaNacimiento='" . $alumno->getFechaNacimiento() . "',
            Direccion='" . $alumno->getDireccion() . "',
            idCursos='" . $alumno->getIdCurso() . "'
        WHERE idAlumnos='" . $alumno->getId() . "';";
    
        $this->setConsulta($consulta);
        $id = $this->Execute();
        return $id;
    }
    

    public function doLoad($columna)
    {
        $id = (int) $columna["idAlumnos"];
        $dni = (string) $columna["DNI"];
        $nombre = (string) $columna["Nombre"];
        $apellido = (string) $columna["Apellido"];
        $nacionalidad = (string) $columna["Nacionalidad"];
        $genero = (string) $columna["Genero"];
        $fechaNacimiento = (string) $columna["FechaNacimiento"];
        $direccion = (string) $columna["Direccion"];
        $idCurso = (int) $columna["idCursos"];
        $idTutor = (int) $columna["idTutores"];
        $idPreceptor = (int) $columna["idUsuarios"];

        $alumno = new Alumno(
            $id,
            $dni,
            $nombre,
            $apellido,
            $genero,
            $nacionalidad,
            $fechaNacimiento,
            $direccion,
            $idCurso,
            $idTutor,
            $idPreceptor
        );

        return $alumno;
    }
}
