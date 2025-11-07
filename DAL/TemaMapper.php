<?php
require_once(__DIR__ . "/AbstractMapper.php");
require_once(__DIR__ . "/../Entidades/Tema.php");

class TemaMapper extends AbstractMapper
{
    // 🔹 Obtener todos los temas por profesor y curso (usando idProfesorCurso si existe)
    public function findAllByProfesorYCarrera(int $idProfesor, ?int $idProfesorCurso = null, ?int $idCurso = null): array
    {
        $conexion = new mysqli("localhost", "root", "2901", "control");
        if ($conexion->connect_error) die("Error de conexión: " . $conexion->connect_error);
        $conexion->set_charset("utf8");

        // Si el profesor tiene un curso asignado por idProfesorCurso
        if ($idProfesorCurso !== null) {
            $stmt = $conexion->prepare("
                SELECT id_tema, id_profesor, idCurso, idProfesorCurso, idtipoClase, numero_clase,
                       titulo, descripcion, fecha, firma_profesor, firma_autoridad
                FROM temas
                WHERE id_profesor = ? AND idProfesorCurso = ?
                ORDER BY id_tema DESC
            ");
            $stmt->bind_param("ii", $idProfesor, $idProfesorCurso);
        }
        // Si usa idCurso directamente
        elseif ($idCurso !== null) {
            $stmt = $conexion->prepare("
                SELECT id_tema, id_profesor, idCurso, idProfesorCurso, idtipoClase, numero_clase,
                       titulo, descripcion, fecha, firma_profesor, firma_autoridad
                FROM temas
                WHERE id_profesor = ? AND idCurso = ?
                ORDER BY id_tema DESC
            ");
            $stmt->bind_param("ii", $idProfesor, $idCurso);
        } else {
            // Si no se pasa ni idCurso ni idProfesorCurso, trae todos los temas del profesor
            $stmt = $conexion->prepare("
                SELECT id_tema, id_profesor, idCurso, idProfesorCurso, idtipoClase, numero_clase,
                       titulo, descripcion, fecha, firma_profesor, firma_autoridad
                FROM temas
                WHERE id_profesor = ?
                ORDER BY id_tema DESC
            ");
            $stmt->bind_param("i", $idProfesor);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $temas = [];
        while ($row = $result->fetch_assoc()) {
            $temas[] = $this->mapRowToObject($row);
        }

        $stmt->close();
        $conexion->close();
        return $temas;
    }

    // 🔹 Insertar nuevo tema
    public function insert(Tema $tema): bool
    {
        $conexion = new mysqli("localhost", "root", "2901", "control");
        if ($conexion->connect_error) die("Error de conexión: " . $conexion->connect_error);
        $conexion->set_charset("utf8");

        $stmt = $conexion->prepare("
            INSERT INTO temas (
                id_profesor, idCurso, idProfesorCurso, idtipoClase,
                numero_clase, titulo, descripcion, fecha, firma_profesor, firma_autoridad
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $idProfesorCurso = $tema->getIdProfesorCurso() ?? null;
        $idTipoClase = $tema->getIdTipoClase() ?? null;
        $numeroClase = $tema->getNumeroClase() ?? null;
        $firmaProfesor = $tema->isFirmaProfesor() ? 1 : 0;
        $firmaAutoridad = $tema->isFirmaAutoridad() ? 1 : 0;

        $stmt->bind_param(
            "iiiiisssii",
            $tema->getIdProfesor(),
            $tema->getIdCurso(),
            $idProfesorCurso,
            $idTipoClase,
            $numeroClase,
            $tema->getTitulo(),
            $tema->getDescripcion(),
            $tema->getFecha(),
            $firmaProfesor,
            $firmaAutoridad
        );

        $ok = $stmt->execute();
        if (!$ok) die("❌ Error al insertar tema: " . $stmt->error);

        $stmt->close();
        $conexion->close();
        return $ok;
    }

    // 🔹 Eliminar tema
    public function delete(int $idTema): bool
    {
        $conexion = new mysqli("localhost", "root", "2901", "control");
        $conexion->set_charset("utf8");
        $stmt = $conexion->prepare("DELETE FROM temas WHERE id_tema = ?");
        $stmt->bind_param("i", $idTema);
        $ok = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $ok;
    }

    // 🔹 Actualizar firma de autoridad
    public function updateFirmaAutoridad(int $idTema, bool $estado): bool
    {
        $conexion = new mysqli("localhost", "root", "2901", "control");
        $conexion->set_charset("utf8");
        $stmt = $conexion->prepare("UPDATE temas SET firma_autoridad = ? WHERE id_tema = ?");
        $flag = $estado ? 1 : 0;
        $stmt->bind_param("ii", $flag, $idTema);
        $ok = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $ok;
    }

    // 🔹 Mapear fila a objeto Tema
    protected function mapRowToObject(array $row): Tema
    {
        return new Tema(
            intval($row['id_profesor']),
            intval($row['id_tema']),
            $row['idCurso'] !== null ? intval($row['idCurso']) : null,
            $row['idProfesorCurso'] !== null ? intval($row['idProfesorCurso']) : null,
            $row['idtipoClase'] !== null ? intval($row['idtipoClase']) : null,
            $row['numero_clase'] !== null ? intval($row['numero_clase']) : null,
            $row['titulo'] ?? "",
            $row['descripcion'] ?? "",
            $row['fecha'] ?? "",
            boolval($row['firma_profesor']),
            boolval($row['firma_autoridad'])
        );
    }

    protected function doLoad($columna) { return $this->mapRowToObject($columna); }
}
?>
