<?php
session_start();
include_once('conexion.php');
class origenes{
  private $conexion;
  private $id_origen;
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

  public function traerOrigenes(){
    $sqltraerOrigenes = "SELECT id AS id_origen, nombre, activo FROM origenes WHERE 1";
    $traerOrigenes = $this->conexion->consultaRetorno($sqltraerOrigenes);
    $origenes = array(); //creamos un array
    
    while ($row = $traerOrigenes->fetch_array()) {
      $origenes[] = array(
        'id_origen'=>$row['id_origen'],
        'nombre'=>$row['nombre'],
        'activo'=>$row['activo'],
      );
    }
    return json_encode($origenes);
  }

  public function traerOrigenUpdate($id_origen){
    $this->id_origen = $id_origen;
    $sqlTraerorigen = "SELECT id as id_origen, nombre, activo FROM origenes WHERE id = $this->id_origen";
    $traerorigen = $this->conexion->consultaRetorno($sqlTraerorigen);

    $origenes = array(); //creamos un array
    while ($row = $traerorigen->fetch_array()) {
      $origenes = array(
        'id_origen'=> $row['id_origen'],
        'nombre'=> $row['nombre']
      );
    }
    return json_encode($origenes);
  }

  public function origenesUpdate($id_origen, $nombre){

    $this->id_origen = $id_origen;

    $sqlupdateOrigen = "UPDATE origenes SET nombre ='$nombre' WHERE id=$this->id_origen";
    $updateOrigen = $this->conexion->consultaSimple($sqlupdateOrigen);
    $mensajeError=$this->conexion->conectar->error;
    
    $respuesta=$mensajeError;
    if($mensajeError!=""){
      $respuesta.="<br><br>".$sqlupdateOrigen;
    }else{
      $respuesta=1;
    }

    return $respuesta;
  }

  public function deleteOrigen($id_origen){
    $this->id_origen = $id_origen;

    /*ELIMINO ALMACEN*/
    $sqldeleteOrigen = "DELETE FROM origenes WHERE id = $this->id_origen";
    $deleteOrigen = $this->conexion->consultaSimple($sqldeleteOrigen);
    $mensajeError=$this->conexion->conectar->error;
    if($mensajeError==""){
      $r=1;
    }else{
      if (strpos($mensajeError, "Cannot delete or update a parent row") === 0) {
        // La cadena comienza con "Cannot delete or update a parent row"
        $r="El registro está siendo utilizado en la base de datos";
      } else {
        $r=$mensajeError;
      }
    }
    return json_encode($r);
  }

  public function cambiarEstado($id_origen, $estado){
    $this->id_origen = $id_origen;
    
    /*if ($estado == 'Activo') {
      $estado = 1;
    }else{
      $estado = 0;
    }*/

    $queryUpdateEstado = "UPDATE origenes SET activo = $estado WHERE id = $this->id_origen";
    $updateEstado = $this->conexion->consultaSimple($queryUpdateEstado);
  }

  public function registrarOrigen( $nombre ){
    $this->nombre = $nombre;
    $usuario = $_SESSION['rowUsers']['id_usuario'];
    $queryInsertUser = "INSERT INTO origenes (id_usuario, nombre, activo, fecha_hora_alta) VALUES('$usuario', '$this->nombre', 1, NOW())";
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
  $origenes = new origenes();
  switch ($_POST['accion']) {
    case 'traerOrigenUpdate':
        $id_origen = $_POST['id_origen'];
        echo $origenes->traerOrigenUpdate($id_origen);
      break;
    case 'updateOrigen':
        $id_origen = $_POST['id_origen'];
        $nombre = $_POST['nombre'];
        echo $origenes->origenesUpdate($id_origen, $nombre);
      break;
    case 'cambiarEstado':
        $id_origen = $_POST['id_origen'];
        $estado = $_POST['estado'];
        $origenes->cambiarEstado($id_origen, $estado);
        
      break;
    case 'eliminarOrigen':
        $id_origen = $_POST['id_origen'];
        echo $origenes->deleteOrigen($id_origen);
      break;
    case 'traerDatosIniciales':
      $origenes->traerDatosIniciales();
      break;
    case 'verificarCuenta':
      $email = $_POST['email'];
      $origenes->verificarCuentaExitente($email);
      break;
    case 'addOrigen':
      $nombre = $_POST['nombre'];
      echo $origenes->registrarOrigen( $nombre);
      break;
  }
}else{
  if (isset($_GET['accion'])) {
    $origenes = new origenes();
    echo $origenes->traerOrigenes();
  }
}
?>