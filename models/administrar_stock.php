<?php
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
  // session isn't started
  session_start();
}
require_once('../../conexion.php');
require_once('administrar_movimientos_stock.php');
extract($_REQUEST);
class Stock{

  private $id_proveedor;
  private $id_orden;
  private $id_almacen;
  private $id_item;

  public function __construct(){
    $this->conexion = new Conexion();
    date_default_timezone_set("America/Buenos_Aires");
  }

  public function traerDatosIniciales(){

    /*ALMACEN*/
    $queryAlmacenes = "SELECT id, almacen FROM almacenes WHERE activo = 1";
    $getAlmacenes = $this->conexion->consultaRetorno($queryAlmacenes);

    /*PROVEEDORES*/
    $queryProveedores = "SELECT id as id_proveedor, razon_social FROM proveedores WHERE activo = 1";
    $getProveedores = $this->conexion->consultaRetorno($queryProveedores);

    /*TOTALIZADORES*/
    $queryGetTotalizadores = "SELECT sum(cantidad_disponible) as totItem, 
                sum(cantidad_disponible) as cantDisp, 
                sum(cantidad_reservada) as cantReserv,
                sum(cantidad_disponible*precio_unitario) as valoracion
                FROM stock";
    $getTotalizadores = $this->conexion->consultaRetorno($queryGetTotalizadores);

    $datosIniciales = array();
    $almacenes = array();
    $proveedores = array();
    $totalizadores = array();

    /*CARGO ARRAY ALMACENES*/
    while ($rowsAlmacenes= $getAlmacenes->fetch_array()) {
      $id_almacen = $rowsAlmacenes['id'];
      $almacen = $rowsAlmacenes['almacen'];
      $almacenes[] = array('id_almacen' => $id_almacen, 'almacen' =>$almacen);
    }

    /*CARGO ARRAY PROVEEDORES*/
    while ($rowProveedores= $getProveedores->fetch_array()) {
      $id_proveedor = $rowProveedores['id_proveedor'];
      $razon_social = $rowProveedores['razon_social'];
      $proveedores[] = array('id_proveedor' => $id_proveedor, 'razon_social' =>$razon_social);
    }

    /*CARGO ARRAY TOTALIZADORES*/
    while ($rowTotalizador = $getTotalizadores->fetch_array()) {
      $totItem = $rowTotalizador['totItem'];
      $cantDisp = $rowTotalizador['cantDisp'];
      $cantReserv = $rowTotalizador['cantReserv'];
      $valoracion = "$".number_format($rowTotalizador['valoracion'],2,',','.');
      $totalizadores[]=array("totItem"=>$totItem, "cantDisp"=>$cantDisp, "cantReserv"=>$cantReserv, "valoracion"=>$valoracion);
    }

    $datosIniciales["almacenes"] = $almacenes;
    $datosIniciales["proveedores"] = $proveedores;
    $datosIniciales["totalizadores"] = $totalizadores;

    echo json_encode($datosIniciales);
  }

