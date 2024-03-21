<?php
//session_start();
require_once('./conexion.php');
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
  // session isn't started
  session_start();
}
require_once('administrar_clientes.php');
require_once('administrar_vehiculos.php');
require_once('administrar_mantenimieno_preventivo.php');
require_once('administrar_tareas.php');
require_once('administrar_elementos.php');
require_once('administrar_pedidos.php');
require_once('administrar_movimientos_stock.php');
require_once('administrar_stock.php');

extract($_REQUEST);
class OrdenTrabajo{

  private $id_orden_trabajo;
  private $id_empresa;

  public function __construct(){
    $this->conexion = new Conexion();
    $this->id_empresa = isset($_SESSION["rowUsers"]["id_empresa"]) ? $_SESSION["rowUsers"]["id_empresa"] : "";
    date_default_timezone_set("America/Buenos_Aires");
  }

  public function traerDatosIniciales(){
    $datosIniciales = array();

    $cliente = new Clientes();
    $listaClientes=$cliente->traerClientes($this->id_empresa);
    $listaClientes=json_decode($listaClientes,true);


    /*CENTRO DE COSTOS*/
    $listaCentroCostos=[];
    $query = "SELECT id, nombre FROM centro_costos WHERE id_empresa = $this->id_empresa";
    $get = $this->conexion->consultaRetorno($query);
    /*CARGO ARRAY CON CENTRO DE COSTOS*/
    while ($row = $get->fetch_assoc()) {
      $listaCentroCostos[]=array(
        'id_centro_costo' =>$row['id'],
        'nombre'          =>$row['nombre']
      );
    }

    $datosIniciales["clientes"] = $listaClientes;
    $datosIniciales["centro_costos"] = $listaCentroCostos;

    echo json_encode($datosIniciales);
  }

  public function traerOrdenTrabajo2($filtros=0){

    /*$filtro_orden_trabajo="";
    if($id_orden_trabajo!=0){
      $filtro_orden_trabajo=" AND cm.id = $id_orden_trabajo";
    }*/
    
    $filtro_orden_trabajo="";
    $selectTecnicos="";
    $joinTecnicos="";
    $filtro_tecnico="";
    $filtro_desde="";
    $filtro_hasta="";
    $filtro_cliente="";
    if($filtros!=0){
        //var_dump($filtros);
        if(isset($filtros["id_orden_trabajo"]) and $filtros["id_orden_trabajo"]!=""){
            $filtro_orden_trabajo=" AND ot.id = ".$filtros["id_orden_trabajo"];
        }
        if(isset($filtros["id_tecnico"]) and $filtros["id_tecnico"]!=""){
            $selectTecnicos=",ttm.fecha_hora_inicio_trabajo,ttm.fecha_hora_fin_trabajo";
            $joinTecnicos="INNER JOIN tecnicos_tareas_mantenimiento ttm ON ttm.id_orden_trabajo=ot.id";
            $filtro_tecnico=" AND ttm.id_tecnico = ".$filtros["id_tecnico"];
        }
        if(isset($filtros["fecha_desde"]) and $filtros["fecha_desde"]!=""){
            $filtro_desde=" AND ot.fecha >= '".$filtros["fecha_desde"]."'";
        }
        if(isset($filtros["fecha_hasta"]) and $filtros["fecha_hasta"]!=""){
            $filtro_hasta=" AND ot.fecha <= '".$filtros["fecha_hasta"]."'";
        }
        if(isset($filtros["id_cliente"]) and $filtros["id_cliente"]!=""){
            $filtro_cliente=" AND c.id = ".$filtros["id_cliente"];
        }
    }

    $filtro_empresa="";
    if($this->id_empresa!=""){
        $filtro_empresa=" AND c.id_empresa = ".$this->id_empresa;
    }
    
    $arrayOrdenTrabajo = [];

    $queryGet = "SELECT ot.id AS id_orden_trabajo,ac.id AS id_activo_cliente,ac.descripcion AS descripcion_activo,ac.ubicacion,dc.id AS id_direccion_cliente,dc.direccion,c.id AS id_cliente,c.razon_social AS cliente,ot.fecha,ot.hora_desde,ot.hora_hasta,ot.fecha_hora_alta,ot.aprobado_cliente,cocl.id AS id_contacto_cliente,cocl.nombre_completo AS contacto_cliente,eot.id AS id_estado,eot.estado,ot.id_centro_costos,ceco.nombre AS nombre_centro_costo $selectTecnicos
    FROM ordenes_trabajo ot 
      INNER JOIN tareas_ordenes_trabajo tot ON tot.id_orden_trabajo=ot.id

      INNER JOIN calendario_mantenimiento cm ON tot.id_calendario_mantenimiento=cm.id 
      INNER JOIN activos_cliente ac ON cm.id_activo_cliente=ac.id 
      INNER JOIN direcciones_clientes dc ON ac.id_direccion_cliente=dc.id 
      INNER JOIN clientes c ON dc.id_cliente=c.id 
      INNER JOIN contactos_clientes cocl ON cm.id_contacto_cliente=cocl.id 
      
      INNER JOIN estados_ordenes_trabajo eot ON ot.id_estado_orden=eot.id
      INNER JOIN centro_costos ceco ON ot.id_centro_costos=ceco.id
      $joinTecnicos
    WHERE 1 $filtro_empresa $filtro_orden_trabajo $filtro_tecnico $filtro_desde $filtro_hasta $filtro_cliente
    GROUP BY ot.id";
    //var_dump($queryGet);
    //echo $queryGet;
    $getDatos = $this->conexion->consultaRetorno($queryGet);
    while ($row = $getDatos->fetch_array()) {
      $fecha_hora_alta=$row["fecha_hora_alta"];
      $fecha=$row["fecha"];
      $hora_desde=$row["hora_desde"];
      $hora_hasta=$row["hora_hasta"];
      $fecha_hora_inicio_trabajo_tecnico="";
      $fecha_hora_inicio_trabajo_tecnico_mostrar="";
      if(isset($row["fecha_hora_inicio_trabajo"])){
        $fecha_hora_inicio_trabajo_tecnico=$row["fecha_hora_inicio_trabajo"];
        $fecha_hora_inicio_trabajo_tecnico_mostrar=date("d/m/Y H:i",strtotime($fecha_hora_inicio_trabajo_tecnico))."hs";
      }
      $fecha_hora_fin_trabajo_tecnico="";
      $fecha_hora_fin_trabajo_tecnico_mostrar="";
      if(isset($row["fecha_hora_fin_trabajo"])){
        $fecha_hora_fin_trabajo_tecnico=$row["fecha_hora_fin_trabajo"];
        $fecha_hora_fin_trabajo_tecnico_mostrar=date("d/m/Y H:i",strtotime($fecha_hora_fin_trabajo_tecnico))."hs";
      }

      $filtro["id_tarea"];
      $tareas->traerTareas($filtro);
      $arrayOrdenTrabajo[] =[
        "id_orden_trabajo"            =>$row["id_orden_trabajo"],
        //"id_calendario_mantenimiento" =>$row["id_calendario_mantenimiento"],
        "fecha_hora_alta"             =>($fecha_hora_alta == 0) ? "" : $fecha_hora_alta,
        "fecha_hora_alta_mostrar"     =>($fecha_hora_alta == 0) ? "" : date("d/m/Y", strtotime($fecha_hora_alta)),
        "id_activo_cliente"           =>$row["id_activo_cliente"],
        "descripcion_activo"          =>$row["descripcion_activo"],
        "id_direccion_cliente"        =>$row["id_direccion_cliente"],
        "direccion"                   =>$row["direccion"],
        //"asunto"                      =>$row["asunto"],
        "aprobado_cliente"            =>$row["aprobado_cliente"],
        "id_centro_costos"            =>$row["id_centro_costos"],
        "nombre_centro_costo"         =>$row["nombre_centro_costo"],
        "fecha"                       =>($fecha == 0) ? "" : $fecha,
        "fecha_mostrar"               =>($fecha == 0) ? "" : date("d/m/Y",strtotime($fecha)),
        "hora_desde"                  =>($hora_desde == NULL) ? "" : $hora_desde,
        "hora_desde_mostrar"          =>($hora_desde == NULL) ? "" : date("H:i",strtotime($hora_desde))."hs",
        "hora_hasta"                  =>($hora_hasta == NULL) ? "" : $hora_hasta,
        "hora_hasta_mostrar"          =>($hora_hasta == NULL) ? "" : date("H:i",strtotime($hora_hasta))."hs",
        "id_estado"                   =>$row["id_estado"],
        "estado"                      =>$row["estado"],
        "id_contacto_cliente"         =>$row["id_contacto_cliente"],
        "id_cliente"                  =>$row["id_cliente"],
        "cliente"                     =>$row["cliente"],
        "contacto_cliente"            =>$row["contacto_cliente"],
        "fecha_hora_inicio_trabajo_tecnico"         =>$fecha_hora_inicio_trabajo_tecnico,
        "fecha_hora_inicio_trabajo_tecnico_mostrar" =>$fecha_hora_inicio_trabajo_tecnico_mostrar,
        "fecha_hora_fin_trabajo_tecnico"            =>$fecha_hora_fin_trabajo_tecnico,
        "fecha_hora_fin_trabajo_tecnico_mostrar"    =>$fecha_hora_fin_trabajo_tecnico_mostrar,
      ];
    }
    //echo json_encode($arrayOrdenTrabajo);
    return json_encode($arrayOrdenTrabajo);
  }

