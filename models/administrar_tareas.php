<?php
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
  // session isn't started
  session_start();
}
require_once('./conexion.php');
require_once('administrar_elementos.php');
require_once('administrar_clientes.php');
require_once('administrar_presupuestos.php');
require_once('administrar_listas.php');
require_once('administrar_rubros.php');
require_once('administrar_unidades.php');

extract($_REQUEST);
class Tarea{

  private $conexion;
  private $id_tarea;
  private $id_empresa;
  private $ruta_adjuntos;

  public function __construct(){
    $this->conexion = new Conexion();
    //var_dump($_SESSION["rowUsers"]);
    $this->id_empresa = $_SESSION["rowUsers"]["id_empresa"];
    $this->ruta_adjuntos = "../views/tareas/";
    date_default_timezone_set("America/Buenos_Aires");
  }

  public function traerDatosIniciales($id_cliente){
    
    $datosIniciales = array();

    /*$cliente = new Clientes();
    $listaUbicaciones=$cliente->traerDomicilios($id_cliente);
    $listaUbicaciones=json_decode($listaUbicaciones,true);*/
    //var_dump($listaUbicaciones);

    $rubro = new Rubros();
    $listaRubros=$rubro->traerRubros();
    $listaRubros=json_decode($listaRubros,true);
    //var_dump($listaRubros);

    $unidades = new Unidades();
    $listaUnidades=$unidades->traerUnidades();
    $listaUnidades=json_decode($listaUnidades,true);
    //var_dump($listaUnidades);

    $datosIniciales=[
      //"ubicaciones" => $listaUbicaciones,
      "rubros" => $listaRubros,
      "unidades" => $listaUnidades,
    ];

    return json_encode($datosIniciales);
  }

