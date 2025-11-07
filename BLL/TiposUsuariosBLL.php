<?php
require_once(__DIR__ . "/../DAL/TiposUsuauriosDAL.php");

class TiposUsuariosBLL
{
    public static function ListaTiposUsuarios(): array
    {
        // ✅ Nombre correcto de la clase DAL
        $tiposDAL = new TiposUsuariosDAL();
        return $tiposDAL->getAllTipos();
    }
}