  public function traerOrdenTrabajo($filtros=0){

    /*$filtro_orden_trabajo="";
    if($id_orden_trabajo!=0){
      $filtro_orden_trabajo=" AND cm.id = $id_orden_trabajo";
    }*/
    
    $filtro_orden_trabajo="";
    $selectTecnicos="";
    $joinTecnicos="";
    $filtro_tecnico="";
    $filtro_desde="";
    $filtro_hasta="";
    $filtro_cliente="";
    $filtro_preventiva="";

    if($filtros!=0){
        //var_dump($filtros);
        if(isset($filtros["id_orden_trabajo"]) and $filtros["id_orden_trabajo"]!=""){
            $filtro_orden_trabajo=" AND ot.id = ".$filtros["id_orden_trabajo"];
        }
        if(isset($filtros["id_tecnico"]) and $filtros["id_tecnico"]!=""){
            $selectTecnicos=",ttm.fecha_hora_inicio_trabajo,ttm.fecha_hora_fin_trabajo";
            $joinTecnicos="INNER JOIN tecnicos_tareas_mantenimiento ttm ON ttm.id_orden_trabajo=ot.id";
            $filtro_tecnico=" AND ttm.id_tecnico = ".$filtros["id_tecnico"];
        }
        if(isset($filtros["fecha_desde"]) and $filtros["fecha_desde"]!=""){
            $filtro_desde=" AND ot.fecha >= '".$filtros["fecha_desde"]."'";
        }
        if(isset($filtros["fecha_hasta"]) and $filtros["fecha_hasta"]!=""){
            $filtro_hasta=" AND ot.fecha <= '".$filtros["fecha_hasta"]."'";
        }
        if(isset($filtros["id_cliente"]) and $filtros["id_cliente"]!=""){
            $filtro_cliente=" AND ot.id_cliente = ".$filtros["id_cliente"];
        }
        if(isset($filtros["tipo"]) and $filtros["tipo"]=="preventiva"){
          $filtro_cliente=" AND ot.id_pedido IS NULL";
      }
    }

    $filtro_empresa="";
    if($this->id_empresa!=""){
        $filtro_empresa=" AND ot.id_empresa = ".$this->id_empresa;
    }
    
    $arrayOrdenTrabajo = [];

    $queryGet = "SELECT ot.id AS id_orden_trabajo,ot.id_cliente,c.razon_social,ot.fecha,ot.hora_desde,ot.hora_hasta,ot.fecha_hora_alta,ot.id_pedido,ot.aprobado_cliente,eot.id AS id_estado,eot.estado,ot.id_centro_costos,tot.id_calendario_mantenimiento,tot.id_tarea,ceco.nombre AS nombre_centro_costo $selectTecnicos
    FROM ordenes_trabajo ot 
      INNER JOIN clientes c ON ot.id_cliente=c.id
      INNER JOIN tareas_ordenes_trabajo tot ON tot.id_orden_trabajo=ot.id 
      INNER JOIN estados_ordenes_trabajo eot ON ot.id_estado_orden=eot.id 
      INNER JOIN centro_costos ceco ON ot.id_centro_costos=ceco.id 
      $joinTecnicos
    WHERE 1 $filtro_empresa $filtro_orden_trabajo $filtro_tecnico $filtro_desde $filtro_hasta $filtro_cliente
    GROUP BY ot.id
    ORDER BY ot.fecha DESC, ot.id DESC";
    //var_dump($queryGet);
    $getDatos = $this->conexion->consultaRetorno($queryGet);
    $mensajeError=$this->conexion->conectar->error;
    //echo $mensajeError;
    if($mensajeError!=""){
      echo $mensajeError."<br><br>".$queryGet."<br><br>";
    }

    $tareas=new Tarea();
    $mant_preventivo=new MantenimientoPreventivo();
    /*$elementos=new Elemento();*/
    $clientes=new Clientes();
    $pedidos=new Pedidos();

    while ($row = $getDatos->fetch_array()) {
      $fecha_hora_alta=$row["fecha_hora_alta"];
      $fecha=$row["fecha"];
      $hora_desde=$row["hora_desde"];
      $hora_hasta=$row["hora_hasta"];
      $fecha_hora_inicio_trabajo_tecnico="";
      $fecha_hora_inicio_trabajo_tecnico_mostrar="";
      if(isset($row["fecha_hora_inicio_trabajo"])){
        $fecha_hora_inicio_trabajo_tecnico=$row["fecha_hora_inicio_trabajo"];
        $fecha_hora_inicio_trabajo_tecnico_mostrar=date("d/m/Y H:i",strtotime($fecha_hora_inicio_trabajo_tecnico))."hs";
      }
      $fecha_hora_fin_trabajo_tecnico="";
      $fecha_hora_fin_trabajo_tecnico_mostrar="";
      if(isset($row["fecha_hora_fin_trabajo"])){
        $fecha_hora_fin_trabajo_tecnico=$row["fecha_hora_fin_trabajo"];
        $fecha_hora_fin_trabajo_tecnico_mostrar=date("d/m/Y H:i",strtotime($fecha_hora_fin_trabajo_tecnico))."hs";
      }

      $elemento=null;
      $ubicacion=null;
      if($row["id_tarea"]){
        //echo "get tareas<br>";
        $filtroTarea["id_tarea"]=$row["id_tarea"];
        $tarea=$tareas->traerTareas($filtroTarea);
        $tarea=json_decode($tarea,true);
        $row["id_pedido"];

        $filtroPedido["id_pedido"]=$row["id_pedido"];
        $pedido=$pedidos->traerPedidos($filtroPedido);
        $pedido=json_decode($pedido,true);
        //var_dump($pedido);
        
        $filtroContacto["id_contacto"]=$pedido[0]["id_contacto_cliente"];
        $contacto=$clientes->traerContactos($filtroContacto);
        $contacto=json_decode($contacto,true);
        $contacto=$contacto["contactos"][0];

        //$ubicacion=$ubicacion["contactos"][0];
        $id_ubicacion=$pedido[0]["id_direccion_cliente"];
      }elseif($row["id_calendario_mantenimiento"]){
        //echo "get calendario mantenimiento<br>";
        $filtroTarea["id_mantenimiento_preventivo"]=$row["id_calendario_mantenimiento"];
        $tarea=$mant_preventivo->traerMantenimientoPreventivo($filtroTarea);
        $tarea=json_decode($tarea,true);

        $filtroContacto["id_contacto"]=$tarea[0]["id_contacto_cliente"];
        $contacto=$clientes->traerContactos($filtroContacto);
        $contacto=json_decode($contacto,true);
        $contacto=$contacto["contactos"][0];

        $elemento=$tarea[0]["elemento"];
        $id_ubicacion=$elemento["id_direccion_cliente"];
      }

      $ubicacion=$clientes->traerDomicilios($row["id_cliente"]);
      $ubicacion=json_decode($ubicacion,true);
      $key_ubicacion = array_search($id_ubicacion, array_column($ubicacion, 'id_direccion'));
      $ubicacion=$ubicacion[$key_ubicacion];
      
      //var_dump($tarea);
      //var_dump($elemento);
      $arrayOrdenTrabajo[] =[
        "id_orden_trabajo"            =>$row["id_orden_trabajo"],
        "id_cliente"                  =>$row["id_cliente"],
        "cliente"                     =>$row["razon_social"],
        //"id_calendario_mantenimiento" =>$row["id_calendario_mantenimiento"],//comentados previamente
        "fecha_hora_alta"             =>($fecha_hora_alta == 0) ? "" : $fecha_hora_alta,
        "fecha_hora_alta_mostrar"     =>($fecha_hora_alta == 0) ? "" : date("d/m/Y", strtotime($fecha_hora_alta)),
        //"id_activo_cliente"           =>$row["id_activo_cliente"],
        //"descripcion_activo"          =>$row["descripcion_activo"],
        //"id_direccion_cliente"        =>$row["id_direccion_cliente"],
        //"direccion"                   =>$row["direccion"],
        //"asunto"                      =>$row["asunto"],//comentados previamente
        "elemento"                    =>$elemento,
        "ubicacion"                   =>$ubicacion,
        "contacto"                    =>$contacto,
        "aprobado_cliente"            =>$row["aprobado_cliente"],
        "id_centro_costos"            =>$row["id_centro_costos"],
        "nombre_centro_costo"         =>$row["nombre_centro_costo"],
        "fecha"                       =>($fecha == 0) ? "" : $fecha,
        "fecha_mostrar"               =>($fecha == 0) ? "" : date("d/m/Y",strtotime($fecha)),
        "hora_desde"                  =>($hora_desde == NULL) ? "" : $hora_desde,
        "hora_desde_mostrar"          =>($hora_desde == NULL) ? "" : date("H:i",strtotime($hora_desde))."hs",
        "hora_hasta"                  =>($hora_hasta == NULL) ? "" : $hora_hasta,
        "hora_hasta_mostrar"          =>($hora_hasta == NULL) ? "" : date("H:i",strtotime($hora_hasta))."hs",
        "id_estado"                   =>$row["id_estado"],
        "estado"                      =>$row["estado"],
        //"id_contacto_cliente"         =>$row["id_contacto_cliente"],
        //"id_cliente"                  =>$row["id_cliente"],
        //"cliente"                     =>$row["cliente"],
        //"contacto_cliente"            =>$row["contacto_cliente"],
        "fecha_hora_inicio_trabajo_tecnico"         =>$fecha_hora_inicio_trabajo_tecnico,
        "fecha_hora_inicio_trabajo_tecnico_mostrar" =>$fecha_hora_inicio_trabajo_tecnico_mostrar,
        "fecha_hora_fin_trabajo_tecnico"            =>$fecha_hora_fin_trabajo_tecnico,
        "fecha_hora_fin_trabajo_tecnico_mostrar"    =>$fecha_hora_fin_trabajo_tecnico_mostrar,
      ];
    }
    //echo json_encode($arrayOrdenTrabajo);
    return json_encode($arrayOrdenTrabajo);
  }

