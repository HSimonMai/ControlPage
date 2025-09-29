<?php

class Mail
{
    private string $nombreAlumno;
    private string $apellidoAlumno;
    private int $dniAlumno;
    private string $cursoAno;
    private string $cursoDivision;
    private string $nombreTutor;
    private string $apellidoTutor;
    private string $emailTutor;
    public function __construct(string $nombreAlumno, string $apellidoAlumno, int $dniAlumno, string $cursoAno, string $cursoDivision, string $nombreTutor, string $apellidoTutor, string $emailTutor)
    {
        $this->nombreAlumno = $nombreAlumno;
        $this->apellidoAlumno = $apellidoAlumno;
        $this->dniAlumno = $dniAlumno;
        $this->cursoAno = $cursoAno;
        $this->cursoDivision = $cursoDivision;
        $this->nombreTutor = $nombreTutor;
        $this->apellidoTutor = $apellidoTutor;
        $this->emailTutor = $emailTutor;
    }

    public function getNombreAlumno(): string
    {
        return $this->nombreAlumno;
    }

    public function getApellidoAlumno(): string
    {
        return $this->apellidoAlumno;
    }

    public function getDniAlumno(): int
    {
        return $this->dniAlumno;
    }

    public function getCursoAno(): string
    {
        return $this->cursoAno;
    }

    public function getCursoDivision(): string
    {
        return $this->cursoDivision;
    }

    public function getNombreTutor(): string
    {
        return $this->nombreTutor;
    }

    public function getApellidoTutor(): string
    {
        return $this->apellidoTutor;
    }

    public function getEmailTutor(): string
    {
        return $this->emailTutor;
    }

    public function setNombreAlumno(string $nombreAlumno): void
    {
        $this->nombreAlumno = $nombreAlumno;
    }

    public function setApellidoAlumno(string $apellidoAlumno): void
    {
        $this->apellidoAlumno = $apellidoAlumno;
    }

    public function setDniAlumno(int $dniAlumno): void
    {
        $this->dniAlumno = $dniAlumno;
    }

    public function setCursoAno(string $cursoAno): void
    {
        $this->cursoAno = $cursoAno;
    }

    public function setCursoDivision(string $cursoDivision): void
    {
        $this->cursoDivision = $cursoDivision;
    }

    public function setNombreTutor(string $nombreTutor): void
    {
        $this->nombreTutor = $nombreTutor;
    }

    public function setApellidoTutor(string $apellidoTutor): void
    {
        $this->apellidoTutor = $apellidoTutor;
    }

    public function setEmailTutor(string $emailTutor): void
    {
        $this->emailTutor = $emailTutor;
    }
}
