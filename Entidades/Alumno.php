<?php
class Alumno
{
    private int $idAlumnos;
    private string $nombre;
    private string $apellido;
    private string $dni;

    public function __construct(int $idAlumnos, string $nombre, string $apellido, string $dni)
    {
        $this->idAlumnos = $idAlumnos;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->dni = $dni;
    }

    public function getId(): int { return $this->idAlumnos; }
    public function getNombre(): string { return $this->nombre; }
    public function getApellido(): string { return $this->apellido; }
    public function getDni(): string { return $this->dni; }
}
