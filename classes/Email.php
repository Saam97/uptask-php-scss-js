<?php
namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email{

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token) {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion(){
        $mail = new PHPMailer();
        
        //Configurar SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = '';//quien envia el email
        $mail->Password = ''; //clave de aplicacion
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;


        //Configurar el Contenido del email
        $mail->setFrom('');//quien envia el email
        $mail->addAddress( $this->email, $this->nombre);//correo y nombre de usuario a quien enviamos
        $mail->Subject = 'Confirma tu cuenta Uptask';//encabezado del gmail


        //Habilitar HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        //Definir el Contenido
        $contenido = "<html>";
        $contenido .=" <p> Hola!, . $this->nombre. para poder continuar debes confirmar tu cuenta  </p>";
        $contenido .=" <p> correo " . $this->email. ", para hacer eso debes dar click en el siguiente enlace </p>";
        $contenido .=" <p> PRESIONA AQUÍ: <a href='http://localhost:4000/confirmar?token="
        . $this->token ."'> Confirmar Cuenta</a> </p>";
        $contenido .=" <p> Si no solicitaste una cuenta, por favor ignora el mensaje </p>";
        $contenido .=" </html>";

        $mail->Body = $contenido;

        $mail->send();

    }


    public function enviarInstrucciones(){

        //Crear Instancia /Objeto de phpMailer
        $mail = new PHPMailer();

        //Configurar SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = '97021720023s@gmail.com';//quien envia el email
        $mail->Password = 'wiuvbxkjwretwkvl'; //clave de aplicacion
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;


        //Configurar el Contenido del email
        $mail->setFrom('97021720023s@gmail.com');//quien envia el email
        $mail->addAddress( $this->email, $this->nombre);//de que email va a llegar el correo
        $mail->Subject = 'Reestablece tu Password';//encabezado del gmail


        //Habilitar HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        //Definir el Contenido
        $contenido = "<html>";
        $contenido .=" <p> Hola!, para poder Reestablecer tu contraseña de el  </p>";
        $contenido .=" <p> correo " . $this->email. ", debes dar click en el siguiente enlace </p>";
        $contenido .=" <p> PRESIONA AQUÍ: <a href='http://localhost:4000/reestablecer?token="
        . $this->token ."'> Reestablecer Password</a> </p>";
        $contenido .=" <p> Si no solicitaste reestablecer tu contraseña, por favor ignora el mensaje </p>";
        $contenido .=" </html>";

        $mail->Body = $contenido;

        $mail->send();

    }


}
