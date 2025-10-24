<?php
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!empty($_POST["btningresar"])) {
    require_once('../BLL/UsuariosBLL.php');
    $usuarioBLL = new UsuariosBLL();

    if (!empty($_POST["usuario"]) && !empty($_POST["contrasena"])) {
        $nombreUsuario = trim($_POST["usuario"]);
        $contrasena = trim($_POST["contrasena"]);

        $usuario = $usuarioBLL->AuthUsuario($nombreUsuario, $contrasena);

        if ($usuario !== null) {
            $_SESSION["usuario"] = serialize($usuario);
            $idTipoUsuario = (int) $usuario->getIdTiposUsuarios();

            switch ($idTipoUsuario) {
                case 1: // Preceptor
                    header('Location: ../UI/preceptor.php');
                    exit;

                case 2: // SuperPreceptor
                    header('Location: ../UI/SuperPreceptor.php');
                    exit;

                case 3: // Administrador
                    header('Location: ../UI/administrador.php');
                    exit;

                case 4: // Profesor
                    // Conexión a la base de datos
                    $conexion = new mysqli("localhost", "root", "2901", "control");
                    if ($conexion->connect_error) {
                        die("Error de conexión: " . $conexion->connect_error);
                    }

                    // Buscar el idProfesor correspondiente al email del usuario autenticado
                    $emailUsuario = $usuario->getEmail();
                    $stmt = $conexion->prepare("SELECT idProfesores FROM profesores WHERE email = ?");
                    if (!$stmt) {
                        die("Error al preparar la consulta: " . $conexion->error);
                    }

                    $stmt->bind_param("s", $emailUsuario);
                    $stmt->execute();
                    $stmt->bind_result($idProfesor);
                    $stmt->fetch();
                    $stmt->close();
                    $conexion->close();

                    // Guardamos el idProfesor real en sesión
                    if (!empty($idProfesor)) {
                        $_SESSION["idProfesor"] = $idProfesor;
                        header('Location: ../UI/seleccionar_materia.php');
                        exit;
                    } else {
                        $_SESSION['error_message'] = "No se encontró un profesor asociado a este usuario.";
                        header('Location: ../UI/login.php');
                        exit;
                    }

                case 5: // Alumno
                    header('Location: ../UI/alumno.php');
                    exit;

                default:
                    $_SESSION['error_message'] = "Rol de usuario no reconocido.";
                    header('Location: ../UI/login.php');
                    exit;
            }
        } else {
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
?>
