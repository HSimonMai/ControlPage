<?php
    require_once("../BLL/AlumnoBLL.php");
    require_once("../Entidades/Alumno.php");
    require_once("../BLL/AsistenciaBLL.php");

    if(isset($_POST["idAlumno"]))
    {
        $id= (int) $_POST["idAlumno"];    
        $alumnoBLL= new AlumnoBLL();
        $deleteAsistencias= AsistenciaBLL::deleteAsistencias($id);
        $methodDelete= $alumnoBLL->deleteAlumno($id);
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