  public function agregarOrdenTrabajo($id_cliente, $id_pedido, $id_centro_costos, $fecha, $nro_orden_trabajo, $hora_desde, $hora_hasta, $aTareas, $aTecnicos, $tipoTareas){
    //$id_calendario_mantenimiento=$aTareas[0]["id_mantenimiento_preventivo"];
    $id_estado_orden=1;

    /*$fecha_hora_desde=date("Y-m-d H:i",strtotime($fecha_hora_desde));
    $fecha_hora_hasta=date("Y-m-d H:i",strtotime($fecha_hora_hasta));*/

    //$queryInsert = "INSERT INTO ordenes_trabajo (id_calendario_mantenimiento, nro_orden_trabajo, fecha_hora_alta, fecha, hora_desde, hora_hasta, id_estado_orden) VALUES ('$id_calendario_mantenimiento', '$nro_orden_trabajo', NOW(), '$fecha', '$hora_desde', '$hora_hasta', '$id_estado_orden')";
    $queryInsert = "INSERT INTO ordenes_trabajo (id_empresa, id_cliente, id_pedido, nro_orden_trabajo, fecha_hora_alta, id_centro_costos, fecha, hora_desde, hora_hasta, id_estado_orden) VALUES ($this->id_empresa, $id_cliente, $id_pedido, '$nro_orden_trabajo', NOW(), '$id_centro_costos', '$fecha', '$hora_desde', '$hora_hasta', '$id_estado_orden')";
    //echo $queryInsert;
    $insertar= $this->conexion->consultaSimple($queryInsert);
    $mensajeError=$this->conexion->conectar->error;
    $id_orden_trabajo=$this->conexion->conectar->insert_id;

    echo $mensajeError;
    if($mensajeError!=""){
      echo "<br><br>".$queryInsert;
    }

    //$orden_trabajo = new OrdenTrabajo();
    switch($tipoTareas){
      case "preventivo":
        $this->agregarTareasPreventivasOrdenTrabajo($id_orden_trabajo,$aTareas);
      break;
      case "correctivo":
        $this->agregarTareasCorrectivasOrdenTrabajo($id_orden_trabajo,$aTareas);
      break;
    }
    //$this->agregarMaterialesOrdenTrabajo($id_orden_trabajo);
    $this->agregarTecnicosOrdenTrabajo($id_orden_trabajo,$aTecnicos);

    //echo 1;
  }

