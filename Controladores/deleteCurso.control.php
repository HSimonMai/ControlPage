<?php
require_once("../BLL/CursoBLL.php");
require_once("../Entidades/Cursos.php");

    if(isset($_POST["id"]) && !empty($_POST["id"]))
    {
        $id= (int) $_POST["id"];    
        $cursoBLL= new CursoBLL();
        $delete= $cursoBLL->deleteCurso($id);
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
