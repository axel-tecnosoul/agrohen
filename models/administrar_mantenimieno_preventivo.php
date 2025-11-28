<?php
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
  // session isn't started
  session_start();
}
require_once('./conexion.php');
require_once('administrar_elementos.php');
require_once('administrar_vehiculos.php');
require_once('administrar_clientes.php');
require_once('administrar_almacenes.php');

extract($_REQUEST);
class MantenimientoPreventivo{
  private $conexion;
  private $id_mantenimiento_preventivo;
  private $id_empresa;

  public function __construct(){
    $this->conexion = new Conexion();
    //var_dump($_SESSION["rowUsers"]);
    $this->id_empresa = $_SESSION["rowUsers"]["id_empresa"];
    date_default_timezone_set("America/Buenos_Aires");
  }

  public function traerDatosIniciales(){
    $datosIniciales = array();

    $vehiculo = new Vehiculo();
    $listaVehiculos=$vehiculo->traerVehiculos();
    $listaVehiculos=json_decode($listaVehiculos,true);

    $cliente = new Clientes();
    $listaClientes=$cliente->traerClientes($this->id_empresa);
    $listaClientes=json_decode($listaClientes,true);

    $almacen = new Almacenes();
    //$listaAlmacenes=$almacen->traerAlmacenes($this->id_empresa);
    $listaAlmacenes=$almacen->traerAlmacenes();
    $listaAlmacenes=json_decode($listaAlmacenes,true);
    //var_dump($listaAlmacenes);

    /*PRIORIDADES*/
    $prioridades=[];
    $query = "SELECT id as id_prioridad, prioridad FROM prioridades_tareas";
    $get = $this->conexion->consultaRetorno($query);
    while ($row= $get->fetch_array()) {
      $prioridades[] = array(
        'id_prioridad' => $row['id_prioridad'],
        'prioridad' =>$row['prioridad']
      );
    }

    $datosIniciales["vehiculos"] = $listaVehiculos;
    $datosIniciales["clientes"] = $listaClientes;
    $datosIniciales["almacenes"] = $listaAlmacenes;
    $datosIniciales["prioridades"] = $prioridades;

    echo json_encode($datosIniciales);
  }

