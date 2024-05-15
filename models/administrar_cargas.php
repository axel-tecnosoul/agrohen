<?php
session_start();
include_once('conexion.php');
class cargas{
  private $id_deposito;
  private $nombre;
  private $id_responsable;
  private $porcentaje_extra;
  
  public function __construct(){
      $this->conexion = new Conexion();
      date_default_timezone_set("America/Buenos_Aires");
  }

  public function traerDatosIniciales(){
    $datosIniciales = array();

    /*Choferes*/
    $queryChoferes = "SELECT id as id_chofer, nombre FROM choferes";
    $getChoferes = $this->conexion->consultaRetorno($queryChoferes);
    $arrayChoferes[] = [
      'id_chofer' => "",
      'chofer' =>"Seleccione..."
    ];
    /*CARGO ARRAY choferes*/
    while ($rowChoferes = $getChoferes->fetch_array()) {
      $arrayChoferes[]= array(
        'id_chofer' =>$rowChoferes['id_chofer'],
        'chofer' =>$rowChoferes['nombre'],
      );
    }

    /*Origenes*/
    $queryOrigenes = "SELECT id as id_origen, nombre FROM origenes";
    $getOrigenes = $this->conexion->consultaRetorno($queryOrigenes);
    $arrayOrigenes[] = [
      'id_origen' => "",
      'origen' =>"Seleccione..."
    ];
    /*CARGO ARRAY origenes*/
    while ($rowOrigenes = $getOrigenes->fetch_array()) {
      $arrayOrigenes[]= array(
        'id_origen' =>$rowOrigenes['id_origen'],
        'origen' =>$rowOrigenes['nombre'],
      );
    }

    /*Proveedores*/
    $queryProveedres = "SELECT id as id_proveedor, nombre FROM proveedores";
    $getProveedres = $this->conexion->consultaRetorno($queryProveedres);
    $arrayProveedres[] = [
      'id_proveedor' => "",
      'proveedor' =>"Seleccione..."
    ];
    /*CARGO ARRAY proveedores*/
    while ($rowProveedres = $getProveedres->fetch_array()) {
      $arrayProveedres[]= array(
        'id_proveedor' =>$rowProveedres['id_proveedor'],
        'proveedor' =>$rowProveedres['nombre'],
      );
    }

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

    /*Destino*/
    $queryDestinos = "SELECT id as id_destino, nombre, porcentaje_extra FROM destinos";
    $getDestinos = $this->conexion->consultaRetorno($queryDestinos);
    /*CARGO ARRAY Destinos*/
    while ($row = $getDestinos->fetch_array()) {
      $arrayDestinos[]=[
        'id_destino' => $row["id_destino"],
        'destino' =>$row["nombre"],
        'porcentaje_extra' =>$row["porcentaje_extra"],
      ];
    }

    $datosIniciales["choferes"] = $arrayChoferes;
    $datosIniciales["origenes"] = $arrayOrigenes;
    $datosIniciales["proveedores"] = $arrayProveedres;
    $datosIniciales["familias"] = $arrayFamilias;
    $datosIniciales["destinos"] = $arrayDestinos;
    echo json_encode($datosIniciales);
  }

  public function getProductosByFamilia($id_familia){
    $productosByFamilia = array();

    if($id_familia>0){
      /*Productos*/
      $queryProductos = "SELECT id as id_producto, nombre FROM productos WHERE id_familia = $id_familia";
      $getProductos = $this->conexion->consultaRetorno($queryProductos);

      if ($getProductos->num_rows > 0) {
        $productosByFamilia[] = [
          'id_producto' => "",
          'producto' =>"Seleccione..."
        ];
        /*CARGO ARRAY productos*/
        while ($rowProductos = $getProductos->fetch_array()) {
          $productosByFamilia[]= array(
            'id_producto' =>$rowProductos['id_producto'],
            'producto' =>$rowProductos['nombre'],
          );
        }
      }else{
        $productosByFamilia[] = [
          'id_producto' => "",
          'producto' =>"Sin resultados"
        ];
      }
    }else{
      $productosByFamilia[] = [
        'id_producto' => "",
        'producto' =>"Seleccione una familia"
      ];
    }

    echo json_encode($productosByFamilia);
  }

