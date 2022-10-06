<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpMailer/Exception.php';
require 'phpMailer/PHPMailer.php';
require 'phpMailer/SMTP.php';
require 'modelLogin.php';


$comb = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$shfl = str_shuffle($comb);
$pwd = substr($shfl,0,8);

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 0;                                       //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through

    //Servers                    
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'l.reme001023@itses.edu.mx';                     //SMTP username
    $mail->Password   = 'EARM2310';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('l.reme001023@itses.edu.mx', 'Edwin Reyes');  
    $mail->addAddress('l.zada010425@itses.edu.mx');      //Add a recipient
    
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Envio de nueva clave';
    $mail->Body    = 'Esta es su nueva contraseña ' .$pwd.'';

    $correo="edwin@mail.com";

    $data =[ "_function"=>"fnUpdatePWD",
    "usuario"=> ["correo"=> $correo, "password"=> $pwd] ];

    fnUpdatePWD($data);

    $mail->send();
    echo 'Mensaje enviado con exito';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}