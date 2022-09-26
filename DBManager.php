<?php

class DBManager {

  /**
  * Gestiona la conexión con la base de datos
  */
  private $dbhost = 'localhost';
  private $dbuser = 'root';
  private $dbpass = '';
  private $dbname = 'sistemaspropietarios';

  public function conexion () {

  /**
  * @return object connect con la conexión
  */

  $connect = new mysqli($this->dbhost,$this->dbuser,$this->dbpass,$this->dbname);

    if ($connect->connect_error) {

      $arrayError = array("error1"=>($connect->connect_errno),
                        "error2"=>$connect->connect_error);
      $error = json_encode($arrayError);
      echo "Error de Connexion ($connect->connect_errno) $connect->connect_error\n";

      header('Location: error_conexion.php?error='.$error);

      //exit;
      //Esto es una prueba 

    } else {
      echo "Conectado";
      return $connect;

    }

  }


}