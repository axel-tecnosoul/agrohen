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
  private $user = "root";
  private $pass = "TqCStQMYhPj2qgm";
  private $db = "agrohen";*/
  public $conectar;

  public function __construct(){
    $this->conectar = new mysqli($this->host, $this->user, $this->pass, $this->db);
  }

  public function consultaSimple($sql){
    $this->conectar->query($sql);
  }

  public function consultaRetorno($sql){
    $datos = $this->conectar->query($sql);
    return $datos;
  }
}