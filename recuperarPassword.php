<?php
header("Access-Control-Allow-Headers: Authorization, Cache-Control, Content-Type, X-Requested-With");
# header("Access-Control-Allow-Headers: X-Requested-With, Content-type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Origin: *");
header('content-type: application/json; charset=utf-8');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once './pwdRand.php';

require 'phpMailer/Exception.php';
require 'phpMailer/PHPMailer.php';
require 'phpMailer/SMTP.php';
require 'modelLogin.php';

include_once "./ReturnData.php";
include_once "./DB.class.php";
//require_once "Auth.php";

$response = json_decode(file_get_contents('php://input'));

function fnUpdatePwd($response){
    $mail = new PHPMailer(true);
    $newPassword = $response->usuario->password;

    try {
        $DB = new DB;
        $arrSQL = array("password" => "md5('$newPassword')");
        $correo = $response->usuario->correo;
        $result = $DB->updatePWD($arrSQL, "tbl_usuarios", "correo='$correo'");

        //Server settings
        $mail->SMTPDebug = 0;                                       //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through

        //Servers                    
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = '';                     //SMTP username
        $mail->Password   = '';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('', 'Sistemas Propietarios I'); //add a email
        $mail->addAddress($correo);      //Add a recipient

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Envio de nueva clave';
        //$mail->Body    = 'New Password: ' . $password->password . '';
        $mail->Body    = 'New Password: ' . $newPassword . '';

        $mail->send();
        echo json_encode(['respuesta'=>'Mensaje enviado con éxito','data'=>['email'=>$correo],['new_password'=>$result]]);
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(array(
            "haserror" => true,
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
    }
}