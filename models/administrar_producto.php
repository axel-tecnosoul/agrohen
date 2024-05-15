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

    $datosIniciales = array();
    
    /*Familia*/
    $queryFamilias = "SELECT id as id_familia, familia FROM familias_productos";
    $getFamilias = $this->conexion->consultaRetorno($queryFamilias);
    $arrayFamilias[] = [
      'id_familia' => "",
      'familia' =>"Seleccione..."
    ];
    /*CARGO ARRAY Familias*/
    while ($row = $getFamilias->fetch_array()) {
      $arrayFamilias[]=[
        'id_familia' => $row["id_familia"],
        'familia' =>$row["familia"]
      ];
    }

    /*presentaciones*/
    $queryPresentaciones = "SELECT id as id_presentacion, nombre FROM presentaciones_productos";
    $getPresentaciones = $this->conexion->consultaRetorno($queryPresentaciones);
    $arrayPresentaciones[] = [
      'id_presentacion' => "",
      'presentacion' =>"Seleccione..."
    ];
    /*CARGO ARRAY presentaciones*/
    while ($row = $getPresentaciones->fetch_array()) {
      $arrayPresentaciones[]=[
        'id_presentacion' => $row["id_presentacion"],
        'presentacion' =>$row["nombre"]
      ];
    };

    /*Unidades de medida*/
    $queryUnidadMedida = "SELECT id as id_unidad_medida, unidad_medida FROM unidades_medida";
    $getUnidadMedida = $this->conexion->consultaRetorno($queryUnidadMedida);
    $arrayUnidadMedida[] = [
      'id_unidad_medida' => "",
      'unidad_medida' =>"Seleccione..."
    ];
    /*CARGO ARRAY UnidadMedida*/
    while ($row = $getUnidadMedida->fetch_array()) {
      $arrayUnidadMedida[]=[
        'id_unidad_medida' => $row["id_unidad_medida"],
        'unidad_medida' =>$row["unidad_medida"]
      ];
    };

    $datosIniciales["familias"] = $arrayFamilias;
    $datosIniciales["presentacion"] = $arrayPresentaciones;
    $datosIniciales["unidades_medidas"] = $arrayUnidadMedida;
    //var_dump($datosIniciales);
    echo json_encode($datosIniciales);
  }

  public function traerProducto(){
    $sqltraerProducto = "SELECT p.id AS id_producto, p.nombre, pe.nombre as presentacion, um.unidad_medida, fp.familia, ultimo_precio FROM productos p LEFT JOIN presentaciones_productos pe ON p.id_presentacion = pe.id LEFT JOIN familias_productos fp ON p.id_familia=fp.id LEFT JOIN unidades_medida um ON p.id_unidad_medida=um.id WHERE 1";
    $traerProducto = $this->conexion->consultaRetorno($sqltraerProducto);
    $producto = array(); //creamos un array
    
    while ($row = $traerProducto->fetch_array()) {
      $producto[] = array(
        'id_producto'=>$row['id_producto'],
        'nombre'=>$row['nombre'],
        'familia'=>$row['familia'],
        'presentacion'=>$row['presentacion'],
        'unidad_medida'=>$row['unidad_medida'],
        'ultimo_precio'=>$row['ultimo_precio'],
      );
    }
    return json_encode($producto);
  }

  public function traerProductoUpdate($id_producto){
    $this->id_producto = $id_producto;
    $sqlTraerproducto = "SELECT id as id_producto, nombre, id_presentacion, id_unidad_medida, id_familia FROM productos WHERE id = $this->id_producto";
    $traerproducto = $this->conexion->consultaRetorno($sqlTraerproducto);

    $producto = array(); //creamos un array
    while ($row = $traerproducto->fetch_array()) {
      $producto = array(
        'id_producto'=> $row['id_producto'],
        'nombre'=> $row['nombre'],
        'presentacion'=> $row['id_presentacion'],
        'id_unidad_medida'=> $row['id_unidad_medida'],
        'id_familia'=> $row['id_familia']
      );
    }
    return json_encode($producto);
  }

  public function productoUpdate($id_producto, $nombre, $presentacion, $id_unidad_medida, $id_familia){

    $this->id_producto = $id_producto;

    $sqlupdateProducto = "UPDATE productos SET nombre ='$nombre', id_presentacion ='$presentacion', id_unidad_medida ='$id_unidad_medida', id_familia ='$id_familia' WHERE id=$this->id_producto";
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
    $sqldeleteProducto = "DELETE FROM productos WHERE id = $this->id_producto";
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

  public function registrarProducto( $nombre, $id_presentacion, $id_unidad_medida, $id_familia ){
    $this->nombre = $nombre;
    $this->id_presentacion = $id_presentacion;
    $this->id_unidad_medida = $id_unidad_medida;
    $this->id_familia = $id_familia;
    $usuario = $_SESSION['rowUsers']['id_usuario'];

    $queryInsertUser = "INSERT INTO productos (id_usuario, nombre, id_presentacion, id_unidad_medida, id_familia, fecha_hora_alta) VALUES('$usuario', '$this->nombre', '$this->id_presentacion', '$this->id_unidad_medida', '$this->id_familia', NOW())";
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
        $id_unidad_medida = $_POST['id_unidad_medida'];
        $id_familia = $_POST['id_familia'];
        echo $producto->productoUpdate($id_producto, $nombre, $presentacion, $id_unidad_medida, $id_familia);
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
      $id_presentacion = $_POST['id_presentacion'];
      $id_unidad_medida = $_POST['id_unidad_medida'];
      $id_familia = $_POST['id_familia'];
      echo $producto->registrarProducto( $nombre, $id_presentacion, $id_unidad_medida, $id_familia);
      break;
  }
}else{
  if (isset($_GET['accion'])) {
    $producto = new producto();
    echo $producto->traerProducto();
  }
}
?>