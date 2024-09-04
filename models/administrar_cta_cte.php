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

class ctacte{
  private $id_deposito;
  private $nombre;
  private $id_responsable;
  private $tipo_aumento_extra;
  private $valor_extra;

  public function __construct(){
      $this->conexion = new Conexion();
      date_default_timezone_set("America/Buenos_Aires");
  }

  public function traerDatosIniciales(){
    $datosIniciales = array();

    $arrayCuenta[] = [
      'id' => "",
      'cuenta' => "Seleccione...",
      'tipo' => "",
      'tipo_aumento_extra'=> "",
      'valor_extra' => "",
    ];

    /*Responsables*/
    $queryResponsable = "SELECT id as id_responsable, nombre FROM responsables_deposito WHERE activo = 1";
    $getResponsable = $this->conexion->consultaRetorno($queryResponsable);
    $arrayResponsable[] = [
      'id_responsable' => "",
      'responsable' =>"Seleccione..."
    ];
    /*CARGO ARRAY responsables*/
    while ($row = $getResponsable->fetch_array()) {
      $arrayResponsable[]= array(
        'id_responsable' =>$row['id_responsable'],
        'responsable' =>$row['nombre'],
      );

      $arrayCuenta[] = [
        'id' => $row['id_responsable'],
        'cuenta' => $row['nombre'],
        'tipo' => "responsable",
        'tipo_aumento_extra'=> "",
        'valor_extra' => "",
      ];
    }

    /*Destino*/
    $queryDestinos = "SELECT id as id_destino, nombre, tipo_aumento_extra, valor_extra, id_responsable FROM destinos WHERE activo = 1";
    $getDestinos = $this->conexion->consultaRetorno($queryDestinos);
    $arrayDestinos[] = [
      'id_destino' => "",
      'destino' =>"Seleccione..."
    ];
    /*CARGO ARRAY Destinos*/
    while ($row = $getDestinos->fetch_array()) {
      $arrayDestinos[]=[
        'id_destino' => $row["id_destino"],
        'id_responsable' => $row['id_responsable'],
        'destino' =>$row["nombre"],
        'tipo_aumento_extra' =>$row["tipo_aumento_extra"],
        'valor_extra' =>$row["valor_extra"],
      ];

      $arrayCuenta[] = [
        'id' => $row['id_destino'],
        'cuenta' =>$row['nombre'],
        'tipo' =>"destino",
        'tipo_aumento_extra' =>$row["tipo_aumento_extra"],
        'valor_extra' =>$row["valor_extra"],
      ];
    }

    $datosIniciales["responsables"] = $arrayResponsable;
    $datosIniciales["destinos"] = $arrayDestinos;
    $datosIniciales["cuentas"] = $arrayCuenta;

    echo json_encode($datosIniciales);
  }