  public function traerTareas($filtros=0){

    $filtro_tareas="";
    $filtro_cliente="";
    $filtro_ubicacion="";
    $filtro_estado="";
    $filtro_fecha="";
    $filtro_pedido="";
    $filtro_cotizacion="";
    
    if($filtros!=0){
        //var_dump($filtros);
        if(isset($filtros["id_tarea"]) and $filtros["id_tarea"]!=""){
            $filtro_tareas=" AND t.id IN (".$filtros["id_tarea"].")";
        }
        if(isset($filtros["id_cliente"]) and $filtros["id_cliente"]!=""){
            $filtro_cliente=" AND c.id = ".$filtros["id_cliente"];
        }
        if(isset($filtros["id_ubicacion"]) and $filtros["id_ubicacion"]!=""){
            $filtro_ubicacion=" AND dc.id = ".$filtros["id_ubicacion"];
        }
        if(isset($filtros["id_estado"]) and $filtros["id_estado"]!=""){
            $filtro_estado=" AND cm.id_estado = ".$filtros["id_estado"];
        }
        if(isset($filtros["fecha"]) and $filtros["fecha"]!=""){
            $filtro_fecha=" AND '".$filtros["fecha"]."' BETWEEN DATE(cm.fecha) AND DATE(cm.fecha)";
        }
        if(isset($filtros["id_pedido"]) and $filtros["id_pedido"]!=""){
            $filtro_pedido=" AND t.id_pedido IN (".$filtros["id_pedido"].")";
        }
        if(isset($filtros["id_cotizacion"]) and $filtros["id_cotizacion"]!=""){
          $filtro_cotizacion=" AND t.id_cotizacion IN (".$filtros["id_cotizacion"].")";
      }
    }
    
    $arrayTareas = [];

    $queryGet = "SELECT t.id AS id_tarea,id_pedido,t.id_usuario_alta,u.email,t.fecha_hora_alta,t.id_activo_cliente,t.id_rubro,r.rubro,t.asunto,t.detalle,t.cantidad,t.id_unidad_medida,um.unidad_medida,t.id_estado,et.estado,total_materiales,total_cargos FROM tareas t INNER JOIN usuarios u ON t.id_usuario_alta=u.id INNER JOIN estados_tareas_mantenimiento et ON t.id_estado=et.id INNER JOIN rubros r ON t.id_rubro=r.id INNER JOIN unidades_medida um ON t.id_unidad_medida=um.id
    WHERE t.id_empresa = $this->id_empresa $filtro_tareas $filtro_cliente $filtro_ubicacion $filtro_estado $filtro_fecha $filtro_pedido $filtro_cotizacion";
    $getDatos = $this->conexion->consultaRetorno($queryGet);
    $mensajeError=$this->conexion->conectar->error;
    //echo $queryGet;
    if($mensajeError!="")echo $mensajeError."<br><br>".$queryGet."<br><br>";

    $elementos=new Elemento();
    while ($row = $getDatos->fetch_array()) {
      /*$fecha=$row["fecha"];
      $hora_desde=date("H:i", strtotime($row["hora_desde"]));
      $hora_hasta=date("H:i", strtotime($row["hora_hasta"]));*/
      /*$filtros["id_elemento"]=$row["id_activo_cliente"];
      $elemento=$elementos->traerElementos($filtros);
      $elemento=json_decode($elemento);
      $elemento=$elemento[0];*/
      
      $arrayTareas[] =[
        "id_tarea"                =>$row["id_tarea"],
        "id_usuario_alta"         =>$row["id_usuario_alta"],
        "fecha_hora_alta_mostrar" =>date("d/m/Y", strtotime($row["fecha_hora_alta"])),
        //"elemento"                =>$elemento,//get class activos
        "id_rubro"                =>$row["id_rubro"],
        "rubro"                   =>$row["rubro"],
        "asunto"                  =>$row["asunto"],
        "detalle"                 =>$row["detalle"],
        "cantidad"                =>$cantidad=$row["cantidad"],
        "cantidad_mostrar"        =>number_format($cantidad,2,",","."),
        "id_unidad_medida"        =>$row["id_unidad_medida"],
        "unidad_medida"           =>$row["unidad_medida"],
        "id_estado"               =>$row["id_estado"],
        "estado"                  =>$row["estado"],
        "total_materiales"        =>$row["total_materiales"],
        "total_materiales_mostrar"=>"$".number_format($row["total_materiales"],2,",","."),
        "total_cargos"            =>$row["total_cargos"],
        "total_cargos_mostrar"    =>"$".number_format($row["total_cargos"],2,",","."),
        "total_tarea"             =>$total_tarea=$row["total_cargos"]+$row["total_materiales"],
        "total_tarea_mostrar"     =>"$".number_format($total_tarea,2,",","."),
        //"id_cliente"              =>$row["id_cliente"],
        //"cliente"                 =>$row["cliente"],
        //"contacto_cliente"        =>$row["contacto_cliente"],
        //"id_prioridad"            =>$row["id_prioridad"],
        //"prioridad"               =>$row["prioridad"],
      ];
    }
    //echo json_encode($arrayTareas);
    return json_encode($arrayTareas);
  }

  public function agregarTareaCorrectiva($id_pedido, $id_rubro, $asunto, $detalle, $cantidad,
  $id_unidad_medida, $aItems, $aCargos, $adjuntos, $cantAdjuntos){//$id_elemento_cliente
    
    $presupuestos=new Presupuestos();
    $total_materiales=0;
    foreach ($aItems as $item) {
      $total_materiales+=$item["cantidad"]*$item["precio"];
    }
    $total_cargos=0;
    foreach ($aCargos as $cargo) {
      $total_cargos+=$cargo["valor_hora"]*$cargo["cant_personas"]*$cargo["cant_jornadas"]*$cargo["horas_jornada"];
    }
    $id_tarea=$this->agregarTarea($id_pedido, $id_rubro, $asunto, $detalle, $cantidad,
    $id_unidad_medida, $total_materiales, $total_cargos);//$id_elemento_cliente
    $this->agregarMaterialesTarea($id_tarea, $aItems);
    $this->agregarCargosTarea($id_tarea, $aCargos);
    $this->agregarAdjuntosTarea($id_tarea, $adjuntos, $cantAdjuntos);
    $presupuestos->updateTotales($id_pedido);

    return $id_tarea;
  }

