<?php
require_once("../Entidades/TipoUsuario.php");
require_once("AbstractMapper.php");

class TiposUsuauriosDAL extends AbstractMapper
{

    //Retorna un array de objetos de tipo "TiposUsuarios"
    public function getAllTipos()
    {
        $consulta= "SELECT * FROM tiposusuarios";
        $this->setConsulta($consulta);
        $id= $this->FindAll();
        return $id;
    }

    public function doLoad($columna)
    {
        $id= (int) $columna["idTiposUsuarios"];
        $tipo= (string) $columna["TipoUsuario"];
        $tiposUsuarios= new TipoUsuario(
            $id,
            $tipo
        );
        return $tiposUsuarios;

    }
}