  public function getCtacte($desde,$hasta,$id_cuenta,$id_deposito,$tipo,$tipo_aumento_extra,$valor_extra){
    //var_dump($desde,$hasta,$id_cuenta,$id_deposito,$tipo,$tipo_aumento_extra,$valor_extra);

    $ctacte=[];
    //if(!in_array($id,["","null","undefined"])){
    if($id_cuenta>0 or $id_deposito>0){

      $filtroDesde="";
      if(!empty($desde)){
        $filtroDesde=" AND DATE(c.fecha_hora_despacho)>='$desde'";
      }
      $filtroHasta="";
      if(!empty($hasta)){
        $filtroHasta=" AND DATE(c.fecha_hora_despacho)<='$hasta'";
      }

      if($id_cuenta=="undefined"){
        $id_cuenta=$id_deposito;//cuando el perfil del usuario es de tipo deposito, mostramos el monto con valor extra
      }

      $depositos=$id_cuenta;
      $columna_utilizar="cpd.monto_valor_extra";
      if($tipo=="responsable"){
        $columna_utilizar="cpd.monto";

        $filtroCuentaParaMovimientos=" AND mcc.id_responsable=".$id_cuenta;

        if($id_deposito==""){
          $query = "SELECT GROUP_CONCAT(id SEPARATOR ',') AS depositos FROM destinos WHERE id_responsable = ".$id_cuenta;
          $get = $this->conexion->consultaRetorno($query);
          $row = $get->fetch_array();
          $depositos=$row["depositos"];
        }else{
          $depositos=$id_deposito;
        }
      }

      if(!isset($filtroCuentaParaMovimientos)){
        $filtroCuentaParaMovimientos="AND mcc.id_destino IN ($depositos)";
      }

      $query = "SELECT SUM($columna_utilizar) AS debe_saldo_anterior FROM cargas c INNER JOIN cargas_productos cp ON cp.id_carga=c.id INNER JOIN cargas_productos_destinos cpd ON cpd.id_carga_producto=cp.id WHERE c.fecha_hora_despacho IS NOT NULL AND c.anulado=0 AND cpd.id_destino IN ($depositos) AND DATE(c.fecha_hora_despacho)<'$desde'";
      $get = $this->conexion->consultaRetorno($query);
      //echo $query;
      //$get->num_rows;
      $row = $get->fetch_array();
      $debe_saldo_anterior=$row["debe_saldo_anterior"];
      if(!is_numeric($debe_saldo_anterior)){
        $debe_saldo_anterior=0;
      }

      /*CARGO MOVIMIENTOS REGISTRADOS A MANO */
      //$query = "SELECT SUM(IF(tipo_movimiento='haber',monto,(monto*-1))) AS haber_saldo_anterior FROM movimientos_cta_cte WHERE anulado=0 AND id_destino IN ($depositos) AND fecha_hora<'$desde'";
      $query = "SELECT SUM(IF(mcc.tipo_movimiento='debe',mcc.monto,(mcc.monto*-1))) AS haber_saldo_anterior FROM movimientos_cta_cte mcc WHERE mcc.anulado=0 $filtroCuentaParaMovimientos AND DATE(mcc.fecha_hora)<'$desde'";
      $get = $this->conexion->consultaRetorno($query);
      //echo $query;
      //$get->num_rows;
      $row = $get->fetch_array();
      $haber_saldo_anterior=$row["haber_saldo_anterior"];
      if(!is_numeric($haber_saldo_anterior)){
        $haber_saldo_anterior=0;
      }

      //$saldo_anterior=$debe_saldo_anterior-$haber_saldo_anterior;
      $saldo_anterior=$haber_saldo_anterior+$debe_saldo_anterior;

      $aSaldoInicial[]= array(
        'fecha_hora' => $desde,
        'fecha_hora_formatted' => formatFechaHora($desde),
        'deposito' => '',
        'origen' => '',
        'descripcion' => "Saldo inicial",
        'saldo_anterior' => $saldo_anterior,
      );


      /*CARGO MOVIMIENTOS EN LA CTA CTE EN BASE A LAS CARGAS */

      //$query = "SELECT c.fecha,c.id AS id_carga,SUM(cpd.monto) AS monto,c.id_origen,o.nombre AS origen,c.id_chofer,ch.nombre AS chofer,c.datos_adicionales_chofer,GROUP_CONCAT('+ $',FORMAT(cpd.monto, 2, 'de_DE'),' | ',fp.familia,' ',p.nombre,' (',pp.nombre,' - ',um.unidad_medida,')' SEPARATOR '<br>') AS detalle_productos,IF(c.fecha_hora_despacho IS NULL,'No','Si') AS despachado_lbl,c.fecha_hora_despacho,c.id_usuario,u.usuario,c.anulado FROM cargas c INNER JOIN cargas_productos cp ON cp.id_carga=c.id INNER JOIN cargas_productos_destinos cpd ON cpd.id_carga_producto=cp.id INNER JOIN choferes ch ON c.id_chofer=ch.id INNER JOIN origenes o ON c.id_origen=o.id INNER JOIN usuarios u ON c.id_usuario=u.id INNER JOIN productos p ON cp.id_producto=p.id INNER JOIN familias_productos fp ON p.id_familia=fp.id INNER JOIN presentaciones_productos pp ON p.id_presentacion=pp.id INNER JOIN unidades_medida um ON p.id_unidad_medida=um.id WHERE id_destino IN ($depositos) GROUP BY c.id";

      /*$query = "SELECT c.fecha_hora_despacho AS fecha_hora,c.id AS id_carga,SUM($columna_utilizar) AS monto,c.id_origen,o.nombre AS origen,c.id_chofer,ch.nombre AS chofer,c.datos_adicionales_chofer,GROUP_CONCAT(
        '<div class=\"detalle-producto\">',
        '<span class=\"simbolo\">+</span>',
        '<span class=\"precio\">$', FORMAT($columna_utilizar, 2, 'de_DE'), '</span>',
        '<span class=\"descripcion\">', fp.familia, ' ', p.nombre, ' (', pp.nombre, ' - ', um.unidad_medida, ') | $', cpd.cantidad_bultos,' x ', FORMAT(cp.precio, 2, 'de_DE'),'</span>',
        '</div>' SEPARATOR ''
      ) AS detalle_productos,IF(c.fecha_hora_despacho IS NULL,'No','Si') AS despachado_lbl,cpd.id_destino,d.nombre AS deposito,c.fecha_hora_despacho,c.id_usuario,u.usuario,c.anulado FROM cargas c INNER JOIN cargas_productos cp ON cp.id_carga=c.id INNER JOIN cargas_productos_destinos cpd ON cpd.id_carga_producto=cp.id INNER JOIN choferes ch ON c.id_chofer=ch.id INNER JOIN origenes o ON c.id_origen=o.id INNER JOIN usuarios u ON c.id_usuario=u.id INNER JOIN productos p ON cp.id_producto=p.id INNER JOIN familias_productos fp ON p.id_familia=fp.id INNER JOIN presentaciones_productos pp ON p.id_presentacion=pp.id INNER JOIN unidades_medida um ON p.id_unidad_medida=um.id INNER JOIN destinos d ON cpd.id_destino=d.id WHERE c.fecha_hora_despacho IS NOT NULL AND c.anulado=0 AND cpd.id_destino IN ($depositos) $filtroDesde $filtroHasta GROUP BY c.id";*/

      $query = "SELECT c.fecha_hora_despacho AS fecha_hora,c.id AS id_carga,SUM($columna_utilizar) AS monto,c.id_origen,o.nombre AS origen,c.id_chofer,ch.nombre AS chofer,c.datos_adicionales_chofer,IF(c.fecha_hora_despacho IS NULL,'No','Si') AS despachado_lbl,cpd.id_destino,d.nombre AS deposito,c.fecha_hora_despacho,c.id_usuario,u.usuario,c.anulado FROM cargas c INNER JOIN cargas_productos cp ON cp.id_carga=c.id INNER JOIN cargas_productos_destinos cpd ON cpd.id_carga_producto=cp.id INNER JOIN choferes ch ON c.id_chofer=ch.id INNER JOIN origenes o ON c.id_origen=o.id INNER JOIN usuarios u ON c.id_usuario=u.id INNER JOIN productos p ON cp.id_producto=p.id INNER JOIN familias_productos fp ON p.id_familia=fp.id INNER JOIN presentaciones_productos pp ON p.id_presentacion=pp.id INNER JOIN unidades_medida um ON p.id_unidad_medida=um.id INNER JOIN destinos d ON cpd.id_destino=d.id WHERE c.fecha_hora_despacho IS NOT NULL AND c.anulado=0 AND cpd.id_destino IN ($depositos) $filtroDesde $filtroHasta GROUP BY c.id";
      $get = $this->conexion->consultaRetorno($query);
      //echo $query;
      //$get->num_rows;
      while ($row = $get->fetch_array()) {
        $ctacte[]= array(
          'fecha_hora' =>$fecha_hora=$row['fecha_hora'],
          'fecha_hora_formatted' =>formatFechaHora($fecha_hora),
          'id_carga' =>$row['id_carga'],
          'monto' =>$row['monto'],
          //'monto_valor_extra' =>$row['monto_valor_extra'],
          'id_destino' =>$row['id_destino'],
          'deposito' =>$row['deposito'],
          'id_origen' =>$row['id_origen'],
          'origen' =>$row['origen'],
          'id_chofer' =>$row['id_chofer'],
          'chofer' =>$row['chofer'],
          'datos_adicionales_chofer' =>$row['datos_adicionales_chofer'],
          'despachado_lbl' =>$row['despachado_lbl'],
          'fecha_hora_despacho' =>$row['fecha_hora_despacho'],
          'id_usuario' =>$row['id_usuario'],
          'usuario' =>$row['usuario'],
          //'descripcion' =>$row["detalle_productos"],
          'descripcion' =>"",
        );
      }


      /*CARGO MOVIMIENTOS REGISTRADOS A MANO */
      $filtroDesde=str_replace("c.fecha_hora_despacho","mcc.fecha_hora",$filtroDesde);
      $filtroHasta=str_replace("c.fecha_hora_despacho","mcc.fecha_hora",$filtroHasta);

      //$query = "SELECT mcc.id AS id_movimiento,mcc.fecha_hora,mcc.tipo_movimiento,mcc.id_destino,d.nombre AS deposito,mcc.monto,mcc.descripcion,mcc.id_usuario,mcc.fecha_hora_alta,uc.usuario,mcc.id_usuario_ultima_modificacion,um.usuario AS usuario_ultima_modificacion,mcc.fecha_hora_ultima_modificacion FROM movimientos_cta_cte mcc INNER JOIN destinos d ON mcc.id_destino=d.id INNER JOIN usuarios uc ON mcc.id_usuario=uc.id LEFT JOIN usuarios um ON mcc.id_usuario_ultima_modificacion=um.id WHERE mcc.anulado=0 AND mcc.id_destino IN ($depositos) $filtroDesde $filtroHasta";
      $query = "SELECT mcc.id AS id_movimiento,mcc.fecha_hora,mcc.tipo_movimiento,mcc.id_destino,d.nombre AS deposito,mcc.monto,mcc.descripcion,mcc.id_usuario,mcc.fecha_hora_alta,uc.usuario,mcc.id_usuario_ultima_modificacion,um.usuario AS usuario_ultima_modificacion,mcc.fecha_hora_ultima_modificacion FROM movimientos_cta_cte mcc LEFT JOIN destinos d ON mcc.id_destino=d.id INNER JOIN usuarios uc ON mcc.id_usuario=uc.id LEFT JOIN usuarios um ON mcc.id_usuario_ultima_modificacion=um.id WHERE mcc.anulado=0 $filtroCuentaParaMovimientos $filtroDesde $filtroHasta";
      $get = $this->conexion->consultaRetorno($query);
      //echo $query;
      //$get->num_rows;
      while ($row = $get->fetch_array()) {
        $ctacte[]= array(
          'fecha_hora' =>$fecha_hora=$row['fecha_hora'],
          'fecha_hora_formatted' =>(!is_null($fecha_hora)) ? formatFechaHora($fecha_hora) : "",
          'fecha_hora_alta' =>$fecha_hora_alta=$row['fecha_hora_alta'],
          'fecha_hora_alta_formatted' =>formatFechaHora($fecha_hora_alta),
          'id_movimiento' =>$row['id_movimiento'],
          'monto' =>$row['monto'],
          'id_destino' =>$row['id_destino'],
          'deposito' =>$row['deposito'],
          'tipo_movimiento' =>$row['tipo_movimiento'],
          'descripcion' =>$row['descripcion'],
          'id_usuario_creador' =>$row['id_usuario'],
          'usuario_creador' =>$row['usuario'],
          'id_usuario_ultima_modificacion' =>$row['id_usuario_ultima_modificacion'],
          'usuario_ultima_modificacion' =>$row['usuario_ultima_modificacion'],
          'fecha_hora_ultima_modificacion' =>$fecha_hora_ultima_modificacion=$row['fecha_hora_ultima_modificacion'],
          'fecha_hora_ultima_modificacion_formatted' =>(!is_null($fecha_hora_ultima_modificacion)) ? formatFechaHora($fecha_hora_ultima_modificacion) : "",
        );
      }

      function date_compare($a, $b){
        $t1 = strtotime($a['fecha_hora']);
        $t2 = strtotime($b['fecha_hora']);
        return $t1 - $t2;
      }

      usort($ctacte, 'date_compare');

      //var_dump($ctacte);

      //$ctacte = $aSaldoInicial + $ctacte;
      $ctacte = array_merge($aSaldoInicial, $ctacte);

      //var_dump($ctacte);

    }

    //$saldo=0;
    foreach($ctacte as $key => $value){
      $debe=$haber=0;

      if(isset($value["saldo_anterior"])){
        $saldo=$value["saldo_anterior"];
      }else{
        $esHaber=0;
        if(isset($value["tipo_movimiento"])){
          if($value["tipo_movimiento"]=="haber"){
            $esHaber=1;
          }
        }

        if($esHaber==1){
          $haber=$value["monto"];
        }else{
          $debe=$value["monto"];
        }
      }

      $saldo=$saldo+$debe-$haber;

      $ctacte[$key]["debe"]=$debe;
      $ctacte[$key]["haber"]=$haber;
      $ctacte[$key]["saldo"]=$saldo;
    }

    return json_encode($ctacte);
  }