  public function getDatosCarga($id_carga){
    $datosCarga = array();

    $queryCarga = "SELECT id_proveedor_default as id_proveedor, fecha, id_origen, o.nombre AS origen, id_chofer, datos_adicionales_chofer, ch.nombre AS chofer,IF(c.fecha_hora_despacho IS NULL,'No','Si') AS despachado,c.fecha_hora_despacho,c.id_usuario,u.usuario FROM cargas c INNER JOIN origenes o ON c.id_origen=o.id INNER JOIN choferes ch ON c.id_chofer=ch.id INNER JOIN usuarios u ON c.id_usuario=u.id WHERE c.id = $id_carga";
    $getCarga = $this->conexion->consultaRetorno($queryCarga);
    $rowCarga = $getCarga->fetch_array();

    $fecha_hora_despacho="(La carga aún no fue despachada)";
    if($rowCarga['fecha_hora_despacho']){
      $fecha_hora_despacho=date("d-m-Y H:i",strtotime($rowCarga['fecha_hora_despacho']));
    }

    $datosCarga=[
      'id_proveedor' =>$rowCarga['id_proveedor'],
      'fecha' =>$fecha=$rowCarga['fecha'],
      'fecha_formatted' =>date("d-m-Y",strtotime($fecha)),
      'id_origen' =>$rowCarga['id_origen'],
      'origen' =>$rowCarga['origen'],
      'id_chofer' =>$rowCarga['id_chofer'],
      'datos_adicionales_chofer' =>$rowCarga['datos_adicionales_chofer'],
      'chofer' =>$rowCarga['chofer'],
      'despachado' =>$rowCarga['despachado'],
      'fecha_hora_despacho' =>$fecha_hora_despacho,
      'usuario' =>$rowCarga['usuario'],
    ];

    echo json_encode($datosCarga);
  }

  public function traerCargas(){
    $sqltraerCargas = "SELECT c.id AS id_carga,c.fecha,c.id_origen,o.nombre AS origen,c.id_chofer,ch.nombre AS chofer,c.datos_adicionales_chofer,total_kilos,total_monto,IF(c.fecha_hora_despacho IS NULL,'No','Si') AS despachado,c.fecha_hora_despacho,c.id_usuario,u.usuario,c.anulado FROM cargas c INNER JOIN choferes ch ON c.id_chofer=ch.id INNER JOIN origenes o ON c.id_origen=o.id INNER JOIN usuarios u ON c.id_usuario=u.id WHERE 1";
    $traerCargas = $this->conexion->consultaRetorno($sqltraerCargas);
    $cargas = array(); //creamos un array
    
    while ($row = $traerCargas->fetch_array()) {
      $cargas[] = array(
        'id_carga'=>$row['id_carga'],
        'fecha'=>$fecha=$row['fecha'],
        'fecha_formatted'=>date("d-m-Y",strtotime($fecha)),
        'id_origen'=>$row['id_origen'],
        'origen'=>$row['origen'],
        'id_chofer'=>$row['id_chofer'],
        'chofer'=>$row['chofer'],
        'datos_adicionales_chofer'=>$row['datos_adicionales_chofer'],
        'total_kilos'=>$row['total_kilos'],
        'total_monto'=>$row['total_monto'],
        'fecha_hora_despacho'=>date("d-m-Y H:i",strtotime($row['fecha_hora_despacho'])),
        'despachado'=>$row['despachado'],
        'id_usuario'=>$row['id_usuario'],
        'usuario'=>$row['usuario'],
        'anulado'=>$row['anulado'],
      );
    }

    return json_encode($cargas);
  }

