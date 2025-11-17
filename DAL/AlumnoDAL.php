<?php
require_once __DIR__ . "/AbstractMapper.php";
require_once __DIR__ . "/../Entidades/Alumno.php";

class AlumnosDAL extends AbstractMapper
{
    // 🔹 Convierte una fila en un objeto Alumno
    protected function doLoad($fila): Alumno
    {
        return new Alumno(
            (int)($fila["idAlumnos"] ?? 0),
            (string)($fila["Dni"] ?? ''),
            (string)($fila["Nombre"] ?? ''),
            (string)($fila["Apellido"] ?? ''),
            (string)($fila["Genero"] ?? ''),
            (string)($fila["Nacionalidad"] ?? ''),
            (string)($fila["FechaNacimiento"] ?? ''),
            (string)($fila["Direccion"] ?? ''),
            (int)($fila["idCursos"] ?? 0),
            (int)($fila["idTutores"] ?? 0),
            0 // valor por defecto para idPreceptor (no está en la tabla)
        );
    }

    // 🔹 Devuelve todos los alumnos de un curso (por idCursos)
    public function getByCurso(int $idCurso): array
    {
        $consulta = "SELECT 
                idAlumnos, Dni, Nombre, Apellido, Genero, Nacionalidad,
                FechaNacimiento, Direccion, idCursos, idTutores
            FROM alumnos
            WHERE idCursos = '$idCurso'";

        $this->setConsulta($consulta);
        return $this->FindAll();
    }

    // 🔹 Obtener un alumno por su ID
    public function getById(int $idAlumno): ?Alumno
    {
        $consulta = "SELECT * FROM alumnos WHERE idAlumnos = '$idAlumno'";
        $this->setConsulta($consulta);
        return $this->Find();
    }

    // 🔹 Obtener todos los alumnos
    public function getAll(): array
    {
        $consulta = "SELECT * FROM alumnos";
        $this->setConsulta($consulta);
        return $this->FindAll();
    }

    // 🔹 Eliminar alumno
    public function deleteAlumno(int $idAlumno): bool
    {
        $consulta = "DELETE FROM alumnos WHERE idAlumnos = '$idAlumno'";
        $this->setConsulta($consulta);
        $this->Execute();
        return true;
    }

    // 🔹 Insertar nuevo alumno
    public function insertarAlumno(Alumno $alumno): int|string
    {
        $consulta = sprintf(
            "INSERT INTO alumnos (Dni, Nombre, Apellido, Genero, Nacionalidad, FechaNacimiento, Direccion, idCursos, idTutores)
             VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d')",
            $alumno->getDni(),
            $alumno->getNombre(),
            $alumno->getApellido(),
            $alumno->getGenero(),
            $alumno->getNacionalidad(),
            $alumno->getFechaNacimiento(),
            $alumno->getDireccion(),
            $alumno->getIdCurso(),
            $alumno->getIdTutor()
        );

        $this->setConsulta($consulta);
        return $this->Execute();
    }
}
