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
    $queryFamilias = "SELECT id as id_familia, familia FROM familias_productos WHERE activo = 1";
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
    $queryPresentaciones = "SELECT id as id_presentacion, nombre FROM presentaciones_productos WHERE activo = 1";
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
    $queryUnidadMedida = "SELECT id as id_unidad_medida, unidad_medida FROM unidades_medida WHERE activo = 1";
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
    $sqlTraerProducto = "SELECT p.id AS id_producto, p.nombre, pe.nombre as presentacion, um.unidad_medida, fp.familia, p.ultimo_precio,p.ultimo_kg_x_bulto, p.activo FROM productos p LEFT JOIN presentaciones_productos pe ON p.id_presentacion = pe.id LEFT JOIN familias_productos fp ON p.id_familia=fp.id LEFT JOIN unidades_medida um ON p.id_unidad_medida=um.id WHERE 1";
    $traerProducto = $this->conexion->consultaRetorno($sqlTraerProducto);
    $producto = array(); //creamos un array
    while ($row = $traerProducto->fetch_array()) {
      $producto[] = array(
        'id_producto'=>$row['id_producto'],
        'nombre'=>$row['nombre'],
        'familia'=>$row['familia'],
        'presentacion'=>$row['presentacion'],
        'unidad_medida'=>$row['unidad_medida'],
        'ultimo_precio'=>$row['ultimo_precio'],
        'ultimo_kg_x_bulto'=>$row['ultimo_kg_x_bulto'],
        'activo'=>$row['activo']
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
        'id_producto'=> $row['id_producto'],
        'nombre'=> $row['nombre'],
        'id_presentacion'=> $row['id_presentacion'],
        'id_unidad_medida'=> $row['id_unidad_medida'],
        'id_familia'=> $row['id_familia']
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

  public function traerDatosUltimaFamilia($id_familia){
    $sqlTraerProducto = "SELECT id_presentacion, id_unidad_medida FROM productos WHERE id_familia = $id_familia ORDER BY fecha_hora_alta DESC LIMIT 1";
    $traerProducto = $this->conexion->consultaRetorno($sqlTraerProducto);
    $row = $traerProducto->fetch_array();
    $id_presentacion=$row['id_presentacion'];
    if($id_presentacion<0){
      $id_presentacion="";
    }
    $id_unidad_medida=$row['id_unidad_medida'];
    if($id_unidad_medida<0){
      $id_unidad_medida="";
    }
    $datosFamilia=[
      'id_presentacion'=> $id_presentacion,
      'id_unidad_medida'=> $id_unidad_medida,
    ];

    return json_encode($datosFamilia);
  }

  public function cambiarEstado($id_producto, $estado){

    $this->id_producto = $id_producto;

    $queryUpdateEstado = "UPDATE productos SET activo = $estado WHERE id = $this->id_producto";
    $updateEstado = $this->conexion->consultaSimple($queryUpdateEstado);
  }
}	

if (isset($_POST['accion'])) {
  $producto = new producto();
  switch ($_POST['accion']) {
    case 'traerProductoUpdate':
      $id_producto = $_POST['id_producto'];
      echo $producto->traerProductoUpdate($id_producto);
    break;
    case 'updateProducto':
      $id_producto = $_POST['id_producto'];
      $nombre = $_POST['nombre'];
      $id_presentacion = $_POST['id_presentacion'];
      $id_unidad_medida = $_POST['id_unidad_medida'];
      $id_familia = $_POST['id_familia'];
      echo $producto->productoUpdate($id_producto, $nombre, $id_presentacion, $id_unidad_medida, $id_familia);
    break;
    case 'eliminarProducto':
      $id_producto = $_POST['id_producto'];
      echo $producto->deleteProducto($id_producto);
    break;
    case 'traerDatosInicialesProducto':
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
    case 'traerDatosUltimaFamilia':
      $id_familia = $_POST['id_familia'];
      echo $producto->traerDatosUltimaFamilia($id_familia);
    break;
    case 'cambiarEstado':
      $id_producto = $_POST['id_producto'];
      $estado = $_POST['estado'];
      $producto->cambiarEstado($id_producto, $estado);
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