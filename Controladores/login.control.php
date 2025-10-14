<?php
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!empty($_POST["btningresar"])) {
    require_once('../BLL/UsuariosBLL.php');
    $usuarioBLL = new UsuariosBLL();

    // Verifica que los campos no estén vacíos
    if (!empty($_POST["usuario"]) && !empty($_POST["contrasena"])) {
        $nombreUsuario = trim($_POST["usuario"]);
        $contrasena = trim($_POST["contrasena"]);

        // Llama al método de autenticación (ya maneja password_verify)
        $usuario = $usuarioBLL->AuthUsuario($nombreUsuario, $contrasena);

        if ($usuario !== null) {
            // Guardamos el objeto completo en sesión
            $_SESSION["usuario"] = serialize($usuario);

            // Tomamos el tipo de usuario para redirigir según su rol
            $idTipoUsuario = (int) $usuario->getIdTiposUsuarios();

            switch ($idTipoUsuario) {
                case 1:
                    header('Location: ../UI/preceptor.php');
                    exit;
                case 2:
                    header('Location: ../UI/SuperPreceptor.php');
                    exit;
                case 3:
                    header('Location: ../UI/administrador.php');
                    exit;
                case 4:
                    header('Location: ../UI/profesor.php');
                    exit;
                case 5:
                    header('Location: ../UI/alumno.php');
                    exit;
                default:
                    $_SESSION['error_message'] = "Rol de usuario no reconocido.";
                    header('Location: ../UI/login.php');
                    exit;
            }
        } else {
            // Si no existe el usuario o contraseña incorrecta
            $_SESSION['error_message'] = "Usuario o contraseña incorrectos.";
            header('Location: ../UI/login.php');
            exit;
        }
    } else {
        $_SESSION['error_message'] = "Debe completar todos los campos.";
        header('Location: ../UI/login.php');
        exit;
    }
}