  public function traerMantenimientoPreventivo($filtros=0){

    /*$filtro_mantenimiento_preventivo="";
    if($id_mantenimiento_preventivo!=0){
      $filtro_mantenimiento_preventivo=" AND cm.id = $id_mantenimiento_preventivo";
    }*/
    $filtro_mantenimiento_preventivo="";
    $filtro_cliente="";
    $filtro_ubicacion="";
    $filtro_estado="";
    $filtro_fecha="";
    $filtro_pedido="";
    if($filtros!=0){
        //var_dump($filtros);
        if(isset($filtros["id_mantenimiento_preventivo"]) and $filtros["id_mantenimiento_preventivo"]!=""){
            $filtro_mantenimiento_preventivo=" AND cm.id IN (".$filtros["id_mantenimiento_preventivo"].")";
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
            $filtro_pedido=" AND cm.id_pedido IN (".$filtros["id_pedido"].")";
        }
    }
    
    $arrayMantenimientoPreventivo = [];

    $queryGet = "SELECT cm.id AS id_mantenimiento_preventivo,cm.id_usuario_alta,cm.fecha_alta,cm.id_activo_cliente,ac.descripcion AS descripcion_activo,dc.id AS id_direccion_cliente,dc.direccion,cm.asunto,cm.detalle,cm.fecha,cm.hora_desde,cm.hora_hasta,cm.id_estado,etm.estado,cm.id_contacto_cliente,cc.nombre_completo AS contacto_cliente,c.id AS id_cliente,c.razon_social AS cliente, cm.id_prioridad, pt.prioridad
    FROM calendario_mantenimiento cm 
    INNER JOIN activos_cliente ac ON cm.id_activo_cliente=ac.id 
    INNER JOIN direcciones_clientes dc ON ac.id_direccion_cliente=dc.id
    INNER JOIN estados_tareas_mantenimiento etm ON cm.id_estado=etm.id 
    INNER JOIN contactos_clientes cc ON cm.id_contacto_cliente=cc.id 
    INNER JOIN clientes c ON cc.id_cliente=c.id
    INNER JOIN prioridades_tareas pt ON cm.id_prioridad=pt.id
    WHERE c.id_empresa = $this->id_empresa $filtro_mantenimiento_preventivo $filtro_cliente $filtro_ubicacion $filtro_estado $filtro_fecha $filtro_pedido";
    //var_dump($queryGet);
    $getDatos = $this->conexion->consultaRetorno($queryGet);

    $elementos=new Elemento();
    while ($row = $getDatos->fetch_array()) {
      $fecha_alta=$row["fecha_alta"];
      $fecha=$row["fecha"];
      $hora_desde=date("H:i", strtotime($row["hora_desde"]));
      $hora_hasta=date("H:i", strtotime($row["hora_hasta"]));

      $filtros["id_elemento"]=$row["id_activo_cliente"];
      $elemento=$elementos->traerElementos($filtros);
      $elemento=json_decode($elemento);
      $elemento=$elemento[0];

      $arrayMantenimientoPreventivo[] =[
        "id_mantenimiento_preventivo"       =>$row["id_mantenimiento_preventivo"],
        "id_usuario_alta"                   =>$row["id_usuario_alta"],
        "fecha_alta"                        =>($fecha_alta == 0) ? "" : $fecha_alta,
        "fecha_alta_mostrar"                =>($fecha_alta == 0) ? "" : date("d/m/Y", strtotime($fecha_alta)),
        "id_activo_cliente"                 =>$row["id_activo_cliente"],
        "elemento"                          =>$elemento,//get class activos
        "descripcion_activo"                =>$row["descripcion_activo"],
        "id_direccion_cliente"              =>$row["id_direccion_cliente"],
        "direccion"                         =>$row["direccion"],
        "asunto"                            =>$row["asunto"],
        "detalle"                           =>$row["detalle"],
        "fecha"                             =>($fecha == 0) ? "" : date("Y-m-d", strtotime($fecha)),
        "fecha_mostrar"                     =>($fecha == 0) ? "" : date("d/m/Y", strtotime($fecha)),
        "hora_desde"                        =>($hora_desde == 0) ? "" : $hora_desde,
        "hora_desde_mostrar"                =>($hora_desde == 0) ? "" : $hora_desde."hs",
        "hora_hasta"                        =>($hora_hasta == 0) ? "" : $hora_hasta,
        "hora_hasta_mostrar"                =>($hora_hasta == 0) ? "" : $hora_hasta."hs",
        "id_estado"                         =>$row["id_estado"],
        "estado"                            =>$row["estado"],
        "id_contacto_cliente"               =>$row["id_contacto_cliente"],
        "id_cliente"                        =>$row["id_cliente"],
        "cliente"                           =>$row["cliente"],
        "contacto_cliente"                  =>$row["contacto_cliente"],
        "id_prioridad"                      =>$row["id_prioridad"],
        "prioridad"                         =>$row["prioridad"],
      ];
    }
    //echo json_encode($arrayMantenimientoPreventivo);
    return json_encode($arrayMantenimientoPreventivo);
  }

  public function traerMaterialesMantenimientoPreventivo($id_mantenimiento_preventivo){

    $arrayMaterialesMantenimientoPreventivo = [];

    $queryGet = "SELECT mm.id_item,i.item,mm.id_proveedor,p.razon_social AS proveedor,mm.id_almacen,a.almacen,mm.cantidad_estimada,um.unidad_medida,ti.tipo,ci.categoria
    FROM materiales_mantenimiento mm 
      INNER JOIN item i ON mm.id_item=i.id 
      INNER JOIN proveedores p ON mm.id_proveedor=p.id
      INNER JOIN almacenes a ON mm.id_almacen=a.id
      INNER JOIN unidades_medida um ON i.id_unidad_medida=um.id
      INNER JOIN tipos_items ti ON i.id_tipo=ti.id
      INNER JOIN categorias_item ci ON i.id_categoria=ci.id
    WHERE mm.id_calendario_mantenimiento = $id_mantenimiento_preventivo";
    //var_dump($queryGet);
    $getDatos = $this->conexion->consultaRetorno($queryGet);

    while ($row = $getDatos->fetch_array()) {
      $arrayMaterialesMantenimientoPreventivo[] =[
        "id_item"           =>$row["id_item"],
        "item"              =>$row["item"],
        "id_proveedor"      =>$row["id_proveedor"],
        "proveedor"         =>$row["proveedor"],
        "id_almacen"        =>$row["id_almacen"],
        "almacen"           =>$row["almacen"],
        "unidad_medida"     =>$row["unidad_medida"],
        "tipo"              =>$row["tipo"],
        "categoria"         =>$row["categoria"],
        "cantidad_estimada" =>$row["cantidad_estimada"],
      ];
    }
    //echo json_encode($arrayMaterialesMantenimientoPreventivo);
    return json_encode($arrayMaterialesMantenimientoPreventivo);
  }

