<?php
session_start();
include_once('conexion.php');
class presentaciones{
  private $id_presentacion;
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

  public function traerPresentaciones(){
    $sqltraerPresentaciones = "SELECT id AS id_presentacion, nombre, activo FROM presentaciones_productos WHERE 1";
    $traerPresentaciones = $this->conexion->consultaRetorno($sqltraerPresentaciones);
    $presentaciones = array(); //creamos un array
    
    while ($row = $traerPresentaciones->fetch_array()) {
      $presentaciones[] = array(
        'id_presentacion'=>$row['id_presentacion'],
        'nombre'=>$row['nombre'],
        'activo'=>$row['activo'],
      );
    }
    return json_encode($presentaciones);
  }

  public function traerPresentacionUpdate($id_presentacion){
    $this->id_presentacion = $id_presentacion;
    $sqltraerPresentacion = "SELECT id as id_presentacion, nombre, activo FROM presentaciones_productos WHERE id = $this->id_presentacion";
    $traerPresentacion = $this->conexion->consultaRetorno($sqltraerPresentacion);

    $presentaciones = array(); //creamos un array
    while ($row = $traerPresentacion->fetch_array()) {
      $presentaciones = array(
        'id_presentacion'=> $row['id_presentacion'],
        'nombre'=> $row['nombre']
      );
    }
    return json_encode($presentaciones);
  }

  public function presentacionesUpdate($id_presentacion, $nombre){

    $this->id_presentacion = $id_presentacion;

    $sqlupdatepresentacion = "UPDATE presentaciones_productos SET nombre ='$nombre' WHERE id=$this->id_presentacion";
    $updatepresentacion = $this->conexion->consultaSimple($sqlupdatepresentacion);
    $mensajeError=$this->conexion->conectar->error;
    
    $respuesta=$mensajeError;
    if($mensajeError!=""){
      $respuesta.="<br><br>".$sqlupdatepresentacion;
    }else{
      $respuesta=1;
    }

    return $respuesta;
  }

  public function deletepresentacion($id_presentacion){
    $this->id_presentacion = $id_presentacion;

    /*ELIMINO ALMACEN*/
    $sqldeletepresentacion = "DELETE FROM presentaciones_productos WHERE id = $this->id_presentacion";
    $deletepresentacion = $this->conexion->consultaSimple($sqldeletepresentacion);
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

  public function cambiarEstado($id_presentacion, $estado){
    $this->id_presentacion = $id_presentacion;
    
    /*if ($estado == 'Activo') {
      $estado = 1;
    }else{
      $estado = 0;
    }*/

    $queryUpdateEstado = "UPDATE presentaciones_productos SET activo = $estado WHERE id = $this->id_presentacion";
    $updateEstado = $this->conexion->consultaSimple($queryUpdateEstado);
  }

  public function registrarpresentacion( $nombre ){
    $this->nombre = $nombre;
    $usuario = $_SESSION['rowUsers']['id_usuario'];
    $queryInsertPresentacion = "INSERT INTO presentaciones_productos (id_usuario, nombre, activo, fecha_hora_alta) VALUES('$usuario', '$this->nombre', 1, NOW())";
    $insertPresentacion = $this->conexion->consultaSimple($queryInsertPresentacion);
    $mensajeError=$this->conexion->conectar->error;
    $id_presentacion=$this->conexion->conectar->insert_id;
    
    $respuesta=$mensajeError;
    if($mensajeError!=""){
      $respuesta.="<br><br>".$queryInsertPresentacion;
    }else{
      $respuesta=[
        "ok"=>1,
        "id_key" => "id_presentacion",
        "id_value"=>$id_presentacion,
      ];
      $respuesta=json_encode($respuesta);
    }
  
    return $respuesta;
  }
		
}	

if (isset($_POST['accion'])) {
  $presentaciones = new presentaciones();
  switch ($_POST['accion']) {
    case 'traerPresentacionUpdate':
        $id_presentacion = $_POST['id_presentacion'];
        echo $presentaciones->traerPresentacionUpdate($id_presentacion);
      break;
    case 'updatepresentacion':
        $id_presentacion = $_POST['id_presentacion'];
        $nombre = $_POST['nombre'];
        echo $presentaciones->presentacionesUpdate($id_presentacion, $nombre);
      break;
    case 'cambiarEstado':
        $id_presentacion = $_POST['id_presentacion'];
        $estado = $_POST['estado'];
        $presentaciones->cambiarEstado($id_presentacion, $estado);
        
      break;
    case 'eliminarpresentacion':
        $id_presentacion = $_POST['id_presentacion'];
        echo $presentaciones->deletepresentacion($id_presentacion);
      break;
    case 'traerDatosIniciales':
      $presentaciones->traerDatosIniciales();
      break;
    case 'verificarCuenta':
      $email = $_POST['email'];
      $presentaciones->verificarCuentaExitente($email);
      break;
    case 'addpresentacion':
      $nombre = $_POST['nombre'];
      echo $presentaciones->registrarpresentacion( $nombre);
      break;
  }
}else{
  if (isset($_GET['accion'])) {
    $presentaciones = new presentaciones();
    echo $presentaciones->traerPresentaciones();
  }
}
?>