<?php
    header("Access-Control-Allow-Headers: Authorization, Cache-Control, Content-Type, X-Requested-With");
    # header("Access-Control-Allow-Headers: X-Requested-With, Content-type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
    header("Access-Control-Allow-Origin: *");
    header('content-type: application/json; charset=utf-8');
    
include_once "./ReturnData.php";
include_once "./DB.class.php";
//require_once "Auth.php";

$response = json_decode(file_get_contents('php://input'));

__DATA_RETURN($response);


function fnLogin($response){
    $DB = new DB;

    $pwd = $response->usuario->password;
    $email = $response->usuario->email;

    $query = "SELECT * FROM vw_usuarioPerfil WHERE password = md5('{$pwd}') AND correo = '{$email}'";

    $data = $DB->getAll($query);

    echo json_encode($data);

/*
    if( isset($data[0]['existe']) == "1" ){

        $result['nombre']  = $data;
        $_idUsuario  = $data[0]['idUsuario'];

        $result['error'] =  false;
        $result['message'] = "El usuario existe";

        $queryMenu = "SELECT M.* FROM tblmenu AS M INNER JOIN tblperfilmenu AS PM ON M.idMenu=PM.idMenu
        INNER JOIN tblperfil AS P ON PM.idPerfil = P.idPerfil 
        INNER JOIN tblusuario AS UP ON P.idPerfil = UP.idPerfil 
        WHERE M.activo=1 AND UP.activo = 1 AND UP.idUsuario = $_idUsuario ORDER BY M.orden";

        $result['menu'] = $DB->getAll($queryMenu);

        $querySucursal = "SELECT SUC.idSucursal, UPPER(SUC.nombre) AS nombre FROM tblsucursal AS SUC INNER JOIN tblusuariosucursal AS USUC ON SUC.idSucursal=USUC.idSucursal 
        WHERE USUC.idUsuario = $_idUsuario";

        $result['sucursal'] = $DB->getAll($querySucursal);

        /*$output['data'] = $result['menu'];
        $output['query2'] = $queryMenu;
        echo json_encode($output);
        exit();*/

/*
        $_token = Auth::SignIn($result);

        $output['token'] = $_token;
        $output['error'] = false;
        $output['url'] = $result['menu'][0]['url'];
    }else{
        $output['error'] = true;
        $output['message'] ="No existe el usuario.";
        $output['query'] = $query;
    }

    echo json_encode($output);
    */

}
