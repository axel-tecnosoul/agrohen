<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
include_once('conexion.php');
include_once('administrar_producto.php');
include_once('administrar_deposito.php');
// Includes y configuraciones
require __DIR__.'/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class cargas{
  private $id_deposito;
  private $nombre;
  private $id_responsable;
  private $tipo_aumento_extra;
  private $valor_extra;
  
  public function __construct(){
      $this->conexion = new Conexion();
      date_default_timezone_set("America/Buenos_Aires");
  }

  public function traerDatosIniciales($id_carga){
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

    /*Productos*/
    /*$queryProductos = "SELECT id as id_producto, id_familia, familia FROM productos";
    $getProductos = $this->conexion->consultaRetorno($queryProductos);
    $arrayProductos[] = [
      'id_producto' => "",
      'producto' =>"Seleccione..."
    ];
    //CARGO ARRAY Productos
    while ($row = $getProductos->fetch_array()) {
      $arrayProductos[]=[
        'id_producto' => $row["id_producto"],
        'producto' =>$row["producto"]
      ];
    }*/

    $queryProductos = "SELECT p.id as id_producto, p.nombre, p.id_familia, p.ultimo_precio, p.ultimo_kg_x_bulto, pp.nombre AS presentacion,um.unidad_medida FROM productos p INNER JOIN presentaciones_productos pp ON p.id_presentacion=pp.id INNER JOIN unidades_medida um ON p.id_unidad_medida=um.id";
    $getProductos = $this->conexion->consultaRetorno($queryProductos);

    $arrayProductos[] = [
      'id_producto' => "",
      'producto' =>"Seleccione..."
    ];
    /*CARGO ARRAY productos*/
    while ($rowProductos = $getProductos->fetch_array()) {
      $arrayProductos[]= array(
        'id_producto' =>$rowProductos['id_producto'],
        'producto' =>$rowProductos['nombre'],
        'id_familia' =>$rowProductos['id_familia'],
        'presentacion' =>$rowProductos['presentacion'],
        'unidad_medida' =>$rowProductos['unidad_medida'],
        'ultimo_precio' =>$rowProductos['ultimo_precio'],
        'ultimo_kg_x_bulto' =>$rowProductos['ultimo_kg_x_bulto'],
      );
    }

    /*Destino*/
    $queryDestinos = "SELECT id as id_destino, nombre, tipo_aumento_extra, valor_extra FROM destinos";
    $getDestinos = $this->conexion->consultaRetorno($queryDestinos);
    /*CARGO ARRAY Destinos*/
    while ($row = $getDestinos->fetch_array()) {
      $arrayDestinos[]=[
        'id_destino' => $row["id_destino"],
        'destino' =>$row["nombre"],
        'tipo_aumento_extra' =>$row["tipo_aumento_extra"],
        'valor_extra' =>$row["valor_extra"],
      ];
    }

    $arrayDestinosPreseleccionados=[];
    if($id_carga>0){
      /*Destinos preseleccionados*/
      $queryDestinosCarga = "SELECT id_destino,total_bultos FROM cargas_destinos WHERE id_carga=$id_carga";
      $getDestinosCarga = $this->conexion->consultaRetorno($queryDestinosCarga);
      /*CARGO ARRAY Destinos*/
      while ($row = $getDestinosCarga->fetch_array()) {
        $arrayDestinosPreseleccionados[]=$row["id_destino"];
      }
    }

    $datosIniciales["choferes"] = $arrayChoferes;
    $datosIniciales["origenes"] = $arrayOrigenes;
    $datosIniciales["proveedores"] = $arrayProveedres;
    $datosIniciales["familias"] = $arrayFamilias;
    $datosIniciales["productos"] = $arrayProductos;
    $datosIniciales["destinos"] = $arrayDestinos;
    $datosIniciales["destinos_preseleccionados"] = $arrayDestinosPreseleccionados;
    echo json_encode($datosIniciales);
  }

  public function getProductosByFamilia($id_familia){
    $productosByFamilia = array();

    if($id_familia>0){
      /*Productos*/
      $queryProductos = "SELECT p.id as id_producto, p.nombre, p.ultimo_precio, p.ultimo_kg_x_bulto, pp.nombre AS presentacion,um.unidad_medida FROM productos p INNER JOIN presentaciones_productos pp ON p.id_presentacion=pp.id INNER JOIN unidades_medida um ON p.id_unidad_medida=um.id WHERE id_familia = $id_familia";
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
            'presentacion' =>$rowProductos['presentacion'],
            'unidad_medida' =>$rowProductos['unidad_medida'],
            'ultimo_precio' =>$rowProductos['ultimo_precio'],
            'ultimo_kg_x_bulto' =>$rowProductos['ultimo_kg_x_bulto'],
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

    $queryCarga = "SELECT c.id AS id_carga, id_proveedor_default as id_proveedor, pr.nombre as proveedor, fecha, id_origen, o.nombre AS origen, id_chofer, datos_adicionales_chofer, ch.nombre AS chofer,IF(c.fecha_hora_despacho IS NULL,'No','Si') AS despachado,c.fecha_hora_despacho,c.fecha_hora_confirmacion,if(fecha_hora_confirmacion IS NULL,0,1) AS confirmada,c.id_usuario,u.usuario FROM cargas c INNER JOIN origenes o ON c.id_origen=o.id LEFT JOIN proveedores pr ON c.id_proveedor_default = pr.id INNER JOIN choferes ch ON c.id_chofer=ch.id INNER JOIN usuarios u ON c.id_usuario=u.id WHERE c.id = $id_carga";
    $getCarga = $this->conexion->consultaRetorno($queryCarga);
    $rowCarga = $getCarga->fetch_array();
    $mensajeError=$this->conexion->conectar->error;
    if($mensajeError!=""){
      echo $mensajeError;
    }

    $despacho = 0;
    //$fecha_hora_despacho="(La carga aún no fue despachada)";
    $fecha_hora_despacho=$fecha_hora_confirmacion="";
    //$estado="<span class='badge badge-warning'>Pendiente</span>";
    if($rowCarga['fecha_hora_despacho']){
      //$fecha_hora_despacho=date("d-m-Y H:i",strtotime($rowCarga['fecha_hora_despacho']));
      $fecha_hora_despacho=formatFechaHora($rowCarga['fecha_hora_despacho']);
      $despacho = 1;
      //$estado="<span class='badge badge-primary'>Despachado</span>";
    }
    if($rowCarga['fecha_hora_confirmacion']){
      //$fecha_hora_confirmacion=date("d-m-Y H:i",strtotime($rowCarga['fecha_hora_confirmacion']));
      $fecha_hora_confirmacion=formatFechaHora($rowCarga['fecha_hora_confirmacion']);
      //$estado="<span class='badge badge-primary'>Despachado</span>";
    }

    $datosCarga=[
      'id_carga'=>$rowCarga['id_carga'],
      'id_proveedor' =>$rowCarga['id_proveedor'],
      'proveedor' =>$rowCarga['proveedor'],
      'fecha' =>$fecha=$rowCarga['fecha'],
      'fecha_formatted' =>formatFecha($fecha),
      'id_origen' =>$rowCarga['id_origen'],
      'origen' =>$rowCarga['origen'],
      'id_chofer' =>$rowCarga['id_chofer'],
      'datos_adicionales_chofer' =>$rowCarga['datos_adicionales_chofer'],
      'chofer' =>$rowCarga['chofer'],
      'despachado' =>$rowCarga['despachado'],
      'fecha_hora_despacho' =>$fecha_hora_despacho,
      'fecha_hora_confirmacion' =>$fecha_hora_confirmacion,
      //'estado' => $estado,
      'usuario' =>$rowCarga['usuario'],
      'despacho' => $despacho,
      'confirmada' => $rowCarga['confirmada'],
    ];

    $sqltraerDestinos = "SELECT id_destino,total_bultos FROM cargas_destinos WHERE id_carga=$id_carga";
    $traerDestinos = $this->conexion->consultaRetorno($sqltraerDestinos);
    $destinos_preseleccionados = []; //creamos un array
    while ($row = $traerDestinos->fetch_array()) {
      $destinos_preseleccionados[]=[
        "id_destino"=>$row["id_destino"],
        "total_bultos"=>$row["total_bultos"],
      ];
    }

    $datosCarga["destinos_preseleccionados"]=$destinos_preseleccionados;

    return json_encode($datosCarga);
  }

  public function getDatosVerDetalleCarga($id_carga) {
    $datosCargaJson = $this->getDatosCarga($id_carga); 
    
    // Decodificar el JSON devuelto por getDatosCarga
    $datosCargaArray = json_decode($datosCargaJson, true);
    
    // Filtrar los datos necesarios
    $datosNecesarios = [
      'id_carga'=> $datosCargaArray['id_carga'],
      'fecha_formatted' => $datosCargaArray['fecha_formatted'],
      'origen' => $datosCargaArray['origen'],
      'chofer' => $datosCargaArray['chofer'],
      'datos_adicionales_chofer' => $datosCargaArray['datos_adicionales_chofer'],
      'proveedor' => $datosCargaArray['proveedor'],
      'fecha_hora_despacho' => $datosCargaArray['fecha_hora_despacho'],
      'despacho' => $datosCargaArray['despacho'],
      'fecha_hora_confirmacion' => $datosCargaArray['fecha_hora_confirmacion'],
      'confirmada' => $datosCargaArray['confirmada'],
    ];
    
    $sqltraerProductosCarga = "SELECT id AS id_carga_producto FROM cargas_productos WHERE id_carga = " . $id_carga;
    $traerProductosCarga = $this->conexion->consultaRetorno($sqltraerProductosCarga);
    $aProductosDestinos = array();
        
    if ($traerProductosCarga) {
      while ($row = $traerProductosCarga->fetch_array()) {

        $id_carga_producto=$row['id_carga_producto'];

        $json_destinos=$this->getProductoDestinosCarga($id_carga_producto);
        $destinos=json_decode($json_destinos,true);

        $aProductosDestinos[]=$destinos;

      }
    }
    // Codificar todo el array en JSON y enviarlo como respuesta
    //return json_encode($datosNecesarios,$aProductosDestinos);
    return [$datosNecesarios,$aProductosDestinos];
  }

  public function getDestinoUnicosFromCargaProductosDestinos($aProductosDestinos){
    // Identificar todos los destinos únicos
    $destinos_unicos = [];
    foreach ($aProductosDestinos as $product) {
      foreach ($product['destinos'] as $key => $destino) {
        // Verificar si un id_destino específico existe
        if (!in_array($destino['id_destino'], array_column($destinos_unicos, 'id_destino'))) {
          $destinos_unicos[] =[
            "id_destino"=>$destino['id_destino'],
            "destino"=>$destino['destino'],
          ];
        }
      }
    }
    return $destinos_unicos;
  }

  public function mostrarInfoProductosDestinos($aProductosDestinos,$aDepositos,$tipo){
    //var_dump($aProductosDestinos);
    $mostrar_valor_extra=0;
    if($tipo!="responsable" or $_SESSION["rowUsers"]["id_perfil"]==2){
      $mostrar_valor_extra=1;
    }
    
    $destinos_unicos=$this->getDestinoUnicosFromCargaProductosDestinos($aProductosDestinos);

    //var_dump($aDepositos);
    // Generar la tabla?>
    <table class="table table-striped">
      <thead style="text-align: center;">
        <tr>
          <th rowspan="2" class="fixed-column fixed-column-header align-middle">Producto</th>
          <!-- <th rowspan="2" class="fixed-column-2 fixed-column-header align-middle">Proveedor</th> -->
          <th rowspan="2" class="fixed-column-2 fixed-column-header align-middle">Precio</th>
          <th rowspan="2" class="fixed-column-3 fixed-column-header align-middle">Kg x bulto</th><?php
          foreach ($destinos_unicos as $destino) {
            if($aDepositos=="" or in_array($destino["id_destino"],$aDepositos)){?>
              <th colspan="3" class="destino-group"><?php
                if($aDepositos==""){?>
                  <input type='checkbox' class='checkbox_animated check_destino' id="verDestino-<?=$destino["id_destino"]?>" value="<?=$destino["id_destino"]?>"><?php
                }?>
                <label for="verDestino-<?=$destino["id_destino"]?>" class="mb-0"><?=$destino["destino"]?></label>
              </th><?php
            }
          }
          if($aDepositos=="" or count($aDepositos)>1){ ?>
            <th rowspan="2" class="fixed-column-header align-middle">Total Bultos</th>
            <th rowspan="2" class="fixed-column-header align-middle">Total Kilos</th>
            <th rowspan="2" class="fixed-column-header align-middle">Total Monto</th><?php
          }?>
        </tr>
        <tr><?php
          foreach ($destinos_unicos as $destino) {
            if($aDepositos=="" or in_array($destino["id_destino"],$aDepositos)){?>
              <th style="top:46px" class="destino-group">Bultos</th>
              <th style="top:46px" class="destino-group">Kilos</th>
              <th style="top:46px" class="destino-group">Monto</th><?php
            }
          }?>
        </tr>
      </thead>
      <tbody><?php
        $totals = [];
        //var_dump($aDepositos);
        foreach ($aProductosDestinos as $product) {
          //var_dump($product);
          $precio=$product['precio_general'];
          //if($mostrar_valor_extra==1 and count($product["destinos"])==1){
          if($mostrar_valor_extra==1){
            $id_deposito_mostrar=$aDepositos[0];
            $precio=$product['destinos'][$id_deposito_mostrar]["precio_destino_valor_extra"];
          }
          ?>
          <tr>
            <td class="fixed-column"><?=$product['familia']." ".$product['producto']." (".$product['presentacion']." - ".$product['unidad_medida'].")"?></td>
            <!-- <td class="fixed-column-2"><?=$product['proveedor']?></td> -->
            <td class="fixed-column-2 text-right">$ <?=number_format($precio,2,",",".")?></td>
            <td class="fixed-column-3 text-right"><?=$product['kg_x_bulto']?></td><?php

            $destinos_actuales = [];
            foreach ($product['destinos'] as $destino) {
              $destinos_actuales[$destino['id_destino']] = $destino;
            }

            foreach ($destinos_unicos as $destino) {
              if($aDepositos=="" or in_array($destino["id_destino"],$aDepositos)){
                $id_destino=$destino['id_destino'];
                $cantidad_bultos=0;
                $subtotal_kilos=0;
                $monto=0;
                if (isset($destinos_actuales[$id_destino])) {
                  $cantidad_bultos=$destinos_actuales[$id_destino]['cantidad_bultos'];
                  $subtotal_kilos=$destinos_actuales[$id_destino]['subtotal_kilos'];
                  
                  //if($tipo!="responsable" or $_SESSION["rowUsers"]["id_perfil"]==2){
                  if($mostrar_valor_extra==1){
                    $monto=$destinos_actuales[$id_destino]['monto_valor_extra'];
                  }else{
                    $monto=$destinos_actuales[$id_destino]['monto'];
                  }
                }
                
                if (!isset($totals[$id_destino])) {
                  $totals[$id_destino] = ['bultos' => 0, 'subtotal_kilos' => 0, 'monto' => 0];
                }
                $totals[$id_destino]['bultos'] += $cantidad_bultos;
                $totals[$id_destino]['subtotal_kilos'] += $subtotal_kilos;
                $totals[$id_destino]['monto'] += $monto;?>

                <td class="text-right destino-group destino-start"><?=number_format($cantidad_bultos,0,",",".")?></td>
                <td class="text-right destino-group"><?=number_format($subtotal_kilos,2,",",".")?></td>
                <td class="text-right destino-group destino-end">$ <?=number_format($monto,2,",",".")?></td><?php
              }
            }
            if($aDepositos=="" or count($aDepositos)>1){ ?>
              <td class="text-right fixed-column font-weight-bold"><?=number_format($product['total_bultos'],0,",",".")?></td>
              <td class="text-right fixed-column font-weight-bold"><?=number_format($product['total_kilos'],2,",",".")?></td>
              <td class="text-right fixed-column font-weight-bold">$ <?=number_format($product['total_monto'],2,",",".")?></td><?php
            }?>
          </tr><?php
        }?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3" class="text-right fixed-column" style="background: burlywood;">Totales</td><?php
          foreach ($destinos_unicos as $destino) {
            if($aDepositos=="" or in_array($destino["id_destino"],$aDepositos)){
              $id_destino=$destino["id_destino"]?>
              <td class="text-right destino-group"><?=number_format($totals[$id_destino]['bultos'], 0, ",", ".")?></td>
              <td class="text-right destino-group"><?=number_format($totals[$id_destino]['subtotal_kilos'], 2, ",", ".")?></td>
              <td class="text-right destino-group">$ <?=number_format($totals[$id_destino]['monto'], 2, ",", ".")?></td><?php
            }
          }
          if($aDepositos=="" or count($aDepositos)>1){ ?>
            <td class="text-right fixed-column font-weight-bold"><?=number_format(array_sum(array_column($aProductosDestinos,'total_bultos')),0,",",".")?></td>
            <td class="text-right fixed-column font-weight-bold"><?=number_format(array_sum(array_column($aProductosDestinos,'total_kilos')),2,",",".")?></td>
            <td class="text-right fixed-column font-weight-bold">$ <?=number_format(array_sum(array_column($aProductosDestinos,'total_monto')),2,",",".")?></td>
          <?php }?>
        </tr>
      </tfoot>
    </table><?php
  }

  public function traerCargas($desde,$hasta,$id_origen,$id_chofer,$estado){

    switch ($estado) {
      case 'p':
        $filtroEstado=" AND fecha_hora_despacho IS NULL AND fecha_hora_confirmacion IS NULL";
        $campoFechaBuscar="fecha";
        break;
      case 'd':
        $filtroEstado=" AND fecha_hora_despacho IS NOT NULL AND fecha_hora_confirmacion IS NULL";
        $campoFechaBuscar="fecha_hora_despacho";
        break;
      case 'c':
        $filtroEstado=" AND fecha_hora_despacho IS NOT NULL AND fecha_hora_confirmacion IS NOT NULL";
        $campoFechaBuscar="fecha_hora_confirmacion";
        break;
      default:
        $filtroEstado="";
        $campoFechaBuscar="";
        break;
    }
    $filtroDesde="";
    if(!empty($desde)){
      $filtroDesde=" AND DATE(c.$campoFechaBuscar)>='$desde'";
    }
    $filtroHasta="";
    if(!empty($hasta)){
      $filtroHasta=" AND DATE(c.$campoFechaBuscar)<='$hasta'";
    }
    $filtroOrigen="";
    if($id_origen!="null" and !empty($id_origen)){
      $filtroOrigen=" AND id_origen=$id_origen";
    }
    $filtroChofer="";
    if($id_chofer!="null" and !empty($id_chofer)){
      $filtroChofer=" AND id_chofer=$id_chofer";
    }

    if(!empty($_SESSION["rowUsers"]["id_deposito"])){
      $id_deposito=$_SESSION["rowUsers"]["id_deposito"];

      //SUMAMOS LA INFORMACION DE LAS CARGAS DEL DEPOSITO LOGUEADO
      $sqltraerCargas = "SELECT c.id AS id_carga,c.fecha,c.id_origen,o.nombre AS origen,c.id_chofer,ch.nombre AS chofer,c.datos_adicionales_chofer,SUM(cpd.cantidad_bultos) AS total_bultos, SUM(cpd.subtotal_kilos) AS total_kilos,SUM(cpd.monto_valor_extra) AS total_monto,IF(c.fecha_hora_despacho IS NULL,'No','Si') AS despachado,c.fecha_hora_despacho,c.fecha_hora_confirmacion,if(fecha_hora_confirmacion IS NULL,'No','Si') AS confirmada,c.id_usuario,u.usuario,c.anulado FROM cargas c INNER JOIN choferes ch ON c.id_chofer=ch.id INNER JOIN origenes o ON c.id_origen=o.id INNER JOIN usuarios u ON c.id_usuario=u.id INNER JOIN cargas_productos cp ON cp.id_carga=c.id INNER JOIN cargas_productos_destinos cpd ON cpd.id_carga_producto=cp.id WHERE c.anulado = 0 AND cpd.id_destino=$id_deposito $filtroEstado $filtroDesde $filtroHasta $filtroOrigen $filtroChofer GROUP BY c.id";//,c.total_bultos,c.total_kilos,c.total_monto
    }else{
      $sqltraerCargas = "SELECT c.id AS id_carga,c.fecha,c.id_origen,o.nombre AS origen,c.id_chofer,ch.nombre AS chofer,c.datos_adicionales_chofer,total_bultos,total_kilos,total_monto,IF(c.fecha_hora_despacho IS NULL,'No','Si') AS despachado,c.fecha_hora_despacho,c.fecha_hora_confirmacion,if(fecha_hora_confirmacion IS NULL,'No','Si') AS confirmada,c.id_usuario,u.usuario,c.anulado FROM cargas c INNER JOIN choferes ch ON c.id_chofer=ch.id INNER JOIN origenes o ON c.id_origen=o.id INNER JOIN usuarios u ON c.id_usuario=u.id WHERE c.anulado = 0 $filtroEstado $filtroDesde $filtroHasta $filtroOrigen $filtroChofer";
    }
    //die($sqltraerCargas);
    $traerCargas = $this->conexion->consultaRetorno($sqltraerCargas);
    $cargas = array(); //creamos un array

    while ($row = $traerCargas->fetch_array()) {

      $estado="<span class='badge badge-warning'>Pendiente</span>";
      if($row['fecha_hora_despacho']){
        $estado="<span class='badge badge-primary'>Despachado</span>";
      }
      if($row['fecha_hora_confirmacion']){
        $estado="<span class='badge badge-success'>Confirmada</span>";
      }
      

      $cargas[] = array(
        'id_carga'=>$row['id_carga'],
        'fecha'=>$fecha=$row['fecha'],
        'fecha_formatted'=>formatFecha($fecha),
        'id_origen'=>$row['id_origen'],
        'origen'=>$row['origen'],
        'id_chofer'=>$row['id_chofer'],
        'chofer'=>$row['chofer'],
        'datos_adicionales_chofer'=>$row['datos_adicionales_chofer'],
        'total_bultos'=>$row['total_bultos'],
        'total_kilos'=>$row['total_kilos'],
        'total_monto'=>$row['total_monto'],
        'fecha_hora_despacho'=>formatFechaHora($row['fecha_hora_despacho']),
        'fecha_hora_confirmacion'=>formatFechaHora($row['fecha_hora_confirmacion']),
        'despachado'=>$row['despachado'],
        'confirmada'=>$row['confirmada'],
        'estado' => $estado,
        'id_usuario'=>$row['id_usuario'],
        'usuario'=>$row['usuario'],
        'anulado'=>$row['anulado'],
      );
    }
            
    return json_encode($cargas);
  }

  public function traerProductosCarga($id_carga){
    $sqltraerProductosCarga = "SELECT cp.id AS id_carga_producto,cp.id_producto,p.id_familia,fp.familia,pp.nombre AS presentacion,um.unidad_medida,p.nombre AS producto,cp.id_proveedor,pr.nombre AS proveedor,cp.kg_x_bulto,cp.precio_general,cp.total_bultos,cp.total_kilos,cp.total_monto,u.usuario,cp.fecha_hora_alta FROM cargas_productos cp INNER JOIN productos p ON cp.id_producto=p.id INNER JOIN familias_productos fp ON p.id_familia=fp.id INNER JOIN presentaciones_productos pp ON p.id_presentacion=pp.id INNER JOIN unidades_medida um ON p.id_unidad_medida=um.id LEFT JOIN proveedores pr ON cp.id_proveedor=pr.id INNER JOIN usuarios u ON cp.id_usuario=u.id WHERE cp.id_carga = ".$id_carga." ";//GROUP BY cp.id_producto, cp.id_proveedor, cp.kg_x_bulto
    //echo $sqltraerProductosCarga;
    $traerProductosCarga = $this->conexion->consultaRetorno($sqltraerProductosCarga);
    
    $cargas = array(); //creamos un array
    if($traerProductosCarga){
      while ($row = $traerProductosCarga->fetch_array()) {
        $cargas[] = array(
          'id_carga_producto'=>$row['id_carga_producto'],
          'id_producto'=>$row['id_producto'],
          'producto'=>$row['producto'],
          'id_familia'=>$row['id_familia'],
          'familia'=>$row['familia'],
          'presentacion'=>$row['presentacion'],
          'unidad_medida'=>$row['unidad_medida'],
          'id_proveedor'=>$row['id_proveedor'],
          'proveedor'=>$row['proveedor'],
          'kg_x_bulto'=>$row['kg_x_bulto'],
          'precio_general'=>$row['precio_general'],
          'total_bultos'=>$row['total_bultos'],
          'total_kilos'=>$row['total_kilos'],
          'total_monto'=>$row['total_monto'],
          'usuario'=>$row['usuario'],
          'fecha_hora_alta'=>$row['fecha_hora_alta'],
        );
      }
    }

    return json_encode($cargas);
  }

  public function getProductoDestinosCarga($id_carga_producto,$buscar="actual"){

    $auditoria="";
    $campo_fecha_hora_ultima_modificacion=",cp.fecha_hora_ultima_modificacion";
    if($buscar=="auditoria"){
      $auditoria=$buscar."_";
      $campo_fecha_hora_ultima_modificacion="";
    }

    $sqlTraerProductoDestinosCarga = "SELECT cp.id AS id_carga_producto,cp.id_producto, p.nombre AS producto, p.id_familia, fp.familia, pp.nombre AS presentacion, um.unidad_medida, cp.id_proveedor, pr.nombre AS proveedor,cp.kg_x_bulto,cp.precio_general,cp.motivo_cambio_producto,cp.total_bultos,cp.total_kilos,cp.total_monto,cp.id_usuario,u.usuario,cp.fecha_hora_alta $campo_fecha_hora_ultima_modificacion FROM ".$auditoria."cargas_productos cp INNER JOIN productos p ON cp.id_producto=p.id INNER JOIN familias_productos fp ON fp.id = p.id_familia INNER JOIN presentaciones_productos pp ON p.id_presentacion=pp.id INNER JOIN unidades_medida um ON p.id_unidad_medida=um.id LEFT JOIN proveedores pr ON pr.id = cp.id_proveedor INNER JOIN usuarios u ON cp.id_usuario=u.id WHERE cp.id = $id_carga_producto";
    //var_dump($sqlTraerProductoDestinosCarga);
    //die($sqlTraerProductoDestinosCarga);
    $traerProductoCarga = $this->conexion->consultaRetorno($sqlTraerProductoDestinosCarga);
    $row = $traerProductoCarga->fetch_array();
    
    $motivo_cambio_producto=$row["motivo_cambio_producto"];
    if(empty($motivo_cambio_producto)){
      $motivo_cambio_producto="";
    }

    $fecha_hora_ultima_modificacion="";
    if(isset($row["fecha_hora_ultima_modificacion"])){
      $fecha_hora_ultima_modificacion=formatFechaHora($row["fecha_hora_ultima_modificacion"]);
    }

    $productoCarga = [
      'id_carga_producto'=> $row['id_carga_producto'],
      'id_producto'=> $row['id_producto'],
      'producto'=> $row['producto'],
      'id_familia'=> $row['id_familia'],
      'familia'=> $row['familia'],
      'presentacion'=>$row['presentacion'],
      'unidad_medida'=>$row['unidad_medida'],
      'id_proveedor'=> $row['id_proveedor'],
      'proveedor'=> $row['proveedor'],
      'kg_x_bulto'=> $row['kg_x_bulto'],
      'precio_general'=> $row['precio_general'],
      'motivo_cambio_producto'=> $motivo_cambio_producto,
      'total_bultos'=> $row['total_bultos'],
      'total_kilos'=> $row['total_kilos'],
      'total_monto'=> $row['total_monto'],
      'id_usuario'=> $row["id_usuario"],
      'usuario'=> $row["usuario"],
      'fecha_hora_alta'=> formatFechaHora($row["fecha_hora_alta"]),
      'fecha_hora_ultima_modificacion'=> $fecha_hora_ultima_modificacion,
    ];

    $sqlTraerProductoDestinosCarga = "SELECT cpd.id AS id_producto_destino,cpd.id_destino,d.nombre AS destino,d.tipo_aumento_extra,d.valor_extra,cpd.cantidad_bultos,cpd.motivo_cambio_deposito,cpd.precio_destino,cpd.monto,cpd.precio_destino_valor_extra,cpd.monto_valor_extra,cpd.subtotal_kilos FROM ".$auditoria."cargas_productos_destinos cpd INNER JOIN destinos d ON cpd.id_destino=d.id WHERE cpd.id_".$auditoria."carga_producto = $id_carga_producto";
    //var_dump($sqlTraerProductoDestinosCarga);
    $traerProductoDestinosCarga = $this->conexion->consultaRetorno($sqlTraerProductoDestinosCarga);
    $productoDestinosCarga=[];
    while ($row = $traerProductoDestinosCarga->fetch_array()) {
      $motivo_cambio_deposito=$row["motivo_cambio_deposito"];
      if(empty($motivo_cambio_deposito)){
        $motivo_cambio_deposito="";
      }
      $productoDestinosCarga[$row['id_destino']] = [
        'id_producto_destino'=> $row['id_producto_destino'],
        'id_destino'=> $row['id_destino'],
        'destino'=> $row['destino'],
        'tipo_aumento_extra'=> $row['tipo_aumento_extra'],
        'valor_extra'=> $row['valor_extra'],
        'cantidad_bultos'=> $row['cantidad_bultos'],
        'motivo_cambio_deposito'=> $motivo_cambio_deposito,
        'monto'=> $row['monto'],
        'precio_destino'=> $row['precio_destino'],
        'monto_valor_extra'=> $row['monto_valor_extra'],
        'precio_destino_valor_extra'=> $row['precio_destino_valor_extra'],
        'subtotal_kilos'=> $row['subtotal_kilos'],
      ];
    }
    $productoCarga["destinos"]=$productoDestinosCarga;

    $sqlTraerAuditoriaProductoDestinosCarga = "SELECT acpd.id AS id_auditoria_producto_destino,acpd.fecha_hora_alta,u.usuario FROM auditoria_cargas_productos acpd INNER JOIN usuarios u ON acpd.id_usuario=u.id WHERE acpd.id_carga_producto = $id_carga_producto ORDER BY acpd.id DESC";
    //var_dump($sqlTraerAuditoriaProductoDestinosCarga);
    $traerAuditoriaProductoDestinosCarga = $this->conexion->consultaRetorno($sqlTraerAuditoriaProductoDestinosCarga);
    $productoDestinosCarga=[];
    while ($row = $traerAuditoriaProductoDestinosCarga->fetch_array()) {
      $productoDestinosCarga[] = [
        'id_auditoria_producto_destino'=> $row['id_auditoria_producto_destino'],
        'fecha_hora'=> formatFechaHora($row['fecha_hora_alta']),
        'usuario'=> $row['usuario'],
      ];
    }
    $productoCarga["historial"]=$productoDestinosCarga;
    return json_encode($productoCarga);
  }

  public function updateProductoCarga($id_carga,$id_carga_producto,$id_producto,$id_proveedor,$kg_x_bulto,$precio_general,$motivo_cambio_producto,$datosDepositos){

    $id_usuario = $_SESSION['rowUsers']['id_usuario'];

    if($id_proveedor==""){
      $id_proveedor="NULL";
    }

    $sqltraerCargas = "SELECT IF(fecha_hora_despacho IS NULL,0,1) AS despachado FROM cargas WHERE id = ".$id_carga;
    $traerCargas = $this->conexion->consultaRetorno($sqltraerCargas);
    $row = $traerCargas->fetch_array();

    $insertarMotivoCambioCantidad=0;
    $updateUsuarioFechaHoraAlta="";
    $ok=1;
    if($row["despachado"]==1){
      //die("auditar");
      // Auditar datos antes de actualizar
      $ok=$this->auditarCargaProducto($id_carga_producto);
      $insertarMotivoCambioCantidad=1;
      $updateUsuarioFechaHoraAlta=", id_usuario = ".$id_usuario.", fecha_hora_alta = NOW()";
    }

    if($ok==0){
      return "Ha ocurrido un error al auditar la carga del producto";
    }

    if(empty($motivo_cambio_producto)){
      $motivo_cambio_producto="NULL";
    }else{
      $motivo_cambio_producto="'$motivo_cambio_producto'";
    }

    $queryUpdateProductoCarga = "UPDATE cargas_productos SET id_producto=$id_producto, id_proveedor=$id_proveedor, kg_x_bulto=$kg_x_bulto, precio_general=$precio_general, motivo_cambio_producto=$motivo_cambio_producto $updateUsuarioFechaHoraAlta WHERE id = $id_carga_producto";

    //die($queryUpdateProductoCarga);
    $insertCarga = $this->conexion->consultaSimple($queryUpdateProductoCarga);
    $mensajeError=$this->conexion->conectar->error;
    if($mensajeError!=""){
      die($queryUpdateProductoCarga."<br>".$mensajeError);
    }

    $producto = new producto();
    $producto->actualizarDatosProducto($id_producto, $precio_general, $kg_x_bulto);

    $cantDatos=$cantDatosOk=0;
    $errores="";
    foreach ($datosDepositos as $row) {
      $cantDatos++;
      ////var_dump($row);
      $id_producto_destino=$row["id_producto_destino"];
      $id_deposito=$row["id_deposito"];
      $tipo_aumento_extra=$row["tipo_aumento_extra"];
      $valor_extra=$row["valor_extra"];
      $cantidad_bultos=$row["cantidad_bultos"];
      $subtotal_kilos=$row["subtotal_kilos"];
      $precio_destino=$row["precio_destino"];

      $motivo_cambio_deposito="NULL";
      if($insertarMotivoCambioCantidad==1 and !empty($row["motivo_cambio_deposito"])){
        $motivo_cambio_deposito="'".$row["motivo_cambio_deposito"]."'";
      }

      $mensajeError="";
      if($cantidad_bultos>0){
        $monto=$cantidad_bultos*$precio_destino;

        list($tipo_aumento_extra_bbdd,$monto_valor_extra)=$this->calcularMontoConValorExtra($tipo_aumento_extra,$valor_extra,$monto,$cantidad_bultos);
        list($tipo_aumento_extra_bbdd,$precio_destino_valor_extra)=$this->calcularMontoConValorExtra($tipo_aumento_extra,$valor_extra,$precio_destino,$cantidad_bultos);
      
        ////var_dump($tipo_aumento_extra);
        if($id_producto_destino>0){
          $query=$queryUpdateCarga = "UPDATE cargas_productos_destinos SET id_destino = $id_deposito, tipo_aumento_extra = $tipo_aumento_extra_bbdd, valor_extra = $valor_extra, cantidad_bultos = $cantidad_bultos, subtotal_kilos = $subtotal_kilos, precio_destino = $precio_destino, monto = $monto, precio_destino_valor_extra = $precio_destino_valor_extra, monto_valor_extra = $monto_valor_extra, motivo_cambio_deposito = $motivo_cambio_deposito, id_usuario = $id_usuario WHERE id = $id_producto_destino";
          $updateCarga = $this->conexion->consultaSimple($queryUpdateCarga);
          $mensajeError=$this->conexion->conectar->error;
        }else{
          $query=$queryInsertCarga = "INSERT INTO cargas_productos_destinos (id_carga_producto, id_destino, tipo_aumento_extra, valor_extra, cantidad_bultos, subtotal_kilos, precio_destino, monto, precio_destino_valor_extra, monto_valor_extra, motivo_cambio_deposito, id_usuario) VALUES($id_carga_producto, $id_deposito, $tipo_aumento_extra_bbdd, $valor_extra, $cantidad_bultos, $subtotal_kilos, $precio_destino, $monto, $precio_destino_valor_extra, $monto_valor_extra, $motivo_cambio_deposito, $id_usuario)";
          $insertCarga = $this->conexion->consultaSimple($queryInsertCarga);
          $mensajeError=$this->conexion->conectar->error;
        }
      }elseif($id_producto_destino>0){
        $query=$sqleliminarProductoCarga = "DELETE FROM cargas_productos_destinos WHERE id = $id_producto_destino";
        $eliminarProductoCarga = $this->conexion->consultaSimple($sqleliminarProductoCarga);
        $mensajeError=$this->conexion->conectar->error;
      }
      
      if($mensajeError==""){
        $cantDatosOk++;
      }else{
        $errores.=$mensajeError."<br><br>".$query."<hr>";
      }
    }

    //actualizamos los totales de los productos y destinos
    if($errores==""){
      $okProductos=$this->updateTotalesCargasProductos($id_carga);
      $okDestinos=$this->updateTotalesCargasDestinos($id_carga);
      if($okProductos==0){
        $errores="Algo ha fallado al actualizar los kilos y el monto total de los productos";
      }
      if($okDestinos==0){
        $errores="Algo ha fallado al actualizar los kilos y el monto total de los destinos";
      }
    }

    $respuesta=$errores;

    if($respuesta==""){
      $respuesta=[
        "ok"=>1,
      ];
    }
    $respuesta=json_encode($respuesta);
    
    return $respuesta;
  }

  private function updateTotalesCargasProductos($id_carga){
    $queryGetSumas = "SELECT cpd.id_carga_producto,SUM(cantidad_bultos) AS suma_bultos, SUM(monto) AS suma_monto, SUM(subtotal_kilos) AS suma_kilos FROM cargas_productos_destinos cpd INNER JOIN cargas_productos cp ON cpd.id_carga_producto=cp.id WHERE cp.id_carga=$id_carga GROUP BY cpd.id_carga_producto";
    $getSumas = $this->conexion->consultaRetorno($queryGetSumas);

    $ok=0;
    $c=$c2=0;
    while($row = $getSumas->fetch_array()){
      $c++;
    
      $id_carga_producto=$row["id_carga_producto"];
      $sumaBultos = $row["suma_bultos"] > 0 ? $row["suma_bultos"] : 0;
      $sumaKilos = $row["suma_kilos"] > 0 ? $row["suma_kilos"] : 0;
      $sumaMonto = $row["suma_monto"] > 0 ? $row["suma_monto"] : 0;

      $queryInsertCarga = "UPDATE cargas_productos SET total_bultos = $sumaBultos, total_kilos = $sumaKilos, total_monto = $sumaMonto WHERE id_carga = $id_carga AND id = $id_carga_producto";
      //echo $queryInsertCarga;
      $insertCarga = $this->conexion->consultaSimple($queryInsertCarga);
      $mensajeError=$this->conexion->conectar->error;
      if($mensajeError==""){
        $c2++;
      }else{
        echo $queryInsertCarga."<br>".$mensajeError."<br><br>";
      }
    }

    //echo "$c==$c2";
    if($c==$c2){
      $ok=0;
      $respuesta=$this->updateTotalesCarga($id_carga);
      if($respuesta==1){
        $ok=1;
      }
    }
    return $ok;
  }

  public function updateTotalesCargasDestinos($id_carga){
    $queryGetDestinos = "SELECT id,id_destino FROM cargas_destinos WHERE id_carga=$id_carga";
    $getDestinos = $this->conexion->consultaRetorno($queryGetDestinos);
    $ok=0;
    $c=$c2=0;

    $depositos = new depositos();

    while($row = $getDestinos->fetch_array()){
      $c++;
      $id_cargas_destinos=$row["id"];
      $id_destino=$row["id_destino"];

      $queryGetSumas = "SELECT SUM(cantidad_bultos) AS suma_bultos,SUM(subtotal_kilos) AS suma_kilos,SUM(monto) AS suma_monto,SUM(monto_valor_extra) AS suma_monto_valor_extra FROM cargas_productos_destinos cpd INNER JOIN cargas_productos cp ON cpd.id_carga_producto=cp.id WHERE cp.id_carga=$id_carga AND id_destino=$id_destino";
      $getSumas = $this->conexion->consultaRetorno($queryGetSumas);
      $row2 = $getSumas->fetch_array();

      $total_bultos = $row2["suma_bultos"] > 0 ? $row2["suma_bultos"] : 0;
      $total_kilos = $row2["suma_kilos"] > 0 ? $row2["suma_kilos"] : 0;
      $total_monto = $row2["suma_monto"] > 0 ? $row2["suma_monto"] : 0;
      $total_monto_valor_extra = $row2["suma_monto_valor_extra"] > 0 ? $row2["suma_monto_valor_extra"] : 0;

      $queryInsertCarga = "UPDATE cargas_destinos SET total_bultos = $total_bultos, total_kilos = $total_kilos, total_monto = $total_monto, total_monto_valor_extra = $total_monto_valor_extra WHERE id = $id_cargas_destinos";
      $insertCarga = $this->conexion->consultaSimple($queryInsertCarga);
      $mensajeError=$this->conexion->conectar->error;
      if($mensajeError==""){

        $ok=1;
        if($id_destino>0){
          $depositos = new depositos();

          $ok=$depositos->actualizarSaldoCtaCte($id_destino);
        }

        if($ok==1){
          $c2++;
        }
      }else{
        echo $queryInsertCarga."<br>".$mensajeError."<br><br>";
      }
    }

    if($c==$c2){
      $ok=0;
      
      $respuesta=$this->updateTotalesCarga($id_carga);
      if($respuesta==1){
        $ok=1;
      }
    }
    return $ok;
  }

  public function auditarCargaProducto($id_carga_producto) {
    $ok=0;
    // Obtener los datos del producto desde cargas_productos
    $queryProducto = "SELECT * FROM cargas_productos WHERE id = $id_carga_producto";
    $traerProducto = $this->conexion->consultaRetorno($queryProducto);
    $producto = $traerProducto->fetch_assoc();

    // Verificar si id_proveedor es NULL y manejarlo adecuadamente
    $id_proveedor = is_null($producto['id_proveedor']) ? 'NULL' : $producto['id_proveedor'];

    // Insertar los datos en auditoria_cargas_productos
    $queryInsertAuditoriaProducto = "INSERT INTO auditoria_cargas_productos (id_carga_producto, id_producto, id_proveedor, kg_x_bulto, precio_general, motivo_cambio_producto, total_bultos, total_kilos, total_monto, id_usuario, fecha_hora_alta) VALUES (".$producto['id'].",".$producto['id_producto'].",".$id_proveedor.",".$producto['kg_x_bulto'].",".$producto['precio_general'].",'".$producto['motivo_cambio_producto']."',".$producto['total_bultos'].",".$producto['total_kilos'].",".$producto['total_monto'].",".$producto['id_usuario'].",'".$producto['fecha_hora_alta']."')";
    $this->conexion->consultaSimple($queryInsertAuditoriaProducto);
    $mensajeError=$this->conexion->conectar->error;
    //echo $queryInsertAuditoriaProducto."<br>";
    //echo $mensajeError;

    if($mensajeError==""){
      $id_auditoria_producto = $this->conexion->conectar->insert_id;

      // Obtener los datos de los destinos desde cargas_productos_destinos
      $queryDestinos = "SELECT * FROM cargas_productos_destinos WHERE id_carga_producto = $id_carga_producto";
      $traerDestinos = $this->conexion->consultaRetorno($queryDestinos);
      // Insertar los datos en auditoria_cargas_productos_destinos
      $c=$c2=0;
      while ($row = $traerDestinos->fetch_assoc()) {
        $c++;
        
        $valor_extra=$row['valor_extra'];
        if(is_null($valor_extra)) $valor_extra="'$valor_extra'";

        $cantidad_bultos=$row['cantidad_bultos'];
        if(is_null($cantidad_bultos)) $cantidad_bultos="'$cantidad_bultos'";
        
        $subtotal_kilos=$row['subtotal_kilos'];
        if(is_null($subtotal_kilos)) $subtotal_kilos="'$subtotal_kilos'";

        $monto=$row['monto'];
        if(is_null($monto)) $monto="'$monto'";

        $monto_valor_extra=$row['monto_valor_extra'];
        if(is_null($monto_valor_extra)) $monto_valor_extra="'$monto_valor_extra'";

        $queryInsertAuditoriaDestino = "INSERT INTO auditoria_cargas_productos_destinos (id_auditoria_carga_producto, id_destino, tipo_aumento_extra, valor_extra, cantidad_bultos, subtotal_kilos, precio_destino, monto, precio_destino_valor_extra, monto_valor_extra, motivo_cambio_deposito, id_usuario) VALUES (".$id_auditoria_producto.",".$row['id_destino'].",'".$row['tipo_aumento_extra']."',$valor_extra,$cantidad_bultos,$subtotal_kilos,".$row['precio_destino'].",$monto,".$row['precio_destino_valor_extra'].",$monto_valor_extra,'".$row['motivo_cambio_deposito']."',".$_SESSION['rowUsers']['id_usuario'].")";
        $this->conexion->consultaSimple($queryInsertAuditoriaDestino);
        //die($queryInsertAuditoriaDestino);
        $mensajeError=$this->conexion->conectar->error;
        //echo $queryInsertAuditoriaDestino."<br>";
        //echo $mensajeError;
  
        if($mensajeError==""){
          $c2++;
        }else{
          echo $queryInsertAuditoriaDestino."<br>".$mensajeError."<br><br>";
        }
      }
      if($c==$c2 and $c!=0){
        $ok=1;
      }
    }else{
      echo $queryInsertAuditoriaProducto."<br>".$mensajeError;
    }
    return $ok;
  }

  private function calcularMontoConValorExtra($tipo_aumento_extra,$valor_extra,$monto,$cantidad_bultos){
    ////var_dump("tipo_aumento_extra",$tipo_aumento_extra,"valor_extra",$valor_extra,"monto",$monto,"cantidad_bultos",$cantidad_bultos);
    
    // Inicializar la variable para el monto valor extra
    $monto_valor_extra = 0;

    if(empty($tipo_aumento_extra)){
      $tipo_aumento_extra="NULL";
      //$monto_valor_extra="NULL";
    }else{
      if ($tipo_aumento_extra == "Porcentaje Extra") {
        // Calcula el monto extra basado en un porcentaje
        $monto_valor_extra = $monto * ($valor_extra / 100);
      } elseif ($tipo_aumento_extra == "Precio Fijo") {
        // Calcula el monto extra basado en un precio fijo por bulto
        $monto_valor_extra = $valor_extra * $cantidad_bultos;
      }

      $tipo_aumento_extra="'$tipo_aumento_extra'";
    }

    // Calcula el monto total con el valor extra si es que posee
    $monto_valor_extra+=$monto;
    ////var_dump("tipo_aumento_extra",$tipo_aumento_extra,"monto_valor_extra",$monto_valor_extra);

    return [$tipo_aumento_extra,$monto_valor_extra];
  }

  public function eliminarCarga($id_carga){
    /*$sqltraerCargas = "SELECT id FROM cargas_productos WHERE id_carga = ".$id_carga;
    $traerCargas = $this->conexion->consultaRetorno($sqltraerCargas);
    $cant=$cantOk=0;
    while ($row = $traerCargas->fetch_array()) {
      $cant++;
      $id_carga_producto=$row["id"];
      $respuesta=$this->eliminarProductoCarga($id_carga_producto,$id_carga);
      if($respuesta=="ok"){
        $cantOk++;
      }
    }*/

    //if($cant==$cantOk){
      /*ELIMINO la carga*/
      //$sqleliminarCarga = "DELETE FROM cargas WHERE id = $id_carga";
      $sqleliminarCarga = "UPDATE cargas SET anulado = 1 WHERE id = $id_carga";
      $eliminarCarga = $this->conexion->consultaSimple($sqleliminarCarga);
    //}
  }

  public function despacharCarga($id_carga){
    $queryUpdateEstado = "UPDATE cargas SET fecha_hora_despacho = NOW() WHERE id = $id_carga";
    // Verificar si la actualización fue exitosa
    $updateEstado = $this->conexion->consultaSimpleM($queryUpdateEstado);
    if ($updateEstado) {

      $depositos = new depositos();

      $queryGetDestinos = "SELECT id_destino FROM cargas_destinos WHERE id_carga=$id_carga";
      $getDestinos = $this->conexion->consultaRetorno($queryGetDestinos);
      //var_dump($queryGetDestinos);
      
      $c=$c2=0;
      while($row = $getDestinos->fetch_array()){
        $c++;
        $ok=$depositos->actualizarSaldoCtaCte($row["id_destino"]);
        if($ok==1){
          $c2++;
        }
      }
      if($c==$c2){
        $response = array('success' => true, 'message' => 'Carga despachada correctamente');
      }else{
        $response = array('success' => true, 'message' => 'Ha ocurrido un error al actualizar los saldos de cta cte');
      }
    } else {
      $response = array('success' => false, 'message' => 'Error al despachar la carga');
    }
    
    echo json_encode($response);
  }

  public function confirmarCarga($id_carga){
    $queryUpdateEstado = "UPDATE cargas SET fecha_hora_confirmacion = NOW() WHERE id = $id_carga";
    // Verificar si la actualización fue exitosa
    $updateEstado = $this->conexion->consultaSimpleM($queryUpdateEstado);
    if ($updateEstado) {
        $response = array('success' => true, 'message' => 'Carga confirmada correctamente');
    } else {
        $response = array('success' => false, 'message' => 'Error al confirmar la carga');
    }
    
    echo json_encode($response);
  }
  
  public function clonarCarga($id_carga) {
    $ok = 0;
    $id_usuario = $_SESSION['rowUsers']['id_usuario'];

    // Obtener los datos de la carga original
    $queryCarga = "SELECT * FROM cargas WHERE id = $id_carga";
    $traerCarga = $this->conexion->consultaRetorno($queryCarga);
    $carga = $traerCarga->fetch_assoc();

    // Verificar si id_proveedor es NULL y manejarlo adecuadamente
    $id_proveedor = is_null($carga['id_proveedor_default']) ? 'NULL' : $carga['id_proveedor_default'];

    // Insertar la nueva carga, sin precios ni cantidades
    $queryInsertCarga = "INSERT INTO cargas (fecha, id_origen, id_chofer, datos_adicionales_chofer, id_proveedor_default, id_usuario, clonado) VALUES (NOW(),".$carga['id_origen'].",".$carga['id_chofer'].",'".$carga['datos_adicionales_chofer']."',".$id_proveedor.",".$id_usuario.",1)";
    $this->conexion->consultaSimple($queryInsertCarga);
    $id_nueva_carga = $this->conexion->conectar->insert_id;

    $mensajeError = $this->conexion->conectar->error;

    if ($mensajeError == "") {
      // Obtener los destinos de la carga original
      $queryDestinos = "SELECT * FROM cargas_destinos WHERE id_carga = $id_carga";
      $traerDestinos = $this->conexion->consultaRetorno($queryDestinos);

      // Insertar los destinos en la nueva carga
      while ($destino = $traerDestinos->fetch_assoc()) {
        $queryInsertDestino = "INSERT INTO cargas_destinos (id_carga, id_destino) VALUES ($id_nueva_carga,".$destino['id_destino'].")";
        $this->conexion->consultaSimple($queryInsertDestino);
        $mensajeError = $this->conexion->conectar->error;
        if ($mensajeError != "") {
          echo $mensajeError."<br>".$queryInsertDestino;
        }
      }
  
      // Obtener los productos de la carga original
      $queryProductos = "SELECT * FROM cargas_productos WHERE id_carga = $id_carga";
      $traerProductos = $this->conexion->consultaRetorno($queryProductos);

      // Insertar los productos en la nueva carga
      while ($producto = $traerProductos->fetch_assoc()) {
        $id_proveedor = is_null($producto['id_proveedor']) ? 'NULL' : $producto['id_proveedor'];

        $queryInsertProducto = "INSERT INTO cargas_productos (id_carga, id_producto, id_proveedor, id_usuario) VALUES ($id_nueva_carga, ".$producto['id_producto'].", ".$id_proveedor.", $id_usuario)";
        $this->conexion->consultaSimple($queryInsertProducto);
        
        $mensajeError = $this->conexion->conectar->error;
        if ($mensajeError != "") {
          echo $mensajeError."<br>".$queryInsertProducto;
        }

        $id_nuevo_producto = $this->conexion->conectar->insert_id;

        // Obtener los destinos de los productos de la carga original
        $queryProductosDestinos = "SELECT * FROM cargas_productos_destinos WHERE id_carga_producto = ".$producto['id'];
        $traerProductosDestinos = $this->conexion->consultaRetorno($queryProductosDestinos);

        // Insertar los destinos de los productos en la nueva carga
        while ($productoDestino = $traerProductosDestinos->fetch_assoc()) {
          $queryInsertProductoDestino = "INSERT INTO cargas_productos_destinos (id_carga_producto, id_destino, tipo_aumento_extra, id_usuario) VALUES (".$id_nuevo_producto.",".$productoDestino['id_destino'].",'".$productoDestino['tipo_aumento_extra']."',".$productoDestino['id_usuario'].")";
          $this->conexion->consultaSimple($queryInsertProductoDestino);

          $mensajeError = $this->conexion->conectar->error;
          if ($mensajeError != "") {
            echo $mensajeError."<br>".$queryInsertProductoDestino;
          }
        }
      }
  
      $ok = 1;
    }else{
      echo $mensajeError."<br>".$queryInsertCarga;
    }
  
    return json_encode(["ok"=>$ok,"id_carga"=>$id_nueva_carga]);
  }

  public function registrarCarga($fecha_carga,$id_origen,$id_chofer,$datos_adicionales_chofer,$id_proveedor_default,$datosDepositos){
    $id_usuario = $_SESSION['rowUsers']['id_usuario'];

    $queryInsertCarga = "INSERT INTO cargas (id_chofer, id_origen, fecha, datos_adicionales_chofer, id_proveedor_default, id_usuario) VALUES($id_chofer, $id_origen, '$fecha_carga', '$datos_adicionales_chofer', $id_proveedor_default, $id_usuario)";
    $insertCarga = $this->conexion->consultaSimple($queryInsertCarga);
    $mensajeError=$this->conexion->conectar->error;
    $id_carga=$this->conexion->conectar->insert_id;

    if(!empty($datosDepositos)){
      foreach($datosDepositos as $id_destino){
        $queryInsertCarga = "INSERT INTO cargas_destinos (id_carga, id_destino) VALUES($id_carga, $id_destino)";
        $insertCarga = $this->conexion->consultaSimple($queryInsertCarga);
        $mensajeError=$this->conexion->conectar->error;
      }
    }
    
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

  public function addProductoCarga($id_carga,$id_producto,$id_proveedor,$kg_x_bulto,$precio_general,$datosDepositos){
    $id_usuario = $_SESSION['rowUsers']['id_usuario'];
    if($id_proveedor==""){
      $id_proveedor="NULL";
    }

    $queryInsertCarga = "INSERT INTO cargas_productos (id_carga, id_producto, id_proveedor, kg_x_bulto, precio_general, id_usuario) VALUES($id_carga, $id_producto, $id_proveedor, '$kg_x_bulto', $precio_general, $id_usuario)";
    $insertCarga = $this->conexion->consultaSimple($queryInsertCarga);
    $mensajeError=$this->conexion->conectar->error;
    $id_carga_producto=$this->conexion->conectar->insert_id;
    
    if($mensajeError=="" and $id_carga_producto>0){

      $producto = new producto();
      $producto->actualizarDatosProducto($id_producto, $precio_general, $kg_x_bulto);

      $cantDatos=$cantDatosOk=0;
      $errores="";

      $sumaMonto=$sumaKilos=$sumaBultos=0;
      foreach ($datosDepositos as $row) {
        $cantDatos++;
        ////var_dump($row);
        $id_deposito=$row["id_deposito"];
        $tipo_aumento_extra=$row["tipo_aumento_extra"];
        $valor_extra=$row["valor_extra"];
        $cantidad_bultos=$row["cantidad_bultos"];
        $subtotal_kilos=$row["subtotal_kilos"];
        $precio_destino=$row["precio_destino"];

        if($cantidad_bultos>0){
          $monto=$cantidad_bultos*$precio_destino;

          list($tipo_aumento_extra_bbdd,$monto_valor_extra)=$this->calcularMontoConValorExtra($tipo_aumento_extra,$valor_extra,$monto,$cantidad_bultos);
          list($tipo_aumento_extra_bbdd,$precio_destino_valor_extra)=$this->calcularMontoConValorExtra($tipo_aumento_extra,$valor_extra,$precio_destino,$cantidad_bultos);

          $sumaMonto+=$monto;
          $sumaKilos+=$subtotal_kilos;
          $sumaBultos+=$cantidad_bultos;

          $queryInsertCarga = "INSERT INTO cargas_productos_destinos (id_carga_producto, id_destino, tipo_aumento_extra, valor_extra, cantidad_bultos, subtotal_kilos, precio_destino, monto, precio_destino_valor_extra, monto_valor_extra, id_usuario) VALUES($id_carga_producto, $id_deposito, $tipo_aumento_extra_bbdd, $valor_extra, $cantidad_bultos, $subtotal_kilos, $precio_destino, $monto, $precio_destino_valor_extra, $monto_valor_extra, $id_usuario)";
          $insertCarga = $this->conexion->consultaSimple($queryInsertCarga);
          $mensajeError=$this->conexion->conectar->error;
          
          if($mensajeError==""){
            $cantDatosOk++;
          }else{
            $errores.=$mensajeError."<br><br>".$queryInsertCarga."<hr>";
          }
        }
      }

      //actualizamos los totales de los productos y destinos
      if($errores==""){
        $okProductos=$this->updateTotalesCargasProductos($id_carga);
        $okDestinos=$this->updateTotalesCargasDestinos($id_carga);
        if($okProductos==0){
          $errores="Algo ha fallado al actualizar los kilos y el monto total de los productos";
        }
        if($okDestinos==0){
          $errores="Algo ha fallado al actualizar los kilos y el monto total de los destinos";
        }
      }
    }else{
      $errores=$queryInsertCarga."<br>Algo ha fallado al registrar el producto en la carga: ".$mensajeError;
    }

    $respuesta=$errores;

    if($respuesta==""){
      $respuesta=[
        "ok"=>1,
      ];
    }
    
    return json_encode($respuesta);
  }

  public function eliminarProductoCarga($id_carga_producto,$id_carga){

    /*ELIMINO los destinos*/
    $sqleliminarProductoCarga = "DELETE FROM cargas_productos_destinos WHERE id_carga_producto = $id_carga_producto";
    $eliminarProductoCarga = $this->conexion->consultaSimple($sqleliminarProductoCarga);
    $mensajeError=$this->conexion->conectar->error;
    $errores="";
    if($mensajeError==""){
      /*ELIMINO el producto*/
      $sqleliminarProductoCarga = "DELETE FROM cargas_productos WHERE id = $id_carga_producto";
      $eliminarProductoCarga = $this->conexion->consultaSimple($sqleliminarProductoCarga);
      $mensajeError=$this->conexion->conectar->error;
      if($mensajeError==""){
        //$this->updateTotalesCarga($id_carga);
        $okDestinos=$this->updateTotalesCargasDestinos($id_carga);
        if($okDestinos==0){
          $errores="Algo ha fallado al actualizar los kilos y el monto total de los destinos";
        }
      }else{
        $errores=$mensajeError;
      }
    }else{
      $errores=$mensajeError;
    }

    $respuesta=$errores;

    if($respuesta==""){
      $respuesta=[
        "ok"=>1,
      ];
    }
    
    return json_encode($respuesta);
  }

  private function updateTotalesCarga($id_carga){
    $queryGetSumas = "SELECT SUM(total_bultos) AS suma_bultos, SUM(total_kilos) AS suma_kilos, SUM(total_monto) AS suma_monto FROM cargas_productos WHERE id_carga=".$id_carga;
    $getSumas = $this->conexion->consultaRetorno($queryGetSumas);
    $row = $getSumas->fetch_array();

    $sumaBultos = $row["suma_bultos"] > 0 ? $row["suma_bultos"] : 0;
    $sumaKilos = $row["suma_kilos"] > 0 ? $row["suma_kilos"] : 0;
    $sumaMonto = $row["suma_monto"] > 0 ? $row["suma_monto"] : 0;

    $queryInsertCarga = "UPDATE cargas SET total_bultos = $sumaBultos, total_kilos = $sumaKilos, total_monto = $sumaMonto WHERE id = ".$id_carga;
    $insertCarga = $this->conexion->consultaSimple($queryInsertCarga);
    $mensajeError=$this->conexion->conectar->error;

    if($mensajeError==""){
      $respuesta=1;
    }else{
      $respuesta="Algo ha fallado al actualizar los totales de la carga";
    }
    return $respuesta;
  }

  public function updateCarga($id_carga,$fecha_carga,$id_origen,$id_chofer,$datos_adicionales_chofer,$id_proveedor_default,$datosDepositos){

    $id_usuario = $_SESSION['rowUsers']['id_usuario'];

    $queryUpdateCarga = "UPDATE cargas SET fecha='$fecha_carga', id_origen=$id_origen, id_chofer=$id_chofer, datos_adicionales_chofer='$datos_adicionales_chofer', id_proveedor_default=$id_proveedor_default WHERE id = $id_carga";
    $insertCarga = $this->conexion->consultaSimple($queryUpdateCarga);
    $mensajeError=$this->conexion->conectar->error;

    if(!empty($datosDepositos)){
      $queryDeleteDestinos = "DELETE FROM cargas_destinos WHERE id_carga=$id_carga";
      $deleteDestinos = $this->conexion->consultaSimple($queryDeleteDestinos);
      foreach($datosDepositos as $id_deposito){
        $queryInsertDestinos = "INSERT INTO cargas_destinos (id_carga, id_destino) VALUES($id_carga, $id_deposito)";
        $insertCarga = $this->conexion->consultaSimple($queryInsertDestinos);
        $mensajeError=$this->conexion->conectar->error;
      }

      $okDestinos=$this->updateTotalesCargasDestinos($id_carga);
      if($okDestinos==0){
        $mensajeError="Algo ha fallado al actualizar los kilos y el monto total de los destinos";
      }
    }

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

  public function exportar_excel($id_carga) {
    // Obtener datos de la carga
    $cargas = new Cargas($id_carga);
    list($datosNecesarios, $aProductosDestinos) = $cargas->getDatosVerDetalleCarga($id_carga);
    $destinos_unicos = $cargas->getDestinoUnicosFromCargaProductosDestinos($aProductosDestinos);

    // Crear nuevo objeto Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Detalle Carga ID '.$id_carga);

    // Configuración del archivo
    $sheet->setCellValue('A1', "ID Carga:")->setCellValue('A2', $datosNecesarios['id_carga']);
    $sheet->setCellValue('B1', "Fecha Carga:")->setCellValue('B2', \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(strtotime($datosNecesarios['fecha_formatted'])));
    $sheet->setCellValue('C1', "Origen:")->setCellValue('C2', $datosNecesarios['origen']);
    $sheet->setCellValue('E1', "Chofer:")->setCellValue('E2', $datosNecesarios['chofer']);
    $sheet->setCellValue('G1', "Datos adicionales:")->setCellValue('G2', $datosNecesarios['datos_adicionales_chofer']);

    $sheet->mergeCells('C1:D1');
    $sheet->mergeCells('C2:D2');
    $sheet->mergeCells('E1:F1');
    $sheet->mergeCells('E2:F2');
    $sheet->mergeCells('G1:J1');
    $sheet->mergeCells('G2:J2');

    // Encabezado de la tabla de productos
    $sheet->setCellValue('A4', "Familia");
    $sheet->setCellValue('B4', "Producto");
    $sheet->setCellValue('C4', "Presentación");
    $sheet->setCellValue('D4', "Unidad Medida");
    //$sheet->setCellValue('E4', "Proveedor");
    $sheet->setCellValue('E4', "Precio");
    $sheet->setCellValue('F4', "Kg x bulto");

    // Combinar celdas de los encabezados
    $sheet->mergeCells('A4:A5');
    $sheet->mergeCells('B4:B5');
    $sheet->mergeCells('C4:C5');
    $sheet->mergeCells('D4:D5');
    $sheet->mergeCells('E4:E5');
    $sheet->mergeCells('F4:F5');
    //$sheet->mergeCells('G4:G5');

    // Añadir destinos únicos al encabezado (Fila 4)
    $aStyleColorGris = [
      'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
          'argb' => 'd0cece', // Color para las celdas de totales
        ],
      ],
    ];

    /*$column = 'H';
    foreach ($destinos_unicos as $destino) {
      $sheet->setCellValue($column . '4', $destino['destino']);
      $sheet->mergeCells($column . '4:' . chr(ord($column) + 2) . '4');
      $column = chr(ord($column) + 3);
    }*/

    $column = 'G';
    foreach ($destinos_unicos as $destino) {
      $startColumnIndex = Coordinate::columnIndexFromString($column); // Convierte la columna a un índice numérico
      $endColumnIndex = $startColumnIndex + 2; // Calcula la columna final

      $startColumn = Coordinate::stringFromColumnIndex($startColumnIndex); // Convierte el índice de vuelta a letra de columna
      $endColumn = Coordinate::stringFromColumnIndex($endColumnIndex); // Convierte el índice de vuelta a letra de columna

      $sheet->setCellValue($startColumn . '4', $destino['destino']);
      $sheet->mergeCells($startColumn . '4:' . $endColumn . '4');
      $column = Coordinate::stringFromColumnIndex($endColumnIndex + 1); // Avanza a la siguiente columna de inicio
    }

    // Subtítulos de los destinos (Fila 5)
    $column = 'G';
    foreach ($destinos_unicos as $destino) {
      $sheet->setCellValue($column . '5', "Bultos");
      $column++;
      
      $sheet->setCellValue($column . '5', "Kilos");
      $column++;
      
      $sheet->setCellValue($column . '5', "Monto");
      $column++;
    }

    // Subtítulos para totales (Fila 4)
    $column_aux = $column;
    $sheet->setCellValue($column . '4', "Total Bultos");
    $sheet->mergeCells($column . '4:' . $column . '5');
    
    $column++;
    $sheet->setCellValue($column . '4', "Total Kilos");
    $sheet->mergeCells($column . '4:' . $column . '5');
    
    $column++;
    $sheet->setCellValue($column . '4', "Total Monto");
    $sheet->mergeCells($column . '4:' . $column . '5');

    // Datos de productos y destinos
    $row = 6; // Ajustar fila inicial para los datos
    foreach ($aProductosDestinos as $producto) {
      $sheet->setCellValue('A' . $row, $producto['familia']);
      $sheet->setCellValue('B' . $row, $producto['producto']);
      $sheet->setCellValue('C' . $row, $producto['presentacion']);
      $sheet->setCellValue('D' . $row, $producto['unidad_medida']);
      //$sheet->setCellValue('E' . $row, $producto['proveedor']);
      $sheet->setCellValue('E' . $row, $producto['precio_general']);
      $sheet->setCellValue('F' . $row, $producto['kg_x_bulto']);

      $column = 'G';
      foreach ($destinos_unicos as $destino) {
        $found = false;
        foreach ($producto['destinos'] as $dest) {
          if ($dest['id_destino'] == $destino['id_destino']) {
            $sheet->setCellValue($column . $row, $dest['cantidad_bultos']);
            $sheet->getStyle($column . $row)->applyFromArray($aStyleColorGris);
            $sheet->getStyle($column . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);

            $column++;
            $sheet->setCellValue($column . $row, $dest['subtotal_kilos']);
            $sheet->getStyle($column . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            
            $column++;
            $sheet->setCellValue($column . $row, $dest['monto']);
            $sheet->getStyle($column . $row)->applyFromArray($aStyleColorGris);
            $sheet->getStyle($column . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD);
            $found = true;
            break;
          }
        }
        if (!$found) {
          $sheet->setCellValue($column . $row, '0');
          $sheet->getStyle($column . $row)->applyFromArray($aStyleColorGris);
          $sheet->getStyle($column . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
          
          $column++;
          $sheet->setCellValue($column . $row, '0');
          $sheet->getStyle($column . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
          
          $column++;
          $sheet->setCellValue($column . $row, '0');
          $sheet->getStyle($column . $row)->applyFromArray($aStyleColorGris);
          $sheet->getStyle($column . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD);
        }
        $column++;
      }

      // Añadir totales
      $sheet->setCellValue($column . $row, $producto['total_bultos']);
      $sheet->getStyle($column . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);

      $column++;
      $sheet->setCellValue($column . $row, $producto['total_kilos']);
      $sheet->getStyle($column . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);

      $column++;
      $sheet->setCellValue($column . $row, $producto['total_monto']);
      $sheet->getStyle($column . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD);

      $row++;
    }

    // Fila de totales
    $sheet->setCellValue('F' . $row, "Totales");
    $column = 'G';
    foreach ($destinos_unicos as $destino) {
      $sheet->setCellValue($column . $row, "=SUM($column" . "6:$column" . ($row - 1) . ")");
      $sheet->getStyle($column . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);

      $column++;
      $sheet->setCellValue($column . $row, "=SUM($column" . "6:$column" . ($row - 1) . ")");
      $sheet->getStyle($column . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);

      $column++;
      $sheet->setCellValue($column . $row, "=SUM($column" . "6:$column" . ($row - 1) . ")");
      $sheet->getStyle($column . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD);
      $column++;
    }

    $sheet->setCellValue($column . $row, "=SUM($column" . "6:$column" . ($row - 1) . ")");
    $sheet->getStyle($column . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);

    $column++;
    $sheet->setCellValue($column . $row, "=SUM($column" . "6:$column" . ($row - 1) . ")");
    $sheet->getStyle($column . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);

    $column++;
    $sheet->setCellValue($column . $row, "=SUM($column" . "6:$column" . ($row - 1) . ")");
    $sheet->getStyle($column . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD);

    // Formatear celdas para precios y montos
    $sheet->getStyle('E6:E' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD);

    // Aplicar bordes a todas las celdas
    $aStyleBordes = [
      'borders' => [
        'allBorders' => [
          'borderStyle' => Border::BORDER_THIN,
        ],
      ],
    ];
    $sheet->getStyle('A4:' . $column . $row)->applyFromArray($aStyleBordes);

    // Formatear la columna de fecha
    $dateFormat = 'dd/mm/yyyy';
    $sheet->getStyle('B2')->getNumberFormat()->setFormatCode($dateFormat);

    // Estilos para los encabezados
    $aStyleCenter = [
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
      ],
    ];
    $aStyleBold = [
      'font' => [
        'bold' => true,
      ],
    ];

    $sheet->getStyle('A4:' . $column . '5')->applyFromArray($aStyleCenter)->applyFromArray($aStyleBold);
    $sheet->getStyle('A1:J1')->applyFromArray($aStyleCenter)->applyFromArray($aStyleBold);
    $sheet->getStyle('A1:J2')->applyFromArray($aStyleBordes);
    $sheet->getStyle('F' . $row . ':' . $column . $row)->applyFromArray($aStyleBold);

    $aStyleColorEncabezado = [
      'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
          'argb' => 'deb887', // Color para las celdas de encabezado
        ],
      ],
    ];
    $sheet->getStyle('A1:J1')->applyFromArray($aStyleColorEncabezado);
    $sheet->getStyle('A4:F' . $row)->applyFromArray($aStyleColorEncabezado);
    $sheet->getStyle('G4:' . $column . '5')->applyFromArray($aStyleColorEncabezado);
    $sheet->getStyle('F' . $row . ':' . $column . $row)->applyFromArray($aStyleColorEncabezado);
    $sheet->getStyle($column_aux . '4:' . $column . $row)->applyFromArray($aStyleColorEncabezado);

    // Auto-ajustar columnas
    /*foreach (range('A', $column) as $col) {
      $sheet->getColumnDimension($col)->setAutoSize(true);
    }*/
    // Calcula el índice de la última columna usada
    $lastColumnIndex = Coordinate::columnIndexFromString($column);

    // Auto-ajustar columnas
    for ($i = 1; $i <= $lastColumnIndex; $i++) {
      $colString = Coordinate::stringFromColumnIndex($i);
      $sheet->getColumnDimension($colString)->setAutoSize(true);
    }

    // Establecer la celda seleccionada
    $sheet->setSelectedCell('A1');

    // Redirigir la salida al navegador
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Carga ID ' . $id_carga . '.xlsx"');
    header('Cache-Control: max-age=0');

    ob_end_clean();
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
  }

  // Función auxiliar para obtener la siguiente columna
  private function getNextColumn($col, $offset = 1) {
      $ascii = ord($col);
      return chr($ascii + $offset);
  }
}	

