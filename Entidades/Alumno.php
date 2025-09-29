<?php
require_once("Persona.php");

class Alumno
{
    private ?int $id;
    private string $dni;
    private string $nombre;
    private string $apellido;

    private string $genero;
    private string $nacionalidad;
    private string $fechaNacimiento;
    private string $direccion;
    private int $idCurso;
    private int $idTutor;
    private int $idPreceptor;
    public function __construct(?int $id, string $dni, string $nombre, string $apellido, string $genero, string $nacionalidad, string $fechaNacimiento, string $direccion, int $idCurso, int $idTutor, int $idPreceptor)
    {
        $this->id = $id;
        $this->dni = $dni;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->genero = $genero;
        $this->nacionalidad = $nacionalidad;
        $this->fechaNacimiento = $fechaNacimiento;
        $this->direccion = $direccion;
        $this->idCurso = $idCurso;
        $this->idTutor = $idTutor;
        $this->idPreceptor = $idPreceptor;
    }




    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of dni
     */
    public function getDni(): string
    {
        return $this->dni;
    }

    /**
     * Set the value of dni
     */
    public function setDni(string $dni): self
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * Get the value of nombre
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     */
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of apellido
     */
    public function getApellido(): string
    {
        return $this->apellido;
    }

    /**
     * Set the value of apellido
     */
    public function setApellido(string $apellido): self
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get the value of genero
     */
    public function getGenero(): string
    {
        return $this->genero;
    }

    /**
     * Set the value of genero
     */
    public function setGenero(string $genero): self
    {
        $this->genero = $genero;

        return $this;
    }

    /**
     * Get the value of nacionalidad
     */
    public function getNacionalidad(): string
    {
        return $this->nacionalidad;
    }

    /**
     * Set the value of nacionalidad
     */
    public function setNacionalidad(string $nacionalidad): self
    {
        $this->nacionalidad = $nacionalidad;

        return $this;
    }

    /**
     * Get the value of fechaNacimiento
     */
    public function getFechaNacimiento(): string
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set the value of fechaNacimiento
     */
    public function setFechaNacimiento(string $fechaNacimiento): self
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get the value of direccion
     */
    public function getDireccion(): string
    {
        return $this->direccion;
    }

    /**
     * Set the value of direccion
     */
    public function setDireccion(string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get the value of idCurso
     */
    public function getIdCurso(): int
    {
        return $this->idCurso;
    }

    /**
     * Set the value of idCurso
     */
    public function setIdCurso(int $idCurso): self
    {
        $this->idCurso = $idCurso;

        return $this;
    }

    /**
     * Get the value of idTutor
     */
    public function getIdTutor(): int
    {
        return $this->idTutor;
    }

    /**
     * Set the value of idTutor
     */
    public function setIdTutor(int $idTutor): self
    {
        $this->idTutor = $idTutor;

        return $this;
    }

    /**
     * Get the value of idPreceptor
     */
    public function getIdPreceptor(): int
    {
        return $this->idPreceptor;
    }

    /**
     * Set the value of idPreceptor
     */
    public function setIdPreceptor(int $idPreceptor): self
    {
        $this->idPreceptor = $idPreceptor;

        return $this;
    }
}
