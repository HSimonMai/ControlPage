<?php
require_once(__DIR__ . "/../DAL/TemaMapper.php");
require_once(__DIR__ . "/../Entidades/Tema.php");

class TemaBLL
{
    private TemaMapper $mapper;

    public function __construct()
    {
        $this->mapper = new TemaMapper();
    }


    public function listarTemasPorCurso(int $idProfesor, ?int $idCurso = null): array
    {
        $idProfesorCurso = null; // Solo si manejas asignaciones especiales
        return $this->mapper->findAllByProfesorYCarrera(
            $idProfesor,
            $idProfesorCurso,
            $idCurso
        );
    }

    public function agregarTema(Tema $tema): bool
    {
        return $this->mapper->insert($tema);
    }

    public function eliminarTema(int $idTema): bool
    {
        return $this->mapper->delete($idTema);
    }

    public function actualizarFirmaAutoridad(int $idTema, bool $estado): bool
    {
        return $this->mapper->updateFirmaAutoridad($idTema, $estado);
    }

    public function getTiposClase(): array
    {
        $conexion = new mysqli("localhost", "root", "2901", "control");
        if ($conexion->connect_error) die("Error de conexión: " . $conexion->connect_error);
        $conexion->set_charset("utf8");

        $stmt = $conexion->prepare("SELECT idtipoClase, tipoClase FROM tipoclase ORDER BY tipoClase");
        $stmt->execute();
        $result = $stmt->get_result();

        $tipos = [];
        while ($row = $result->fetch_assoc()) {
            $tipos[] = $row;
        }

        $stmt->close();
        $conexion->close();
        return $tipos;
    }
}
?>
