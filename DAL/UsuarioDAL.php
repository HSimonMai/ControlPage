<?php
require_once(__DIR__ . "/../Entidades/Usuario.php");
require_once("AbstractMapper.php");

class UsuarioDAL extends AbstractMapper
{
    // 🔹 Obtener todas las asistencias (solo ejemplo)
    public function findAllAsistencias(): array
    {
        $this->setConsulta("SELECT FechaAsistencia, ValorAsistencia FROM asistencias");
        return $this->findAll();
    }

    // 🔹 Actualizar usuario
    public function updateUser(Usuario $usuario): bool
    {
        $sql = "UPDATE usuarios 
                SET DNI = ?, Email = ?, Contrasena = ?, Nombre = ?, Apellido = ?, idTiposUsuarios = ?
                WHERE idUsuarios = ?";
        return $this->executeNonQuery($sql, [
            $usuario->getDni(),
            $usuario->getEmail(),
            $usuario->getContrasena(),
            $usuario->getNombre(),
            $usuario->getApellido(),
            $usuario->getIdTiposUsuarios(),
            $usuario->getId()
        ]);
    }

    // 🔹 Eliminar usuario
    public function deleteUser(int $id): bool
    {
        $sql = "DELETE FROM usuarios WHERE idUsuarios = ?";
        return $this->executeNonQuery($sql, [$id]);
    }

    // 🔹 Insertar usuario
    public function insertarUsuario(Usuario $usuario): bool|int
    {
        $sql = "INSERT INTO usuarios (DNI, Email, Contrasena, Nombre, Apellido, idTiposUsuarios)
                VALUES (?, ?, ?, ?, ?, ?)";
        return $this->executeNonQuery($sql, [
            $usuario->getDni(),
            $usuario->getEmail(),
            $usuario->getContrasena(),
            $usuario->getNombre(),
            $usuario->getApellido(),
            $usuario->getIdTiposUsuarios()
        ]);
    }

    // 🔹 Obtener usuario por email
    public function getUsuarioByEmail(string $email): ?Usuario
    {
        $sql = "SELECT * FROM usuarios WHERE Email = ? LIMIT 1";
        $resultado = $this->executeQuery($sql, [$email]);

        if ($fila = $resultado->fetch_assoc()) {
            return $this->doLoad($fila);
        }
        return null;
    }

    // 🔹 Obtener todos los usuarios
    public function getAllUsuarios(): array
    {
        $this->setConsulta("SELECT * FROM usuarios");
        return $this->findAll();
    }

    // 🔹 Obtener usuario por ID
    public function getUsuarioById(int $idUsuario): ?Usuario
    {
        $sql = "SELECT * FROM usuarios WHERE idUsuarios = ?";
        $resultado = $this->executeQuery($sql, [$idUsuario]);

        if ($fila = $resultado->fetch_assoc()) {
            return $this->doLoad($fila);
        }
        return null;
    }

    // 🔹 Autenticación de usuario
    public function authUsuario(string $nombreUsuario, string $contrasena): ?Usuario
    {
        // Permite login tanto por nombre como por email
        $sql = "SELECT * FROM usuarios WHERE Nombre = ? OR Email = ? LIMIT 1";
        $resultado = $this->executeQuery($sql, [$nombreUsuario, $nombreUsuario]);

        if ($fila = $resultado->fetch_assoc()) {
            $usuario = $this->doLoad($fila);
            $hash = $usuario->getContrasena();

            // Si la contraseña está hasheada
            if (password_verify($contrasena, $hash)) {
                return $usuario;
            }

            // Si está en texto plano (modo compatibilidad)
            if ($contrasena === $hash) {
                return $usuario;
            }
        }

        // Usuario no encontrado o credenciales incorrectas
        return null;
    }

    // 🔹 Cargar un usuario desde una fila
    protected function doLoad(array $columna): Usuario
    {
        return new Usuario(
            (int)$columna["idUsuarios"],
            $columna["DNI"] ?? "",
            $columna["Email"] ?? "",
            $columna["Contrasena"] ?? "",
            $columna["Nombre"] ?? "",
            $columna["Apellido"] ?? "",
            isset($columna["idTiposUsuarios"]) ? (int)$columna["idTiposUsuarios"] : null
        );
    }
}
