<?php
require_once(__DIR__ . "/../Entidades/Cursos.php");
require_once("AbstractMapper.php");

class CursoDAL extends AbstractMapper
{
    // Busca los cursos mediante el id de la preceptora
    public function findCursosById($idPreceptor): array
    {
        $consulta = "SELECT * FROM cursos WHERE idUsuarios = '$idPreceptor'";
        $this->setConsulta($consulta);
        $lista = $this->findAll(); // ✅ corregido
        return $lista;
    }

    public function findCursosByAlumno($idAlumno): array
    {
        $consulta = "SELECT * FROM cursos WHERE idCursos = '$idAlumno'";
        $this->setConsulta($consulta);
        $lista = $this->findAll(); // ✅ corregido
        return $lista;
    }

    public function getAllCursos(): array
    {
        $consulta = "SELECT * FROM cursos";
        $this->setConsulta($consulta);
        $lista = $this->findAll(); // ✅ corregido
        return $lista;
    }

    public function deleteCurso($id)
    {
        $consulta = "DELETE FROM cursos WHERE idCursos = ?";
        $resultado = $this->executeNonQuery($consulta, [$id]); // ✅ corregido
        return $resultado;
    }

    public function InsertarCurso($curso)
    {
        $consulta = "
            INSERT INTO cursos (Año, Division, idUsuarios)
            VALUES (?, ?, ?)
        ";
        $params = [
            $curso->getAno(),
            $curso->getDivision(),
            $curso->getIdUsuario()
        ];

        $id = $this->executeNonQuery($consulta, $params); // ✅ corregido
        return $id;
    }

    public function UpdateCurso($curso)
    {
        $consulta = "
            UPDATE cursos 
            SET Año = ?, Division = ?, idUsuarios = ?
            WHERE idCursos = ?
        ";
        $params = [
            $curso->getAno(),
            $curso->getDivision(),
            $curso->getIdUsuario(),
            $curso->getId()
        ];

        $resultado = $this->executeNonQuery($consulta, $params); // ✅ corregido
        return $resultado;
    }

    public function getCursoById($idCurso)
    {
        $consulta = "SELECT * FROM cursos WHERE idCursos = ?";
        $resultado = $this->executeQuery($consulta, [$idCurso]); // ✅ corregido

        if ($fila = $resultado->fetch_assoc()) {
            return $this->doLoad($fila);
        }

        return null;
    }

public function doLoad($columna)
{
    $id = (int) $columna['idCursos'];
    $ano = (int) $columna['Año'];
    $division = (string) $columna['Division'];
    $asignatura = $columna['asignatura'] ?? '';
    $anioLectivo = $columna['año_lectivo'] ?? null;

    return new Curso(
        $id,
        $ano,
        $division,
        $asignatura,
        $anioLectivo
    );
}


public function findCursosByProfesor($idProfesor): array
{
    $consulta = "
        SELECT c.* 
        FROM cursos c
        INNER JOIN profesor_curso pc ON c.idCursos = pc.curso_id
        WHERE pc.profesor_id = ?
    ";
    $this->setConsulta($consulta);
    $lista = $this->findAll([$idProfesor]);
    return $lista;
}
public function getCursosYAsignaturasPorProfesor(int $idProfesor): array
{
    $consulta = "
        SELECT 
            c.idCursos,
            c.Año,
            c.Division,
            pc.asignatura,
            pc.año_lectivo
        FROM cursos c
        INNER JOIN profesor_curso pc ON c.idCursos = pc.curso_id
        WHERE pc.profesor_id = ?
    ";

    $stmt = $this->conexion->prepare($consulta);
    $stmt->bind_param("i", $idProfesor);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $cursos = [];
    while ($fila = $resultado->fetch_assoc()) {
        $cursos[] = $fila;
    }

    $stmt->close();

    return $cursos;
}
public function findCursoPorProfesorYId(int $idProfesor, int $idCurso): ?array
{
    $consulta = "
        SELECT 
            c.idCursos,
            c.Año,
            c.Division,
            pc.asignatura,
            pc.año_lectivo
        FROM profesor_curso pc
        INNER JOIN cursos c ON pc.curso_id = c.idCursos
        WHERE pc.curso_id = ? AND pc.profesor_id = ?
    ";

    $stmt = $this->conexion->prepare($consulta);
    $stmt->bind_param("ii", $idCurso, $idProfesor);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $curso = $resultado->fetch_assoc();

    $stmt->close();
    return $curso ?: null;
}


}
