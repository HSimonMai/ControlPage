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
                t.idTutores, 
                t.Nombre AS TutorNombre, 
                t.Apellido AS TutorApellido, 
                t.Dni AS TutorDni,
                t.Email, 
                t.Telefono,
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

        // 🔹 Agrupar tutores y sus alumnos
        $tutores = [];
        foreach ($filas as $fila) {
            $idTutor = $fila->id;
            if (!isset($tutores[$idTutor])) {
                $tutores[$idTutor] = $fila;
            } else {
                $tutores[$idTutor]->alumnos = array_merge(
                    $tutores[$idTutor]->alumnos,
                    $fila->alumnos
                );
            }
        }
        return array_values($tutores);
    }

    protected function doLoad($columna): Tutor
    {
        $alumnos = [];
        if (!empty($columna["AlumnoNombre"])) {
            $alumnos[] = [
                "nombre" => $columna["AlumnoNombre"],
                "apellido" => $columna["AlumnoApellido"],
                "dni" => $columna["AlumnoDni"]
            ];
        }

        return new Tutor(
            id: (int)$columna["idTutores"],
            nombre: $columna["TutorNombre"],
            apellido: $columna["TutorApellido"],
            dni: $columna["TutorDni"],
            email: $columna["Email"],
            telefono: $columna["Telefono"],
            alumnos: $alumnos
        );
    }
}