  public function agregarTareasPreventivasOrdenTrabajo($id_orden_trabajo,$aTareas){
    $aIdCalendarioMantenimiento=[];
    foreach ($aTareas as $key => $tarea) {
      $aIdCalendarioMantenimiento[]=$id_mantenimiento_preventivo=$tarea["id_mantenimiento_preventivo"];
      
      $queryInsert = "INSERT INTO tareas_ordenes_trabajo (id_orden_trabajo, id_calendario_mantenimiento) VALUES ('$id_orden_trabajo', '$id_mantenimiento_preventivo')";
      //echo $queryInsert;
      $insertar= $this->conexion->consultaSimple($queryInsert);
      $mensajeError=$this->conexion->conectar->error;

      echo $mensajeError;
      if($mensajeError!=""){
        echo "<br><br>".$queryInsert;
      }
    }

    $idCalendarioMantenimiento=implode(",",$aIdCalendarioMantenimiento);

    $queryItems = "SELECT id_item,id_proveedor,id_almacen,SUM(cantidad_estimada) AS cantidad_estimada_total FROM materiales_mantenimiento WHERE id_calendario_mantenimiento IN ($idCalendarioMantenimiento) GROUP BY id_item,id_proveedor,id_almacen";
    //$queryItems = "SELECT id_item,id_proveedor,id_almacen,SUM(cantidad_estimada) AS cantidad_estimada_total FROM materiales_mantenimiento WHERE id_calendario_mantenimiento IN (SELECT GROUP_CONCAT(id_calendario_mantenimiento SEPARATOR ',') FROM tareas_ordenes_trabajo WHERE id_orden_trabajo = $id_orden_trabajo) GROUP BY id_item,id_proveedor,id_almacen";
    //var_dump($queryItems);
    $getItems = $this->conexion->consultaRetorno($queryItems);
    while ($row = $getItems->fetch_array()) {

      $cantidad_reservada=$row["cantidad_estimada_total"];

      //RESERVAMOS EL STOCK DE LOS ITEMS A UTILIZAR
      $queryReservarStock = "UPDATE stock SET cantidad_disponible = cantidad_disponible - $cantidad_reservada, cantidad_reservada = cantidad_reservada + $cantidad_reservada WHERE id_item=".$row["id_item"]." AND id_proveedor=".$row["id_proveedor"]." AND id_almacen=".$row["id_almacen"];
      //var_dump($queryItems);
      $insertAdjuntos = $this->conexion->consultaSimple($queryReservarStock);
      $mensajeError=$this->conexion->conectar->error;
  
      echo $mensajeError;
      if($mensajeError!=""){
        echo "<br><br>".$queryReservarStock;
      }

      $this->agregarMaterialesOrdenTrabajo($id_orden_trabajo, $row["id_item"], $row["id_proveedor"], $row["id_almacen"],$cantidad_reservada,$cantidad_utilizada=0);
    }
  }

  public function agregarTareasCorrectivasOrdenTrabajo($id_orden_trabajo,$aTareas){
    $aIdTareas=[];
    //var_dump($aTareas);
    foreach ($aTareas as $key => $tarea) {
      $aIdTareas[]=$id_tarea=$tarea["id_tarea"];
      
      $queryInsert = "INSERT INTO tareas_ordenes_trabajo (id_orden_trabajo, id_tarea) VALUES ('$id_orden_trabajo', '$id_tarea')";
      //echo $queryInsert;
      $insertar= $this->conexion->consultaSimple($queryInsert);
      $mensajeError=$this->conexion->conectar->error;

      if($mensajeError!="") echo $mensajeError."<br><br>".$queryInsert."<br><br>";
    }

    $idTareas=implode(",",$aIdTareas);

    $queryItems = "SELECT id_item,id_proveedor,id_almacen,SUM(cantidad_estimada) AS cantidad_estimada_total FROM materiales_tarea WHERE id_tarea IN ($idTareas) GROUP BY id_item,id_proveedor,id_almacen";
    //var_dump($queryItems);
    $getItems = $this->conexion->consultaRetorno($queryItems);
    while ($row = $getItems->fetch_array()) {

      $cantidad_reservada=$row["cantidad_estimada_total"];

      //RESERVAMOS EL STOCK DE LOS ITEMS A UTILIZAR
      $queryReservarStock = "UPDATE stock SET cantidad_disponible = cantidad_disponible - $cantidad_reservada, cantidad_reservada = cantidad_reservada + $cantidad_reservada WHERE id_item=".$row["id_item"]." AND id_proveedor=".$row["id_proveedor"]." AND id_almacen=".$row["id_almacen"];
      //var_dump($queryItems);
      $insertAdjuntos = $this->conexion->consultaSimple($queryReservarStock);
      $mensajeError=$this->conexion->conectar->error;
  
      echo $mensajeError;
      if($mensajeError!=""){
        echo "<br><br>".$queryReservarStock;
      }

      $this->agregarMaterialesOrdenTrabajo($id_orden_trabajo, $row["id_item"], $row["id_proveedor"], $row["id_almacen"],$cantidad_reservada,$cantidad_utilizada=0);
    }
  }

  public function agregarMaterialesOrdenTrabajo($id_orden_trabajo, $id_item, $id_proveedor, $id_almacen, $cantidad_reservada, $cantidad_utilizada){
    $cargado_vehiculo=0;
    $aprobado_cliente=0;

    //INSERTO DATOS EN LA TABLA MATERIALES ORDEN_COMPRA
    $queryInsertMateriales = "INSERT INTO materiales_orden_trabajo (id_orden_trabajo, id_item, id_proveedor, id_almacen, cantidad_reservada, cargado_vehiculo, cantidad_utilizada, aprobado_cliente) VALUES ($id_orden_trabajo, $id_item, $id_proveedor, $id_almacen, ".$cantidad_reservada.", $cargado_vehiculo, $cantidad_utilizada, $aprobado_cliente)";
    //var_dump($queryItems);
    $insertAdjuntos = $this->conexion->consultaSimple($queryInsertMateriales);
    $mensajeError=$this->conexion->conectar->error;

    if($mensajeError!="") echo "<br><br>".$mensajeError."<br><br>".$queryInsertMateriales;

    if($cantidad_utilizada>0){
      $stock=new Stock();
      $stock->reducirStock($id_item,$id_proveedor,$id_almacen,$cantidad_utilizada,$tipo_movimiento="OT ID ".$id_orden_trabajo);
      //descontarStock();
    }
  }

  public function agregarTecnicosOrdenTrabajo($id_orden_trabajo,$aTecnicos){
    foreach($aTecnicos as $infoTecnicos){
      /*GUARDO EN TABLA EMPRESA*/
      $id_tecnico=$infoTecnicos["id_tecnico"];
      $id_vehiculo=$infoTecnicos["id_vehiculo"];
      $fecha_hora_desde="NOW()";
      $fecha_hora_hasta="NOW()";
      $id_tipo_turno_laboral=0;
      $cantidad_horas_estimadas=0;

      //$queryInsert = "INSERT INTO tecnicos_tareas_mantenimiento (id_orden_trabajo, id_tecnico, id_vehiculo, fecha_hora_desde, fecha_hora_hasta, id_tipo_turno_laboral, cantidad_horas_estimadas) VALUES ($id_orden_trabajo, $id_tecnico, $id_vehiculo, '$fecha_hora_desde', '$fecha_hora_hasta', '$id_tipo_turno_laboral', $cantidad_horas_estimadas)";
      $queryInsert = "INSERT INTO tecnicos_tareas_mantenimiento (id_orden_trabajo, id_tecnico, id_vehiculo, fecha_hora_desde, fecha_hora_hasta, cantidad_horas_estimadas) VALUES ($id_orden_trabajo, $id_tecnico, $id_vehiculo, '$fecha_hora_desde', '$fecha_hora_hasta', $cantidad_horas_estimadas)";
      //echo $queryInsert;
      $insertar= $this->conexion->consultaSimple($queryInsert);
      //var_dump($insertar);
      
      $mensajeError=$this->conexion->conectar->error;
      echo $mensajeError;
      if($mensajeError!=""){
        echo "<br><br>".$queryInsert;
      }
    }
  }