  public function exportar_excel($id_cuenta, $desde, $hasta, $id_deposito, $tipo, $tipo_aumento_extra, $valor_extra) {    
    //include_once('models/administrar_cta_cte.php');

    // Obtener datos de la cuenta corriente
    $ctacte = new ctacte();
    $ctacteJson = $ctacte->getCtacte($desde, $hasta, $id_cuenta, $id_deposito, $tipo, $tipo_aumento_extra, $valor_extra);

    // Decodificar el JSON
    $aCtaCte = json_decode($ctacteJson, true);
    //var_dump($aCtaCte);

    // Verificar si la decodificación fue exitosa
    if (json_last_error() !== JSON_ERROR_NONE) {
      echo json_encode(['error' => 'Datos inválidos recibidos']);
      exit;
    }

    if($_SESSION["rowUsers"]["id_perfil"]==1){
      if($tipo=="responsable"){
        $query = "SELECT nombre FROM responsables_deposito WHERE id=$id_cuenta";
      }else{
        $query = "SELECT nombre FROM destinos WHERE id=$id_cuenta";
      }
      $get = $this->conexion->consultaRetorno($query);
      $row = $get->fetch_array();
      $cuenta=$row["nombre"];

      $depositos=$id_cuenta;
      if($tipo=="responsable"){;

        if($id_deposito==""){
          $query = "SELECT GROUP_CONCAT(id SEPARATOR ',') AS depositos FROM destinos WHERE id_responsable = ".$id_cuenta;
          $get = $this->conexion->consultaRetorno($query);
          $row = $get->fetch_array();
          $depositos=$row["depositos"];
        }else{
          $depositos=$id_deposito;
        }
      }
    }else{
      $depositos=$_SESSION["rowUsers"]["id_deposito"];
    }

    $query = "SELECT GROUP_CONCAT(nombre SEPARATOR ', ') AS depositos FROM destinos WHERE id IN ($depositos)";
    $get = $this->conexion->consultaRetorno($query);
    $row = $get->fetch_array();
    $depositos=$row["depositos"];

    // Filtrar solo los datos necesarios
    $aCtaCte_filtrados = array();
    foreach ($aCtaCte as $row) {
      // Verificar que los campos necesarios existen en cada movimiento
      if (isset($row['fecha_hora_formatted'], $row['descripcion'], $row['debe'], $row['haber'], $row['saldo'])) {
        if (isset($row['id_carga'])) {
          $descripcion = "Carga #" . $row['id_carga'] . " - ". $row['chofer'];;
        } else if (isset($row['id_movimiento'])) {
          $descripcion = "Movimiento #" . $row['id_movimiento'];
        } else {
          $descripcion = strip_tags($row['descripcion']);
        }

        $aCtaCte_filtrados[] = array(
          'fecha_hora_formatted' => $row['fecha_hora_formatted'],
          'descripcion' => $descripcion,
          'debe' => (float) $row['debe'],
          'haber' => (float) $row['haber'],
          'saldo' => (float) $row['saldo']
        );
      }
    }

    if($cuenta=="" or $cuenta==$depositos){
      $cuenta=$depositos;
      $depositos="";
    }

    // Crear nuevo objeto Spreadsheet
    $spreadsheet = new Spreadsheet();

    // Configuración del archivo
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', "Cuenta");
    $sheet->setCellValue('A2', $cuenta);
    if($depositos!=""){
      $sheet->setCellValue('B1', "Depositos");
      $sheet->setCellValue('B2', $depositos);
    }

    // Encabezado de la tabla de movimientos
    $sheet->setCellValue('A4', "Fecha y Hora");
    $sheet->setCellValue('B4', "Descripcion");
    $sheet->setCellValue('C4', "Debe");
    $sheet->setCellValue('D4', "Haber");
    $sheet->setCellValue('E4', "Saldo");

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

    $aStyleBorders = [
      'borders' => [
        'allBorders' => [
          'borderStyle' => Border::BORDER_THIN,
        ],
      ],
    ];

    $sheet->getStyle('A4:E4')->applyFromArray($aStyleCenter)->applyFromArray($aStyleBold);
    $sheet->getStyle('A1:B1')->applyFromArray($aStyleCenter)->applyFromArray($aStyleBold);
    $sheet->getStyle('A1:B2')->applyFromArray($aStyleBorders);

    // Añadir los datos de los movimientos
    $row = 5; // Fila inicial para los datos
    foreach ($aCtaCte_filtrados as $mov) {
      $sheet->setCellValue('A' . $row, $mov['fecha_hora_formatted']);
      $sheet->setCellValue('B' . $row, $mov['descripcion']);
      $sheet->setCellValue('C' . $row, $mov['debe']);
      $sheet->setCellValue('D' . $row, $mov['haber']);
      $sheet->setCellValue('E' . $row, $mov['saldo']);
      $row++;
    }

    // Formatear celdas para valores numéricos
    $sheet->getStyle('C4:C' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
    $sheet->getStyle('D4:D' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
    $sheet->getStyle('E4:E' . $row)->getNumberFormat()->setFormatCode('#,##0.00');

    // Aplicar bordes a todas las celdas
    $sheet->getStyle('A4:E' . ($row - 1))->applyFromArray($aStyleBorders);

    // Auto-ajustar columnas
    foreach (range('A', 'E') as $col) {
      $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Establecer la celda seleccionada
    $sheet->setSelectedCell('A1');

    $sheet->setTitle('Detalle Cuenta');

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Detalle_Cuenta.xlsx"');
    header('Cache-Control: max-age=0');
    
    $writer = new Xlsx($spreadsheet);
    ob_end_clean(); // Limpiar el búfer de salida para evitar errores de codificación
    $writer->save('php://output');
    exit();
  }

  public function registrarMovimiento($fecha_hora,$id_deposito,$id_responsable,$tipo_movimiento,$monto,$descripcion){

    $id_usuario = $_SESSION['rowUsers']['id_usuario'];

    $queryInsertCarga = "INSERT INTO movimientos_cta_cte (fecha_hora, id_destino, id_responsable, tipo_movimiento, monto, descripcion, id_usuario) VALUES('$fecha_hora', $id_deposito, $id_responsable, '$tipo_movimiento', $monto, '$descripcion', $id_usuario)";
    $insertCarga = $this->conexion->consultaSimple($queryInsertCarga);
    $mensajeError = $this->conexion->conectar->error;
    $id_movimiento = $this->conexion->conectar->insert_id;

    $respuesta=$mensajeError;
    if($mensajeError!=""){
      $respuesta.="<br><br>".$queryInsertCarga;
    }else{

      $ok=1;

      if($id_deposito>0){
        $depositos = new depositos();

        $ok=$depositos->actualizarSaldoCtaCte($id_deposito);
      }

      if($ok==1){
        $respuesta=[
          "ok"=>1,
          "id_movimiento"=>$id_movimiento,
        ];
      }else{
        $respuesta="Algo falló al actualizar el saldo en la cta cte";
      }

      $respuesta=json_encode($respuesta);
    }

    return $respuesta;
  }

  public function getDatosMovimientoCtaCte($id_movimiento){
    $sqltraerMovimientoCtaCte = "SELECT mcc.fecha_hora,mcc.id_destino,mcc.id_responsable,d.nombre AS destino,mcc.tipo_movimiento,mcc.monto,mcc.descripcion,mcc.id_usuario,u.usuario,mcc.fecha_hora_alta FROM movimientos_cta_cte mcc LEFT JOIN destinos d ON mcc.id_destino=d.id INNER JOIN usuarios u ON mcc.id_usuario=u.id WHERE mcc.id = ".$id_movimiento;
    //echo $sqltraerMovimientoCtaCte;
    $traerMovimientoCtaCte = $this->conexion->consultaRetorno($sqltraerMovimientoCtaCte);

    $cargas = array(); //creamos un array
    if($traerMovimientoCtaCte){
      $row = $traerMovimientoCtaCte->fetch_array();
      $cargas = array(
        'fecha_hora'=>$fecha_hora=$row['fecha_hora'],
        'fecha_hora_formatted' => formatFecha($fecha_hora),
        'id_destino'=>$row['id_destino'],
        'id_responsable'=>$row['id_responsable'],
        'destino'=>$row['destino'],
        'tipo_movimiento'=>$row['tipo_movimiento'],
        'monto'=>$row['monto'],
        'descripcion'=>$row['descripcion'],
        'id_usuario'=>$row['id_usuario'],
        'usuario'=>$row['usuario'],
        'fecha_hora_alta'=>$row['fecha_hora_alta'],
      );
    }

    return json_encode($cargas);
  }

  public function updateMovimiento($id_movimiento,$fecha_hora,$id_deposito,$id_responsable,$tipo_movimiento,$monto,$descripcion){

    $id_usuario = $_SESSION['rowUsers']['id_usuario'];

    $queryUpdateMovimiento = "UPDATE movimientos_cta_cte SET fecha_hora='$fecha_hora', id_destino=$id_deposito, id_responsable=$id_responsable, tipo_movimiento='$tipo_movimiento', monto=$monto, descripcion='$descripcion', id_usuario_ultima_modificacion=$id_usuario WHERE id = $id_movimiento";
    $insertCarga = $this->conexion->consultaSimple($queryUpdateMovimiento);
    $mensajeError=$this->conexion->conectar->error;

    $respuesta=$mensajeError;
    if($mensajeError!=""){
      $respuesta.="<br><br>".$queryUpdateMovimiento;
    }else{

      $ok=1;
      if($id_deposito>0){
        $depositos = new depositos();

        $ok=$depositos->actualizarSaldoCtaCte($id_deposito);
      }

      if($ok==1){
        $respuesta=[
          "ok"=>1,
          "id_movimiento"=>$id_movimiento,
        ];
      }else{
        $respuesta="Algo falló al actualizar el saldo en la cta cte";
      }
      $respuesta=json_encode($respuesta);
    }

    return $respuesta;
  }

  public function eliminarMovimiento($id_movimiento){

    $sqlEliminarMovimiento = "UPDATE movimientos_cta_cte SET anulado = 1 WHERE id = $id_movimiento";
    $eliminarMovimiento = $this->conexion->consultaSimple($sqlEliminarMovimiento);
    $affected_rows=$this->conexion->conectar->affected_rows;
    if($affected_rows==1){
      $result=1;
    }elseif($affected_rows>1){
      $result="Se ha anulado mas de 1 registros";
    }elseif($affected_rows<1){
      $result="No se han anulado registros";
    }

    return $result;
  }

  public function traerProductosCarga($id_carga,$id_cuenta,$tipo,$id_deposito){

    $depositos=$id_cuenta;
    $columna_utilizar="cpd.monto_valor_extra";
    if($tipo=="responsable"){
      $columna_utilizar="cpd.monto";

      if($id_deposito==""){
        $query = "SELECT GROUP_CONCAT(id SEPARATOR ',') AS depositos FROM destinos WHERE id_responsable = ".$id_cuenta;
        $get = $this->conexion->consultaRetorno($query);
        $row = $get->fetch_array();
        $depositos=$row["depositos"];
      }else{
        $depositos=$id_deposito;
      }
    }

    $aProductosCarga=[];
    $query = "SELECT cpd.monto,cpd.monto_valor_extra,fp.familia,p.nombre AS producto,pp.nombre AS presentacion,um.unidad_medida,cpd.cantidad_bultos,cpd.kilos,cp.precio,cpd.id_destino,d.nombre AS deposito FROM cargas_productos cp INNER JOIN cargas_productos_destinos cpd ON cpd.id_carga_producto=cp.id INNER JOIN productos p ON cp.id_producto=p.id INNER JOIN familias_productos fp ON p.id_familia=fp.id INNER JOIN presentaciones_productos pp ON p.id_presentacion=pp.id INNER JOIN unidades_medida um ON p.id_unidad_medida=um.id INNER JOIN destinos d ON cpd.id_destino=d.id WHERE cp.id_carga=$id_carga AND cpd.id_destino IN ($depositos)";
    $get = $this->conexion->consultaRetorno($query);
    //echo $query;
    //$get->num_rows;
    while ($row = $get->fetch_array()) {
      $aProductosCarga[]= array(
        'monto' =>$row['monto'],
        'monto_valor_extra' =>$row['monto_valor_extra'],
        'id_destino' =>$row['id_destino'],
        'deposito' =>$row['deposito'],
        'familia' =>$row["familia"],
        'producto' =>$row['producto'],
        'presentacion' =>$row['presentacion'],
        'unidad_medida' =>$row['unidad_medida'],
        'cantidad_bultos' =>$row['cantidad_bultos'],
        'kilos' =>$row['kilos'],
        'precio' =>$row['precio'],
      );
    }

    return json_encode($aProductosCarga);
  }
}

if (isset($_POST['accion'])) {
  $ctacte = new ctacte();
  switch ($_POST['accion']) {
    case 'traerDatosInicialesCtaCte':
      $ctacte->traerDatosIniciales();
    break;
    case 'addMovimiento':
      $fecha_hora=$_POST["fecha_hora"];
      $id_deposito=$_POST["id_deposito"];
      $id_responsable=$_POST["id_responsable"];
      $tipo_movimiento=$_POST["tipo_movimiento"];
      $monto=$_POST["monto"];
      $descripcion=$_POST["descripcion"];
      echo $ctacte->registrarMovimiento($fecha_hora,$id_deposito,$id_responsable,$tipo_movimiento,$monto,$descripcion);
    break;
    case 'traerDatosMovimientoCtaCte':
      $id_movimiento=$_POST["id_movimiento"];
      echo $ctacte->getDatosMovimientoCtaCte($id_movimiento);
    break;
    case 'updateMovimiento':
      $id_movimiento = $_POST['id_movimiento'];
      $fecha_hora=$_POST["fecha_hora"];
      $id_deposito=$_POST["id_deposito"];
      $id_responsable=$_POST["id_responsable"];
      $tipo_movimiento=$_POST["tipo_movimiento"];
      $monto=$_POST["monto"];
      $descripcion=$_POST["descripcion"];
      echo $ctacte->updateMovimiento($id_movimiento,$fecha_hora,$id_deposito,$id_responsable,$tipo_movimiento,$monto,$descripcion);
    break;
    case 'eliminarMovimiento':
      $id_movimiento = $_POST['id_movimiento'];
      echo $ctacte->eliminarMovimiento($id_movimiento);
    break;
    case 'traerProductosCarga':
      $id_carga = $_POST['id_carga'];
      $id_cuenta = $_POST['id_cuenta'];
      $tipo = $_POST['tipo'];
      $id_deposito = $_POST['id_deposito'];
      echo $ctacte->traerProductosCarga($id_carga,$id_cuenta,$tipo,$id_deposito);
    break;
    case 'exportar_excel':
      $id_cuenta = $_POST['id_cuenta'];
      $desde = $_POST['desde'];
      $hasta = $_POST['hasta'];
      $id_deposito = $_POST['id_deposito'];
      $tipo = $_POST['tipo'];
      $tipo_aumento_extra = $_POST['tipo_aumento_extra'];
      $valor_extra = $_POST['valor_extra'];
      //var_dump("id_cuenta: " . $id_cuenta, "desde: " . $desde, " hasta: " . $hasta, " tipo: " . $tipo, " tipo_ aumento_extra : " . $tipo_aumento_extra, " id_deposito: " . $id_deposito, " valor_extra: " . $valor_extra);
      //die();
      $ctacte->exportar_excel($id_cuenta, $desde, $hasta, $id_deposito, $tipo, $tipo_aumento_extra, $valor_extra);
    break;
  }
}elseif(isset($_GET['accion'])){
  $ctacte = new ctacte();
  switch ($_GET['accion']) {
    case 'getCtacte':
      $desde=$_GET["desde"];
      $hasta=$_GET["hasta"];
      $id_cuenta=$_GET["id_cuenta"];
      $id_deposito=$_GET["id_deposito"];
      $tipo=$_GET["tipo"];
      $tipo_aumento_extra=$_GET["tipo_aumento_extra"];
      $valor_extra=$_GET["valor_extra"];

      echo $ctacte->getCtacte($desde,$hasta,$id_cuenta,$id_deposito,$tipo,$tipo_aumento_extra,$valor_extra);
    break;
    case 'exportar_excel':
      var_dump($_GET);
      die();
      $id_cuenta = $_GET['id_cuenta'];
      $desde = $_GET['desde'];
      $hasta = $_GET['hasta'];
      $id_deposito = $_GET['id_deposito'];
      $tipo = $_GET['tipo'];
      $tipo_aumento_extra = $_GET['tipo_aumento_extra'];
      $valor_extra = $_GET['valor_extra'];
      var_dump("id_cuenta: " . $id_cuenta, "desde: " . $desde, " hasta: " . $hasta, " tipo: " . $tipo, " tipo_ aumento_extra : " . $tipo_aumento_extra, " id_deposito: " . $id_deposito, " valor_extra: " . $valor_extra);
      die();
      $ctacte->exportar_excel($id_cuenta, $desde, $hasta, $id_deposito, $tipo, $tipo_aumento_extra, $valor_extra);
    break;
  }
}
?>