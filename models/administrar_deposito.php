<?php
session_start();
include_once('conexion.php');
class depositos{
  private $id_deposito;
  private $nombre;
  
  public function __construct(){
      $this->conexion = new Conexion();
      date_default_timezone_set("America/Buenos_Aires");
  }

   public function traerDatosIniciales(){

   /*Usuarios*/
    $queryUsuarios = "SELECT id as id_usuario, usuario FROM usuarios";
    $getUsuarios = $this->conexion->consultaRetorno($queryUsuarios);

    $datosIniciales = array();
    $arrayUsuarios = array();

    /*CARGO ARRAY usuarios*/
    while ($rowUsuarios = $getUsuarios->fetch_array()) {
      $id_usuario = $rowUsuarios['id_usuario'];
      $usuario = $rowUsuarios['usuario'];
      $arrayUsuarios[]= array('id_usuario' => $id_usuario, 'usuario' =>$usuario);
    }

    $datosIniciales["usuarios"] = $arrayUsuarios;
    echo json_encode($datosIniciales);
  }

  public function traerDepositos(){
    $sqltraerDepositos = "SELECT id AS id_deposito, nombre, activo FROM destino WHERE 1";
    $traerDepositos = $this->conexion->consultaRetorno($sqltraerDepositos);
    $depositos = array(); //creamos un array
    
    while ($row = $traerDepositos->fetch_array()) {
      $depositos[] = array(
        'id_deposito'=>$row['id_deposito'],
        'nombre'=>$row['nombre'],
        'activo'=>$row['activo'],
      );
    }
    return json_encode($depositos);
  }

  public function traerDepositoUpdate($id_deposito){
    $this->id_deposito = $id_deposito;
    $sqlTraerdeposito = "SELECT id as id_deposito, nombre, activo FROM destino WHERE id = $this->id_deposito";
    $traerdeposito = $this->conexion->consultaRetorno($sqlTraerdeposito);

    $depositos = array(); //creamos un array
    while ($row = $traerdeposito->fetch_array()) {
      $depositos = array(
        'id_deposito'=> $row['id_deposito'],
        'nombre'=> $row['nombre']
      );
    }
    return json_encode($depositos);
  }

  public function depositoUpdate($id_deposito, $nombre){

    $this->id_deposito = $id_deposito;

    $sqlUpdateDeposito = "UPDATE destino SET nombre ='$nombre' WHERE id=$this->id_deposito";
    $updateDeposito = $this->conexion->consultaSimple($sqlUpdateDeposito);
    $mensajeError=$this->conexion->conectar->error;
    
    $respuesta=$mensajeError;
    if($mensajeError!=""){
      $respuesta.="<br><br>".$sqlUpdateDeposito;
    }else{
      $respuesta=1;
    }

    return $respuesta;
  }

  public function deletedeposito($id_deposito){
    $this->id_deposito = $id_deposito;

    /*ELIMINO ALMACEN*/
    $sqldeletedeposito = "DELETE FROM destino WHERE id = $this->id_deposito";
    $deletedeposito = $this->conexion->consultaSimple($sqldeletedeposito);
  }

  public function cambiarEstado($id_deposito, $estado){
    $this->id_deposito = $id_deposito;
    
    /*if ($estado == 'Activo') {
      $estado = 1;
    }else{
      $estado = 0;
    }*/

    $queryUpdateEstado = "UPDATE destino SET activo = $estado WHERE id = $this->id_deposito";
    $updateEstado = $this->conexion->consultaSimple($queryUpdateEstado);
  }

  public function registrardeposito( $nombre ){
    $this->nombre = $nombre;
    $usuario = $_SESSION['rowUsers']['id_usuario'];
    $queryInsertUser = "INSERT INTO destino (id_usuario, nombre, activo, fecha_hora_alta) VALUES('$usuario', '$this->nombre', 1, NOW())";
    $insertUser = $this->conexion->consultaSimple($queryInsertUser);
    $mensajeError=$this->conexion->conectar->error;
    
    $respuesta=$mensajeError;
    if($mensajeError!=""){
      $respuesta.="<br><br>".$queryInsertUser;
    }else{
      $respuesta=1;
    }
    
    return $respuesta;
  }
		
}	

if (isset($_POST['accion'])) {
  $depositos = new depositos();
  switch ($_POST['accion']) {
    case 'traerAlmacenes':
      $almacenes->traerTodosdepositos();
      break;
    case 'traerDepositoUpdate':
        $id_deposito = $_POST['id_deposito'];
        echo $depositos->traerDepositoUpdate($id_deposito);
      break;
    case 'updateDeposito':
        $id_deposito = $_POST['id_deposito'];
        $nombre = $_POST['nombre'];
        echo $depositos->depositoUpdate($id_deposito, $nombre);
      break;
    case 'cambiarEstado':
        $id_deposito = $_POST['id_deposito'];
        $estado = $_POST['estado'];
        $depositos->cambiarEstado($id_deposito, $estado);
        
      break;
    case 'eliminarDeposito':
        $id_deposito = $_POST['id_deposito'];
        $depositos->deletedeposito($id_deposito);
      break;
    case 'traerDatosIniciales':
      $depositos->traerDatosIniciales();
      break;
    case 'verificarCuenta':
      $email = $_POST['email'];
      $depositos->verificarCuentaExitente($email);
      break;
    case 'adddeposito':
      $nombre = $_POST['nombre'];
      echo $depositos->registrardeposito( $nombre);
      break;
  }
}else{
  if (isset($_GET['accion'])) {
    $depositos = new depositos();
    echo $depositos->traerDepositos();
  }
}
?>