  public function agregarTarea($id_pedido, $id_rubro, $asunto, $detalle, $cantidad,
  $id_unidad_medida, $total_materiales, $total_cargos){//$id_elemento_cliente
    $activo = 1;
    $id_usuario_alta=$_SESSION["rowUsers"]["id_usuario"];
    $id_estado=1;

    /*GUARDO EN TABLA EMPRESA*/
    $queryInsert = "INSERT INTO tareas (id_empresa, id_pedido, id_usuario_alta, fecha_hora_alta, id_rubro, asunto, detalle, cantidad, id_unidad_medida, id_estado, total_materiales, total_cargos) VALUES ($this->id_empresa, '$id_pedido', '$id_usuario_alta', NOW(), '$id_rubro', '$asunto', '$detalle', '$cantidad', '$id_unidad_medida', $id_estado, '$total_materiales', '$total_cargos')";//$id_elemento_cliente
    //echo $queryInsert;
    $insertar= $this->conexion->consultaSimple($queryInsert);
    $id_tarea=$this->conexion->conectar->insert_id;
    
    $mensajeError=$this->conexion->conectar->error;
    echo $mensajeError;
    if($mensajeError!=""){
      echo "<br><br>".$queryInsert;
    }
    
    return $id_tarea;
  }

  public function agregarMaterialesTarea($id_tarea, $aItems){

    $queryGet = "SELECT id_almacen FROM tareas t INNER JOIN pedidos p ON t.id_pedido=p.id INNER JOIN direcciones_clientes dc ON p.id_direccion_cliente=dc.id WHERE t.id=$id_tarea";
    $getDatos = $this->conexion->consultaRetorno($queryGet);
    $mensajeError=$this->conexion->conectar->error;
    //echo $queryGet;
    if($mensajeError!="")echo $mensajeError."<br><br>".$queryGet."<br><br>";
    $row = $getDatos->fetch_array();
    $id_almacen=$row["id_almacen"];
    
    $lista_precios=new Listas();
    foreach($aItems as $items){
      $id_item=$items["id_item"];
      $cantidad=$items["cantidad"];
      $precio=$items["precio"];
      $id_proveedor=$items["id_proveedor"];

      $lista_precios->updatePrecio($id_item, $id_proveedor, $precio);
      /*var_dump($id_item);
      var_dump($items);*/

      //INSERTO DATOS EN LA TABLA MATERIALES ORDEN_COMPRA
      $queryInsertMateriales = "INSERT INTO materiales_tarea (id_tarea, id_item, id_proveedor, id_almacen, cantidad_estimada, precio_unitario) VALUES ($id_tarea, $id_item, $id_proveedor, '$id_almacen', '$cantidad', '$precio')";
      $this->conexion->consultaSimple($queryInsertMateriales);
      $mensajeError=$this->conexion->conectar->error;
      if($mensajeError!="")echo $mensajeError."<br><br>".$queryInsertMateriales."<br><br>";
    }
  }

  public function agregarCargosTarea($id_tarea, $aCargos){
    foreach($aCargos as $cargos){
      $id_cargo=$cargos["id_cargo"];
      $valor_hora=$cargos["valor_hora"];
      $cant_personas=$cargos["cant_personas"];
      $cant_jornadas=$cargos["cant_jornadas"];
      $horas_jornada=$cargos["horas_jornada"];
      /*var_dump($id_cargo);
      var_dump($cargos);*/

      //INSERTO DATOS EN LA TABLA MATERIALES ORDEN_COMPRA
      $queryInsertMateriales = "INSERT INTO cargos_tareas (id_tarea, id_cargo, valor_hora, cantidad_personas, cantidad_jornadas, horas_jornada) VALUES ($id_tarea, $id_cargo, '$valor_hora', '$cant_personas', '$cant_jornadas', '$horas_jornada')";
      $this->conexion->consultaSimple($queryInsertMateriales);
      $mensajeError=$this->conexion->conectar->error;
  
      echo $mensajeError;
      if($mensajeError!=""){
        echo "<br><br>".$queryInsertMateriales;
      }
    }
  }