  public function traerAdjuntosMantenimientoPreventivo($id_mantenimiento_preventivo){

    $arrayAdjuntosMantenimientoPreventivo = [];

    $queryGet = "SELECT atm.id AS id_adjunto,atm.archivo,atm.fecha_hora,u.email AS usuario
    FROM adjuntos_tareas_mantenimiento atm 
      INNER JOIN usuarios u ON atm.id_usuario_alta=u.id 
    WHERE atm.id_calendario_mantenimiento = $id_mantenimiento_preventivo";
    //var_dump($queryGet);
    $getDatos = $this->conexion->consultaRetorno($queryGet);

    while ($row = $getDatos->fetch_array()) {
      $arrayAdjuntosMantenimientoPreventivo[] =[
        "id_adjunto"  =>$row["id_adjunto"],
        "archivo"     =>$row["archivo"],
        "fecha_hora"  =>date("d-M-Y H:i",strtotime($row["fecha_hora"]))."hs",
        "usuario"     =>$row["usuario"],
      ];
    }
    //echo json_encode($arrayAdjuntosMantenimientoPreventivo);
    return json_encode($arrayAdjuntosMantenimientoPreventivo);
  }

  public function agregarMantenimientoPreventivo($id_elemento_cliente, $asunto, $detalle, $prioridad, $id_contacto_cliente, $fecha, $hora_desde, $hora_hasta, $frecuencia_cantidad, $frecuencia_repeticion, $frecuencia_stop, $id_almacen, $aItems, $adjuntos, $cantAdjuntos){
    $activo = 1;
    $id_usuario_alta=$_SESSION["rowUsers"]["id_usuario"];
    $id_estado=1;

    $eventos=[];
    //$eventos[]=date("Y-m-d",strtotime($fecha));

    while($fecha<=$frecuencia_stop){
      //echo "$fecha<=$frecuencia_stop<br>";
      $eventos[]=$fecha;
      $fecha=date("Y-m-d",strtotime($fecha."+$frecuencia_cantidad $frecuencia_repeticion"));
    }
    //var_dump($eventos);

    $b=1;
    $id_referencia_original=0;
    foreach($eventos as $repeticion){
      /*GUARDO EN TABLA EMPRESA*/
      $queryInsert = "INSERT INTO calendario_mantenimiento (id_usuario_alta, fecha_alta, id_activo_cliente, asunto, detalle, id_prioridad, id_contacto_cliente, fecha, hora_desde, hora_hasta, id_estado, id_referencia_original) VALUES ('$id_usuario_alta', NOW(), '$id_elemento_cliente', '$asunto', '$detalle', '$prioridad', '$id_contacto_cliente', '$repeticion', '$hora_desde', '$hora_hasta', '$id_estado',$id_referencia_original)";
      //echo $queryInsert;
      $insertar= $this->conexion->consultaSimple($queryInsert);
      //var_dump($insertar);
      
      $id_calendario_mantenimiento=$this->conexion->conectar->insert_id;
      if($b==1){
        $id_referencia_original=$id_calendario_mantenimiento;
        $b=0;
      }
      $mensajeError=$this->conexion->conectar->error;
      echo $mensajeError;
      if($mensajeError!=""){
        echo "<br><br>".$queryInsert;
      }

      $this->agregarMaterialesMantenimientoPreventivo($id_calendario_mantenimiento, $id_almacen, $aItems);
      $this->agregarAdjuntosMantenimientoPreventivo($id_calendario_mantenimiento, $adjuntos, $cantAdjuntos);

    }

    $queryUpdate = "UPDATE calendario_mantenimiento SET id_referencia_original = $id_referencia_original WHERE id = $id_referencia_original";
    //echo $queryUpdate;
    $update= $this->conexion->consultaSimple($queryUpdate);
    $mensajeError=$this->conexion->conectar->error;
  
    echo $mensajeError;
    if($mensajeError!=""){
      echo "<br><br>".$queryUpdate;
    }
    //echo 1;

  }

