<?php
require_once(__DIR__ . "/../entidades/Tutor.php");
require_once(__DIR__ . "/../entidades/Alumno.php");
require_once(__DIR__ . "/AbstractMapper.php");

class TutorDAL extends AbstractMapper
{
    public function findByCurso(int $idCurso): array
    {
        $this->setConsulta("
            SELECT 
                t.idTutores AS id,
                t.Nombre AS nombre,
                t.Apellido AS apellido,
                t.Dni AS dni,
                t.Email AS email,
                t.Telefono AS telefono,
                a.idAlumnos,
                a.Nombre AS AlumnoNombre, 
                a.Apellido AS AlumnoApellido, 
                a.Dni AS AlumnoDni
            FROM tutores t
            INNER JOIN cursos_tutores ct ON t.idTutores = ct.idTutor
            LEFT JOIN tutor_alumno ta ON t.idTutores = ta.idTutor
            LEFT JOIN alumnos a ON ta.idAlumno = a.idAlumnos
            WHERE ct.idCurso = ?
            ORDER BY t.Apellido, t.Nombre, a.Apellido, a.Nombre
        ");

        $filas = $this->FindAll([$idCurso]);

        $tutores = [];
        foreach ($filas as $fila) {
            $idTutor = $fila->id;
            if (!isset($tutores[$idTutor])) {
                $tutores[$idTutor] = $this->doLoad((array)$fila);
            } else {
                if (!empty($fila->AlumnoNombre)) {
                    $tutores[$idTutor]->alumnos[] = [
                        "nombre" => $fila->AlumnoNombre,
                        "apellido" => $fila->AlumnoApellido,
                        "dni" => $fila->AlumnoDni
                    ];
                }
            }
        }
        return array_values($tutores);
    }

    public function findAllTutor(): array
    {
        $this->setConsulta("
            SELECT 
                idTutores AS id,
                Nombre AS nombre,
                Apellido AS apellido,
                Dni AS dni,
                Email AS email,
                Telefono AS telefono
            FROM tutores
            ORDER BY Apellido, Nombre
        ");

        $filas = $this->FindAll();
        $tutores = [];

        foreach ($filas as $fila) {
            $tutores[] = $this->doLoad((array)$fila);
        }

        return $tutores;
    }

    protected function doLoad($columna): Tutor
    {
        return new Tutor(
            id: isset($columna["id"]) ? (int)$columna["id"] : 0,
            nombre: $columna["nombre"] ?? "",
            apellido: $columna["apellido"] ?? "",
            dni: $columna["dni"] ?? "",
            email: $columna["email"] ?? "",
            telefono: $columna["telefono"] ?? "",
            alumnos: []
        );
    }
}