  public function traerDetalleOrdenTrabajo($id_orden_trabajo){

    $filtrosOT["id_orden_trabajo"]=$id_orden_trabajo;

    $detalle_orden_trabajo=$this->traerOrdenTrabajo($filtrosOT);
    $detalle_orden_trabajo=json_decode($detalle_orden_trabajo,true);
    $detalle_orden_trabajo=$detalle_orden_trabajo[0];

    $tareas_orden_trabajo=$this->traerTareasOrdenTrabajo($id_orden_trabajo);
    $tareas_orden_trabajo=json_decode($tareas_orden_trabajo,true);
    
    $materiales_orden_trabajo=$this->traerMaterialesOrdenTrabajo($id_orden_trabajo);
    $materiales_orden_trabajo=json_decode($materiales_orden_trabajo,true);

    $tecnicos_orden_trabajo=$this->traerTecnicosOrdenTrabajo($id_orden_trabajo);
    $tecnicos_orden_trabajo=json_decode($tecnicos_orden_trabajo,true);

    //var_dump($detalle_orden_trabajo);
    $filtrosCliente["id_cliente"]=$detalle_orden_trabajo["id_cliente"];
    if(isset($detalle_orden_trabajo["elemento"])){
      $id_ubicacion=$detalle_orden_trabajo["elemento"]["id_direccion_cliente"];
    }
    if(isset($detalle_orden_trabajo["ubicacion"])){
      $id_ubicacion=$detalle_orden_trabajo["ubicacion"]["id_direccion"];
    }
    $filtrosCliente["id_ubicacion"]=$id_ubicacion;

    $clientes = new Clientes();
    $contactos=$clientes->traerContactos($filtrosCliente);
    $contactos_orden_trabajo=json_decode($contactos,true);
    $contactos_orden_trabajo=$contactos_orden_trabajo["contactos"];

    $adjuntos_orden_trabajo=$this->traerAdjuntos($id_orden_trabajo);
    $adjuntos_orden_trabajo=json_decode($adjuntos_orden_trabajo,true);

    //var_dump($aOrdenesTrabajoTecnico);
    $arrayDatosIniciales['detalle_orden_trabajo'] = $detalle_orden_trabajo;
    $arrayDatosIniciales['tareas_orden_trabajo'] = $tareas_orden_trabajo;
    $arrayDatosIniciales['materiales_orden_trabajo'] = $materiales_orden_trabajo;
    $arrayDatosIniciales['tecnicos_orden_trabajo'] = $tecnicos_orden_trabajo;
    $arrayDatosIniciales['contactos_orden_trabajo'] = $contactos_orden_trabajo;
    $arrayDatosIniciales['adjuntos_orden_trabajo'] = $adjuntos_orden_trabajo;
    
    //var_dump($arrayDatosIniciales);

    return json_encode($arrayDatosIniciales);
  }

  public function updateOrdenTrabajo($id_orden_trabajo, $id_centro_costos, $fecha, $nro_orden_trabajo, $hora_desde, $hora_hasta, $id_estado_orden, $aTareas, $aTecnicos, $tipoTareas){
    //$this->id_orden_trabajo=$id_orden_trabajo;

    $queryInsert = "UPDATE ordenes_trabajo SET nro_orden_trabajo='$nro_orden_trabajo', id_centro_costos = '$id_centro_costos', fecha='$fecha', hora_desde='$hora_desde', hora_hasta='$hora_hasta', id_estado_orden='$id_estado_orden' WHERE id = $id_orden_trabajo";
    //echo $queryInsert;
    $insertar= $this->conexion->consultaSimple($queryInsert);
    $filasAfectadas=$this->conexion->conectar->affected_rows;
    $mensajeError=$this->conexion->conectar->error;

    //var_dump($mensajeError);
    echo $mensajeError;
    if($mensajeError!=""){
      echo "<br><br>".$query;
    }

    /*Eliminamos registros de la base de datos*/
    $this->eliminarTecnicosOrdenTrabajo($id_orden_trabajo);
    $this->eliminarMaterialesOrdenTrabajo($id_orden_trabajo);
    $this->eliminarTareasOrdenTrabajo($id_orden_trabajo);

    switch($tipoTareas){
      case "preventivo":
        $this->agregarTareasPreventivasOrdenTrabajo($id_orden_trabajo,$aTareas);
      break;
      case "correctivo":
        $this->agregarTareasCorrectivasOrdenTrabajo($id_orden_trabajo,$aTareas);
      break;
    }
    $this->agregarTecnicosOrdenTrabajo($id_orden_trabajo,$aTecnicos);

  }

  public function eliminarOrdenTrabajo($id_orden_trabajo){
    $this->id_orden_trabajo = $id_orden_trabajo;

    /*Eliminamos registros de la base de datos*/
    $this->eliminarAdjuntosOrdenTrabajo($id_orden_trabajo);
    $this->eliminarTecnicosOrdenTrabajo($id_orden_trabajo);
    $this->eliminarMaterialesOrdenTrabajo($id_orden_trabajo);
    $this->eliminarTareasOrdenTrabajo($id_orden_trabajo);
    
    /*Tabla ordenes_trabajo*/
    $queryDelelte = "DELETE FROM ordenes_trabajo WHERE id=$this->id_orden_trabajo";
    $delete = $this->conexion->consultaSimple($queryDelelte);

  }

  public function eliminarAdjuntosOrdenTrabajo($id_orden_trabajo){
    /*Tabla adjuntos_orden_trabajo*/
    $queryDelelte = "DELETE FROM adjuntos_orden_trabajo WHERE id_orden_trabajo=$id_orden_trabajo";
    $delete = $this->conexion->consultaSimple($queryDelelte);
  }

  public function eliminarTecnicosOrdenTrabajo($id_orden_trabajo){
    /*Tabla tecnicos_tareas_mantenimiento*/
    $queryDelelte = "DELETE FROM tecnicos_tareas_mantenimiento WHERE id_orden_trabajo=$id_orden_trabajo";
    $delete = $this->conexion->consultaSimple($queryDelelte);
    /*var_dump($queryDelelte);
    var_dump($delete);*/
  }

  public function eliminarMaterialesOrdenTrabajo($id_orden_trabajo){
    /*Tabla materiales_orden_trabajo*/
    $queryDelelte = "DELETE FROM materiales_orden_trabajo WHERE id_orden_trabajo=$id_orden_trabajo";
    $delete = $this->conexion->consultaSimple($queryDelelte);
  }
  
  public function eliminarTareasOrdenTrabajo($id_orden_trabajo){
    /*Tabla tareas_ordenes_trabajo*/
    $queryDelelte = "DELETE FROM tareas_ordenes_trabajo WHERE id_orden_trabajo=$id_orden_trabajo";
    $delete = $this->conexion->consultaSimple($queryDelelte);
  }

  public function traerTareasOrdenTrabajo($id_orden_trabajo){

    $tareasOrdenTrabajo = [];

    $queryGet = "SELECT GROUP_CONCAT(id_calendario_mantenimiento) AS id_calendario_mantenimiento,GROUP_CONCAT(id_tarea) AS id_tarea FROM tareas_ordenes_trabajo WHERE id_orden_trabajo = $id_orden_trabajo";
    $getDatos = $this->conexion->consultaRetorno($queryGet);
    $row = $getDatos->fetch_array();
    $tareasPreventivasOrdenTrabajo=$row["id_calendario_mantenimiento"];
    $tareasCorrectivasOrdenTrabajo=$row["id_tarea"];

    if($tareasPreventivasOrdenTrabajo){
      $filtros["id_mantenimiento_preventivo"]=$tareasPreventivasOrdenTrabajo;

      $MantenimientoPreventivo = new MantenimientoPreventivo();
      $tareasOrdenTrabajo=$MantenimientoPreventivo->traerMantenimientoPreventivo($filtros);
      $tareasOrdenTrabajo=json_decode($tareasOrdenTrabajo,true);
    }

    if($tareasCorrectivasOrdenTrabajo){
      $filtros["id_tarea"]=$tareasCorrectivasOrdenTrabajo;

      $Tarea = new Tarea();
      $tareasOrdenTrabajo=$Tarea->traerTareas($filtros);
      $tareasOrdenTrabajo=json_decode($tareasOrdenTrabajo,true);
    }

    //echo json_encode($tareasOrdenTrabajo);
    return json_encode($tareasOrdenTrabajo);
  }

