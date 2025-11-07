<?php
class Tutor
{
    public int $id;
    public string $nombre;
    public string $apellido;
    public string $dni;
    public string $email;
    public string $telefono;
    public array $alumnos; // lista de alumnos asociados

    public function __construct(
        int $id = 0,
        string $nombre = "",
        string $apellido = "",
        string $dni = "",
        string $email = "",
        string $telefono = "",
        array $alumnos = []
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->dni = $dni;
        $this->email = $email;
        $this->telefono = $telefono;
        $this->alumnos = $alumnos;
    }
}
