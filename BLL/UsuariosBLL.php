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
        return $usuario; // devuelve null si no existe
    }

    public function GrabarUsuario(Usuario $usuario): int
    {
        $usuarioDAL = new UsuarioDAL();
        return $usuarioDAL->InsertarUsuario($usuario);
    }

    public function UpdateUsuario(Usuario $usuario): bool
    {
        $usuarioDAL = new UsuarioDAL();
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