  public function traerProductosCarga($id_carga){
    $sqltraerProductosCarga = "SELECT cp.id AS id_carga_producto,cp.id_producto,fp.familia,pp.nombre AS presentacion,um.unidad_medida,p.nombre AS producto,pr.nombre AS proveedor,cp.kg_x_bulto,cp.total_kilos,cp.total_monto,u.usuario,cp.fecha_hora_alta FROM carga_productos cp INNER JOIN productos p ON cp.id_producto=p.id INNER JOIN familias_productos fp ON p.id_familia=fp.id INNER JOIN presentaciones_productos pp ON p.id_presentacion=pp.id INNER JOIN unidades_medida um ON p.id_unidad_medida=um.id INNER JOIN proveedores pr ON cp.id_proveedor=pr.id INNER JOIN usuarios u ON cp.id_usuario=u.id WHERE cp.id_carga = ".$id_carga." ";//GROUP BY cp.id_producto, cp.id_proveedor, cp.kg_x_bulto
    //echo $sqltraerProductosCarga;
    $traerProductosCarga = $this->conexion->consultaRetorno($sqltraerProductosCarga);
    
    $cargas = array(); //creamos un array
    if($traerProductosCarga){
      while ($row = $traerProductosCarga->fetch_array()) {
        $cargas[] = array(
          'id_carga_producto'=>$row['id_carga_producto'],
          'id_producto'=>$row['id_producto'],
          'familia'=>$row['familia'],
          'presentacion'=>$row['presentacion'],
          'unidad_medida'=>$row['unidad_medida'],
          'producto'=>$row['producto'],
          'proveedor'=>$row['proveedor'],
          'kg_x_bulto'=>$row['kg_x_bulto'],
          'total_kilos'=>$row['total_kilos'],
          'total_monto'=>$row['total_monto'],
          'usuario'=>$row['usuario'],
          'fecha_hora_alta'=>$row['fecha_hora_alta'],
        );
      }
    }

    return json_encode($cargas);
  }

  public function traerProductoDestinosCarga($id_carga_producto){
    $sqlTraerProductoDestinosCarga = "SELECT cp.id AS id_carga_producto,cp.id_producto,p.id_familia,cp.id_proveedor,cp.kg_x_bulto,cp.precio,cp.total_kilos,cp.total_monto FROM carga_productos cp INNER JOIN productos p ON cp.id_producto=p.id WHERE cp.id = $id_carga_producto";
    //var_dump($sqlTraerProductoDestinosCarga);
    $traerProductoCarga = $this->conexion->consultaRetorno($sqlTraerProductoDestinosCarga);
    $row = $traerProductoCarga->fetch_array();
    $productoCarga = [
      'id_carga_producto'=> $row['id_carga_producto'],
      'id_familia'=> $row['id_familia'],
      'id_producto'=> $row['id_producto'],
      'id_proveedor'=> $row['id_proveedor'],
      'kg_x_bulto'=> $row['kg_x_bulto'],
      'precio'=> $row['precio'],
      'total_kilos'=> $row['total_kilos'],
      'total_monto'=> $row['total_monto'],
    ];

    $sqlTraerProductoDestinosCarga = "SELECT cpd.id AS id_producto_destino,cpd.id_destino,d.nombre AS destino,cpd.cantidad_bultos,cpd.monto,cpd.kilos FROM carga_productos_destinos cpd INNER JOIN destinos d ON cpd.id_destino=d.id WHERE cpd.id_carga_producto = $id_carga_producto";
    //var_dump($sqlTraerProductoDestinosCarga);
    $traerProductoDestinosCarga = $this->conexion->consultaRetorno($sqlTraerProductoDestinosCarga);
    $productoDestinosCarga=[];
    while ($row = $traerProductoDestinosCarga->fetch_array()) {
      $productoDestinosCarga[] = [
        'id_producto_destino'=> $row['id_producto_destino'],
        'id_destino'=> $row['id_destino'],
        'destino'=> $row['destino'],
        'cantidad_bultos'=> $row['cantidad_bultos'],
        'monto'=> $row['monto'],
        'kilos'=> $row['kilos'],
      ];
    }
    $productoCarga["destinos"]=$productoDestinosCarga;
    return json_encode($productoCarga);
  }