  public function traerMaterialesOrdenTrabajo($id_orden_trabajo){

    $materialesOrdenTrabajo = [];

    $queryGet = "SELECT mot.id AS id_materiales_orden_trabajo,mot.id_item,i.item,mot.id_proveedor,p.razon_social AS proveedor,mot.id_almacen,a.almacen,cargado_vehiculo,mot.cantidad_reservada,IF(mot.cargado_vehiculo=1,'Si','No') AS cargado_vehiculo_mostrar,mot.cantidad_utilizada,aprobado_cliente,IF(mot.aprobado_cliente=1,'Si','No') AS aprobado_cliente_mostrar
    FROM materiales_orden_trabajo mot 
      INNER JOIN item i ON mot.id_item=i.id 
      INNER JOIN proveedores p ON mot.id_proveedor=p.id
      INNER JOIN almacenes a ON mot.id_almacen=a.id
    WHERE id_orden_trabajo = $id_orden_trabajo";
    //var_dump($queryGet);
    $getDatos = $this->conexion->consultaRetorno($queryGet);

    while ($row = $getDatos->fetch_array()) {
      $materialesOrdenTrabajo[] =[
        "id_materiales_orden_trabajo" =>$row["id_materiales_orden_trabajo"],
        "id_item"                     =>$row["id_item"],
        "item"                        =>$row["item"],
        "id_proveedor"                =>$row["id_proveedor"],
        "proveedor"                   =>$row["proveedor"],
        "id_almacen"                  =>$row["id_almacen"],
        "almacen"                     =>$row["almacen"],
        "cantidad_reservada"          =>$row["cantidad_reservada"],
        "cargado_vehiculo"            =>$row["cargado_vehiculo"],
        "cargado_vehiculo_mostrar"    =>$row["cargado_vehiculo_mostrar"],
        "cantidad_utilizada"          =>$row["cantidad_utilizada"],
        "aprobado_cliente"            =>$row["aprobado_cliente"],
        "aprobado_cliente_mostrar"    =>$row["aprobado_cliente_mostrar"],
      ];
    }
    //echo json_encode($materialesOrdenTrabajo);
    return json_encode($materialesOrdenTrabajo);
  }

  public function traerTecnicosOrdenTrabajo($id_orden_trabajo){

    $tecnicosOrdenTrabajo = [];

    $queryGet = "SELECT ttm.id_tecnico,t.nombre_completo AS tecnico,ttm.id_vehiculo,ttm.fecha_hora_inicio_trabajo,ttm.fecha_hora_fin_trabajo,ttm.geoposicion_inicio_trabajo 
    FROM tecnicos_tareas_mantenimiento ttm 
      INNER JOIN tecnicos t ON ttm.id_tecnico=t.id
    WHERE id_orden_trabajo = $id_orden_trabajo";
    //var_dump($queryGet);
    $getDatos = $this->conexion->consultaRetorno($queryGet);

    $vehiculo = new Vehiculo();

    while ($row = $getDatos->fetch_array()) {
      $id_vehiculo=$row["id_vehiculo"];
      $vehiculo_tecnico="";
      if($id_vehiculo!=0){
        $listaVehiculos=$vehiculo->traerVehiculos($id_vehiculo);
        $listaVehiculos=json_decode($listaVehiculos,true);
        $vehiculo_tecnico=$listaVehiculos[0]["vehiculo"];
      }
      $fecha_hora_inicio=$row["fecha_hora_inicio_trabajo"];
      $fecha_hora_fin=$row["fecha_hora_fin_trabajo"];
      $geoposicion_inicio_trabajo=$row["geoposicion_inicio_trabajo"];

      $tecnicosOrdenTrabajo[] =[
        "id_tecnico"                =>$row["id_tecnico"],
        "tecnico"                   =>$row["tecnico"],
        "id_vehiculo"               =>$id_vehiculo,
        "vehiculo"                  =>$vehiculo_tecnico,
        "fecha_hora_inicio_trabajo" =>($fecha_hora_inicio == 0) ? "" : date("d/m/Y H:i",strtotime($fecha_hora_inicio))." hs.",
        "fecha_hora_fin_trabajo"    =>($fecha_hora_fin == 0) ? "" : date("d/m/Y H:i",strtotime($fecha_hora_fin))." hs.",
        "geoposicion_inicio_trabajo"=>($geoposicion_inicio_trabajo == NULL) ? "" : $geoposicion_inicio_trabajo,
      ];
    }
    //echo json_encode($tecnicosOrdenTrabajo);
    return json_encode($tecnicosOrdenTrabajo);
  }

  public function marcarCargadoMaterial($id_orden_trabajo,$id_item,$id_proveedor,$id_almacen){
    $query = "UPDATE materiales_orden_trabajo SET cargado_vehiculo = 1
    WHERE id_orden_trabajo = $id_orden_trabajo AND id_item = $id_item AND id_proveedor = $id_proveedor AND id_almacen = $id_almacen";
    //var_dump($query);
    $update = $this->conexion->consultaSimple($query);
    
    $filasAfectadas=$this->conexion->conectar->affected_rows;
    $mensajeError=$this->conexion->conectar->error;
    //var_dump($mensajeError);
    echo $mensajeError;
    if($mensajeError!=""){
      echo "<br><br>".$query;
    }
    //echo json_encode($tecnicosOrdenTrabajo);
    //return json_encode($tecnicosOrdenTrabajo);
  }

  public function marcarAprobadoClienteMaterial($id_materiales_orden_trabajo){
    $query = "UPDATE materiales_orden_trabajo SET aprobado_cliente = 1 WHERE id = $id_materiales_orden_trabajo";
    //var_dump($query);
    $update = $this->conexion->consultaSimple($query);
    
    $filasAfectadas=$this->conexion->conectar->affected_rows;
    $mensajeError=$this->conexion->conectar->error;
    //var_dump($mensajeError);
    echo $mensajeError;
    if($mensajeError!=""){
      echo "<br><br>".$query;
    }
  }

  public function marcarAprobadoOrdenTrabajo($id_orden_trabajo){
    $query = "UPDATE ordenes_trabajo SET aprobado_cliente = 1 WHERE id = $id_orden_trabajo";
    //var_dump($query);
    $update = $this->conexion->consultaSimple($query);
    
    $filasAfectadas=$this->conexion->conectar->affected_rows;
    $mensajeError=$this->conexion->conectar->error;
    //var_dump($mensajeError);
    echo $mensajeError;
    if($mensajeError!=""){
      echo "<br><br>".$query;
    }
  }

