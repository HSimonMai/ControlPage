<?php

//control que se encarga de cerrar y destruir la sesion 
session_start();
session_destroy();
header("Location: ../UI/login.php");

?>