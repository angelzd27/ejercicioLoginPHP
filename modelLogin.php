<?php
    header("Access-Control-Allow-Headers: Authorization, Cache-Control, Content-Type, X-Requested-With");
    # header("Access-Control-Allow-Headers: X-Requested-With, Content-type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
    header("Access-Control-Allow-Origin: *");
    header('content-type: application/json; charset=utf-8');
    
include_once "./ReturnData.php";
include_once "./DB.class.php";
//require_once "Auth.php";

//json_decode: convierte el json en un arreglo
$response = json_decode(file_get_contents('php://input'));//Va obtener Todo lo que tenga desde las entradas desde el frontend 

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

function fnInsert($response){
    try {
        $DB = new DB;
        //SET CAMPOS TABLA
        $arrSQL = (array) $response->usuario;

$result = $DB->insert($arrSQL,'tbl_usuarios');

echo json_encode($result);

    } catch (Exception $e) {
        http_response_code(401);

        echo json_encode(array(
            "haserror" => true,
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
    }
}

function fnUpdate($response){
    try {
        $DB = new DB;
        //SET CAMPOS TABLA
        $arrSQL = (array) $response->usuario;
       
        $result = $DB->update($arrSQL, 'tbl_usuarios', "id=".$response->id_usuario);

    echo json_encode($result);

    } catch (Exception $e) {
        http_response_code(401);

        echo json_encode(array(
            "haserror" => true,
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
    }
}

function fnDelete($response){
    try{
        $DB = new DB;
        $arrSQL=$response->usuario;
        $id=$response->usuario->id;
        $result = $DB->update($arrSQL, "tbl_usuarios", "id=$id");
        echo json_encode($result);
    }catch(Exception $e){
        http_response_code(401);
        echo json_encode(array(
            "haserror" => true,
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
    }
}

function fnRegister($response){
    try{
        $DB = new DB;
       
        //Response: Esto es lo que va venir del JS 
        $result = $DB->execSP("CALL nuevo_usuario('$response->nombre','$response->apellido_paterno', 
        '$response->apellido_materno','$response->correo','$response->password','$response->perfil')");
        echo json_encode($result);
    }catch(Exception $e){
        http_response_code(401);
        echo json_encode(array(
            "haserror" => true,
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
    }
}

function fnCheckEmail($response){
    $DB = new DB;

    $query = "SELECT 1 AS existe, id_usuario, 
    CONCAT(usuario,' ',correo,' ',activo) AS usuario
    FROM vw_usuarioperfil WHERE correo = '{$response->usuario->correo}'";

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
        $output['data'] = $result['menu'];
        $output['error'] = false;
    }else{
        $output['error'] = true;
        $output['message'] ="No existe el usuario.";
        $output['query'] = $query;
    }

    echo json_encode($output);

}
?>