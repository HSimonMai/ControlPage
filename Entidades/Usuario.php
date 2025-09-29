<?php
class Usuario
{
    public int $id;
    private string $dni;
    private string $email;
    private string $contrasena;
    private string $nombre;
    private string $apellido;
    private string $idTiposUsuarios;

    
public function __construct(int $id, string $dni, string $email, string $contrasena, string $nombre, string $apellido, string $idTiposUsuarios){$this->id = $id;$this->dni = $dni;$this->email = $email;$this->contrasena = $contrasena;$this->nombre = $nombre;$this->apellido = $apellido;$this->idTiposUsuarios = $idTiposUsuarios;}
	

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
     * Get the value of email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of contrasena
     */
    public function getContrasena(): string
    {
        return $this->contrasena;
    }

    /**
     * Set the value of contrasena
     */
    public function setContrasena(string $contrasena): self
    {
        $this->contrasena = $contrasena;

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
     * Get the value of idTiposUsuarios
     */
    public function getIdTiposUsuarios(): string
    {
        return $this->idTiposUsuarios;
    }

    /**
     * Set the value of idTiposUsuarios
     */
    public function setIdTiposUsuarios(string $idTiposUsuarios): self
    {
        $this->idTiposUsuarios = $idTiposUsuarios;

        return $this;
    }
}
