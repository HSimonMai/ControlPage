<?php
require_once(__DIR__ . "/../Entidades/TipoUsuario.php");
require_once(__DIR__ . "/AbstractMapper.php");

class TiposUsuariosDAL extends AbstractMapper
{
    /**
     * 🔹 Retorna todos los tipos de usuarios
     * @return TipoUsuario[]
     */
    public function getAllTipos(): array
    {
        // ✅ Asignamos la consulta directamente (sin setConsulta)
        $this->consulta = "SELECT * FROM tiposusuarios";
        return $this->FindAll();
    }

    /**
     * 🔹 Mapea una fila del resultado a un objeto TipoUsuario
     */
    protected function doLoad($columna): TipoUsuario
    {
        return new TipoUsuario(
            (int)($columna["idTiposUsuarios"] ?? 0),
            (string)($columna["TipoUsuario"] ?? "")
        );
    }
}
