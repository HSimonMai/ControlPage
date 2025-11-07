<?php
require_once(__DIR__ . "/../DAL/CursoDAL.php");
require_once(__DIR__ . "/../Entidades/Cursos.php");

class CursoController
{
    private CursoDAL $cursoDAL;

    public function __construct()
    {
        $this->cursoDAL = new CursoDAL();
        $this->iniciarSesionSegura();
    }

    // 🔹 Verifica que la sesión esté activa solo una vez
    private function iniciarSesionSegura(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // 🔹 Devuelve todos los cursos del profesor (usa DAL)
    public function obtenerCursosPorProfesor(int $idProfesor): array
    {
        $cursos = $this->cursoDAL->obtenerCursosPorProfesor($idProfesor);
        return is_array($cursos) ? $cursos : [];
    }

    // 🔹 Guarda el curso seleccionado en la sesión
    public function seleccionarCurso(int $idCurso): void
    {
        $_SESSION["idCursoSeleccionado"] = $idCurso;
    }

    // 🔹 Obtiene el curso seleccionado actualmente
    public function obtenerCursoSeleccionado(): ?int
    {
        return $_SESSION["idCursoSeleccionado"] ?? null;
    }

    // 🔹 Limpia la selección (por ejemplo, al salir)
    public function limpiarCursoSeleccionado(): void
    {
        unset($_SESSION["idCursoSeleccionado"]);
    }
}
