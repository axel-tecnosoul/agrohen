<?php
session_start();
include_once('conexion.php');
class responsables_depositos{
  private $id_responsable;
  private $nombre;
  
  public function __construct(){
      $this->conexion = new Conexion();
      date_default_timezone_set("America/Buenos_Aires");
  }

  public function traerDatosIniciales(){
    echo json_encode([]);
  }

  public function traerResponsablesDeposito(){
    $sqltraerResponsablesDeposito = "SELECT id AS id_responsable, nombre, activo FROM responsables_deposito WHERE 1";
    $traerResponsablesDeposito = $this->conexion->consultaRetorno($sqltraerResponsablesDeposito);
    $responsables_depositos = array(); //creamos un array
    
    while ($row = $traerResponsablesDeposito->fetch_array()) {
      $responsables_depositos[] = array(
        'id_responsable'=>$row['id_responsable'],
        'nombre'=>$row['nombre'],
        'activo'=>$row['activo'],
      );
    }
    return json_encode($responsables_depositos);
  }

  public function registrarResponsable( $nombre ){
    $this->nombre = $nombre;
    $usuario = $_SESSION['rowUsers']['id_usuario'];
    $queryInsertUser = "INSERT INTO responsables_deposito (id_usuario, nombre, activo, fecha_hora_alta) VALUES('$usuario', '$this->nombre', 1, NOW())";
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

  public function traerResponsableUpdate($id_responsable){
    $this->id_responsable = $id_responsable;
    $sqlTraerresponsables_deposito = "SELECT id as id_responsable, nombre, activo FROM responsables_deposito WHERE id = $this->id_responsable";
    $traerresponsables_deposito = $this->conexion->consultaRetorno($sqlTraerresponsables_deposito);

    $responsables_depositos = array(); //creamos un array
    while ($row = $traerresponsables_deposito->fetch_array()) {
      $responsables_depositos = array(
        'id_responsable'=> $row['id_responsable'],
        'nombre'=> $row['nombre']
      );
    }
    return json_encode($responsables_depositos);
  }

  public function responsables_depositosUpdate($id_responsable, $nombre){

    $this->id_responsable = $id_responsable;

    $sqlupdateResponsable = "UPDATE responsables_deposito SET nombre ='$nombre' WHERE id=$this->id_responsable";
    $updateResponsable = $this->conexion->consultaSimple($sqlupdateResponsable);
    $mensajeError=$this->conexion->conectar->error;
    
    $respuesta=$mensajeError;
    if($mensajeError!=""){
      $respuesta.="<br><br>".$sqlupdateResponsable;
    }else{
      $respuesta=1;
    }

    return $respuesta;
  }

  public function deleteResponsable($id_responsable){
    $this->id_responsable = $id_responsable;

    /*ELIMINO ALMACEN*/
    $sqldeleteResponsable = "DELETE FROM responsables_deposito WHERE id = $this->id_responsable";
    $deleteResponsable = $this->conexion->consultaSimple($sqldeleteResponsable);
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

  public function cambiarEstado($id_responsable, $estado){
    $this->id_responsable = $id_responsable;
    
    /*if ($estado == 'Activo') {
      $estado = 1;
    }else{
      $estado = 0;
    }*/

    $queryUpdateEstado = "UPDATE responsables_deposito SET activo = $estado WHERE id = $this->id_responsable";
    $updateEstado = $this->conexion->consultaSimple($queryUpdateEstado);
  }

}

if (isset($_POST['accion'])) {
  $responsables_depositos = new responsables_depositos();
  switch ($_POST['accion']) {
    case 'traerResponsableUpdate':
        $id_responsable = $_POST['id_responsable'];
        echo $responsables_depositos->traerResponsableUpdate($id_responsable);
      break;
    case 'updateResponsable':
        $id_responsable = $_POST['id_responsable'];
        $nombre = $_POST['nombre'];
        echo $responsables_depositos->responsables_depositosUpdate($id_responsable, $nombre);
      break;
    case 'cambiarEstado':
        $id_responsable = $_POST['id_responsable'];
        $estado = $_POST['estado'];
        $responsables_depositos->cambiarEstado($id_responsable, $estado);
        
      break;
    case 'eliminarResponsable':
        $id_responsable = $_POST['id_responsable'];
        echo $responsables_depositos->deleteResponsable($id_responsable);
      break;
    case 'traerDatosIniciales':
      $responsables_depositos->traerDatosIniciales();
      break;
    case 'verificarCuenta':
      $email = $_POST['email'];
      $responsables_depositos->verificarCuentaExitente($email);
      break;
    case 'addResponsable':
      $nombre = $_POST['nombre'];
      echo $responsables_depositos->registrarResponsable( $nombre);
      break;
  }
}else{
  if (isset($_GET['accion'])) {
    $responsables_depositos = new responsables_depositos();
    echo $responsables_depositos->traerResponsablesDeposito();
  }
}
?>