  public function agregarMaterialesMantenimientoPreventivo($id_calendario_mantenimiento, $id_almacen, $aItems){
    foreach($aItems as $id_item => $datos){
      $id_item=explode("-",$id_item);
      $id_item=$id_item[1];
      $cantidad_estimada=$datos["cantidad"];
      $id_proveedor=$datos["proveedor"];
      /*var_dump($id_item);
      var_dump($datos);*/

      //INSERTO DATOS EN LA TABLA MATERIALES ORDEN_COMPRA
      $queryInsertMateriales = "INSERT INTO materiales_mantenimiento (id_item, id_calendario_mantenimiento, cantidad_estimada, id_proveedor, id_almacen) VALUES ($id_item, $id_calendario_mantenimiento, $cantidad_estimada, $id_proveedor, $id_almacen)";
      $insertAdjuntos = $this->conexion->consultaSimple($queryInsertMateriales);
      $mensajeError=$this->conexion->conectar->error;
  
      echo $mensajeError;
      if($mensajeError!=""){
        echo "<br><br>".$queryInsertMateriales;
      }
    }
  }

  public function agregarAdjuntosMantenimientoPreventivo($id_calendario_mantenimiento, $adjuntos, $cantAdjuntos){
    $id_usuario_alta=$_SESSION["rowUsers"]["id_usuario"];
    //SI VIENEN ADJUNTOS LOS GUARDO.
    if ($adjuntos > 0) {
      $comentarios="";
      $etiquetas="";
      for ($i=0; $i < $cantAdjuntos; $i++) { 
        $indice = "file".$i;
        $nombreADJ = $_FILES[$indice]['name'];

        //INSERTO DATOS EN LA TABLA ADJUNTOS ORDEN_COMPRA
        $queryInsertAdjuntos = "INSERT INTO adjuntos_tareas_mantenimiento (id_calendario_mantenimiento, archivo, fecha_hora, id_usuario_alta, comentarios, etiquetas)VALUES($id_calendario_mantenimiento, '$nombreADJ',NOW(),'$id_usuario_alta','$comentarios', '$etiquetas')";
        //var_dump($queryInsertAdjuntos);
        $insertAdjuntos = $this->conexion->consultaSimple($queryInsertAdjuntos);

        $mensajeError=$this->conexion->conectar->error;
    
        echo $mensajeError;
        if($mensajeError!=""){
          echo "<br><br>".$queryInsert;
        }
        
        //INGRESO ARCHIVOS EN EL DIRECTORIO
        $directorio = "../views/mantenimiento_preventivo/";
        $nombre_archivo_guardado=$directorio."adj_".$id_calendario_mantenimiento."_".$nombreADJ;

        $imagenGuardada=move_uploaded_file($_FILES[$indice]['tmp_name'], $nombre_archivo_guardado);

        //var_dump($imagenGuardada);
        if(!$imagenGuardada){

          $queryGet = "SELECT DISTINCT(cm.id_referencia_original) AS id_referencia_original FROM adjuntos_tareas_mantenimiento atm INNER JOIN calendario_mantenimiento cm ON atm.id_calendario_mantenimiento=cm.id WHERE atm.id_calendario_mantenimiento = $id_calendario_mantenimiento";
          $getDatos = $this->conexion->consultaRetorno($queryGet);
          $row = $getDatos->fetch_array();
          //var_dump($row);
          $id_referencia_original=$row["id_referencia_original"];

          $nuevo_nombre_archivo_guardado=str_replace($id_calendario_mantenimiento,$id_referencia_original,$nombre_archivo_guardado);

          if (!copy($nuevo_nombre_archivo_guardado, $nombre_archivo_guardado)) {
              //echo "Error al copiar $fichero...\n";
          }
        }
        //$ruta_completa_imagen = $directorio.$nombreFinalArchivo;
      }
    }
  }

