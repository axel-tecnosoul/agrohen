<?php
session_start();
include_once('conexion.php');
class unidades_medidas{
  private $id_unidad_medida;
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

  public function traerUnidad_medida(){
    $sqltraerUnidad_medida = "SELECT id AS id_unidad_medida, unidad_medida, activo FROM unidades_medida WHERE 1";
    $traerUnidad_medida = $this->conexion->consultaRetorno($sqltraerUnidad_medida);
    $unidades_medidas = array(); //creamos un array
    
    while ($row = $traerUnidad_medida->fetch_array()) {
      $unidades_medidas[] = array(
        'id_unidad_medida'=>$row['id_unidad_medida'],
        'nombre'=>$row['unidad_medida'],
        'activo'=>$row['activo'],
      );
    }
    return json_encode($unidades_medidas);
  }

  public function traerUnidad_medidaUpdate($id_unidad_medida){
    $this->id_unidad_medida = $id_unidad_medida;
    $sqltraerUnidad_medida = "SELECT id as id_unidad_medida, unidad_medida, activo FROM unidades_medida WHERE id = $this->id_unidad_medida";
    $traerUnidad_medida = $this->conexion->consultaRetorno($sqltraerUnidad_medida);

    $unidades_medidas = array(); //creamos un array
    while ($row = $traerUnidad_medida->fetch_array()) {
      $unidades_medidas = array(
        'id_unidad_medida'=> $row['id_unidad_medida'],
        'nombre'=> $row['unidad_medida']
      );
    }
    return json_encode($unidades_medidas);
  }

  public function unidades_medidasUpdate($id_unidad_medida, $nombre){

    $this->id_unidad_medida = $id_unidad_medida;

    $sqlupdateUnidad_medida = "UPDATE unidades_medida SET unidad_medida ='$nombre' WHERE id=$this->id_unidad_medida";
    $updateUnidad_medida = $this->conexion->consultaSimple($sqlupdateUnidad_medida);
    $mensajeError=$this->conexion->conectar->error;
    
    $respuesta=$mensajeError;
    if($mensajeError!=""){
      $respuesta.="<br><br>".$sqlupdateUnidad_medida;
    }else{
      $respuesta=1;
    }

    return $respuesta;
  }

  public function deleteUnidad_medida($id_unidad_medida){
    $this->id_unidad_medida = $id_unidad_medida;

    /*ELIMINO ALMACEN*/
    $sqldeleteUnidad_medida = "DELETE FROM unidades_medida WHERE id = $this->id_unidad_medida";
    $deleteUnidad_medida = $this->conexion->consultaSimple($sqldeleteUnidad_medida);
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

  public function cambiarEstado($id_unidad_medida, $estado){
    $this->id_unidad_medida = $id_unidad_medida;
    
    /*if ($estado == 'Activo') {
      $estado = 1;
    }else{
      $estado = 0;
    }*/

    $queryUpdateEstado = "UPDATE unidades_medida SET activo = $estado WHERE id = $this->id_unidad_medida";
    $updateEstado = $this->conexion->consultaSimple($queryUpdateEstado);
  }

  public function resgistrarUnidad_medida( $nombre ){
    $this->nombre = $nombre;
    $usuario = $_SESSION['rowUsers']['id_usuario'];
    $queryInsertUnidadM = "INSERT INTO unidades_medida (id_usuario, unidad_medida, activo, fecha_hora_alta) VALUES('$usuario', '$this->nombre', 1, NOW())";
    $insertUnidadM = $this->conexion->consultaSimple($queryInsertUnidadM);
    $mensajeError=$this->conexion->conectar->error;
    $id_unidad_medida=$this->conexion->conectar->insert_id;
    
    $respuesta=$mensajeError;
    if($mensajeError!=""){
      $respuesta.="<br><br>".$queryInsertUnidadM;
    }else{
      $respuesta=[
        "ok"=>1,
        "id_key" => "id_unidad_medida",
        "id_value"=>$id_unidad_medida,
      ];
      $respuesta=json_encode($respuesta);
    }
  
    return $respuesta;
  }
		
}	

if (isset($_POST['accion'])) {
  $unidades_medidas = new unidades_medidas();
  switch ($_POST['accion']) {
    case 'traerUnidad_medidaUpdate':
        $id_unidad_medida = $_POST['id_unidad_medida'];
        echo $unidades_medidas->traerUnidad_medidaUpdate($id_unidad_medida);
      break;
    case 'updateUnidad_medida':
        $id_unidad_medida = $_POST['id_unidad_medida'];
        $nombre = $_POST['nombre'];
        echo $unidades_medidas->unidades_medidasUpdate($id_unidad_medida, $nombre);
      break;
    case 'cambiarEstado':
        $id_unidad_medida = $_POST['id_unidad_medida'];
        $estado = $_POST['estado'];
        $unidades_medidas->cambiarEstado($id_unidad_medida, $estado);
        
      break;
    case 'eliminarUnidad_medida':
        $id_unidad_medida = $_POST['id_unidad_medida'];
        echo $unidades_medidas->deleteUnidad_medida($id_unidad_medida);
      break;
    case 'traerDatosIniciales':
      $unidades_medidas->traerDatosIniciales();
      break;
    case 'addUnidad_medida':
      $nombre = $_POST['nombre'];
      echo $unidades_medidas->resgistrarUnidad_medida( $nombre);
      break;
  }
}else{
  if (isset($_GET['accion'])) {
    $unidades_medidas = new unidades_medidas();
    echo $unidades_medidas->traerUnidad_medida();
  }
}
?>