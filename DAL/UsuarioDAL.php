<?php
require_once("../Entidades/Usuario.php");
require_once("AbstractMapper.php");

class UsuarioDAL extends AbstractMapper
{
    public function FindAllAsistencias(): array
    {
        $this->setConsulta("SELECT FechaAsistencia, ValorAsistencia FROM asistencias");
        return $this->FindAll();
    }

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
        // RECIBE LA CONTRASENA DESDE BLL YA HASHEADA
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
        // Escapar comillas simples (sin acceder a la conexión directamente)
        $nombreUsuario = str_replace("'", "''", $nombreUsuario);

        // Permitir login tanto por nombre como por email
        $consulta = "SELECT * FROM usuarios WHERE Nombre = '$nombreUsuario' OR Email = '$nombreUsuario' LIMIT 1";
        $this->setConsulta($consulta);

        $usuario = $this->Find();

        if ($usuario instanceof Usuario) {
            $hash = $usuario->getContrasena();

            // Si la contraseña está hasheada
            if (password_verify($contrasena, $hash)) {
                return $usuario;
            }

            // Si está en texto plano (para compatibilidad)
            if ($contrasena === $hash) {
                return $usuario;
            }
        }

        // Usuario no encontrado o credenciales incorrectas
        return null;
    }
}
