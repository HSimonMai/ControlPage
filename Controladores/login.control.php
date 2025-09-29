<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();
if (!empty($_POST["btningresar"])) {
    require_once('../BLL/UsuariosBLL.php');
    $usuarioBLL = new UsuariosBLL();
    $isValid = (bool) !empty($_POST["usuario"]) && !empty($_POST["contrasena"]);
    // Verifica que los POST enviados no estén vacíos
    if ($isValid) {
        $nombre = $_POST["usuario"];
        $contrasena = $_POST["contrasena"];
        
        // Llama al método para autenticar al usuario
        $usuario = $usuarioBLL->AuthUsuario($nombre, $contrasena);
        
        // Si se encuentra el usuario
        if ($usuario != null) {
            $_SESSION["usuario"] = serialize($usuario);
            $idUsuario = (int) $usuario->getIdTiposUsuarios();
            
            if ($idUsuario === 1 ) {
                header('Location: ../UI/preceptor.php');
                exit;
            } 
            if ( $idUsuario === 2) {
                header('Location: ../UI/SuperPreceptor.php');
                exit;
            } 
            elseif ($idUsuario == 3) {
                header('Location: ../UI/administrador.php');
                exit;
            }
               elseif ($idUsuario == 4) {
                header('Location: ../UI/profesor.php');
                exit;
            }
               elseif ($idUsuario == 5) {
                header('Location: ../UI/alumno.php');
                exit;
            }
        }

        if($usuario === null)
        {
            $_SESSION['error_message'] = "Usuario o Contraseña incorrectos.";
            header('Location: ../UI/login.php');
        }
    } 


}