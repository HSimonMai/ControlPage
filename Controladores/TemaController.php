<?php
require_once(__DIR__ . "/../BLL/TemaBLL.php");
require_once(__DIR__ . "/../entidades/Tema.php");

class TemaController {
    private TemaBLL $temaBLL;

    public function __construct() {
        $this->temaBLL = new TemaBLL();
    }

    // 📋 Listar temas por curso
    public function listarTemas(int $idProfesor, int $idCurso): array {
        return $this->temaBLL->listarTemasPorCurso($idProfesor, $idCurso);
    }

    public function agregarTema(array $datos, int $idProfesor, ?int $idCurso): bool
    {
        $cantidadModulos = isset($datos["cantidad_modulos"]) ? intval($datos["cantidad_modulos"]) : 1;

        // Si no envía número de clase → autoincrement
        if (empty($datos["numero_clase"])) {
            $ultimo = $this->temaBLL->obtenerUltimoNumeroClase($idProfesor, $idCurso);
            $numeroActual = $ultimo + 1;
        } else {
            $numeroActual = intval($datos["numero_clase"]);
        }

        $ok = true;

        for ($i = 0; $i < $cantidadModulos; $i++) {

            $tema = new Tema(
                $idProfesor,
                null,
                $idCurso,
                null,
                !empty($datos["idtipoClase"]) ? intval($datos["idtipoClase"]) : null,
                $numeroActual + $i, // 🔹 autoincrementa para cada módulo
                trim($datos["titulo"]),
                trim($datos["descripcion"]),
                $datos["fecha"] ?? date('Y-m-d'),
                isset($datos["firma_profesor"]),
                false 
            );

            // Inserta uno por uno
            if (!$this->temaBLL->agregarTema($tema)) {
                $ok = false;
            }
        }

        return $ok;
    }

    // 🗑️ Eliminar tema
    public function eliminarTema(int $id): bool {
        return $this->temaBLL->eliminarTema($id);
    }

    // ✍️ Actualizar firma de autoridad
    public function actualizarFirmaAutoridad(int $idTema, int $estado): bool {
        return $this->temaBLL->actualizarFirmaAutoridad($idTema, $estado);
    }

    // 🔹 Obtener tipos de clase
    public function getTiposClase(): array {
        return $this->temaBLL->getTiposClase();
    }

    public function getProximoNumeroClase(int $idProfesor, int $idCurso): int {
        return $this->temaBLL->getProximoNumeroClase($idProfesor, $idCurso);
    }
}
?>