  public function darInicioOrdenTrabajoTecnico($id_orden_trabajo,$id_tecnico){
    //cambiamos de estado la orden. Ponemos "En proceso"
    $query = "UPDATE ordenes_trabajo SET id_estado_orden = 2 WHERE id = $id_orden_trabajo";
    //var_dump($query);
    $update = $this->conexion->consultaSimple($query);
    
    $filasAfectadas=$this->conexion->conectar->affected_rows;
    $mensajeError=$this->conexion->conectar->error;
    //var_dump($mensajeError);
    echo $mensajeError;
    if($mensajeError!=""){
      echo "<br><br>".$query;
    }

    //cargamos la fecha y hora y geoposicion del inicio de trabajo
    $query = "UPDATE tecnicos_tareas_mantenimiento SET fecha_hora_inicio_trabajo = NOW(), geoposicion_inicio_trabajo='obtener geo'
    WHERE id_orden_trabajo = $id_orden_trabajo AND id_tecnico = $id_tecnico";
    //var_dump($query);
    $update = $this->conexion->consultaSimple($query);
    
    $filasAfectadas=$this->conexion->conectar->affected_rows;
    $mensajeError=$this->conexion->conectar->error;
    //var_dump($mensajeError);
    echo $mensajeError;
    if($mensajeError!=""){
      echo "<br><br>".$query;
    }
    //echo json_encode($tecnicosOrdenTrabajo);
    //return json_encode($tecnicosOrdenTrabajo);
  }

  public function finalizarOrden($id_orden_trabajo,$id_tecnico,$datosFinalizarOrden){
    //cambiamos de estado la orden. Ponemos "Finalizado"
    $query = "UPDATE ordenes_trabajo SET id_estado_orden = 3 WHERE id = $id_orden_trabajo";
    //var_dump($query);
    $update = $this->conexion->consultaSimple($query);
    
    $filasAfectadas=$this->conexion->conectar->affected_rows;
    $mensajeError=$this->conexion->conectar->error;
    //var_dump($mensajeError);
    if($mensajeError!="")echo "<br><br>".$mensajeError."<br><br>".$query;

    $query = "UPDATE calendario_mantenimiento cm 
      INNER JOIN tareas_ordenes_trabajo tot ON tot.id_calendario_mantenimiento=cm.id 
    SET id_estado = 3 
    WHERE id_orden_trabajo = $id_orden_trabajo";
    //var_dump($query);
    $update = $this->conexion->consultaSimple($query);
    
    $filasAfectadas=$this->conexion->conectar->affected_rows;
    $mensajeError=$this->conexion->conectar->error;
    //var_dump($mensajeError);
    if($mensajeError!="")echo "<br><br>".$mensajeError."<br><br>".$query;

    $query = "UPDATE tecnicos_tareas_mantenimiento SET fecha_hora_fin_trabajo = NOW() WHERE id_orden_trabajo = $id_orden_trabajo AND id_tecnico = $id_tecnico";
    //var_dump($query);
    $update = $this->conexion->consultaSimple($query);
    
    $filasAfectadas=$this->conexion->conectar->affected_rows;
    $mensajeError=$this->conexion->conectar->error;
    //var_dump($mensajeError);
    if($mensajeError!="")echo "<br><br>".$mensajeError."<br><br>".$query;

    $cantidadesUtilizadas=$datosFinalizarOrden["cantidadesUtilizadas"];
    $items=$datosFinalizarOrden["items"];
    $proveedores=$datosFinalizarOrden["proveedores"];
    $almacenes=$datosFinalizarOrden["almacenes"];
    foreach ($items as $key => $id_item) {
      $query = "UPDATE materiales_orden_trabajo SET cantidad_utilizada = '".$cantidadesUtilizadas[$key]."' WHERE id_orden_trabajo = $id_orden_trabajo AND id_item = '".$items[$key]."' AND id_proveedor = '".$proveedores[$key]."' AND id_almacen = '".$almacenes[$key]."'";
      //var_dump($query);
      $update = $this->conexion->consultaSimple($query);
      
      $filasAfectadas=$this->conexion->conectar->affected_rows;
      $mensajeError=$this->conexion->conectar->error;
      //var_dump($mensajeError);
      if($mensajeError!="")echo "<br><br>".$mensajeError."<br><br>".$query;

    }

    $queryGetItemsUtilizados = "SELECT cantidad_reservada,cantidad_utilizada,id_item,id_proveedor,id_almacen FROM materiales_orden_trabajo WHERE id_orden_trabajo = $id_orden_trabajo";
    $getItems = $this->conexion->consultaRetorno($queryGetItemsUtilizados);

    $stock=new Stock();

    while($row = $getItems->fetch_array()){
      $cantidad_utilizada=$row["cantidad_utilizada"];
      $cantidad_reponer=$row["cantidad_reservada"]-$cantidad_utilizada;
      $id_item=$row["id_item"];
      $id_proveedor=$row["id_proveedor"];
      $id_almacen=$row["id_almacen"];

      //SI CANTIDAD A REPONER ES NEGATIVO, QUIERE DECIR QUE SE UTILIZÓ MAS DE LO RESERVADO/ESTIMADO, POR LO QUE SE DESCUENTA DEL STOCK DISPONIBLE
      $queryGetIdStock = "SELECT id as id_stock FROM stock WHERE id_item = $id_item AND id_proveedor = $id_proveedor AND id_almacen = $id_almacen";
      $getIdStock = $this->conexion->consultaRetorno($queryGetIdStock);
      $rowIdStock = $getIdStock->fetch_assoc();
      $id_stock=$rowIdStock['id_stock'];

      //REPONEMOS EL STOCK SOBRANTE Y DESCONTAMOS LO UTILIZADO
      $queryReservarStock = "UPDATE stock SET cantidad_disponible = cantidad_disponible + $cantidad_reponer, cantidad_reservada = cantidad_reservada - $cantidad_utilizada WHERE id=$id_stock";
      //var_dump($queryItems);
      $insertAdjuntos = $this->conexion->consultaSimple($queryReservarStock);
      $mensajeError=$this->conexion->conectar->error;

      $movimiento_stock=new Movimientos();
      $movimiento_stock->insertarMovimientoStock($id_stock, ($cantidad_utilizada*-1), $tipo_movimiento="OT ID ".$id_orden_trabajo);
  
      if($mensajeError!="")echo "<br><br>".$mensajeError."<br><br>".$queryReservarStock;
    }
    //echo json_encode($tecnicosOrdenTrabajo);
    //return json_encode($tecnicosOrdenTrabajo);
  }

  public function traerAdjuntos($id_orden_trabajo){
    $this->id_orden_trabajo = $id_orden_trabajo;

    $queryGetAdjuntos = "SELECT aot.id AS id_adjunto, aot.archivo, u.email, aot.comentarios, aot.fecha_hora as fecha
    FROM adjuntos_orden_trabajo aot 
      JOIN usuarios as u ON aot.id_usuario = u.id
    WHERE id_orden_trabajo = $this->id_orden_trabajo";
    $getAdjuntos = $this->conexion->consultaRetorno($queryGetAdjuntos);

    $arrayAdjuntos = array();

    while($rowAdj = $getAdjuntos->fetch_array()){
      $arrayAdjuntos[]=array(
        "id_adjunto"=>$rowAdj['id_adjunto'],
        "archivo"=>$rowAdj['archivo'],
        "email"=>$rowAdj['email'],
        "comentarios"=>$rowAdj['comentarios'],
        "fecha"=>$fecha= date("d/m/Y H:m:s", strtotime($rowAdj['fecha']))
      );
    }

    return json_encode($arrayAdjuntos);

  }

