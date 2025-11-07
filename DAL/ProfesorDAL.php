<?php
require_once(__DIR__ . "/../Entidades/Profesor.php");
require_once("AbstractMapper.php");

class ProfesorDAL extends AbstractMapper
{
    // 🔹 Devuelve todos los profesores activos
    public function getAllProfesores(): array
    {
        $this->setConsulta("SELECT * FROM profesores WHERE activo = 1 ORDER BY apellido, nombre");
        return $this->findAll();
    }

    // 🔹 Busca un profesor por su ID
    public function getProfesorById(int $idProfesor): ?Profesor
    {
        $sql = "SELECT * FROM profesores WHERE idProfesores = ?";
        $resultado = $this->executeQuery($sql, [$idProfesor]);

        if ($fila = $resultado->fetch_assoc()) {
            return $this->doLoad($fila);
        }

        return null;
    }

    // 🔹 Busca un profesor por su DNI
    public function getProfesorByDni(string $dni): ?Profesor
    {
        $sql = "SELECT * FROM profesores WHERE dni = ?";
        $resultado = $this->executeQuery($sql, [$dni]);

        if ($fila = $resultado->fetch_assoc()) {
            return $this->doLoad($fila);
        }

        return null;
    }

    // 🔹 Busca un profesor por ID de usuario
    public function getProfesorByUsuarioId(int $usuarioId): ?Profesor
    {
        $sql = "SELECT * FROM profesores WHERE usuario_id = ?";
        $resultado = $this->executeQuery($sql, [$usuarioId]);

        if ($fila = $resultado->fetch_assoc()) {
            return $this->doLoad($fila);
        }

        return null;
    }

    // 🔹 Inserta un nuevo profesor
    public function insertarProfesor(Profesor $profesor): bool|int
    {
        $sql = "INSERT INTO profesores (dni, nombre, apellido, email, usuario_id, activo, created_at)
                VALUES (?, ?, ?, ?, ?, 1, NOW())";

        return $this->executeNonQuery($sql, [
            $profesor->getDni(),
            $profesor->getNombre(),
            $profesor->getApellido(),
            $profesor->getEmail(),
            $profesor->getUsuarioId()
        ]);
    }

    // 🔹 Actualiza un profesor existente
    public function updateProfesor(Profesor $profesor): bool
    {
        $sql = "UPDATE profesores SET 
                    dni = ?, 
                    nombre = ?, 
                    apellido = ?, 
                    email = ?, 
                    telefono = ?
                WHERE idProfesores = ?";

        return $this->executeNonQuery($sql, [
            $profesor->getDni(),
            $profesor->getNombre(),
            $profesor->getApellido(),
            $profesor->getEmail(),
            $profesor->getTelefono(),
            $profesor->getId()
        ]);
    }

    // 🔹 Elimina (lógicamente) un profesor
    public function deleteProfesor(int $id): bool
    {
        $sql = "UPDATE profesores SET activo = 0 WHERE idProfesores = ?";
        return $this->executeNonQuery($sql, [$id]);
    }

    // 🔹 Elimina profesor por usuario_id (borrado físico)
    public function eliminarPorUsuarioId(int $usuario_id): bool
    {
        $sql = "DELETE FROM profesores WHERE usuario_id = ?";
        return $this->executeNonQuery($sql, [$usuario_id]);
    }

    // 🔹 Asigna un curso a un profesor
    public function asignarCursoAProfesor(int $idProfesor, int $idCurso): bool
    {
        $sql = "INSERT INTO profesor_curso (id_profesor, id_curso) VALUES (?, ?)";
        return $this->executeNonQuery($sql, [$idProfesor, $idCurso]);
    }

    // 🔹 Obtiene los cursos de un profesor
    public function getCursosDelProfesor(int $idProfesor): array
    {
        $sql = "SELECT c.* FROM cursos c 
                INNER JOIN profesor_curso pc ON c.idCurso = pc.id_curso 
                WHERE pc.id_profesor = ?";
        $resultado = $this->executeQuery($sql, [$idProfesor]);

        $cursos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $cursos[] = $fila;
        }

        return $cursos;
    }

    // 🔹 Elimina una relación curso-profesor
    public function eliminarCursoDelProfesor(int $idProfesor, int $idCurso): bool
    {
        $sql = "DELETE FROM profesor_curso WHERE id_profesor = ? AND id_curso = ?";
        return $this->executeNonQuery($sql, [$idProfesor, $idCurso]);
    }

    // 🔹 Carga un objeto Profesor desde una fila
    protected function doLoad(array $columna): Profesor
    {
        return new Profesor(
            (int)$columna['idProfesores'],
            $columna['dni'] ?? null,
            $columna['nombre'] ?? '',
            $columna['apellido'] ?? '',
            $columna['email'] ?? null,
            $columna['telefono'] ?? null,
            (bool)$columna['activo'],
            isset($columna['usuario_id']) ? (int)$columna['usuario_id'] : null
        );
    }
}