  public function traerDetalleMantenimientoPreventivo($id_mantenimiento_preventivo){

    $filtros["id_mantenimiento_preventivo"]=$id_mantenimiento_preventivo;
    $datos_mantenimiento_preventivo=$this->traerMantenimientoPreventivo($filtros);
    $datos_mantenimiento_preventivo=json_decode($datos_mantenimiento_preventivo,true);
    $datos_mantenimiento_preventivo=$datos_mantenimiento_preventivo[0];

    $materiales_mantenimiento_preventivo=$this->traerMaterialesMantenimientoPreventivo($id_mantenimiento_preventivo);
    $materiales_mantenimiento_preventivo=json_decode($materiales_mantenimiento_preventivo,true);

    $adjuntos_mantenimiento_preventivo=$this->traerAdjuntosMantenimientoPreventivo($id_mantenimiento_preventivo);
    $adjuntos_mantenimiento_preventivo=json_decode($adjuntos_mantenimiento_preventivo,true);

    $aDetalleMantenimientoPreventivo=[
      "datos_mantenimiento_preventivo"      =>$datos_mantenimiento_preventivo,
      "materiales_mantenimiento_preventivo" =>$materiales_mantenimiento_preventivo,
      "adjuntos_mantenimiento_preventivo"   =>$adjuntos_mantenimiento_preventivo,
    ];
    return json_encode($aDetalleMantenimientoPreventivo);

  }

  public function eliminarAdjuntos($id_adjunto, $nombre_adjunto){

    $this->id_adj = $id_adjunto;

    $queryGet = "SELECT id_calendario_mantenimiento FROM adjuntos_tareas_mantenimiento WHERE id = $this->id_adj";
    $getDatos = $this->conexion->consultaRetorno($queryGet);
    $row = $getDatos->fetch_array();
    $id_calendario_mantenimiento=$row["id_calendario_mantenimiento"];

    $queryDelAdjunto= "DELETE FROM adjuntos_tareas_mantenimiento WHERE id = $this->id_adj";
    $delAdjunto = $this->conexion->consultaSimple($queryDelAdjunto);

    $directorio = "../views/mantenimiento_preventivo/";

    $rutaCompleta = $directorio."adj_".$id_calendario_mantenimiento."_".$nombre_adjunto;
    
    unlink($rutaCompleta);

  }

  public function updateMantenimientoPreventivo($id_mantenimiento_preventivo, $id_elemento_cliente, $asunto, $detalle, $prioridad, $id_contacto_cliente, $id_almacen, $aItems, $adjuntos, $cantAdjuntos){

    //Actualizo datos de la tarea de mantenimiento preventivo
    $query = "UPDATE calendario_mantenimiento set id_activo_cliente = '$id_elemento_cliente', asunto = '$asunto', detalle = '$detalle', id_prioridad = '$prioridad', id_contacto_cliente = '$id_contacto_cliente' WHERE id_estado = 1 AND id_referencia_original = (SELECT id_referencia_original FROM calendario_mantenimiento b WHERE id = $id_mantenimiento_preventivo)";
    //echo $query;
    $update = $this->conexion->consultaSimple($query);
    
    $filasAfectadas=$this->conexion->conectar->affected_rows;
    $mensajeError=$this->conexion->conectar->error;
    //var_dump($mensajeError);
    echo $mensajeError;
    if($mensajeError!=""){
      echo "<br><br>".$query;
    }

    $queryGet = "SELECT id AS id_mantenimiento_preventivo FROM calendario_mantenimiento WHERE id_estado = 1 AND id_referencia_original = (SELECT id_referencia_original FROM calendario_mantenimiento b WHERE id = $id_mantenimiento_preventivo)";
    //var_dump($queryGet);
    $getDatos = $this->conexion->consultaRetorno($queryGet);

    while ($row = $getDatos->fetch_array()) {
      $id=$row["id_mantenimiento_preventivo"];
      $this->eliminarAdjuntosOrdenTrabajo($id);
      $this->eliminarMaterialesOrdenTrabajo($id);

      $this->agregarMaterialesMantenimientoPreventivo($id, $id_almacen, $aItems);
      $this->agregarAdjuntosMantenimientoPreventivo($id, $adjuntos, $cantAdjuntos);
    }

  }

