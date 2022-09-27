<?php
//4771323773
//Author: Fortino Bustamante Villafuerte
//Date: 15/Oct/2018
//Description: Clase que utiliza mysqli
include_once 'DBManager.php';

class DB{

  /*var $con;
	var $DB;


  //constructor 
  function __construct(){
		$this->DB = new DBManager;
 		$this->con = $this->DB->conexion();
 	}*/

  //Esto lo comento porque no se usa!!!!
  /*
  function insert($p_datos,$table){

    $DB = new DBManager;
    $con = $DB->conexion();

     // build query...
     $sql  = "INSERT INTO {$table}";

     $sql .= "(`".implode("`, `", array_keys($p_datos))."`)";

     // implode values of $array...
     $sql .= " VALUES ('".implode("', '", $p_datos)."') ";

     $result = $con->query($sql);

      if (!$result){
       //return false;
       $data["error"] = true;
       $data["message"] = "Ha ocurrido un error";
       $data["errno"] = $this->typeError($con->errno);
       $data["query"] = $sql;
     }else{
       //return true;
        $data["error"] = false;
        $data["message"] = "Se realizo el registro con exito";
        $data["id"] = $con->insert_id;  

     }

     return $data;

  }

  function update($p_datos,$p_table,$p_where){

    $DB = new DBManager;
    $con = $DB->conexion();

    $sql = "UPDATE $p_table SET ";

    foreach ($p_datos as $key => $value) {
      $valor[] = $key . "='" . $value."'";
    }

    $sql  .= implode(', ',$valor);

    $sql .= " WHERE $p_where";

    $result = $con->query($sql);

    if (!$result){
     //return false;
       $data["error"] = true;
       $data["message"] = "Ha ocurrido un error";
       $data["errno"] = $this->typeError($con->errno);
       $data["query"] = $sql;
     }else{
       //return true;
        $data["error"] = false;
        $data["message"] = "Se actualizo el registro con exito";
     }

   return $data;
  }

  function delete($p_table,$p_query){
    $DB = new DBManager;
    $con = $DB->conexion();

    $sql ="DELETE FROM";

    $sql .=" $p_table WHERE";

    $sql .=" $p_query";


   $result = $con->query($sql);

   if (!$result){
    //return false;
      $data["error"] = true;
      $data["message"] = "Ha ocurrido un error";
      $data["errno"] = $this->typeError($con->errno);
      $data["query"] = $sql;
    }else{
      //return true;
       $data["error"] = false;
       $data["message"] = "Se elimino el registro con exito";
    }

    return $data;

}
*/

  function getAll($p_query){
    $DB = new DBManager;
    $con = $DB->conexion();

    $consult = $con->query($p_query);

    //SE OBTIENE LA CANTIDAD DE COLUMNAS
    $numfields = $consult->field_count;

    //SE OBTIENE EL NOMBRE DE LAS COLUMNAS
    for ($i=0; $i < $numfields; $i++) {
      $fieldname[$i] = mysqli_fetch_field_direct($consult, $i)->name;
    }

    //SE CREA EL ARREGLO DEL TIPO KEY=>VALOR
    $result = array();
    while($row=$consult->fetch_array()){
        //Finally we assign the new variables
        for($i=0; $i < $numfields; $i++){
            $datos[$fieldname[$i]] = $row[$fieldname[$i]];
       }

       array_push($result, $datos);

    }

    return $result;

  }

  function execQuery($p_query){
    $DB = new DBManager;
    $con = $DB->conexion();

    if($con == true){

    // build query...
    $sql  = $p_query;

    $result = $con->query($sql);

   if (!$result)
    return false;
   else
    return true;

    }
 }

 function execSP($p_query){
  $DB = new DBManager;
  $con = $DB->conexion();

  if($con == true){

  // build query...
  $sql  = $p_query;

  $result = $con->query($sql);
  

  if (!$result){
    //return false;
      $data["error"] = true;
      $data["message"] = "Ha ocurrido un error";
      $data["errno"] = $this->typeError($con->errno);
      $data["query"] = $sql;
    }else{
      //return true;
      //SE OBTIENE LA CANTIDAD DE COLUMNAS
      $numfields = $result->field_count;

      //SE OBTIENE EL NOMBRE DE LAS COLUMNAS
      for ($i=0; $i < $numfields; $i++) {
        $fieldname[$i] = mysqli_fetch_field_direct($result, $i)->name;
      }

      //SE CREA EL ARREGLO DEL TIPO KEY=>VALOR
      $result_data = array();
      while($row=$result->fetch_array()){
          //Finally we assign the new variables
          for($i=0; $i < $numfields; $i++){
              $datos[$fieldname[$i]] = $row[$fieldname[$i]];
        }

        array_push($result_data, $datos);

      }

       $data["error"] = false;
       $data["message"] = "Se ejecuto el SP con exito";
       $data["datos"] = $result_data;
    }

    return $data;

  }
}

 function begin(){
  $DB = new DBManager;
  $con = $DB->conexion();

  mysqli_query($con, "START TRANSACTION");

  // mysqli_begin_transaction($con, MYSQLI_TRANS_START_READ_ONLY);
  /*if($con == true){
    mysqli_query($con, "BEGIN");
  }*/
}

function commit(){
  $DB = new DBManager;
  $con = $DB->conexion();
  mysqli_query($con, "COMMIT");
  //mysqli_autocommit($con, FALSE);
  //mysqli_commit($con);
  /*if($con == true){
    mysqli_query($con, "COMMIT");
  }*/
    
}

function rollback(){
  $DB = new DBManager;
  $con = $DB->conexion();
  mysqli_query($con, "ROLLBACK");
  //mysqli_rollback($con);
  //$con->rollback();
  /*if($con == true){
    mysqli_query($con, "ROLLBACK");
  }*/
  
}

function typeError($errno){
  $_typeError = null;
  switch($errno){
    case 1062:
      $_typeError = "Registro duplicado";
      break;

    case 1451:
      $_typeError = "El registro no se puede eliminar por que esta siendo utilizado por otro recurso.";
      break;

    default: 
      $_typeError = "Error desconocido";
      break;
      
  }

  return $_typeError;
}

}

 ?>
