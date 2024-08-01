<?php
error_reporting(E_ALL);
class Conexion{
  //LOCALHOST
  private $host = "localhost";
  private $user = "root";
  private $pass = "";
  private $db = "agrohen";

  //PRODUCCION
  /*private $host = "localhost";
  private $user = "agrohen";
  private $pass = "TqCStQMYhPj2qgm";
  private $db = "agrohen";*/
  
  public $conectar;

  public function __construct(){
    $this->conectar = new mysqli($this->host, $this->user, $this->pass, $this->db);
    if ($this->conectar->connect_error) {
      die("Connection failed: " . $this->conectar->connect_error);
    }
    $this->conectar->set_charset("utf8"); // Aquí configuras el charset a UTF-8
  }

  public function consultaSimple($sql){
    $this->conectar->query($sql);
  }

  public function consultaSimpleM($sql){
    $resultado = $this->conectar->query($sql);
    if (!$resultado) {
        error_log('Error en consulta SQL: ' . $this->conectar->error);
        return false; // Retorna false si la consulta falla
    }
    return true; // Retorna true si la consulta se ejecutó correctamente
  }

  public function consultaRetorno($sql){
    $datos = $this->conectar->query($sql);
    if ($this->conectar->error) {
      die("Error en la consulta: " . $this->conectar->error);
    }
    return $datos;
  }
}