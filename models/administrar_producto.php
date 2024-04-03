<?php
session_start();
include_once('conexion.php');
class producto{
  private $id_producto;
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

  public function traerProducto(){
    $sqltraerProducto = "SELECT id AS id_producto, nombre, presentacion, unidad_medida, ultimo_precio FROM producto WHERE 1";
    $traerProducto = $this->conexion->consultaRetorno($sqltraerProducto);
    $producto = array(); //creamos un array
    
    while ($row = $traerProducto->fetch_array()) {
      $producto[] = array(
        'id_producto'=>$row['id_producto'],
        'nombre'=>$row['nombre'],
        'presentacion'=>$row['presentacion'],
        'unidad_medida'=>$row['unidad_medida'],
        'ultimo_precio'=>$row['ultimo_precio'],
      );
    }
    return json_encode($producto);
  }

  public function traerProductoUpdate($id_producto){
    $this->id_producto = $id_producto;
    $sqlTraerproducto = "SELECT id as id_producto, nombre, presentacion, unidad_medida, ultimo_precio FROM producto WHERE id = $this->id_producto";
    $traerproducto = $this->conexion->consultaRetorno($sqlTraerproducto);

    $producto = array(); //creamos un array
    while ($row = $traerproducto->fetch_array()) {
      $producto = array(
        'id_producto'=> $row['id_producto'],
        'nombre'=> $row['nombre'],
        'presentacion'=> $row['presentacion'],
        'unidad_medida'=> $row['unidad_medida'],
        'ultimo_precio'=> $row['ultimo_precio']
      );
    }
    return json_encode($producto);
  }

  public function productoUpdate($id_producto, $nombre, $presentacion, $unidad_medida, $ultimo_precio){

    $this->id_producto = $id_producto;

    $sqlupdateProducto = "UPDATE producto SET nombre ='$nombre', presentacion ='$presentacion', unidad_medida ='$unidad_medida', ultimo_precio ='$ultimo_precio' WHERE id=$this->id_producto";
    $updateProducto = $this->conexion->consultaSimple($sqlupdateProducto);
    $mensajeError=$this->conexion->conectar->error;
    
    $respuesta=$mensajeError;
    if($mensajeError!=""){
      $respuesta.="<br><br>".$sqlupdateProducto;
    }else{
      $respuesta=1;
    }

    return $respuesta;
  }

  public function deleteProducto($id_producto){
    $this->id_producto = $id_producto;

    /*ELIMINO ALMACEN*/
    $sqldeleteProducto = "DELETE FROM producto WHERE id = $this->id_producto";
    $deleteProducto = $this->conexion->consultaSimple($sqldeleteProducto);
  }

  // public function cambiarEstado($id_producto, $estado){
  //   $this->id_producto = $id_producto;
    
  //   /*if ($estado == 'Activo') {
  //     $estado = 1;
  //   }else{
  //     $estado = 0;
  //   }*/

  //   $queryUpdateEstado = "UPDATE producto SET activo = $estado WHERE id = $this->id_producto";
  //   $updateEstado = $this->conexion->consultaSimple($queryUpdateEstado);
  // }

  public function registrarProducto( $nombre, $presentacion, $unidad_medida, $ultimo_precio ){
    $this->nombre = $nombre;
    $this->presentacion = $presentacion;
    $this->unidad_medida = $unidad_medida;
    $this->ultimo_precio = $ultimo_precio;
    $usuario = $_SESSION['rowUsers']['id_usuario'];
    $queryInsertUser = "INSERT INTO producto (id_usuario, nombre, presentacion, unidad_medida, ultimo_precio, fecha_hora_alta) VALUES('$usuario', '$this->nombre', '$this->presentacion', '$this->unidad_medida', '$this->ultimo_precio', NOW())";
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
  $producto = new producto();
  switch ($_POST['accion']) {
    case 'traerProductos':
      $productos->traerTodosProducto();
      break;
    case 'traerProductoUpdate':
        $id_producto = $_POST['id_producto'];
        echo $producto->traerProductoUpdate($id_producto);
      break;
    case 'updateProducto':
        $id_producto = $_POST['id_producto'];
        $nombre = $_POST['nombre'];
        $presentacion = $_POST['presentacion'];
        $unidad_medida = $_POST['unidad_medida'];
        $ultimo_precio = $_POST['ultimo_precio'];
        echo $producto->productoUpdate($id_producto, $nombre, $presentacion, $unidad_medida, $ultimo_precio);
      break;
    // case 'cambiarEstado':
    //     $id_producto = $_POST['id_producto'];
    //     $estado = $_POST['estado'];
    //     $producto->cambiarEstado($id_producto, $estado);
        
    //   break;
    case 'eliminarproducto':
        $id_producto = $_POST['id_producto'];
        $producto->deleteProducto($id_producto);
      break;
    case 'traerDatosIniciales':
      $producto->traerDatosIniciales();
      break;
    case 'verificarCuenta':
      $email = $_POST['email'];
      $producto->verificarCuentaExitente($email);
      break;
    case 'addProducto':
      $nombre = $_POST['nombre'];
      $presentacion = $_POST['presentacion'];
      $unidad_medida = $_POST['unidad_medida'];
      $ultimo_precio = $_POST['ultimo_precio'];
      echo $producto->registrarProducto( $nombre, $presentacion, $unidad_medida, $ultimo_precio);
      break;
  }
}else{
  if (isset($_GET['accion'])) {
    $producto = new producto();
    echo $producto->traerProducto();
  }
}
?>