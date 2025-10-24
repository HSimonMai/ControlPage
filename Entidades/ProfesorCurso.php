<?php 


class ProfesorCurso{
    private $id;
    private $profesor_id;
    private $curso_id;
    private $asignatura;
    private $año_lectivo;



    public function __construct($id,$profesor_id,$curso_id,$asignatura,$año_lectivo)
    {
     $this->id=$id;
     $this->profesor_id=$profesor_id;
     $this->curso_id=$curso_id;
     $this->asignatura=$asignatura;
     $this->año_lectivo=$año_lectivo;   
    }


    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of profesor_id
     */
    public function getProfesorId()
    {
        return $this->profesor_id;
    }

    /**
     * Get the value of curso_id
     */
    public function getCursoId()
    {
        return $this->curso_id;
    }

    /**
     * Get the value of asignatura
     */
    public function getAsignatura()
    {
        return $this->asignatura;
    }

    /**
     * Get the value of año_lectivo
     */
    public function getAñoLectivo()
    {
        return $this->año_lectivo;
    }
}




?>