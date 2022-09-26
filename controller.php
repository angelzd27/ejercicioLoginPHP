<?php

include "DBManager.php";

//input
$posData = file_get_contents("php://input");
//Hacer el json a arreglo
$request = json_decode($posData);

@$option = $request->option;
@$usuario = $request->usuario;

switch ($option){

    case 'getUser':
        GetUser($connect, $request);
        break;

}

function GetUser($con, $data)
{

    // extraemos el password
    $hash = "";
    $jsPass = $data->password;
    $arreglo = [];

    $sql = "SELECT password FROM tbl_usuarios WHERE usuario = '$data->usuario' AND activo = 1 ";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_row($result);
    if($row == null){
        mysqli_close($con);
        $arreglo['response'] = 'mal';
    } else {
        $hash = $row[0];
    }
 
    if (password_verify($jsPass, $hash)) {
        $sql2 = "SELECT * FROM tbl_usuarios WHERE correo = '$data->correo'";
        mysqli_set_charset($con, "utf8");

        $result2 = mysqli_query($con, $sql2);

        while ($data = mysqli_fetch_assoc($result2)) {
        $arreglo[] = $data;
        }
        
        mysqli_close($con);
        $arreglo['response'] = 'bien';
        echo json_encode($arreglo);
    } else {
        $arreglo['response'] = 'mal';
        echo json_encode($arreglo);
    }
}