  public function traerItems($filtros=0){
    
    $arrayStock = array();

    $filtro_almacen="";
    if($filtros!=0){
        //var_dump($filtros);
        if(isset($filtros["id_almacen"]) and $filtros["id_almacen"]!=""){
            $filtro_almacen=" AND alm.id = '".$filtros["id_almacen"]."'";
        }
    }

    $queryGetStock = "SELECT st.id_item, item, razon_social as proveedor, prov.id AS id_proveedor, almacen, st.cantidad_disponible, st.cantidad_reservada, st.precio_unitario, it.punto_reposicion, it.hash, st.fecha_hora_ultima_actualizacion as fecha, um.unidad_medida, cat.categoria, tp.tipo, it.imagen, st.id_almacen
    FROM stock st JOIN item as it ON(st.id_item = it.id)
      JOIN proveedores prov ON(st.id_proveedor = prov.id)
      JOIN almacenes alm ON(st.id_almacen = alm.id)
      JOIN categorias_item as cat ON(it.id_categoria = cat.id)
      JOIN unidades_medida as um ON(it.id_unidad_medida = um.id)
      JOIN tipos_items as tp ON(it.id_tipo = tp.id)
    WHERE 1 $filtro_almacen";
    $getStock = $this->conexion->consultaRetorno($queryGetStock);
    $mensajeError=$this->conexion->conectar->error;
    //echo $mensajeError;
    if($mensajeError!=""){
      echo $mensajeError."<br><br>".$queryGet."<br><br>";
    }

    while ($rowStock = $getStock->fetch_array()) {
      $arrayStock[] = array(
        'id_item'=>$rowStock['id_item'],
        'item'=>$rowStock['item'],
        'unidad_medida'=>$rowStock['unidad_medida'],
        'categoria'=>$rowStock['categoria'],
        'tipo'=>$rowStock['tipo'],
        'id_proveedor'=>$rowStock['id_proveedor'],
        'proveedor'=>$rowStock['proveedor'],
        'id_almacen'=>$rowStock['id_almacen'],
        'almacen'=>$rowStock['almacen'],
        'cantDisp'=>$rowStock['cantidad_disponible'],
        'cantReserv'=>$rowStock['cantidad_reservada'],
        'precio_unitario' =>"$".number_format($rowStock['precio_unitario'],2,',','.'),
        'precio_unitario_sin_formato' =>$rowStock['precio_unitario'],
        'punto_reposicion'=>$rowStock['punto_reposicion'],
        'hash'=>$rowStock['hash'],
        'fecha'=>date("d/m/Y H:m:s", strtotime($rowStock['fecha'])),
        'imagen'=>$rowStock["imagen"]
      );
    }

    echo json_encode($arrayStock);
  }

  public function traerStockFiltro($almacen, $proveedor){
    $arrayStock = array();
    $condicion = "";

    if($proveedor != "" || $almacen !=""){

      if($proveedor != "" && $almacen !=""){
        $condicion = " WHERE id_proveedor = ".$proveedor." AND id_almacen =".$almacen;
      }else if($proveedor !=""){
        $condicion = " WHERE id_proveedor = ".$proveedor;
      }else{
        $condicion = " WHERE id_almacen = ".$almacen;
      }
    }
    $queryGetStock = "SELECT st.id_item, item, razon_social as proveedor, 
          almacen, st.cantidad_disponible, st.cantidad_reservada, 
          st.precio_unitario, it.punto_reposicion, it.hash, st.fecha_hora_ultima_actualizacion as fecha
          FROM stock as st JOIN item as it
          ON(st.id_item = it.id)
          JOIN proveedores as prov
          ON(st.id_proveedor = prov.id)
          JOIN almacenes as alm
          ON(st.id_almacen = alm.id) ".$condicion;
    $getStock = $this->conexion->consultaRetorno($queryGetStock);

    if($getStock){

      while ($rowStock = $getStock->fetch_array()) {
      $id_item= $rowStock['id_item'];
      $item= $rowStock['item'];
      $proveedor= $rowStock['proveedor'];
      $almacen = $rowStock['almacen'];
      $cantDisp = $rowStock['cantidad_disponible'];
      $cantReserv = $rowStock['cantidad_reservada'];
      $precio_unitario= "$".number_format($rowStock['precio_unitario'],2,',','.');
      $fecha= date("d/m/Y H:m:s", strtotime($rowStock['fecha']));
      $punto_reposicion = $rowStock['punto_reposicion'];
      $hash = $rowStock['hash'];
      $arrayStock[] = array('id_item'=>$id_item, 'item'=>$item, 'proveedor'=>$proveedor, 'almacen'=>$almacen, 'cantDisp'=>$cantDisp, 'cantReserv'=>$cantReserv, "precio_unitario" =>$precio_unitario,'punto_reposicion'=>$punto_reposicion, 'hash'=>$hash, 'fecha'=>$fecha);
    }
    }

    echo json_encode($arrayStock);
  }

