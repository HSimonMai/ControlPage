<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

   <link rel="stylesheet" href="css/bootstrap.css">
   <link rel="stylesheet" type="text/css" href="css/style.css">
   <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">

   <title>Inicio de sesión</title>
</head>

<body>
   <img class="wave" src="assets/img/wave.png">
   <div class="container">
      <div class="img">
         <!-- <img src="img/bg.svg"> -->
      </div>
      <div class="login-content">
         <form method="post" action="../Controladores/login.control.php">
            <img src="assets/img/logo.jpg">
            <h2 class="title">BIENVENIDA/O</h2>

            <?php
            //Mensaje de acceso denegado
            session_start();
              if (isset($_SESSION['error_message'])) {
              echo "<div class='alert alert-danger'>" . $_SESSION['error_message'] . "</div>";
              unset($_SESSION['error_message']); // Limpiar el mensaje después de mostrarlo
            }
            
?>

            <div class="input-div one">
               <div class="i">
                  <i class="fas fa-user"></i>
               </div>
               <div class="div">
                  <h5>Usuario</h5>
                  <input type="text" class="input" name="usuario" id="usuario" required>
               </div>
            </div>
            <div class="input-div pass">
               <div class="i">
                  <i class="fas fa-lock"></i>
               </div>
               <div class="div">
                  <h5>Contraseña</h5>
                  <input type="password" id="contrasena" class="input" name="contrasena" required>
               </div>
            </div>

            

            <div class="view">
               <div class="fas fa-eye verPassword" onclick="vista()" id="verPassword"></div>
            </div>
            <input name="btningresar" class="btn btn-primary" type="submit" value="INICIAR SESION">
         </form>
      </div>
   </div>
   <script src="js/fontawesome.js"></script>
   <script src="js/main.js"></script>
   <script src="js/main2.js"></script>
   <script src="js/jquery.min.js"></script>
   <script src="js/bootstrap.js"></script>
   <script src="js/bootstrap.bundle.js"></script>
</body>

</html>
