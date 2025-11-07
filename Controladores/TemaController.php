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

    // ➕ Agregar un tema nuevo
    public function agregarTema(array $datos, int $idProfesor, ?int $idCurso): bool {
        $tema = new Tema(
            $idProfesor,
            null, // id
            $idCurso,
            null, // idProfesorCurso
            !empty($datos["idtipoClase"]) ? intval($datos["idtipoClase"]) : null,
            !empty($datos["numero_clase"]) ? intval($datos["numero_clase"]) : null,
            trim($datos["titulo"]),
            trim($datos["descripcion"]),
            $datos["fecha"] ?? date('Y-m-d'),
            isset($datos["firma_profesor"]),
            false // firmaAutoridad
        );

        return $this->temaBLL->agregarTema($tema);
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
}