  public function traerTotalizadoresFiltro($almacen, $proveedor){
    
    $condicion="";

    if($proveedor != "" || $almacen !=""){

      if($proveedor != "" && $almacen !=""){
        $condicion = "WHERE id_proveedor = ".$proveedor." AND id_almacen =".$almacen;
      }else if($proveedor !=""){
        $condicion = "WHERE id_proveedor = ".$proveedor;
      }else{
        $condicion = "WHERE id_almacen = ".$almacen;
      }
    }

    /*TOTALIZADORES*/
    $queryGetTotalizadores = "SELECT sum(cantidad_disponible) as totItem, 
                sum(cantidad_disponible) as cantDisp, 
                sum(cantidad_reservada) as cantReserv,
                sum(cantidad_disponible*precio_unitario) as valoracion
                FROM stock ".$condicion;
    $getTotalizadores = $this->conexion->consultaRetorno($queryGetTotalizadores);


    $totalizadores=array();

    /*CARGO ARRAY TOTALIZADORES*/
    while ($rowTotalizador = $getTotalizadores->fetch_array()) {
      $totItem = $rowTotalizador['totItem'];
      $cantDisp = $rowTotalizador['cantDisp'];
      $cantReserv = $rowTotalizador['cantReserv'];
      $valoracion = "$".number_format($rowTotalizador['valoracion'],2,',','.');
      $totalizadores[]=array("totItem"=>$totItem, "cantDisp"=>$cantDisp, "cantReserv"=>$cantReserv, "valoracion"=>$valoracion);
    }

    echo json_encode($totalizadores);

  }

  public function conciliar(){

    if (isset($_FILES['file0'])) {
      include_once('./PHPExcel/Classes/PHPExcel.php');
      $archivo = $_FILES['file0']['tmp_name'];
      $nombreArchivo = $_FILES['file0']['name'];
      $tipoArchivo = PHPExcel_IOFactory::identify($archivo);
      $objectReader = PHPExcel_IOFactory::createReader($tipoArchivo);
      $objPHPExcel = $objectReader->load($archivo);
      $sheet = $objPHPExcel->getSheet(0);
      $filaMasAlta = $sheet->getHighestRow();
      $colMasAlta = $sheet->getHighestColumn();
      $arrayResultados = array();
      $procesados = 0;
      $actualizados = 0;
      $no_procesados = 0;
      for ($row=2; $row <= $filaMasAlta ; $row++) {

        if($sheet->getCell("A".$row)->getFormattedValue()!=''){
          $id_item = $sheet->getCell("A".$row)->getFormattedValue();
          $cantDisponible = $sheet->getCell("B".$row)->getFormattedValue();
          $cantReservada = $sheet->getCell("C".$row)->getFormattedValue();
          $resultado = $this->conciliarItem($id_item, $cantDisponible, $cantReservada);

          switch ($resultado) {
            case 1:
              $procesados += 1;
              break;
            case 2:
              $actualizados +=1;
              break;
            case 3:
              $no_procesados += 1;
              break;
          }
        }
      }
    }
  }

  public function conciliarItem($id_item, $cantDisponible, $cantReservada){
    
    $fecha = date('Y-m-d H:i:s');
    $usuario = $_SESSION['rowUsers']['id_usuario'];

    /*SI EXISTE ACTUALIZO*/

          $queryUpdateStock = "UPDATE stock SET cantidad_disponible = $cantDisponible, cantidad_reservada = $cantReservada, fecha_hora_ultima_actualizacion = '$fecha'
                    WHERE id_item = $id_item";
          $updateStock = $this->conexion->consultaSimple($queryUpdateStock);

          /*OBTENGO ID DE STOCK*/
          $queryGetIdStock = "SELECT id as id_stock FROM stock 
                    WHERE id_item = $id_item";
          $getIdStock = $this->conexion->consultaRetorno($queryGetIdStock);

          $rowIdStock = $getIdStock->fetch_assoc();

          $id_stock = $rowIdStock['id_stock'];

          /*INSERTO MOVIMIENTOS STOCK*/
          $queryInsertMS = "INSERT INTO movimientos_stock(id_stock, cantidad, id_usuario, fecha_hora, tipo_movimiento)VALUES($id_stock, $cantDisponible, $usuario, '$fecha', 'Conciliación')";
          $insertMS = $this->conexion->consultaSimple($queryInsertMS);
  }

