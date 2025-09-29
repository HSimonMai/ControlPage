    <?php
    require_once("../BLL/UsuariosBLL.php");
    require_once("../Entidades/Usuario.php");

    if(isset($_POST["id"]))
    {
        $id= (int) $_POST["id"];    
        $usuarioBLL= new UsuariosBLL();
        $methodDelete= $usuarioBLL->DeleteUser($id);
        header('Location: ../UI/administrador.php');
        exit; // Asegúrate de detener la ejecución después de redirigir
    }