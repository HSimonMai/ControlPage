<?php
class Tema
{
    private ?int $id;
    private int $idProfesor;
    private ?int $idCurso;
    private ?int $idProfesorCurso;
    private ?int $idtipoClase;
    private ?int $numeroClase;
    private string $titulo;
    private string $descripcion;
    private string $fecha;
    private bool $firmaProfesor;
    private bool $firmaAutoridad;

    public function __construct(
        int $idProfesor,
        ?int $id = null,
        ?int $idCurso = null,
        ?int $idProfesorCurso = null,
        ?int $idtipoClase = null,
        ?int $numeroClase = null,
        string $titulo = "",
        string $descripcion = "",
        string $fecha = "",
        bool $firmaProfesor = false,
        bool $firmaAutoridad = false
    ) {
        $this->id = $id;
        $this->idProfesor = $idProfesor;
        $this->idCurso = $idCurso;
        $this->idProfesorCurso = $idProfesorCurso;
        $this->idtipoClase = $idtipoClase;
        $this->numeroClase = $numeroClase;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->fecha = $fecha;
        $this->firmaProfesor = $firmaProfesor;
        $this->firmaAutoridad = $firmaAutoridad;
    }

    // 🔹 Getters
    public function getId(): ?int { return $this->id; }
    public function getIdProfesor(): int { return $this->idProfesor; }
    public function getIdCurso(): ?int { return $this->idCurso; }
    public function getIdProfesorCurso(): ?int { return $this->idProfesorCurso; }
    public function getIdTipoClase(): ?int { return $this->idtipoClase; }
    public function getNumeroClase(): ?int { return $this->numeroClase; }
    public function getTitulo(): string { return $this->titulo; }
    public function getDescripcion(): string { return $this->descripcion; }
    public function getFecha(): string { return $this->fecha; }
    public function isFirmaProfesor(): bool { return $this->firmaProfesor; }
    public function isFirmaAutoridad(): bool { return $this->firmaAutoridad; }

    // 🔹 Setters
    public function setId(?int $id): void { $this->id = $id; }
    public function setIdProfesor(int $idProfesor): void { $this->idProfesor = $idProfesor; }
    public function setIdCurso(?int $idCurso): void { $this->idCurso = $idCurso; }
    public function setIdProfesorCurso(?int $idProfesorCurso): void { $this->idProfesorCurso = $idProfesorCurso; }
    public function setIdTipoClase(?int $idtipoClase): void { $this->idtipoClase = $idtipoClase; }
    public function setNumeroClase(?int $numeroClase): void { $this->numeroClase = $numeroClase; }
    public function setTitulo(string $titulo): void { $this->titulo = $titulo; }
    public function setDescripcion(string $descripcion): void { $this->descripcion = $descripcion; }
    public function setFecha(string $fecha): void { $this->fecha = $fecha; }
    public function setFirmaProfesor(bool $firmaProfesor): void { $this->firmaProfesor = $firmaProfesor; }
    public function setFirmaAutoridad(bool $firmaAutoridad): void { $this->firmaAutoridad = $firmaAutoridad; }
}
?>
