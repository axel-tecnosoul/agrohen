<?php
error_reporting(E_ALL); // Reportar todos los errores de PHP
ini_set('display_errors', '1'); // Mostrar los errores en la salida
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
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
        'id_familia' => utf8_encode($row["id_familia"]),
        'familia' =>utf8_encode($row["familia"])
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
        'id_presentacion' => utf8_encode($row["id_presentacion"]),
        'presentacion' =>utf8_encode($row["nombre"])
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
        'id_unidad_medida' => utf8_encode($row["id_unidad_medida"]),
        'unidad_medida' =>utf8_encode($row["unidad_medida"])
      ];
    };

    $datosIniciales["familias"] = $arrayFamilias;
    $datosIniciales["presentacion"] = $arrayPresentaciones;
    $datosIniciales["unidades_medidas"] = $arrayUnidadMedida;
    //var_dump($datosIniciales);
    echo json_encode($datosIniciales);
  }

  public function traerProducto(){
    $sqlTraerProducto = "SELECT p.id AS id_producto, p.nombre, pe.nombre as presentacion, um.unidad_medida, fp.familia, p.ultimo_precio,p.ultimo_kg_x_bulto FROM productos p LEFT JOIN presentaciones_productos pe ON p.id_presentacion = pe.id LEFT JOIN familias_productos fp ON p.id_familia=fp.id LEFT JOIN unidades_medida um ON p.id_unidad_medida=um.id WHERE 1";
    $traerProducto = $this->conexion->consultaRetorno($sqlTraerProducto);
    $producto = array(); //creamos un array
    while ($row = $traerProducto->fetch_array()) {
      $producto[] = array(
        'id_producto'=>utf8_encode($row['id_producto']),
        'nombre'=>utf8_encode($row['nombre']),
        'familia'=>utf8_encode($row['familia']),
        'presentacion'=>utf8_encode($row['presentacion']),
        'unidad_medida'=>utf8_encode($row['unidad_medida']),
        'ultimo_precio'=>utf8_encode($row['ultimo_precio']),
        'ultimo_kg_x_bulto'=>utf8_encode($row['ultimo_kg_x_bulto']),
      );
    }
    // var_dump($producto);
    // $json = json_encode($producto);

    // if ($json === false) {
    //     echo "JSON encode error: " . json_last_error_msg();
    // } else {
    //   echo $json;
    // }

    return json_encode($producto);
  }

  public function traerProductoUpdate($id_producto){
    $this->id_producto = $id_producto;
    $sqlTraerProducto = "SELECT id as id_producto, nombre, id_presentacion, id_unidad_medida, id_familia FROM productos WHERE id = $this->id_producto";
    $traerProducto = $this->conexion->consultaRetorno($sqlTraerProducto);

    $producto = array(); //creamos un array
    while ($row = $traerProducto->fetch_array()) {
      $producto = array(
        'id_producto'=> utf8_encode($row['id_producto']),
        'nombre'=> utf8_encode($row['nombre']),
        'id_presentacion'=> utf8_encode($row['id_presentacion']),
        'id_unidad_medida'=> utf8_encode($row['id_unidad_medida']),
        'id_familia'=> utf8_encode($row['id_familia'])
      );
    }
    return json_encode($producto);
  }

  public function productoUpdate($id_producto, $nombre, $id_presentacion, $id_unidad_medida, $id_familia){

    $this->id_producto = $id_producto;

    $sqlupdateProducto = "UPDATE productos SET nombre ='$nombre', id_presentacion ='$id_presentacion', id_unidad_medida ='$id_unidad_medida', id_familia ='$id_familia' WHERE id=$this->id_producto";
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
    //$mensajeError=$this->conexion->conectar->error;
    $error=$this->conexion->conectar;

    $mensajeError=$error->error;
    $respuesta=$mensajeError;
    if($mensajeError!=""){
      if($error->errno==1451){
        echo "No es posible eliminar el producto ya que está siendo utilizado en otra tabla de la base de datos";
      }else{
        echo $respuesta."<br><br>".$sqldeleteProducto;
        //var_dump($error);
      }
    }
  }

  public function registrarProducto( $nombre, $id_presentacion, $id_unidad_medida, $id_familia ){
    $this->nombre = $nombre;
    $this->id_presentacion = $id_presentacion;
    $this->id_unidad_medida = $id_unidad_medida;
    $this->id_familia = $id_familia;
    $usuario = $_SESSION['rowUsers']['id_usuario'];

    $queryInsertProducto = "INSERT INTO productos (id_usuario, nombre, id_presentacion, id_unidad_medida, id_familia, fecha_hora_alta) VALUES('$usuario', '$this->nombre', '$this->id_presentacion', '$this->id_unidad_medida', '$this->id_familia', NOW())";
    $insertUser = $this->conexion->consultaSimple($queryInsertProducto);
    $mensajeError=$this->conexion->conectar->error;
    $id_producto=$this->conexion->conectar->insert_id;
    
    $respuesta=$mensajeError;
    if($mensajeError!=""){
      $respuesta.="<br><br>".$queryInsertProducto;
    }else{
      $respuesta=[
        "ok"=>1,
        "id_producto"=>$id_producto,
      ];
      $respuesta=json_encode($respuesta);
    }
  
    return $respuesta;
  }
		
  public function actualizarDatosProducto($id_producto, $precio, $kg_x_bulto){

    $this->id_producto = $id_producto;

    $sqlupdateProducto = "UPDATE productos SET ultimo_precio='$precio', ultimo_kg_x_bulto='$kg_x_bulto' WHERE id=$this->id_producto";
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
}	

if (isset($_POST['accion'])) {
  $producto = new producto();
  switch ($_POST['accion']) {
    /*case 'traerProducto':
      $producto->traerTodosProducto();
      break;*/
    case 'traerProductoUpdate':
        $id_producto = $_POST['id_producto'];
        echo $producto->traerProductoUpdate($id_producto);
      break;
    case 'updateProducto':
        $id_producto = $_POST['id_producto'];
        $nombre = utf8_encode($_POST['nombre']);
        $id_presentacion = utf8_encode($_POST['id_presentacion']);
        $id_unidad_medida = utf8_encode($_POST['id_unidad_medida']);
        $id_familia = utf8_encode($_POST['id_familia']);
        echo $producto->productoUpdate($id_producto, $nombre, $id_presentacion, $id_unidad_medida, $id_familia);
      break;
    // case 'cambiarEstado':
    //     $id_producto = $_POST['id_producto'];
    //     $estado = $_POST['estado'];
    //     $producto->cambiarEstado($id_producto, $estado);
        
    //   break;
    case 'eliminarProducto':
        $id_producto = $_POST['id_producto'];
        $producto->deleteProducto($id_producto);
      break;
    case 'traerDatosInicialesProducto':
      $producto->traerDatosIniciales();
      break;
    case 'verificarCuenta':
      $email = $_POST['email'];
      $producto->verificarCuentaExitente($email);
      break;
    case 'addProducto':
      $nombre = utf8_encode($_POST['nombre']);
      $id_presentacion = $_POST['id_presentacion'];
      $id_unidad_medida = $_POST['id_unidad_medida'];
      $id_familia = $_POST['id_familia'];
      echo $producto->registrarProducto( $nombre, $id_presentacion, $id_unidad_medida, $id_familia);
      break;
  }
}elseif(isset($_GET['accion'])){
  $producto = new producto();
  switch ($_GET['accion']) {
    case 'traerProducto':
      echo $producto->traerProducto();
    break;
  }
}
?>