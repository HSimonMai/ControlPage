<?php
require_once("../Entidades/Usuario.php");
require_once("AbstractMapper.php");

class UsuarioDAL extends AbstractMapper
{
    public function UpdateUser($usuario)
    {
        $consulta = "UPDATE usuarios 
            SET DNI='" . $usuario->getDni() . "',
                Email='" . $usuario->getEmail() . "',
                Contrasena='" . $usuario->getContrasena() . "',
                Nombre='" . $usuario->getNombre() . "',
                Apellido='" . $usuario->getApellido() . "',
                idTiposUsuarios='" . $usuario->getIdTiposUsuarios() . "'
            WHERE idUsuarios='" . $usuario->getId() . "';";   
        $this->setConsulta($consulta);
        return $this->Execute();
    }

    public function DeleteUser($id)
    {
        $consulta = "DELETE FROM usuarios WHERE idUsuarios = '$id'";
        $this->setConsulta($consulta);
        return $this->Execute();
    }

    public function InsertarUsuario($usuario)
    {
        $consulta = "INSERT INTO usuarios(DNI,Email,Contrasena,Nombre,Apellido,idTiposUsuarios) VALUES
        ('" . $usuario->getDni() . "',
        '" . $usuario->getEmail() . "',
        '" . $usuario->getContrasena() . "',
        '" . $usuario->getNombre() . "',
        '" . $usuario->getApellido() . "',
        '" . $usuario->getIdTiposUsuarios() . "')";
        $this->setConsulta($consulta);
        return $this->Execute();
    }

    //CONSEGUIR USUARIOS POR GMAIL
    public function getUsuarioByEmail($email): ?Usuario
    {
        $consulta = "SELECT * FROM usuarios WHERE Email = '$email' LIMIT 1";
        $this->setConsulta($consulta);
        $usuario = $this->Find();
        if ($usuario instanceof Usuario) {
            return $usuario;
        }
        return null;
    }

    public function getAllUsuarios(): array
    {
        $consulta = "SELECT * FROM usuarios";
        $this->setConsulta($consulta);
        return $this->FindAll();
    }

    public function getUsuarioByIdCurso($idUsuario)
    {
        $consulta = "SELECT * FROM usuarios WHERE idUsuarios= '$idUsuario'";
        $this->setConsulta($consulta);
        return $this->FindAll();
    }

    public function getCursoById($idCurso)
    {
        $consulta = "SELECT * FROM usuarios WHERE idUsuarios= '$idCurso'";
        $this->setConsulta($consulta);
        return $this->Find();
    }

    public function doLoad($columna)
    {
        $id = (int)$columna["idUsuarios"];
        $dni = (string)$columna["DNI"];
        $email = (string)$columna["Email"];
        $contrasena = (string)$columna["Contrasena"];
        $nombre = (string)$columna["Nombre"];
        $apellido = (string)$columna["Apellido"];
        $idTipoUsuario = (int)$columna["idTiposUsuarios"];

        return new Usuario(
            $id,
            $dni,
            $email,
            $contrasena,
            $nombre,
            $apellido,
            $idTipoUsuario
        );
    }
    public function AuthUsuario(string $nombreUsuario, string $contrasena): ?Usuario
{
    // Buscamos el usuario por su nombre
    $consulta = "SELECT * FROM usuarios WHERE Nombre = '$nombreUsuario' LIMIT 1";
    $this->setConsulta($consulta);

    // Ejecutamos Find() para obtener un objeto Usuario
    $usuario = $this->Find();

    // Verificamos que exista y que la contraseña coincida
    if ($usuario instanceof Usuario && password_verify($contrasena, $usuario->getContrasena())) {
        return $usuario; // autenticación correcta
    }

    return null; // usuario no existe o contraseña incorrecta
}

}