  public function agregarAdjuntosTarea($id_tarea, $adjuntos, $cantAdjuntos){
    $id_usuario_alta=$_SESSION["rowUsers"]["id_usuario"];
    //SI VIENEN ADJUNTOS LOS GUARDO.
    if ($adjuntos > 0) {
      $comentarios="";
      $etiquetas="";
      for ($i=0; $i < $cantAdjuntos; $i++) { 
        $indice = "file".$i;
        $nombreADJ = $_FILES[$indice]['name'];

        //INSERTO DATOS EN LA TABLA ADJUNTOS ORDEN_COMPRA
        $queryInsertAdjuntos = "INSERT INTO adjuntos_tarea (id_tarea, adjunto, comentarios, fecha_hora_alta, id_usuario_alta)VALUES($id_tarea, '$nombreADJ','$comentarios',NOW(),'$id_usuario_alta')";
        //var_dump($queryInsertAdjuntos);
        $insertAdjuntos = $this->conexion->consultaSimple($queryInsertAdjuntos);

        $mensajeError=$this->conexion->conectar->error;
    
        echo $mensajeError;
        if($mensajeError!=""){
          echo "<br><br>".$queryInsertAdjuntos;
        }
        
        //INGRESO ARCHIVOS EN EL DIRECTORIO
        $directorio=$this->ruta_adjuntos;
        $nombre_archivo_guardado=$directorio.$id_tarea."_".$nombreADJ;

        $imagenGuardada=move_uploaded_file($_FILES[$indice]['tmp_name'], $nombre_archivo_guardado);

      }
    }
  }

  public function borrarTarea($id_tarea){
    /*Eliminamos registros de la base de datos*/
    $this->eliminarAdjuntosTarea($id_tarea);
    $this->eliminarMaterialesTarea($id_tarea);
    $this->eliminarCargosTarea($id_tarea);

    $queryGet = "SELECT id_pedido FROM tareas WHERE id = $id_tarea";
    $getDatos = $this->conexion->consultaRetorno($queryGet);
    $mensajeError=$this->conexion->conectar->error;
    //echo $mensajeError;
    if($mensajeError!="") echo $mensajeError."<br><br>".$queryGet."<br><br>";
    $row = $getDatos->fetch_array();
    $id_pedido=$row["id_pedido"];

    /*Tabla calendario_mantenimiento*/
    $queryDelelte = "DELETE FROM tareas WHERE id=$id_tarea";
    //echo $queryDelelte."<br><br>";
    $delete = $this->conexion->consultaSimple($queryDelelte);

    $presupuestos=new Presupuestos();
    $presupuestos->updateTotales($id_pedido);
  }

  public function eliminarAdjuntosTarea($id_tarea){
    $queryGet = "SELECT adjunto FROM adjuntos_tarea WHERE id_tarea = $id_tarea";
    $getDatos = $this->conexion->consultaRetorno($queryGet);
    $directorio = $this->ruta_adjuntos;
    while ($row = $getDatos->fetch_array()) {
      $rutaCompleta = $directorio.$id_tarea."_".$nombre_adjunto;
      unlink($rutaCompleta);
    }

    /*Tabla adjuntos_tarea*/
    $queryDelelte = "DELETE FROM adjuntos_tarea WHERE id_tarea=$id_tarea";
    //echo $queryDelelte."<br><br>";
    $delete = $this->conexion->consultaSimple($queryDelelte);
  }

  public function eliminarMaterialesTarea($id_tarea){
    /*Tabla materiales_tarea*/
    $queryDelelte = "DELETE FROM materiales_tarea WHERE id_tarea=$id_tarea";
    //echo $queryDelelte."<br><br>";
    $delete = $this->conexion->consultaSimple($queryDelelte);
    /*var_dump($queryDelelte);
    var_dump($delete);*/
  }

