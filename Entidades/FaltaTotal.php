<?php
class FaltaTotal
{
    private int $idAlumno;
    private int $dni;
    private string $nombre;
    private string $apellido;
    private float $totalAsitencias;
    private float $mediasFaltas;

    private float $totalFaltas;
    public function __construct(int $idAlumno, int $dni, string $nombre, string $apellido, float $totalAsitencias, float $mediasFaltas, float $totalFaltas)
    {
        $this->idAlumno = $idAlumno;
        $this->dni = $dni;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->totalAsitencias = $totalAsitencias;
        $this->mediasFaltas = $mediasFaltas;
        $this->totalFaltas = $totalFaltas;
    }
    public function getIdAlumno(): int
    {
        return $this->idAlumno;
    }

    public function getDni(): int
    {
        return $this->dni;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getApellido(): string
    {
        return $this->apellido;
    }

    public function getTotalAsitencias(): float
    {
        return $this->totalAsitencias;
    }

    public function getMediasFaltas(): float
    {
        return $this->mediasFaltas;
    }

    public function getTotalFaltas(): float
    {
        return $this->totalFaltas;
    }
}
