<?php

class BotonComponent
{
    private string $type;
    private string $idModal;

    private string $svg;

    public function __construct(string $type, string $idModal, string $svg)
    {
        $this->type = $type;
        $this->idModal = $idModal;
        $this->svg = $svg;
    }


    public function render()
    {
        echo '
        <button type="button" class="btn btn-' . $this->type . '" data-bs-toggle="modal" data-bs-target="#' . $this->idModal . '">
        ' . $this->svg . '
        </button>';

        return;
    }
}
