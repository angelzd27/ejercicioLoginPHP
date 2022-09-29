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
    //$correo = $response->usuario->correo;

    $query = "SELECT 1 AS existe, id_usuario, 
    CONCAT(usuario,' ',correo,' ',activo) AS usuario
    FROM vw_usuarioperfil WHERE correo = '{$response->usuario->correo}' AND password = md5('{$pwd}')";

    $data = $DB->getAll($query);
    

    if( isset($data[0]['existe']) == "1" ){

        $result['nombre'] = $data;
        $_idUsuario = $data[0]['id_usuario'];

        $result['error'] =  false;
        $result['message'] = "El usuario existe";

        $queryMenu = "SELECT M.* FROM tbl_recursos AS M INNER JOIN perfil_recursos AS PM ON M.id=PM.id_recurso
        INNER JOIN tbl_perfil AS P ON PM.id_perfil = P.id
        INNER JOIN vw_usuarioperfil AS UP ON P.id = UP.id_perfil
        WHERE UP.activo = 1 AND UP.id_usuario = $_idUsuario ORDER BY M.orden";

        $result['menu'] = $DB->getAll($queryMenu);

       

        /*$output['data'] = $result['menu'];
        $output['query2'] = $queryMenu;
        echo json_encode($output);
        exit();



        
        */
        $output['data'] = $result['menu'];
        $output['error'] = false;
    }else{
        $output['error'] = true;
        $output['message'] ="No existe el usuario.";
        $output['query'] = $query;
    }

    echo json_encode($output);
    

}