  public function updateProductoCarga($id_carga,$id_carga_producto,$id_producto,$id_proveedor,$kg_x_bulto,$precio,$datosDepositos){

    $id_usuario = $_SESSION['rowUsers']['id_usuario'];

    $queryUpdateProductoCarga = "UPDATE carga_productos SET id_producto=$id_producto, id_proveedor=$id_proveedor, kg_x_bulto=$kg_x_bulto, precio=$precio WHERE id = $id_carga_producto";
    $insertCarga = $this->conexion->consultaSimple($queryUpdateProductoCarga);
    $mensajeError=$this->conexion->conectar->error;

    $cantDatos=$cantDatosOk=0;
    $errores="";
    foreach ($datosDepositos as $row) {
      $cantDatos++;
      //var_dump($row);
      $id_producto_destino=$row["id_producto_destino"];
      $cantidad_bultos=$row["cantidad_bultos"];
      $id_deposito=$row["id_deposito"];
      $porcentaje_extra=$row["porcentaje_extra"];
      $subtotal_kilos=$row["subtotal_kilos"];

      $mensajeError="";
      if($cantidad_bultos>0){
        $monto=$cantidad_bultos*$precio;
        if($id_producto_destino>0){
          $query=$queryUpdateCarga = "UPDATE carga_productos_destinos SET id_destino = $id_deposito, porcentaje_extra = $porcentaje_extra, cantidad_bultos = $cantidad_bultos, monto = $monto, kilos = $subtotal_kilos, id_usuario = $id_usuario WHERE id = $id_producto_destino";
          $updateCarga = $this->conexion->consultaSimple($queryUpdateCarga);
          $mensajeError=$this->conexion->conectar->error;
        }else{
          $query=$queryInsertCarga = "INSERT INTO carga_productos_destinos (id_carga_producto, id_destino, porcentaje_extra, cantidad_bultos, monto, kilos, id_usuario) VALUES($id_carga_producto, $id_deposito, $porcentaje_extra, $cantidad_bultos, $monto, $subtotal_kilos, $id_usuario)";
          $insertCarga = $this->conexion->consultaSimple($queryInsertCarga);
          $mensajeError=$this->conexion->conectar->error;
        }
      }elseif($id_producto_destino>0){
        $query=$sqleliminarProductoCarga = "DELETE FROM carga_productos_destinos WHERE id = $id_producto_destino";
        $eliminarProductoCarga = $this->conexion->consultaSimple($sqleliminarProductoCarga);
        $mensajeError=$this->conexion->conectar->error;
      }
      
      if($mensajeError==""){
        $cantDatosOk++;
      }else{
        $errores.=$mensajeError."<br><br>".$query."<hr>";
      }
    }

    $respuesta=$errores;
    if($respuesta==""){

      $queryGetSumas = "SELECT SUM(monto) AS suma_monto, SUM(kilos) AS suma_kilos FROM carga_productos_destinos WHERE id_carga_producto=".$id_carga_producto;
      $getSumas = $this->conexion->consultaRetorno($queryGetSumas);
      $row = $getSumas->fetch_array();
      $sumaKilos=0;
      if($row["suma_kilos"]>0){
        $sumaKilos=$row["suma_kilos"];
      }
      $sumaMonto=0;
      if($row["suma_monto"]>0){
        $sumaMonto=$row["suma_monto"];
      }

      $queryInsertCarga = "UPDATE carga_productos SET total_kilos = $sumaKilos, total_monto = $sumaMonto WHERE id = ".$id_carga_producto;
      $insertCarga = $this->conexion->consultaSimple($queryInsertCarga);
      $mensajeError=$this->conexion->conectar->error;

      if($mensajeError==""){
        $this->updateTotalesCarga($id_carga);
        $respuesta=[
          "ok"=>1,
        ];
        $respuesta=json_encode($respuesta);
      }else{
        $respuesta="Algo ha fallado al actualizar los kilos y el monto total del producto";
      }
    }
    
    return $respuesta;
  }

