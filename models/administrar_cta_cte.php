<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
include_once('conexion.php');
include_once('administrar_producto.php');
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
    $queryResponsable = "SELECT id as id_responsable, nombre FROM responsables_deposito";
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
    $queryDestinos = "SELECT id as id_destino, nombre, tipo_aumento_extra, valor_extra, id_responsable FROM destinos";
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
        $filtroDesde=" AND c.fecha>='$desde'";
      }
      $filtroHasta="";
      if(!empty($hasta)){
        $filtroHasta=" AND c.fecha<='$hasta'";
      }

      if($id_cuenta=="undefined"){
        $id_cuenta=$id_deposito;//cuando el perfil del usuario es de tipo deposito, mostramos el monto con valor extra
      }
      
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

      $query = "SELECT SUM($columna_utilizar) AS debe_saldo_anterior FROM cargas c INNER JOIN cargas_productos cp ON cp.id_carga=c.id INNER JOIN cargas_productos_destinos cpd ON cpd.id_carga_producto=cp.id WHERE c.fecha_hora_despacho IS NOT NULL AND c.anulado=0 AND cpd.id_destino IN ($depositos) AND c.fecha<'$desde'";
      $get = $this->conexion->consultaRetorno($query);
      //echo $query;
      //$get->num_rows;
      $row = $get->fetch_array();
      $debe_saldo_anterior=$row["debe_saldo_anterior"];
      if(!is_numeric($debe_saldo_anterior)){
        $debe_saldo_anterior=0;
      }

      /*CARGO MOVIMIENTOS REGISTRADOS A MANO */
      $query = "SELECT SUM(IF(tipo_movimiento='haber',monto,(monto*-1))) AS haber_saldo_anterior FROM movimientos_cta_cte WHERE anulado=0 AND id_destino IN ($depositos) AND fecha<'$desde'";
      $get = $this->conexion->consultaRetorno($query);
      //echo $query;
      //$get->num_rows;
      $row = $get->fetch_array();
      $haber_saldo_anterior=$row["haber_saldo_anterior"];
      if(!is_numeric($haber_saldo_anterior)){
        $haber_saldo_anterior=0;
      }

      $saldo_anterior=$debe_saldo_anterior-$haber_saldo_anterior;

      $aSaldoInicial[]= array(
        'fecha' => $desde,
        'fecha_formatted' => date("d M Y",strtotime($desde)),
        'deposito' => '',
        'origen' => '',
        'descripcion' => "Saldo inicial",
        'saldo_anterior' => $saldo_anterior,
      );
      

      /*CARGO MOVIMIENTOS EN LA CTA CTE EN BASE A LAS CARGAS */

      //$query = "SELECT c.fecha,c.id AS id_carga,SUM(cpd.monto) AS monto,c.id_origen,o.nombre AS origen,c.id_chofer,ch.nombre AS chofer,c.datos_adicionales_chofer,GROUP_CONCAT('+ $',FORMAT(cpd.monto, 2, 'de_DE'),' | ',fp.familia,' ',p.nombre,' (',pp.nombre,' - ',um.unidad_medida,')' SEPARATOR '<br>') AS detalle_productos,IF(c.fecha_hora_despacho IS NULL,'No','Si') AS despachado_lbl,c.fecha_hora_despacho,c.id_usuario,u.usuario,c.anulado FROM cargas c INNER JOIN cargas_productos cp ON cp.id_carga=c.id INNER JOIN cargas_productos_destinos cpd ON cpd.id_carga_producto=cp.id INNER JOIN choferes ch ON c.id_chofer=ch.id INNER JOIN origenes o ON c.id_origen=o.id INNER JOIN usuarios u ON c.id_usuario=u.id INNER JOIN productos p ON cp.id_producto=p.id INNER JOIN familias_productos fp ON p.id_familia=fp.id INNER JOIN presentaciones_productos pp ON p.id_presentacion=pp.id INNER JOIN unidades_medida um ON p.id_unidad_medida=um.id WHERE id_destino IN ($depositos) GROUP BY c.id";

      $query = "SELECT c.fecha,c.id AS id_carga,SUM($columna_utilizar) AS monto,c.id_origen,o.nombre AS origen,c.id_chofer,ch.nombre AS chofer,c.datos_adicionales_chofer,GROUP_CONCAT(
        '<div class=\"detalle-producto\">',
        '<span class=\"simbolo\">+</span>',
        '<span class=\"precio\">$', FORMAT($columna_utilizar, 2, 'de_DE'), '</span>',
        '<span class=\"descripcion\">', fp.familia, ' ', p.nombre, ' (', pp.nombre, ' - ', um.unidad_medida, ') | $', cpd.cantidad_bultos,' x ', FORMAT(cp.precio, 2, 'de_DE'),'</span>',
        '</div>' SEPARATOR ''
      ) AS detalle_productos,IF(c.fecha_hora_despacho IS NULL,'No','Si') AS despachado_lbl,cpd.id_destino,d.nombre AS deposito,c.fecha_hora_despacho,c.id_usuario,u.usuario,c.anulado FROM cargas c INNER JOIN cargas_productos cp ON cp.id_carga=c.id INNER JOIN cargas_productos_destinos cpd ON cpd.id_carga_producto=cp.id INNER JOIN choferes ch ON c.id_chofer=ch.id INNER JOIN origenes o ON c.id_origen=o.id INNER JOIN usuarios u ON c.id_usuario=u.id INNER JOIN productos p ON cp.id_producto=p.id INNER JOIN familias_productos fp ON p.id_familia=fp.id INNER JOIN presentaciones_productos pp ON p.id_presentacion=pp.id INNER JOIN unidades_medida um ON p.id_unidad_medida=um.id INNER JOIN destinos d ON cpd.id_destino=d.id WHERE c.fecha_hora_despacho IS NOT NULL AND c.anulado=0 AND cpd.id_destino IN ($depositos) $filtroDesde $filtroHasta GROUP BY c.id";
      $get = $this->conexion->consultaRetorno($query);
      //echo $query;
      //$get->num_rows;
      while ($row = $get->fetch_array()) {
        $ctacte[]= array(
          'fecha' =>$fecha=$row['fecha'],
          'fecha_formatted' =>date("d M Y",strtotime($fecha)),
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
          'descripcion' =>$row["detalle_productos"],
        );
      }


      /*CARGO MOVIMIENTOS REGISTRADOS A MANO */
      $filtroDesde=str_replace("c.","mcc.",$filtroDesde);
      $filtroHasta=str_replace("c.","mcc.",$filtroHasta);
      $query = "SELECT mcc.id AS id_movimiento,mcc.fecha,mcc.tipo_movimiento,mcc.id_destino,d.nombre AS deposito,mcc.monto,mcc.descripcion,mcc.id_usuario,u.usuario FROM movimientos_cta_cte mcc INNER JOIN destinos d ON mcc.id_destino=d.id INNER JOIN usuarios u ON mcc.id_usuario=u.id WHERE mcc.anulado=0 AND mcc.id_destino IN ($depositos) $filtroDesde $filtroHasta";
      $get = $this->conexion->consultaRetorno($query);
      //echo $query;
      //$get->num_rows;
      while ($row = $get->fetch_array()) {
        $ctacte[]= array(
          'fecha' =>$fecha=$row['fecha'],
          'fecha_formatted' =>date("d M Y",strtotime($fecha)),
          'id_movimiento' =>$row['id_movimiento'],
          'monto' =>$row['monto'],
          'id_destino' =>$row['id_destino'],
          'deposito' =>$row['deposito'],
          'tipo_movimiento' =>$row['tipo_movimiento'],
          'descripcion' =>$row['descripcion'],
          'id_usuario' =>$row['id_usuario'],
          'usuario' =>$row['usuario'],
        );
      }

      function date_compare($a, $b){
        $t1 = strtotime($a['fecha']);
        $t2 = strtotime($b['fecha']);
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

    echo json_encode($ctacte);
  }

  public function registrarMovimiento($fecha,$deposito,$tipo_movimiento,$monto,$descripcion){

    $id_usuario = $_SESSION['rowUsers']['id_usuario'];

    $queryInsertCarga = "INSERT INTO movimientos_cta_cte (fecha, id_destino, tipo_movimiento, monto, descripcion, id_usuario) VALUES('$fecha', $deposito, '$tipo_movimiento', $monto, '$descripcion', $id_usuario)";
    $insertCarga = $this->conexion->consultaSimple($queryInsertCarga);
    $mensajeError = $this->conexion->conectar->error;
    $id_movimiento = $this->conexion->conectar->insert_id;
    
    $respuesta=$mensajeError;
    if($mensajeError!=""){
      $respuesta.="<br><br>".$queryInsertCarga;
    }else{
      $respuesta=[
        "ok"=>1,
        "id_movimiento"=>$id_movimiento,
      ];
      $respuesta=json_encode($respuesta);
    }
    
    return $respuesta;
  }

  public function getDatosMovimientoCtaCte($id_movimiento){
    $sqltraerMovimientoCtaCte = "SELECT mcc.fecha,mcc.id_destino,d.nombre AS destino,mcc.tipo_movimiento,mcc.monto,mcc.descripcion,mcc.id_usuario,u.usuario,mcc.fecha_hora_alta FROM movimientos_cta_cte mcc INNER JOIN destinos d ON mcc.id_destino=d.id INNER JOIN usuarios u ON mcc.id_usuario=u.id WHERE mcc.id = ".$id_movimiento;
    //echo $sqltraerMovimientoCtaCte;
    $traerMovimientoCtaCte = $this->conexion->consultaRetorno($sqltraerMovimientoCtaCte);
    
    $cargas = array(); //creamos un array
    if($traerMovimientoCtaCte){
      $row = $traerMovimientoCtaCte->fetch_array();
      $cargas = array(
        'fecha'=>$fecha=$row['fecha'],
        'fecha_formatted' => date("d M Y",strtotime($fecha)),
        'id_destino'=>$row['id_destino'],
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

  public function updateMovimiento($id_movimiento,$fecha,$deposito,$tipo_movimiento,$monto,$descripcion){

    $id_usuario = $_SESSION['rowUsers']['id_usuario'];

    $queryUpdateMovimiento = "UPDATE movimientos_cta_cte SET fecha='$fecha', id_destino=$deposito, tipo_movimiento='$tipo_movimiento', monto=$monto, descripcion='$descripcion', id_usuario=$id_usuario WHERE id = $id_movimiento";
    $insertCarga = $this->conexion->consultaSimple($queryUpdateMovimiento);
    $mensajeError=$this->conexion->conectar->error;

    $respuesta=$mensajeError;
    if($mensajeError!=""){
      $respuesta.="<br><br>".$queryUpdateMovimiento;
    }else{
      $respuesta=[
        "ok"=>1,
        "id_movimiento"=>$id_movimiento,
      ];
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
}

if (isset($_POST['accion'])) {
  $ctacte = new ctacte();
  switch ($_POST['accion']) {
    case 'traerDatosInicialesCtaCte':
      $ctacte->traerDatosIniciales();
    break;
    case 'addMovimiento':
      $fecha=$_POST["fecha"];
      $deposito=$_POST["deposito"];
      $tipo_movimiento=$_POST["tipo_movimiento"];
      $monto=$_POST["monto"];
      $descripcion=$_POST["descripcion"];
      echo $ctacte->registrarMovimiento($fecha,$deposito,$tipo_movimiento,$monto,$descripcion);
    break;
    case 'traerDatosMovimientoCtaCte':
      $id_movimiento=$_POST["id_movimiento"];
      echo $ctacte->getDatosMovimientoCtaCte($id_movimiento);
    break;
    case 'updateMovimiento':
      $id_movimiento = $_POST['id_movimiento'];
      $fecha=$_POST["fecha"];
      $deposito=$_POST["deposito"];
      $tipo_movimiento=$_POST["tipo_movimiento"];
      $monto=$_POST["monto"];
      $descripcion=$_POST["descripcion"];
      echo $ctacte->updateMovimiento($id_movimiento,$fecha,$deposito,$tipo_movimiento,$monto,$descripcion);
    break;
    case 'eliminarMovimiento':
      $id_movimiento = $_POST['id_movimiento'];
      echo $ctacte->eliminarMovimiento($id_movimiento);
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
  }
}
?>