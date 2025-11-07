<?php
class Profesor
{
    private int $id;
    private ?string $dni;
    private string $nombre;
    private string $apellido;
    private ?string $email;
    private ?string $telefono;
    private bool $activo;
    private ?int $usuario_id;

    public function __construct(
        int $id,
        ?string $dni,
        string $nombre,
        string $apellido,
        ?string $email = null,
        ?string $telefono = null,
        bool $activo = true,
        ?int $usuario_id = null
    ) {
        $this->id = $id;
        $this->dni = $dni;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->email = $email;
        $this->telefono = $telefono;
        $this->activo = $activo;
        $this->usuario_id = $usuario_id;
    }

    // --- Getters ---
    public function getId(): int
    {
        return $this->id;
    }

    public function getDni(): ?string
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function isActivo(): bool
    {
        return $this->activo;
    }

    public function getUsuarioId(): ?int
    {
        return $this->usuario_id;
    }

    // --- Setters ---
    public function setDni(?string $dni): self
    {
        $this->dni = $dni;
        return $this;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function setApellido(string $apellido): self
    {
        $this->apellido = $apellido;
        return $this;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;
        return $this;
    }

    public function setActivo(bool $activo): self
    {
        $this->activo = $activo;
        return $this;
    }

    public function setUsuarioId(?int $usuario_id): self
    {
        $this->usuario_id = $usuario_id;
        return $this;
    }
}
?>