  public function eliminarCargosTarea($id_tarea){
    /*Tabla cargos_tareas*/
    $queryDelelte = "DELETE FROM cargos_tareas WHERE id_tarea=$id_tarea";
    //echo $queryDelelte."<br><br>";
    $delete = $this->conexion->consultaSimple($queryDelelte);
    /*var_dump($queryDelelte);
    var_dump($delete);*/
  }

  public function traerDetalleTarea($id_tarea){
    $filtros["id_tarea"]=$id_tarea;
    $tarea=$this->traerTareas($filtros);
    $tarea=json_decode($tarea,true);
    $tarea=$tarea[0];

    $materialesTareas=$this->traerMaterialesTareas($id_tarea);
    $cargosTareas=$this->traerCargosTareas($id_tarea);
    $adjuntosTareas=$this->traerAdjuntosTareas($id_tarea);

    return json_encode([
      "tarea"     =>$tarea,
      "materiales"=>json_decode($materialesTareas,true),
      "cargos"    =>json_decode($cargosTareas,true),
      "adjuntos"  =>json_decode($adjuntosTareas,true),
    ]);
    //return $detalleTarea
  }

  public function traerMaterialesTareas($id_tarea){
    
    $arrayMaterialesTareas = [];

    $queryGet = "SELECT mt.id,mt.id_item,i.item,mt.id_proveedor,p.razon_social AS proveedor,mt.cantidad_estimada,mt.precio_unitario,mt.cantidad_utilizada,mt.aprobado_cliente FROM materiales_tarea mt INNER JOIN item i ON mt.id_item=i.id INNER JOIN proveedores p ON mt.id_proveedor=p.id WHERE mt.id_tarea = $id_tarea";
    //var_dump($queryGet);
    $getDatos = $this->conexion->consultaRetorno($queryGet);

    while ($row = $getDatos->fetch_array()) {
      $arrayMaterialesTareas[] =[
        "id_item"                   =>$row["id_item"],
        "item"                      =>$row["item"],
        "id_proveedor"              =>$row["id_proveedor"],
        "proveedor"                 =>$row["proveedor"],
        "cantidad_estimada"         =>$row["cantidad_estimada"],
        "cantidad_estimada_mostrar" =>number_format($row["cantidad_estimada"],2,",","."),
        "precio_unitario"           =>$row["precio_unitario"],
        "precio_unitario_mostrar"   =>"$".number_format($row["precio_unitario"],2,",","."),
        "cantidad_utilizada"        =>$row["cantidad_utilizada"],
        "cantidad_utilizada_mostrar"=>number_format($row["cantidad_utilizada"],2,",","."),
        "aprobado_cliente"          =>$row["aprobado_cliente"],
        "aprobado_cliente_mostrar"  =>$row["aprobado_cliente"]==1 ? "Si":"No",
      ];
    }
    //echo json_encode($arrayMaterialesTareas);
    return json_encode($arrayMaterialesTareas);
  }

  public function traerCargosTareas($id_tarea){
    
    $arrayCargosTareas = [];

    $queryGet = "SELECT ct.id,ct.id_cargo,c.cargo,ct.valor_hora,ct.cantidad_personas,ct.cantidad_jornadas,ct.horas_jornada FROM cargos_tareas ct INNER JOIN cargos c ON ct.id_cargo=c.id WHERE ct.id_tarea = $id_tarea";
    //var_dump($queryGet);
    $getDatos = $this->conexion->consultaRetorno($queryGet);

    while ($row = $getDatos->fetch_array()) {
      $arrayCargosTareas[] =[
        "id_cargo"                  =>$row["id_cargo"],
        "cargo"                     =>$row["cargo"],
        "valor_hora"                =>$row["valor_hora"],
        "valor_hora_mostrar"        =>"$".number_format($row["valor_hora"],2,",","."),
        "cantidad_personas"         =>$row["cantidad_personas"],
        "cantidad_personas_mostrar" =>number_format($row["cantidad_personas"],2,",","."),
        "cantidad_jornadas"         =>$row["cantidad_jornadas"],
        "cantidad_jornadas_mostrar" =>number_format($row["cantidad_jornadas"],2,",","."),
        "horas_jornada"             =>$row["horas_jornada"],
        "horas_jornada_mostrar"     =>number_format($row["horas_jornada"],2,",","."),
      ];
    }
    //echo json_encode($arrayCargosTareas);
    return json_encode($arrayCargosTareas);
  }

