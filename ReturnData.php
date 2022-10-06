<?php

//Este no se mueve ya asi esta good 
function __DATA_RETURN($response){  //Para verificar si existe la función

  $_exec_funcion = $response->_function;

  if (function_exists($_exec_funcion)) { //Si hay una función llamada como la que se mande al parametro
      call_user_func($_exec_funcion, $response);
  } else {
      $_string = "No existe la funcion : " . $_exec_funcion;
      $_data_error = array("error" => true, "message" => $_string);
      echo json_encode($_data_error);
  }

}
?>
