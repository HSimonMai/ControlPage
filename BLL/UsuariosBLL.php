<?php
require_once("../Entidades/Usuario.php");
require_once("../DAL/UsuarioDAL.php");
require_once("../BLL/CursoBLL.php");

class UsuariosBLL
{
    public function DeleteUser(int $idUsuario): bool
    {
        $usuarioDAL = new UsuarioDAL();
        return $usuarioDAL->DeleteUser($idUsuario);
    }

    public function AuthUsuario(string $nombreUsuario, string $contrasena): ?Usuario
    {
        $usuarioDAL = new UsuarioDAL();
        $usuario = $usuarioDAL->AuthUsuario($nombreUsuario, $contrasena);

        if ($usuario) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['usuario'] = $usuario;
        }

        return $usuario;
    }

    public function GrabarUsuario(Usuario $usuario): int
    {
        $usuarioDAL = new UsuarioDAL();

        $contrasenaPlano = $usuario->getContrasena();
        $hash = password_hash($contrasenaPlano, PASSWORD_DEFAULT);
        $usuario->setContrasena($hash);

        return $usuarioDAL->InsertarUsuario($usuario);
    }

    public function UpdateUsuario(Usuario $usuario): bool
    {
        $usuarioDAL = new UsuarioDAL();

        $contrasenaPlano = $usuario->getContrasena();
        if (!empty($contrasenaPlano) && strlen($contrasenaPlano) < 60) {
            $hash = password_hash($contrasenaPlano, PASSWORD_DEFAULT);
            $usuario->setContrasena($hash);
        }

        return $usuarioDAL->UpdateUser($usuario);
    }

    public static function ListaAlumnos(): array
    {
        $usuarioDAL = new UsuarioDAL();
        return $usuarioDAL->getAllUsuarios();
    }

    /**
     * 🔹 Devuelve los cursos de un preceptor/profesor
     */
    public static function obtenerCursos(int $idUsuario): array
    {
        return CursoBLL::obtenerCursosPorProfesor($idUsuario); // ✅ ahora se llama correctamente de forma estática
    }

    public static function getCursoByUsuario(int $idUsuario): array
    {
        return CursoBLL::obtenerCursosPorProfesor($idUsuario); // ✅ igual que arriba
    }
}