  public function eliminarMantenimientoPreventivo($id_mantenimiento_preventivo){
    $this->id_mantenimiento_preventivo = $id_mantenimiento_preventivo;

    /*Eliminamos registros de la base de datos*/
    $this->eliminarAdjuntosOrdenTrabajo($id_mantenimiento_preventivo);
    $this->eliminarMaterialesOrdenTrabajo($id_mantenimiento_preventivo);

    /*Tabla calendario_mantenimiento*/
    $queryDelelte = "DELETE FROM calendario_mantenimiento WHERE id=$this->id_mantenimiento_preventivo";
    //echo $queryDelelte."<br><br>";
    $delete = $this->conexion->consultaSimple($queryDelelte);

  }

  public function eliminarMantenimientoPreventivoPendientes($id_mantenimiento_preventivo){

    //$this->eliminarMantenimientoPreventivo($id_mantenimiento_preventivo);

    $queryGet = "SELECT cm.id AS id_mantenimiento_preventivo FROM calendario_mantenimiento cm WHERE id_estado = 1 AND cm.id_referencia_original = (SELECT id_referencia_original FROM calendario_mantenimiento b WHERE id = $id_mantenimiento_preventivo)";
    //var_dump($queryGet);
    $getDatos = $this->conexion->consultaRetorno($queryGet);

    while ($row = $getDatos->fetch_array()) {
      $this->eliminarMantenimientoPreventivo($row["id_mantenimiento_preventivo"]);
    }

  }

  public function eliminarAdjuntosOrdenTrabajo($id_calendario_mantenimiento){
    /*Tabla adjuntos_tareas_mantenimiento*/
    $queryDelelte = "DELETE FROM adjuntos_tareas_mantenimiento WHERE id_calendario_mantenimiento=$id_calendario_mantenimiento";
    $delete = $this->conexion->consultaSimple($queryDelelte);
  }

  public function eliminarMaterialesOrdenTrabajo($id_calendario_mantenimiento){
    /*Tabla materiales_mantenimiento*/
    $queryDelelte = "DELETE FROM materiales_mantenimiento WHERE id_calendario_mantenimiento=$id_calendario_mantenimiento";
    $delete = $this->conexion->consultaSimple($queryDelelte);
    /*var_dump($queryDelelte);
    var_dump($delete);*/
  }

  public function marcarMantenimientoPreventivoRealizada($id_mantenimiento){
    $id_usuario_ultima_actualizacion=$_SESSION["rowUsers"]["id_usuario"];

    $queryMarcarCompleta = "UPDATE mantenimientos_vehiculares set realizado = 1
    WHERE id = $id_mantenimiento";
    //echo $queryMarcarCompleta;
    $marcarCompleta = $this->conexion->consultaSimple($queryMarcarCompleta);

    /*BUSCO EL ID DEL VEHICULO CREADO PARA GUARDAR EL HISTORIAL DE TECNICOS*/
    $queryGetRealizadoMantenimiento = "SELECT realizado FROM mantenimientos_vehiculares 
      WHERE id_mantenimiento = '$id_mantenimiento'";
    $getRealizadoMantenimiento = $this->conexion->consultaRetorno($queryGetRealizadoMantenimiento);
    $realizado=0;
    if ($getRealizadoMantenimiento->num_rows > 0 ) {
      $idRow = $getRealizadoMantenimiento->fetch_assoc();
      $realizado = $idRow['realizado'];
    }

    echo $realizado;
  }

}

