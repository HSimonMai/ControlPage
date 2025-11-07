<?php
require_once(__DIR__ . "/../DAL/TutorDAL.php");

class TutorController
{
    private TutorDAL $dal;

    public function __construct()
    {
        $this->dal = new TutorDAL();
    }

    public function listarPorCurso(int $idCurso): array
    {
        return $this->dal->findByCurso($idCurso);
    }
}