  public function aumentarStock($id_item,$id_proveedor,$id_almacen,$cantidad,$tipo_movimiento){
    $usuario = $_SESSION['rowUsers']['id_usuario'];

    /*OBTENGO ID DE STOCK*/
    $queryGetIdStock = "SELECT id as id_stock FROM stock WHERE id_item = $id_item AND id_proveedor = $id_proveedor AND id_almacen = $id_almacen";
    $getIdStock = $this->conexion->consultaRetorno($queryGetIdStock);
    $rowIdStock = $getIdStock->fetch_assoc();

    if(isset($rowIdStock['id_stock']) and $rowIdStock['id_stock']>0){
      $id_stock=$rowIdStock['id_stock'];
      $query = "UPDATE stock SET cantidad_disponible = cantidad_disponible + $cantidad WHERE id = $id_stock";
      $updateStock = $this->conexion->consultaSimple($query);
    }else{
      $query = "INSERT INTO stock (id_item, id_proveedor, id_almacen, cantidad_disponible, cantidad_reservada, precio_unitario, fecha_hora_ultima_actualizacion) VALUES ($id_item, $id_proveedor, $id_almacen, '$cantidad', 0, 0, NOW())";
      $updateStock = $this->conexion->consultaSimple($query);
      $id_stock=$this->conexion->conectar->insert_id;
    }

    $movimiento_stock=new Movimientos();
    $movimiento_stock->insertarMovimientoStock($id_stock, $cantidad, $tipo_movimiento);
  }

  public function reducirStock($id_item,$id_proveedor,$id_almacen,$cantidad,$tipo_movimiento){
    $usuario = $_SESSION['rowUsers']['id_usuario'];

    /*OBTENGO ID DE STOCK*/
    $queryGetIdStock = "SELECT id as id_stock FROM stock WHERE id_item = $id_item AND id_proveedor = $id_proveedor AND id_almacen = $id_almacen";
    $getIdStock = $this->conexion->consultaRetorno($queryGetIdStock);
    $rowIdStock = $getIdStock->fetch_assoc();
    $id_stock = $rowIdStock['id_stock'];

    $query = "UPDATE stock SET cantidad_disponible = cantidad_disponible - $cantidad WHERE id = $id_stock";
    $updateStock = $this->conexion->consultaSimple($query);

    $movimiento_stock=new Movimientos();
    $movimiento_stock->insertarMovimientoStock($id_stock, ($cantidad*-1), $tipo_movimiento);
  }

  public function movimientoEntreAlmacenes($id_almacen_origen,$id_almacen_destino,$aItems){
    foreach ($aItems as $item) {
      $id_item=$item["id_item"];
      $id_proveedor=$item["id_proveedor"];
      $cantidad=$item["cantidad"];
      $tipo_movimiento="Movimiento entre almacenes";
      $this->aumentarStock($id_item,$id_proveedor,$id_almacen_destino,$cantidad,$tipo_movimiento);
      $this->reducirStock($id_item,$id_proveedor,$id_almacen_origen,$cantidad,$tipo_movimiento);
    }
  }
}

if (isset($_POST['accion'])) {
  $stock = new stock();
  switch ($_POST['accion']) {
    case 'traerDatosIniciales':
      $stock->traerDatosIniciales();
      break;
    case 'actualizarTotalizadores':
      $almacen = $_POST['almacen'];
      $proveedor = $_POST['proveedor'];
      $stock->traerTotalizadoresFiltro($almacen, $proveedor);
      break;
    case 'conciliar':
      $stock->conciliar();
      break;
    case 'movimientoEntreAlmacenes':
      $stock->movimientoEntreAlmacenes($id_almacen_origen,$id_almacen_destino,json_decode($aItems,true));
      break;
  }
}else{
  if (isset($_GET['accion'])) {
    $stock = new Stock();

    switch ($_GET['accion']) {
      case 'traerItems':
        $filtros=[];
        if(isset($id_almacen)) $filtros["id_almacen"]=$id_almacen;
        $stock->traerItems($filtros);
        break;
      case 'traerStockFiltro':
        $almacen = $_GET['almacen'];
        $proveedor = $_GET['proveedor'];
        $stock->traerStockFiltro($almacen, $proveedor);
        break;
    }
  }
}?>