<?php
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
  // session isn't started
  session_start();
}
require_once('./conexion.php');
class Movimientos{
  private $conexion;
  private $id_proveedor;
  private $id_orden;
  private $id_almacen;
  private $id_item;

  public function __construct(){
    $this->conexion = new Conexion();
    date_default_timezone_set("America/Buenos_Aires");
  }

  public function traerMovimientos(){
    
    $arrayMovimientos = array();

    $queryGetMomiviento = "SELECT mst.id as id_movimiento, mst.id_stock, it.item, 	mst.cantidad, usr.email as usuario, fecha_hora as fecha, tipo_movimiento, stk.id_almacen, a.almacen
    FROM movimientos_stock mst 
      JOIN stock stk ON(mst.id_stock = stk.id)
      JOIN item as it ON(stk.id_item=it.id)
      JOIN almacenes a ON stk.id_almacen=a.id
      JOIN usuarios usr ON(mst.id_usuario = usr.id)";
    $getMomiviento = $this->conexion->consultaRetorno($queryGetMomiviento);

    while ($row = $getMomiviento->fetch_array()) {
      $arrayMovimientos[] = array(
        'id_movimiento'=>$row['id_movimiento'],
        'id_stock'=>$row['id_stock'],
        'item'=>$row['item'],
        'almacen'=>$row['almacen'],
        'cantidad'=>$row['cantidad'],
        'usuario'=>$row['usuario'],
        'tipo_movimiento'=>$row['tipo_movimiento'],
        'fecha'=>date("d/m/Y H:m:s", strtotime($row['fecha'])),
      );
    }

    echo json_encode($arrayMovimientos);
  }

  public function traerMovimientosFiltro($fDesde, $fHasta){
    $arrayMovimientos = array();

    $queryGetMomiviento = "SELECT mst.id as id_movimiento, mst.id_stock, it.item, 	mst.cantidad, usr.email as usuario, fecha_hora as fecha, tipo_movimiento 
              FROM movimientos_stock mst JOIN stock stk
              ON(mst.id_stock = stk.id)
              JOIN item as it
              ON(stk.id_item=it.id)
              JOIN usuarios usr
              ON(mst.id_usuario = usr.id)
              WHERE date_format(fecha_hora, '%Y-%m-%d') between '$fDesde' and '$fHasta'";
    $getMomiviento = $this->conexion->consultaRetorno($queryGetMomiviento);

    while ($row = $getMomiviento->fetch_array()) {
      $id_movimiento= $row['id_movimiento'];
      $id_stock= $row['id_stock'];
      $item= $row['item'];
      $cantidad = $row['cantidad'];
      $usuario = $row['usuario'];
      $tipo_movimiento = $row['tipo_movimiento'];
      $fecha= date("d/m/Y H:m:s", strtotime($row['fecha']));
      $arrayMovimientos[] = array('id_movimiento'=>$id_movimiento, 'id_stock'=>$id_stock, 'item'=>$item, 'cantidad'=>$cantidad, 'usuario'=>$usuario, 'tipo_movimiento'=>$tipo_movimiento, 'fecha'=>$fecha);
    }

    echo json_encode($arrayMovimientos);
  }

  public function insertarMovimientoStock($id_stock, $cantidad, $tipo_movimiento){
    $usuario = $_SESSION['rowUsers']['id_usuario'];
    /*INSERTO MOVIMIENTOS STOCK*/
    $queryInsertMS = "INSERT INTO movimientos_stock (id_stock, cantidad, id_usuario, fecha_hora, tipo_movimiento) VALUES ($id_stock, $cantidad, $usuario, NOW(), '$tipo_movimiento')";
    $insertMS = $this->conexion->consultaSimple($queryInsertMS);
  }

}

if (isset($_POST['accion'])) {
  $movimientos = new Movimientos();
  
}else{
  if (isset($_GET['accion'])) {
    $movimientos = new Movimientos();

    switch ($_GET['accion']) {
      case 'traerMovimientos':
        $movimientos->traerMovimientos();
        break;
      case 'traerMovimientosFiltro':
        $fdesde = $_GET['fdesde'];
        $fhasta = $_GET['fhasta'];
        $movimientos->traerMovimientosFiltro($fdesde, $fhasta);
        break;

    }
  }
}?>