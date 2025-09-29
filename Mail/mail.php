<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

class MailProcessor
{
    // Método para enviar correo con datos del alumno
    public static function procesarAlumnosConInasistencias($alumnoData)
    {
        $emailtutor=$alumnoData->getEmailTutor();
        $alumno= $alumnoData->getNombreAlumno().' '.$alumnoData->getApellidoAlumno().'  DNI:'.$alumnoData->getDniAlumno();
        $nombreTutor= $alumnoData->getNombreTutor().' '.$alumnoData->getApellidoTutor();
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Servidor SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'proyectocontrolprueba@gmail.com'; // Usuario SMTP
            $mail->Password = 'muwk yqct rawj ejxt'; // Contraseña SMTP
            $mail->SMTPSecure = 'tls'; // Seguridad TLS
            $mail->Port = 587; // Puerto SMTP

            // Configuración del correo
            $mail->setFrom('proyectocontrolprueba@gmail.com'); // Remitente
            $mail->addAddress('$emailtutor');
            // Construcción del contenido del correo
            $body = file_get_contents(__DIR__ . '/contenido.html');
            $body = str_replace("{BIENVENIDA}", "AVISO DE INASISTENCIAS ACULADAS", $body);
            $body = str_replace("%NOMBRE%", $nombreTutor,$body);
            $body = str_replace("{TEXTO}", "El alumno ".$alumno.' estuvo faltantando por favor acerquese a la  institucion o mande una nota por el cuaderno de comunicados', $body);

            $mail->isHTML(true);
            $mail->Subject = 'Notificación de Inasistencias'; // Asunto
            $mail->AltBody = 'El alumno 3 tiene inasistencias acumuladas.'; // Alternativo
            $mail->MsgHTML($body);

            // Envío del correo
            $mail->send();
            echo 'El mensaje ha sido enviado con éxito.';
        } catch (Exception $e) {
            echo 'Error al enviar el mensaje: ', $mail->ErrorInfo;
        }
    }
}
?>