if (isset($_POST['accion'])) {
  $mantenimiento_preventivo = new MantenimientoPreventivo();
  switch ($_POST['accion']) {
    case 'traerDatosInicialesMantenimientoPreventivo':
        $mantenimiento_preventivo->traerDatosIniciales();
    break;
    case 'addMantenimientoPreventivo':
      //var_dump($_FILES);
      if(isset($_FILES['file0'])) {
        $adjuntos = 1;
      }else{
        $adjuntos = 0;
      }
      $aItems=json_decode($itemsJSON,true);

      $mantenimiento_preventivo->agregarMantenimientoPreventivo($id_elemento_cliente, $asunto, $detalle, $prioridad, $id_contacto_cliente, $fecha, $hora_desde, $hora_hasta, $frecuencia_cantidad, $frecuencia_repeticion, $frecuencia_stop, $id_almacen, $aItems, $adjuntos, $cantAdjuntos);
    break;
    case 'updateMantenimientoPreventivo':
      if(isset($_FILES['file0'])) {
        $adjuntos = 1;
      }else{
        $adjuntos = 0;
      }
      $aItems=json_decode($itemsJSON,true);

      $mantenimiento_preventivo->updateMantenimientoPreventivo($id_mantenimiento_preventivo, $id_elemento_cliente, $asunto, $detalle, $prioridad, $id_contacto_cliente, $id_almacen, $aItems, $adjuntos, $cantAdjuntos);
    break;
    case 'traerDetalleMantenimientoPreventivo':
      //$id_mantenimiento_preventivo = $_POST['id_mantenimiento_preventivo'];
      echo $mantenimiento_preventivo->traerDetalleMantenimientoPreventivo($id_mantenimiento_preventivo);
    break;
    case 'borrarAdjunto':
      $mantenimiento_preventivo->eliminarAdjuntos($id_adjunto, $nombre_adjunto);
    break;
    case 'eliminarMantenimientoPreventivoIndividual':
      //$id_mantenimiento_preventivo = $_POST['id_mantenimiento_preventivo'];
      $mantenimiento_preventivo->eliminarMantenimientoPreventivo($id_mantenimiento_preventivo);
    break;
    case 'eliminarMantenimientoPreventivoPendientes':
      //$id_mantenimiento_preventivo = $_POST['id_mantenimiento_preventivo'];
      $mantenimiento_preventivo->eliminarMantenimientoPreventivoPendientes($id_mantenimiento_preventivo);
    break;
    case 'marcarMantenimientoPreventivoRealizada':
      //$id_mantenimiento_preventivo = $_POST['id_mantenimiento_preventivo'];
      $mantenimiento_preventivo->marcarMantenimientoPreventivoRealizada($id_mantenimiento);
    break;
  }
}else{
  if (isset($_GET['accion'])) {
    $mantenimiento_preventivo = new MantenimientoPreventivo();

    switch ($_GET['accion']) {
      case 'traerMantenimientoPreventivo':
        $filtros=[];
        if(isset($id_mantenimiento_preventivo)) $filtros["id_mantenimiento_preventivo"]=$id_mantenimiento_preventivo;
        if(isset($id_cliente)) $filtros["id_cliente"]=$id_cliente;
        if(isset($id_ubicacion)) $filtros["id_ubicacion"]=$id_ubicacion;
        if(isset($id_estado)) $filtros["id_estado"]=$id_estado;
        if(isset($fecha)) $filtros["fecha"]=$fecha;
        if(isset($id_pedido)) $filtros["id_pedido"]=$id_pedido;
        echo $mantenimiento_preventivo->traerMantenimientoPreventivo($filtros);
      break;
      case 'traerMantenimientoPreventivoCalendario':
        $mantenimientosPreventivo=$mantenimiento_preventivo->traerMantenimientoPreventivo();
        $mantenimientosPreventivo=json_decode($mantenimientosPreventivo,true);

        $mantenimientoCalendario=[];
        foreach ($mantenimientosPreventivo as $mantenimiento) {
          $id_mantenimiento_preventivo= $mantenimiento['id_mantenimiento_preventivo'];
          $descripcion_activo= $mantenimiento['descripcion_activo'];
          $fecha= $mantenimiento['fecha'];
          $fecha_hora_inicio= $fecha." ".$mantenimiento['hora_desde'];
          $fecha_hora_fin= $fecha." ".$mantenimiento['hora_hasta'];
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
            "id"          =>$id_mantenimiento_preventivo,
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