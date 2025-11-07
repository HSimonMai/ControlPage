<?php
class TipoClase {
    public int $id;
    public string $tipo;

    public function __construct(int $id, string $tipo) {
        $this->id = $id;
        $this->tipo = $tipo;
    }
}
