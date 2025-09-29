<?php

class Persona
{
    private int $id;
    private string $nombre;
    private string $apellido;
    private string $dni;
    private string $direccion;
    private int $edad;
    private string $nacionalidad;
    private DateTime $fechaNacimiento;

    public function __construct(int $id, string $nombre, string $apellido, string $dni, string $direccion, int $edad, string $nacionalidad, DateTime $fechaNacimiento)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->dni = $dni;
        $this->direccion = $direccion;
        $this->edad = $edad;
        $this->nacionalidad = $nacionalidad;
        $this->fechaNacimiento = $fechaNacimiento;
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
     * Get the value of edad
     */
    public function getEdad(): int
    {
        return $this->edad;
    }

    /**
     * Set the value of edad
     */
    public function setEdad(int $edad): self
    {
        $this->edad = $edad;

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
    public function getFechaNacimiento(): DateTime
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set the value of fechaNacimiento
     */
    public function setFechaNacimiento(DateTime $fechaNacimiento): self
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }
}
