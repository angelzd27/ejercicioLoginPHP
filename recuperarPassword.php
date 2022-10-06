<?php
header("Access-Control-Allow-Headers: Authorization, Cache-Control, Content-Type, X-Requested-With");
# header("Access-Control-Allow-Headers: X-Requested-With, Content-type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Origin: *");
header('content-type: application/json; charset=utf-8');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpMailer/Exception.php';
require 'phpMailer/PHPMailer.php';
require 'phpMailer/SMTP.php';
require 'modelLogin.php';

include_once "./ReturnData.php";
include_once "./DB.class.php";
//require_once "Auth.php";

$response = json_decode(file_get_contents('php://input'));

function fnUpdatePwd($response)
{
    $mail = new PHPMailer(true);
    $comb = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $shfl = str_shuffle($comb);
    $pwd = substr($shfl, 0, 8);

    try {
        $DB = new DB;
        $arrSQL = array("password" => $pwd);
        $correo = $response->correo;
        $result = $DB->update($arrSQL, "tbl_usuarios", "correo='$correo'");

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
        $mail->Body    = 'Esta es su nueva contraseña ' . $pwd . '';

        $mail->send();
        echo json_encode(['mensaje'=>'Mensaje enviado con éxito','data'=>['data'=>$result]]);
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(array(
            "haserror" => true,
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
    }
}