  public function traerAdjuntosTareas($id_tarea){
    
    $arrayCargosTareas = [];

    $queryGet = "SELECT adt.id AS id_adjunto,adt.adjunto,adt.comentarios,adt.fecha_hora_alta,adt.id_usuario_alta,u.email FROM adjuntos_tarea adt INNER JOIN usuarios u ON adt.id_usuario_alta=u.id WHERE adt.id_tarea = $id_tarea";
    //var_dump($queryGet);
    $getDatos = $this->conexion->consultaRetorno($queryGet);

    while ($row = $getDatos->fetch_array()) {
      $arrayCargosTareas[] =[
        "id_adjunto"      =>$row["id_adjunto"],
        "adjunto"         =>$row["adjunto"],
        "comentarios"     =>$row["comentarios"],
        "fecha_hora_alta" =>date("d/m/Y H:m", strtotime($row["fecha_hora_alta"]))."hs",
        "email"           =>$row["email"],
      ];
    }
    //echo json_encode($arrayCargosTareas);
    return json_encode($arrayCargosTareas);
  }

  public function updateTareaCorrectiva($id_pedido, $id_tarea, $id_rubro, $asunto, $detalle, $aItems, $aCargos, $adjuntos, $cantAdjuntos){//, $id_elemento_cliente
    $total_materiales=0;
    foreach ($aItems as $item) {
      $total_materiales+=$item["cantidad"]*$item["precio"];
    }
    $total_cargos=0;
    foreach ($aCargos as $cargo) {
      $total_cargos+=$cargo["valor_hora"]*$cargo["cant_personas"]*$cargo["cant_jornadas"]*$cargo["horas_jornada"];
    }

    //Actualizo datos de la tarea de mantenimiento preventivo
    $query = "UPDATE tareas set id_rubro = '$id_rubro', asunto = '$asunto', detalle = '$detalle', total_materiales='$total_materiales', total_cargos='$total_cargos' WHERE id_estado = 1 AND id = $id_tarea";//id_activo_cliente = '$id_elemento_cliente',
    //echo $query;
    $update = $this->conexion->consultaSimple($query);
    
    $filasAfectadas=$this->conexion->conectar->affected_rows;
    $mensajeError=$this->conexion->conectar->error;
    //var_dump($mensajeError);
    echo $mensajeError;
    if($mensajeError!=""){
      echo "<br><br>".$query;
    }

    //$this->eliminarAdjuntosTarea($id_tarea);Los adjuntos se borran individualemente
    $this->eliminarMaterialesTarea($id_tarea);
    $this->eliminarCargosTarea($id_tarea);

    $this->agregarMaterialesTarea($id_tarea, $aItems);
    $this->agregarCargosTarea($id_tarea, $aCargos);
    $this->agregarAdjuntosTarea($id_tarea, $adjuntos, $cantAdjuntos);

    $presupuestos=new Presupuestos();
    $presupuestos->updateTotales($id_pedido);

    return $id_tarea;
  }

  public function eliminarAdjuntos($id_adjunto, $nombre_adjunto){

    $queryGet = "SELECT id_tarea FROM adjuntos_tarea WHERE id = $id_adjunto";
    $getDatos = $this->conexion->consultaRetorno($queryGet);
    $row = $getDatos->fetch_array();
    $id_tarea=$row["id_tarea"];

    $queryDelAdjunto= "DELETE FROM adjuntos_tarea WHERE id = $id_adjunto";
    $delAdjunto = $this->conexion->consultaSimple($queryDelAdjunto);

    $directorio = $this->ruta_adjuntos;

    $rutaCompleta = $directorio.$id_tarea."_".$nombre_adjunto;
    
    unlink($rutaCompleta);

  }