  public function adjuntarArchivo($id_orden_trabajo, $file, $comentarios){
    $this->id_orden_trabajo = $id_orden_trabajo;
    $nombreImagen = $file['name'];
    $directorio = "../../admin/views/adjuntosOT/";
    $nombreFinalArchivo = $nombreImagen;
    $id_usuario = $_SESSION['rowUsers']['id_usuario'];
    $fecha= date("Y-m-d H:i:s");

    $nombreEnv = $id_orden_trabajo."_".$nombreFinalArchivo;

    move_uploaded_file($file['tmp_name'], $directorio.$id_orden_trabajo."_".$nombreFinalArchivo);

    /*ACTUALIZO ADJUNTOS*/
    $queryUpdateAdjuntos = "INSERT INTO adjuntos_orden_trabajo (id_orden_trabajo, archivo, fecha_hora, id_usuario, comentarios) VALUES($this->id_orden_trabajo, '$nombreEnv', '$fecha', $id_usuario, '$comentarios')";
    $updateAdjuntos= $this->conexion->consultaSimple($queryUpdateAdjuntos);
  }

  public function traerAgendaTecnicos($id_tecnico,$fecha){

    $query = "SELECT ttm.id_orden_trabajo,ot.id_cliente,c.razon_social,ot.hora_desde,ot.hora_hasta FROM tecnicos_tareas_mantenimiento ttm INNER JOIN ordenes_trabajo ot ON ttm.id_orden_trabajo=ot.id INNER JOIN clientes c ON ot.id_cliente=c.id WHERE ttm.id_tecnico=$id_tecnico AND ot.fecha='$fecha'";
    //echo $query;
    $get = $this->conexion->consultaRetorno($query);
    $mensajeError=$this->conexion->conectar->error;
    if($mensajeError!="") echo $mensajeError."<br><br>".$query."<br><br>";

    $array = array();

    while($row = $get->fetch_array()){
      $array[]=array(
        "id_orden_trabajo"=>$row['id_orden_trabajo'],
        "cliente"=>$row['razon_social'],
        "hora_desde"=>$hora_desde=$row['hora_desde'],
        "hora_desde_mostrar"=>substr($hora_desde,0,-3)."hs.",
        "hora_hasta"=>$hora_hasta=$row['hora_hasta'],
        "hora_hasta_mostrar"=>substr($hora_hasta,0,-3)."hs.",
      );
    }

    return json_encode($array);

  }

}

if (isset($_POST['accion'])) {
  $orden_trabajo = new OrdenTrabajo();
  switch ($_POST['accion']) {
    case 'traerDatosInicialesOrdenesTrabajo':
        $orden_trabajo->traerDatosIniciales();
    break;
    /*case 'traerOrdenTrabajoUpdate':
        echo $orden_trabajo->traerOrdenTrabajo($id_orden_trabajo);
    break;*/
    case 'addOrdenTrabajo':
      //var_dump($_POST);
      $nro_orden_trabajo="";
      $aTareas=[];
      foreach (json_decode($tareas,true) as $key => $value) { $aTareas[]=$value["data"]; }
      //foreach (json_decode($tareas,true) as $key => $value) { $aTareas[]=$value; }
      //$aTareas=json_decode($tareas,true);
      //var_dump($aTareas);
      $aTecnicos=[];
      foreach (json_decode($tecnicos,true) as $key => $value) { $aTecnicos[]=$value["data"]; }
      //var_dump($aTecnicos);

      if(!isset($tipoTareas)) $tipoTareas="preventivo";
      if(!isset($id_pedido)) $id_pedido="NULL";
      $orden_trabajo->agregarOrdenTrabajo($id_cliente, $id_pedido, $id_centro_costos, $fecha, $nro_orden_trabajo, $hora_desde, $hora_hasta, $aTareas, $aTecnicos,$tipoTareas);
    break;
    case 'updateOrdenTrabajo':
      $nro_orden_trabajo="";
      $id_estado_orden=1;//mantenemos en 1 hasta que veamos si se puede cambiar el estado desde aca
      $aTareas=[];
      foreach (json_decode($tareas,true) as $key => $value) { $aTareas[]=$value["data"]; }
      //var_dump($aTareas);
      $aTecnicos=[];
      foreach (json_decode($tecnicos,true) as $key => $value) { $aTecnicos[]=$value["data"]; }
      //var_dump($aTecnicos);

      if(!isset($tipoTareas)) $tipoTareas="preventivo";
      $orden_trabajo->updateOrdenTrabajo($id_orden_trabajo, $id_centro_costos, $fecha, $nro_orden_trabajo, $hora_desde, $hora_hasta, $id_estado_orden, $aTareas, $aTecnicos, $tipoTareas);
    break;
    case 'traerDetalleOrdenTrabajo':
      //$id_orden_trabajo = $_POST['id_orden_trabajo'];
      echo $orden_trabajo->traerDetalleOrdenTrabajo($id_orden_trabajo);
    break;
    case 'eliminarOrdenTrabajo':
      //$id_orden_trabajo = $_POST['id_orden_trabajo'];
      $orden_trabajo->eliminarOrdenTrabajo($id_orden_trabajo);
    break;
    case 'marcarOrdenTrabajoRealizada':
      //$id_orden_trabajo = $_POST['id_orden_trabajo'];
      $orden_trabajo->marcarOrdenTrabajoRealizada($id_mantenimiento);
    break;
    case 'traerAgendaTecnicos':
      echo $orden_trabajo->traerAgendaTecnicos($id_tecnico,$fecha);
    break;
  }
}else{
  if (isset($_GET['accion'])) {
    $orden_trabajo = new OrdenTrabajo();

    switch ($_GET['accion']) {
      case 'traerOrdenTrabajo':
        $filtros=[];
        if(isset($id_orden_trabajo)) $filtros["id_orden_trabajo"]=$id_orden_trabajo;
        echo $orden_trabajo->traerOrdenTrabajo($filtros);
      break;
      case 'traerOrdenesTrabajoPreventiva':
        $filtros["tipo"]="preventiva";//que NO tiene un pedido
        echo $orden_trabajo->traerOrdenTrabajo($filtros);
      break;
      case 'traerOrdenTrabajoCalendario':
        $mantenimientosPreventivo=$orden_trabajo->traerOrdenTrabajo();
        $mantenimientosPreventivo=json_decode($mantenimientosPreventivo,true);

        $mantenimientoCalendario=[];
        foreach ($mantenimientosPreventivo as $mantenimiento) {
          $id_orden_trabajo= $mantenimiento['id_orden_trabajo'];
          $descripcion_activo= $mantenimiento['descripcion_activo'];
          $fecha_hora_inicio= $mantenimiento['fecha_hora_ejecucion_desde'];
          $fecha_hora_fin= $mantenimiento['fecha_hora_ejecucion_hasta'];
          $id_estado= $mantenimiento['id_estado'];
          $estado= $mantenimiento['estado'];
          $color="";
          switch($id_estado){
            case 1:
              $color="orange";//1 = Pendiente
            break;
            case 2:
              $color="red";//1 = Cancelado
            break;
            case 3:
              $color="green";//1 = Realizado
            break;
          }
          
          $contacto_cliente= $mantenimiento["contacto_cliente"];
          $detalle= $mantenimiento['detalle'];
          $asunto = $mantenimiento['asunto'];
          
          $descripcion="<u>Contacto cliente:</u> ".$contacto_cliente."<br><u>Asunto:</u> ".$asunto."<br><u>Detalle:</u> ".$detalle;//."<br><u>Comentarios:</u> ".$comentarios;
  
          $mantenimientoCalendario[]=[
            "id"          =>$id_orden_trabajo,
            "title"       =>$descripcion_activo ?? "(Vacío)",
            //"url"       =>"verEnvioLogistica.php?id=".$row["id"],
            "start"       =>$fecha_hora_inicio,
            "end"         =>$fecha_hora_fin,
            "description" =>$descripcion,
            "estado"      =>$estado,
            "color"       =>$color,
            //"classNames"=>"bg-success border-success"
          ];
  
        }
        echo json_encode($mantenimientoCalendario);
      break;
    }
  }
}
?>