  public function eliminarCarga($id_carga){

    $sqltraerCargas = "SELECT id FROM carga_productos WHERE id_carga = ".$id_carga;
    $traerCargas = $this->conexion->consultaRetorno($sqltraerCargas);
    $cant=$cantOk=0;
    while ($row = $traerCargas->fetch_array()) {
      $cant++;
      $id_carga_producto=$row["id"];
      $respuesta=$this->eliminarProductoCarga($id_carga_producto,$id_carga);
      if($respuesta=="ok"){
        $cantOk++;
      }
    }

    if($cant==$cantOk){
      /*ELIMINO la carga*/
      $sqleliminarCarga = "DELETE FROM cargas WHERE id = $id_carga";
      $eliminarCarga = $this->conexion->consultaSimple($sqleliminarCarga);
    }
  }

  public function despacharCarga($id_carga){

    $queryUpdateEstado = "UPDATE cargas SET fecha_hora_despacho = NOW() WHERE id = $id_carga";
    $updateEstado = $this->conexion->consultaSimple($queryUpdateEstado);
  }

  public function registrarCarga($fecha_carga,$id_origen,$id_chofer,$datos_adicionales_chofer,$id_proveedor_default){

    $id_usuario = $_SESSION['rowUsers']['id_usuario'];

    $queryInsertCarga = "INSERT INTO cargas (id_chofer, id_origen, fecha, datos_adicionales_chofer, id_proveedor_default, id_usuario) VALUES($id_chofer, $id_origen, '$fecha_carga', '$datos_adicionales_chofer', '$id_proveedor_default', $id_usuario)";
    $insertCarga = $this->conexion->consultaSimple($queryInsertCarga);
    $mensajeError=$this->conexion->conectar->error;
    $id_carga=$this->conexion->conectar->insert_id;
    
    $respuesta=$mensajeError;
    if($mensajeError!=""){
      $respuesta.="<br><br>".$queryInsertCarga;
    }else{
      $respuesta=[
        "ok"=>1,
        "id_carga"=>$id_carga,
      ];
      $respuesta=json_encode($respuesta);
    }
    
    return $respuesta;
  }

  public function addProductoCarga($id_carga,$id_producto,$id_proveedor,$kg_x_bulto,$precio,$datosDepositos){

    $id_usuario = $_SESSION['rowUsers']['id_usuario'];

    $queryInsertCarga = "INSERT INTO carga_productos (id_carga, id_producto, id_proveedor, kg_x_bulto, precio, id_usuario) VALUES($id_carga, $id_producto, '$id_proveedor', '$kg_x_bulto', $precio, $id_usuario)";
    $insertCarga = $this->conexion->consultaSimple($queryInsertCarga);
    $mensajeError=$this->conexion->conectar->error;
    $id_carga_producto=$this->conexion->conectar->insert_id;
    
    if($mensajeError=="" and $id_carga_producto>0){
      $cantDatos=$cantDatosOk=0;
      $errores="";

      $sumaMonto=$sumaKilos=0;
      foreach ($datosDepositos as $row) {
        $cantDatos++;
        //var_dump($row);
        $cantidad_bultos=$row["cantidad_bultos"];
        $id_deposito=$row["id_deposito"];
        $kilos=$row["subtotal_kilos"];
        $porcentaje_extra=$row["porcentaje_extra"];

        if($cantidad_bultos>0){
          $monto=$cantidad_bultos*$precio;

          $sumaMonto+=$monto;
          $sumaKilos+=$kilos;

          $queryInsertCarga = "INSERT INTO carga_productos_destinos (id_carga_producto, id_destino, porcentaje_extra, cantidad_bultos, monto, kilos,id_usuario) VALUES($id_carga_producto, $id_deposito, $porcentaje_extra, $cantidad_bultos, $monto, $kilos, $id_usuario)";
          $insertCarga = $this->conexion->consultaSimple($queryInsertCarga);
          $mensajeError=$this->conexion->conectar->error;
          
          if($mensajeError==""){
            $cantDatosOk++;
          }else{
            $errores.=$mensajeError."<br><br>".$queryInsertCarga."<hr>";
          }
        }
      }

      $respuesta=$errores;
      if($respuesta==""){

        $queryInsertCarga = "UPDATE carga_productos SET total_kilos = $sumaKilos, total_monto = $sumaMonto WHERE id = ".$id_carga_producto;
        $insertCarga = $this->conexion->consultaSimple($queryInsertCarga);
        $mensajeError=$this->conexion->conectar->error;

        if($mensajeError==""){
          $this->updateTotalesCarga($id_carga);
          $respuesta=[
            "ok"=>1,
          ];
          $respuesta=json_encode($respuesta);
        }else{
          $respuesta="Algo ha fallado al actualizar los kilos y el monto total del producto";
        }
      }
    }else{
      $respuesta="Algo ha fallado al registrar el producto en la carga";
    }
    
    return $respuesta;
  }