  public function traerTareasCargosSinProgramar($id_pedido){
    $aTareasCargos=[];

    $queryGet = "SELECT GROUP_CONCAT(t.id SEPARATOR ',') AS id_tarea FROM tareas_ordenes_trabajo tot RIGHT JOIN tareas t on tot.id_tarea=t.id WHERE t.id_pedido=$id_pedido AND id_orden_trabajo IS NULL";
    $getDatos = $this->conexion->consultaRetorno($queryGet);
    //var_dump($queryGet);
    $row = $getDatos->fetch_array();
    $id_tareas_sin_programar=$row["id_tarea"];
    //var_dump($id_tareas_sin_programar);

    if($id_tareas_sin_programar){
      $filtro["id_tarea"]=$id_tareas_sin_programar;
      $tareasSinProgramas=$this->traerTareas($filtro);
      
      foreach (json_decode($tareasSinProgramas,true) as $tarea) {
        $cargosTareas=$this->traerCargosTareas($tarea["id_tarea"]);
        $tarea["cargos"]=json_decode($cargosTareas,true);
        $aTareasCargos[]=$tarea;
      }
    }
    
    return json_encode($aTareasCargos);
  }

}

$filtros=[];
if(isset($id_tarea)) $filtros["id_tarea"]=$id_tarea;
if(isset($id_cliente)) $filtros["id_cliente"]=$id_cliente;
if(isset($id_ubicacion)) $filtros["id_ubicacion"]=$id_ubicacion;
if(isset($id_estado)) $filtros["id_estado"]=$id_estado;
if(isset($fecha)) $filtros["fecha"]=$fecha;
if(isset($id_pedido)) $filtros["id_pedido"]=$id_pedido;

if (isset($_POST['accion'])) {
  $tarea = new Tarea();
  switch ($_POST['accion']) {
    case 'traerDatosInicialesTarea':
        echo $tarea->traerDatosIniciales($id_cliente);
    break;
    case 'addTareaCorrectiva':
      //var_dump($_FILES);
      if(isset($_FILES['file0'])) {
        $adjuntos = 1;
      }else{
        $adjuntos = 0;
      }
      $aItems=json_decode($itemsJSON,true);
      $aCargos=json_decode($cargosJSON,true);

      echo $id_tarea=$tarea->agregarTareaCorrectiva($id_pedido, $id_rubro, $asunto, $detalle, $cantidadTarea,
      $unidadMedidaTarea, $aItems, $aCargos, $adjuntos, $cantAdjuntos);//$id_elemento_cliente
    break;
    case 'borrarTarea':
      $tarea->borrarTarea($id_tarea);
    break;
    case 'traerDetalleTarea':
      echo $tarea->traerDetalleTarea($id_tarea);
    break;
    case 'updateTareaCorrectiva':
      //var_dump($_FILES);
      if(isset($_FILES['file0'])) {
        $adjuntos = 1;
      }else{
        $adjuntos = 0;
      }
      $aItems=json_decode($itemsJSON,true);
      $aCargos=json_decode($cargosJSON,true);

      echo $tarea->updateTareaCorrectiva($id_pedido, $id_tarea, $id_rubro, $asunto, $detalle, $aItems, $aCargos, $adjuntos, $cantAdjuntos);//$id_elemento_cliente
    break;
    case 'borrarAdjunto':
      $tarea->eliminarAdjuntos($id_adjunto, $nombre_adjunto);
    break;
    case 'traerTareas':
      echo $tarea->traerTareas($filtros);
    break;
  }
}else{
  if (isset($_GET['accion'])) {
    $tarea = new Tarea();
    switch ($_GET['accion']) {
      case 'traerTareas':
        echo $tarea->traerTareas($filtros);
      break;
    }
  }
}
?>