if (isset($_POST['accion'])) {
  $cargas = new cargas();
  switch ($_POST['accion']) {
    case 'traerProductoDestinosCarga':
      $id_carga_producto = $_POST['id_carga_producto'];
      if(isset($_POST['buscar'])){
        $buscar = $_POST['buscar'];
      }else{
        $buscar="actual";
      }
      echo $cargas->getProductoDestinosCarga($id_carga_producto,$buscar);
    break;
    case 'updateCarga':
      $id_carga = $_POST['id_carga'];
      $fecha_carga=$_POST["fecha_carga"];
      $id_origen=$_POST["id_origen"];
      $id_chofer=$_POST["id_chofer"];
      $datos_adicionales_chofer=$_POST["datos_adicionales_chofer"];
      $id_proveedor_default=$_POST["id_proveedor_default"];
      if(empty($id_proveedor_default)){
        $id_proveedor_default="NULL";
      }
      $datosDepositos=$_POST["datosDepositos"];
      echo $cargas->updateCarga($id_carga,$fecha_carga,$id_origen,$id_chofer,$datos_adicionales_chofer,$id_proveedor_default,$datosDepositos);
    break;
    case 'updateProductoCarga':
      $id_carga_producto=$_POST["id_carga_producto"];
      $id_carga=$_POST["id_carga"];
      $id_producto=$_POST["id_producto"];
      $id_proveedor=$_POST["id_proveedor"];
      $kg_x_bulto=$_POST["kg_x_bulto"];
      $precio_general=$_POST["precio_general"];
      $motivo_cambio_producto=$_POST["motivo_cambio_producto"];
      $datosDepositos=$_POST["datosDepositos"];
      echo $cargas->updateProductoCarga($id_carga,$id_carga_producto,$id_producto,$id_proveedor,$kg_x_bulto,$precio_general,$motivo_cambio_producto,$datosDepositos);
    break;
    case 'despacharCarga':
      // //var_dump($_POST);
      // die;
      $id_carga = $_POST['id_carga'];
      $cargas->despacharCarga($id_carga);
    break;
    case 'confirmarCarga':
      $id_carga = $_POST['id_carga'];
      $cargas->confirmarCarga($id_carga);
    break;
    case 'clonarCarga':
      $id_carga = $_POST['id_carga'];
      echo $cargas->clonarCarga($id_carga);
    break;
    case 'eliminarCarga':
      $id_carga = $_POST['id_carga'];
      $cargas->eliminarCarga($id_carga);
    break;
    case 'traerDatosInicialesCargas':
      $id_carga=0;
      if(isset($_POST["id_carga"])){
        $id_carga=$_POST["id_carga"];
      }
      $cargas->traerDatosIniciales($id_carga);
    break;
    case 'addCarga':
      $fecha_carga=$_POST["fecha_carga"];
      $id_origen=$_POST["id_origen"];
      $id_chofer=$_POST["id_chofer"];
      $datos_adicionales_chofer=$_POST["datos_adicionales_chofer"];
      $id_proveedor_default=$_POST["id_proveedor_default"];
      if(empty($id_proveedor_default)){
        $id_proveedor_default="NULL";
      }
      $datosDepositos=$_POST["datosDepositos"];
      echo $cargas->registrarCarga($fecha_carga,$id_origen,$id_chofer,$datos_adicionales_chofer,$id_proveedor_default,$datosDepositos);
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
      $precio_general=$_POST["precio_general"];
      $datosDepositos=$_POST["datosDepositos"];
      echo $cargas->addProductoCarga($id_carga,$id_producto,$id_proveedor,$kg_x_bulto,$precio_general,$datosDepositos);
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
    case 'traerDatosVerDetalleCarga':
      $id_carga=$_POST["id_carga"];
      list($datosNecesarios,$aProductosDestinos)=$cargas->getDatosVerDetalleCarga($id_carga);

      $aDepositos=$_POST["aDepositos"];
      if($aDepositos!=""){
        $aDepositos=explode(",",$aDepositos);
      }
      $tipo="responsable";
      if(isset($_POST["tipo"])){
        $tipo=$_POST["tipo"];
      }

      $cargas->mostrarInfoProductosDestinos($aProductosDestinos,$aDepositos,$tipo);
      echo "%%";
      echo json_encode($datosNecesarios);
    break;
    case 'exportar_excel':
      $id_carga = $_POST['id_carga'];
      //var_dump($id_carga);
      echo $cargas->exportar_excel($id_carga);
    break;
  }
}elseif(isset($_GET['accion'])){
  $cargas = new cargas();
  switch ($_GET['accion']) {
    case 'traerCargas':
      $desde=$_GET["desde"];
      $hasta=$_GET["hasta"];
      $id_origen=$_GET["id_origen"];
      $id_chofer=$_GET["id_chofer"];
      $estado=$_GET["estado"];
      echo $cargas->traerCargas($desde,$hasta,$id_origen,$id_chofer,$estado);
    break;
    case 'traerProductosCarga':
      $id_carga=$_GET["id_carga"];
      echo $cargas->traerProductosCarga($id_carga);
    break;
    case 'exportar_excel':
      $id_carga = $_GET['id_carga'];
      //var_dump($id_carga);
      echo $cargas->exportar_excel($id_carga);
    break;
  }
}
?>