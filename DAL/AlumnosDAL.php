<?php
require_once __DIR__ . "/AbstractMapper.php";
require_once __DIR__ . "/../Entidades/Alumno.php";

class AlumnosDAL extends AbstractMapper
{
    protected function doLoad(array $columna): Alumno
    {
        return new Alumno(
            $columna["idAlumnos"],          // 🔹 coincide con tu tabla
            $columna["Nombre"],             // 🔹 mayúscula según tu BD
            $columna["Apellido"],
            $columna["DNI"]
        );
    }

    public function getByCurso(int $idCurso): array
    {
        // 🔹 usar idCursos (plural) que es la FK real
        $this->setConsulta("
            SELECT idAlumnos, Nombre, Apellido, DNI
            FROM alumnos
            WHERE idCursos = ?
        ");
        return $this->findAll([$idCurso]);
    }
}
