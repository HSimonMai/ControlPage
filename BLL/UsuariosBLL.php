<?php
require_once("../Entidades/Usuario.php");
require_once("../DAL/UsuarioDAL.php");

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
            // INICIA SESSION SI NO ESTA INICIADA
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // GUARDA EL OBJ COMPLETO EN LA SESSION
            $_SESSION['usuario'] = $usuario;
        }

        return $usuario; // devuelve el objeto o null si falló
    }

    public function GrabarUsuario(Usuario $usuario): int
    {
        $usuarioDAL = new UsuarioDAL();

        // HASHEA LA CONTRASEÑA ANTES DE GUARDAR
        $contrasenaPlano = $usuario->getContrasena();
        $hash = password_hash($contrasenaPlano, PASSWORD_DEFAULT);
        $usuario->setContrasena($hash);

        return $usuarioDAL->InsertarUsuario($usuario);
    }

    public function UpdateUsuario(Usuario $usuario): bool
    {
        $usuarioDAL = new UsuarioDAL();

        // HASHEA LA CONTRASENA SI EL USUAIRO LA VUELVE A CAMBIAR
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

    public static function obtenerCursos(int $idUsuario): array
    {
        return CursoBLL::getCursosByIdPreceptor($idUsuario);
    }

    public static function getCursoByUsuario(int $idUsuario)
    {
        $usuarioDAL = new UsuarioDAL();
        return $usuarioDAL->getCursoById($idUsuario);
    }
}
