<?php
require_once("../DAL/TiposUsuauriosDAL.php");

class TiposUsuariosBLL
{
    public static function ListaTiposUsuarios(): array
    {
        $tiposDAL= new TiposUsuauriosDAL();
        $lista= $tiposDAL->getAllTipos();
        return $lista;
    }
}