  public function eliminarProductoCarga($id_carga_producto,$id_carga){

    /*ELIMINO los destinos*/
    $sqleliminarProductoCarga = "DELETE FROM carga_productos_destinos WHERE id_carga_producto = $id_carga_producto";
    $eliminarProductoCarga = $this->conexion->consultaSimple($sqleliminarProductoCarga);
    $mensajeError=$this->conexion->conectar->error;
    $respuesta="";
    if($mensajeError==""){
      /*ELIMINO el producto*/
      $sqleliminarProductoCarga = "DELETE FROM carga_productos WHERE id = $id_carga_producto";
      $eliminarProductoCarga = $this->conexion->consultaSimple($sqleliminarProductoCarga);
      $mensajeError=$this->conexion->conectar->error;
      if($mensajeError==""){
        $this->updateTotalesCarga($id_carga);
        $respuesta="ok";
      }else{
        $respuesta=$mensajeError;
      }
    }else{
      $respuesta=$mensajeError;
    }

    return $respuesta;
  }

  private function updateTotalesCarga($id_carga){

    $queryGetSumas = "SELECT SUM(total_kilos) AS suma_kilos, SUM(total_monto) AS suma_monto FROM carga_productos WHERE id_carga=".$id_carga;
      $getSumas = $this->conexion->consultaRetorno($queryGetSumas);
      $row = $getSumas->fetch_array();
      $sumaKilos=0;
      if($row["suma_kilos"]>0){
        $sumaKilos=$row["suma_kilos"];
      }
      $sumaMonto=0;
      if($row["suma_monto"]>0){
        $sumaMonto=$row["suma_monto"];
      }

      $queryInsertCarga = "UPDATE cargas SET total_kilos = $sumaKilos, total_monto = $sumaMonto WHERE id = ".$id_carga;
      $insertCarga = $this->conexion->consultaSimple($queryInsertCarga);
      $mensajeError=$this->conexion->conectar->error;

      if($mensajeError==""){
        $respuesta=[
          "ok"=>1,
        ];
        $respuesta=json_encode($respuesta);
      }else{
        $respuesta="Algo ha fallado al actualizar los kilos y el monto total del producto";
      }
  }

  public function updateCarga($id_carga,$fecha_carga,$id_origen,$id_chofer,$datos_adicionales_chofer,$id_proveedor_default){

    $id_usuario = $_SESSION['rowUsers']['id_usuario'];

    $queryUpdateCarga = "UPDATE cargas SET fecha='$fecha_carga', id_origen=$id_origen, id_chofer=$id_chofer, datos_adicionales_chofer='$datos_adicionales_chofer', id_proveedor_default=$id_proveedor_default WHERE id = $id_carga";
    $insertCarga = $this->conexion->consultaSimple($queryUpdateCarga);
    $mensajeError=$this->conexion->conectar->error;

    $respuesta=$mensajeError;
    if($mensajeError!=""){
      $respuesta.="<br><br>".$queryUpdateCarga;
    }else{
      $respuesta=[
        "ok"=>1,
        "id_carga"=>$id_carga,
      ];
      $respuesta=json_encode($respuesta);
    }
    
    return $respuesta;
  }
}	

