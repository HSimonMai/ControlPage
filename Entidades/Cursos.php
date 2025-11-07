<?php
// Entidades/Cursos.php

class Curso
{
    private ?int $idCursos;
    private ?int $Año;
    private ?string $Division;
    private ?string $asignatura;
    private ?string $anio_lectivo;

    public function __construct(
        ?int $idCursos = null,
        ?int $Año = null,
        ?string $Division = null,
        ?string $asignatura = null,
        ?string $anio_lectivo = null
    ) {
        $this->idCursos = $idCursos;
        $this->Año = $Año;
        $this->Division = $Division;
        $this->asignatura = $asignatura;
        $this->anio_lectivo = $anio_lectivo;
    }

    // --- Getters ---
    public function getId(): ?int
    {
        return $this->idCursos;
    }

    public function getAno(): ?int
    {
        return $this->Año;
    }

    public function getDivision(): ?string
    {
        return $this->Division;
    }

    public function getAsignatura(): ?string
    {
        return $this->asignatura;
    }

    public function getAnioLectivo(): ?string
    {
        return $this->anio_lectivo;
    }

    // --- Setters ---
    public function setId(?int $id): void
    {
        $this->idCursos = $id;
    }

    public function setAno(?int $ano): void
    {
        $this->Año = $ano;
    }

    public function setDivision(?string $division): void
    {
        $this->Division = $division;
    }

    public function setAsignatura(?string $asignatura): void
    {
        $this->asignatura = $asignatura;
    }

    public function setAnioLectivo(?string $anio): void
    {
        $this->anio_lectivo = $anio;
    }

    // --- Método auxiliar ---
    public function getNombreCompleto(): string
    {
        return trim("{$this->Año}° {$this->Division}");
    }
}
