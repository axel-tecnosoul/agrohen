<?php
session_start();
include_once('conexion.php');
class proveedores{
  private $id_proveedor;
  private $nombre;
  private $cuit;
  private $email;
  private $telefono;
  private $activo;
  
  public function __construct(){
      $this->conexion = new Conexion();
      date_default_timezone_set("America/Buenos_Aires");
  }

  public function traerDatosIniciales(){
    echo json_encode([]);
  }

  public function traerProveedores(){
    $sqltraerProveedores = "SELECT id AS id_proveedor, nombre, cuit, email, telefono, activo FROM proveedores WHERE 1";
    $traerProveedores = $this->conexion->consultaRetorno($sqltraerProveedores);
    $proveedores = array(); //creamos un array
    
    while ($row = $traerProveedores->fetch_array()) {
      $proveedores[] = array(
        'id_proveedor'=>$row['id_proveedor'],
        'nombre'=>$row['nombre'],
        'cuit'=>$row['cuit'],
        'email'=>$row['email'],
        'telefono'=>$row['telefono'],
        'activo'=>$row['activo'],
      );
    }
    return json_encode($proveedores);
  }

  public function registrarProveedor($nombre,$cuit,$email,$telefono){
    $this->nombre = $nombre;
    $this->cuit = $cuit;
    $this->email = $email;
    $this->telefono = $telefono;
    $id_usuario = $_SESSION['rowUsers']['id_usuario'];

    $queryInsertUser = "INSERT INTO proveedores (nombre, cuit, email, telefono, activo, id_usuario) VALUES('$this->nombre', '$this->cuit', '$this->email', '$this->telefono', 1, '$id_usuario')";
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

  public function traerProveedorUpdate($id_proveedor){
    $this->id_proveedor = $id_proveedor;
    $sqlTraerProveedores = "SELECT id as id_proveedor, nombre, cuit, email, telefono, activo FROM proveedores WHERE id = $this->id_proveedor";
    $traerProveedores = $this->conexion->consultaRetorno($sqlTraerProveedores);

    $proveedores = array(); //creamos un array
    while ($row = $traerProveedores->fetch_array()) {
      $proveedores = array(
        'id_proveedor'=> $row['id_proveedor'],
        'nombre'=> $row['nombre'],
        'cuit'=> $row['cuit'],
        'email'=> $row['email'],
        'telefono'=> $row['telefono'],
      );
    }
    return json_encode($proveedores);
  }

  public function proveedoresUpdate($id_proveedor, $nombre, $cuit, $email, $telefono){

    $this->id_proveedor = $id_proveedor;

    $sqlupdateProveedor = "UPDATE proveedores SET nombre ='$nombre', cuit ='$cuit', email ='$email', telefono ='$telefono' WHERE id=$this->id_proveedor";
    $updateProveedor = $this->conexion->consultaSimple($sqlupdateProveedor);
    $mensajeError=$this->conexion->conectar->error;
    
    $respuesta=$mensajeError;
    if($mensajeError!=""){
      $respuesta.="<br><br>".$sqlupdateProveedor;
    }else{
      $respuesta=1;
    }

    return $respuesta;
  }

  public function deleteProveedor($id_proveedor){
    $this->id_proveedor = $id_proveedor;

    /*ELIMINO ALMACEN*/
    $sqldeleteProveedor = "DELETE FROM proveedores WHERE id = $this->id_proveedor";
    $deleteProveedor = $this->conexion->consultaSimple($sqldeleteProveedor);
  }

  public function cambiarEstado($id_proveedor, $estado){
    $this->id_proveedor = $id_proveedor;
    
    /*if ($estado == 'Activo') {
      $estado = 1;
    }else{
      $estado = 0;
    }*/

    $queryUpdateEstado = "UPDATE proveedores SET activo = $estado WHERE id = $this->id_proveedor";
    $updateEstado = $this->conexion->consultaSimple($queryUpdateEstado);
  }

}

if (isset($_POST['accion'])) {
  $proveedores = new proveedores();
  switch ($_POST['accion']) {
    case 'traerProveedorUpdate':
        $id_proveedor = $_POST['id_proveedor'];
        echo $proveedores->traerProveedorUpdate($id_proveedor);
      break;
    case 'updateProveedor':
        $id_proveedor = $_POST['id_proveedor'];
        $nombre = $_POST['nombre'];
        $cuit = $_POST['cuit'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono']; 
        echo $proveedores->proveedoresUpdate($id_proveedor, $nombre, $cuit, $email, $telefono);
      break;
    case 'cambiarEstado':
        $id_proveedor = $_POST['id_proveedor'];
        $estado = $_POST['estado'];
        $proveedores->cambiarEstado($id_proveedor, $estado);
        
      break;
    case 'eliminarProveedor':
        $id_proveedor = $_POST['id_proveedor'];
        $proveedores->deleteProveedor($id_proveedor);
      break;
    case 'traerDatosIniciales':
      $proveedores->traerDatosIniciales();
      break;
    case 'verificarCuenta':
      $email = $_POST['email'];
      $proveedores->verificarCuentaExitente($email);
      break;
    case 'addProveedor':
      $nombre = $_POST['nombre'];
      $cuit = $_POST['cuit'];
      $email = $_POST['email'];
      $telefono = $_POST['telefono'];
      echo $proveedores->registrarProveedor($nombre,$cuit,$email,$telefono);
      break;
  }
}else{
  if (isset($_GET['accion'])) {
    $proveedores = new proveedores();
    echo $proveedores->traerProveedores();
  }
}
?>