if (isset($_POST['accion'])) {
  $cargas = new cargas();
  switch ($_POST['accion']) {
    case 'traerAlmacenes':
      $almacenes->traerTodoscargas();
    break;
    case 'traerProductoDestinosCarga':
      $id_carga_producto = $_POST['id_carga_producto'];
      echo $cargas->traerProductoDestinosCarga($id_carga_producto);
    break;
    case 'updateCarga':
      $id_carga = $_POST['id_carga'];
      $fecha_carga=$_POST["fecha_carga"];
      $id_origen=$_POST["id_origen"];
      $id_chofer=$_POST["id_chofer"];
      $datos_adicionales_chofer=$_POST["datos_adicionales_chofer"];
      $id_proveedor_default=$_POST["id_proveedor_default"];
      echo $cargas->updateCarga($id_carga,$fecha_carga,$id_origen,$id_chofer,$datos_adicionales_chofer,$id_proveedor_default);
    break;
    case 'updateProductoCarga':
      $id_carga_producto=$_POST["id_carga_producto"];
      $id_carga=$_POST["id_carga"];
      $id_producto=$_POST["id_producto"];
      $id_proveedor=$_POST["id_proveedor"];
      $kg_x_bulto=$_POST["kg_x_bulto"];
      $precio=$_POST["precio"];
      $datosDepositos=$_POST["datosDepositos"];
      echo $cargas->updateProductoCarga($id_carga,$id_carga_producto,$id_producto,$id_proveedor,$kg_x_bulto,$precio,$datosDepositos);
    break;
    case 'despacharCarga':
      $id_carga = $_POST['id_carga'];
      $cargas->despacharCarga($id_carga);
    break;
    case 'eliminarCarga':
      $id_carga = $_POST['id_carga'];
      $cargas->eliminarCarga($id_carga);
    break;
    case 'traerDatosIniciales':
      $cargas->traerDatosIniciales();
    break;
    case 'addCarga':
      $fecha_carga=$_POST["fecha_carga"];
      $id_origen=$_POST["id_origen"];
      $id_chofer=$_POST["id_chofer"];
      $datos_adicionales_chofer=$_POST["datos_adicionales_chofer"];
      $id_proveedor_default=$_POST["id_proveedor_default"];

      echo $cargas->registrarCarga($fecha_carga,$id_origen,$id_chofer,$datos_adicionales_chofer,$id_proveedor_default);
    break;
    case 'getProductosByFamilia':
      $id_familia=$_POST["id_familia"];
      echo $cargas->getProductosByFamilia($id_familia);
    break;
    case 'getDatosCarga':
      $id_carga=$_POST["id_carga"];
      echo $cargas->getDatosCarga($id_carga);
    break;
    case 'addProductoCarga':
      $id_carga=$_POST["id_carga"];
      $id_producto=$_POST["id_producto"];
      $id_proveedor=$_POST["id_proveedor"];
      $kg_x_bulto=$_POST["kg_x_bulto"];
      $precio=$_POST["precio"];
      $datosDepositos=$_POST["datosDepositos"];
      echo $cargas->addProductoCarga($id_carga,$id_producto,$id_proveedor,$kg_x_bulto,$precio,$datosDepositos);
    break;
    case 'traerProductosCarga':
      $id_carga=$_POST["id_carga"];
      echo $cargas->traerProductosCarga($id_carga);
    break;
    case 'eliminarProductoCarga':
      $id_carga=$_POST["id_carga"];
      $id_carga_producto=$_POST["id_carga_producto"];
      echo $cargas->eliminarProductoCarga($id_carga_producto,$id_carga);
    break;
  }
}else{
  $cargas = new cargas();
  switch ($_GET['accion']) {
    case 'traerCargas':
      echo $cargas->traerCargas();
    break;
    case 'traerProductosCarga':
      $id_carga=$_GET["id_carga"];
      echo $cargas->traerProductosCarga($id_carga);
    break;
  }
}
?>