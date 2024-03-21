<?php
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
  // session isn't started
  session_start();
}
require_once('./conexion.php');
require_once('administrar_pedidos.php');
require_once('administrar_tareas.php');
require_once('administrar_almacenes.php');
require_once('administrar_ordenes.php');

extract($_REQUEST);
class Presupuestos{

  private $id_presupuesto;
  private $id_empresa;

  public function __construct(){
    $this->conexion = new Conexion();
    //var_dump($_SESSION["rowUsers"]);
    $this->id_empresa = $_SESSION["rowUsers"]["id_empresa"];
    date_default_timezone_set("America/Buenos_Aires");
  }

  public function traerDatosIniciales(){
    $datosIniciales = array();

    $cliente = new Clientes();
    $listaClientes=$cliente->traerClientes($this->id_empresa);
    $listaClientes=json_decode($listaClientes,true);

    $almacen = new Almacenes();
    $listaAlmacenes=$almacen->traerAlmacenes();
    $listaAlmacenes=json_decode($listaAlmacenes,true);

    //CENTRO DE COSTOS
    $queryCcosto = "SELECT id, nombre FROM centro_costos WHERE id_empresa = $this->id_empresa";
    $getCCostos = $this->conexion->consultaRetorno($queryCcosto);
    $centroCostos = [];
    //CARGO ARRAY CON CENTRO DE COSTOS
    while ($rowCC = $getCCostos->fetch_assoc()) {
      $centroCostos[]=array(
        'id_centro_costo'=>$rowCC['id'],
        'nombre'=>$rowCC['nombre']
      );
    }

    //$datosIniciales["clientes"] = $listaClientes;
    //$datosIniciales["centroCostos"] = $centroCostos;

    //return json_encode($datosIniciales);
    return json_encode([
      "clientes"      =>$listaClientes,
      "centroCostos"  =>$centroCostos,
      "almacenes"     =>$listaAlmacenes,
    ]);
  }

  public function traerPresupuestos($filtros=0){

    /*$filtro_presupuesto="";
    if($id_presupuesto!=0){
      $filtro_presupuesto=" AND cm.id = $id_presupuesto";
    }*/
    $filtro_presupuesto="";
    $filtro_cliente="";
    $filtro_estado="";
    $filtro_fecha="";
    if($filtros!=0){
        //var_dump($filtros);
        if(isset($filtros["id_pedido"]) and $filtros["id_pedido"]!=""){
            $filtro_presupuesto=" AND p.id IN (".$filtros["id_pedido"].")";
        }
        if(isset($filtros["id_cliente"]) and $filtros["id_cliente"]!=""){
            $filtro_cliente=" AND c.id = ".$filtros["id_cliente"];
        }
        if(isset($filtros["id_estado_pedido"]) and $filtros["id_estado_pedido"]!=""){
            $filtro_estado=" AND p.id_estado_pedido IN (".$filtros["id_estado_pedido"].")";
        }
        if(isset($filtros["fecha"]) and $filtros["fecha"]!=""){
            $filtro_fecha=" AND '".$filtros["fecha"]."' BETWEEN DATE(cm.fecha) AND DATE(cm.fecha)";
        }
    }
    
    $arrayPresupuestos = [];

    /*$queryGet = "SELECT p.id AS id_presupuesto,p.fecha_hora_alta,p.id_usuario_alta,u.email,p.id_pedido,p.id_estado_presupuesto,ep.estado,p.nro_presupuesto,p.total_cargos,p.total_materiales,p.total_gastos,p.total_movilidad,p.total_rentabilidad,p.total_precio,p.comentarios FROM presupuestos p 
      INNER JOIN usuarios u ON p.id_usuario_alta=u.id 
      INNER JOIN estados_presupuesto ep ON p.id_estado_presupuesto=ep.id
    WHERE p.id_empresa = $this->id_empresa $filtro_presupuesto $filtro_cliente $filtro_estado $filtro_fecha";*/
    $queryGet = "SELECT p.id AS id_pedido,p.fecha_hora_cotizacion,p.id_usuario_cotizacion,u.email,p.id_estado_pedido,ep.estado,p.nro_presupuesto,p.total_cargos,p.total_materiales,p.porcentaje_gastos,p.porcentaje_movilidad,p.porcentaje_rentabilidad,p.total_precio,p.comentarios FROM pedidos p 
      LEFT JOIN usuarios u ON p.id_usuario_cotizacion=u.id 
      INNER JOIN estados_pedidos ep ON p.id_estado_pedido=ep.id
    WHERE p.id_empresa = $this->id_empresa $filtro_presupuesto $filtro_cliente $filtro_estado $filtro_fecha
    ORDER BY p.fecha_hora_cotizacion DESC, p.id DESC";
    //var_dump($queryGet);
    //echo $queryGet;
    $getDatos = $this->conexion->consultaRetorno($queryGet);
    $mensajeError=$this->conexion->conectar->error;
    //echo $mensajeError;
    if($mensajeError!="") echo $mensajeError."<br><br>".$queryGet."<br><br>";

    $pedidos=new Pedidos();
    while ($row = $getDatos->fetch_array()) {
      $filtros["id_pedido"]=$row["id_pedido"];
      $pedido=$pedidos->traerPedidos($filtros);
      $pedido=json_decode($pedido);
      $pedido=$pedido[0];

      $arrayPresupuestos[] =[
        "id_pedido"                 =>$row["id_pedido"],
        "fecha_hora_alta"           =>date("d/m/Y H:i", strtotime($row["fecha_hora_cotizacion"]))."hs",
        "id_usuario_cotizacion"     =>$row["id_usuario_cotizacion"],
        "email"                     =>$row["email"],
        "pedido"                    =>$pedido,
        "id_estado_pedido"          =>$row["id_estado_pedido"],
        "estado"                    =>$row["estado"],
        "nro_presupuesto"           =>$row["nro_presupuesto"],
        "total_cargos"              =>$total_cargos=$row["total_cargos"],
        "total_cargos_mostrar"      =>"$".number_format($total_cargos,2,",","."),
        "total_materiales"          =>$total_materiales=$row["total_materiales"],
        "total_materiales_mostrar"  =>"$".number_format($total_materiales,2,",","."),
        "total_precio"              =>$total_precio=$row["total_precio"],
        "total_precio_mostrar"      =>"$".number_format($total_precio,2,",","."),
        "porcentaje_gastos"         =>$porcentaje_gastos=$row["porcentaje_gastos"],
        "total_gastos_mostrar"      =>"$".number_format($porcentaje_gastos*$total_precio/100,2,",","."),
        "porcentaje_movilidad"      =>$porcentaje_movilidad=$row["porcentaje_movilidad"],
        "total_movilidad_mostrar"   =>"$".number_format($porcentaje_movilidad*$total_precio/100,2,",","."),
        "porcentaje_rentabilidad"   =>$porcentaje_rentabilidad=$row["porcentaje_rentabilidad"],
        "total_rentabilidad_mostrar"=>"$".number_format($porcentaje_rentabilidad*$total_precio/100,2,",","."),
        "comentarios"               =>$row["comentarios"],
      ];
    }
    //echo json_encode($arrayPresupuestos);
    return json_encode($arrayPresupuestos);
  }

  public function traerMaterialesPresupuestos($id_pedido){

    $arrayMaterialesPresupuestos = [];

    $queryGet = "SELECT mm.id_item,i.item,mm.id_proveedor,p.razon_social AS proveedor,mm.id_almacen,a.almacen,mm.cantidad_estimada,um.unidad_medida,ti.tipo,ci.categoria
    FROM materiales_mantenimiento mm 
      INNER JOIN item i ON mm.id_item=i.id 
      INNER JOIN proveedores p ON mm.id_proveedor=p.id
      INNER JOIN almacenes a ON mm.id_almacen=a.id
      INNER JOIN unidades_medida um ON i.id_unidad_medida=um.id
      INNER JOIN tipos_items ti ON i.id_tipo=ti.id
      INNER JOIN categorias_item ci ON i.id_categoria=ci.id
    WHERE mm.id_calendario_mantenimiento = $id_pedido";
    //var_dump($queryGet);
    $getDatos = $this->conexion->consultaRetorno($queryGet);

    while ($row = $getDatos->fetch_array()) {
      $arrayMaterialesPresupuestos[] =[
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
    //echo json_encode($arrayMaterialesPresupuestos);
    return json_encode($arrayMaterialesPresupuestos);
  }

  public function traerAdjuntosPresupuestos($id_pedido){

    $arrayAdjuntosPresupuestos = [];

    $queryGet = "SELECT atm.id AS id_adjunto,atm.archivo,atm.fecha_hora,u.email AS usuario
    FROM adjuntos_tareas_mantenimiento atm 
      INNER JOIN usuarios u ON atm.id_usuario_alta=u.id 
    WHERE atm.id_calendario_mantenimiento = $id_pedido";
    //var_dump($queryGet);
    $getDatos = $this->conexion->consultaRetorno($queryGet);

    while ($row = $getDatos->fetch_array()) {
      $arrayAdjuntosPresupuestos[] =[
        "id_adjunto"  =>$row["id_adjunto"],
        "archivo"     =>$row["archivo"],
        "fecha_hora"  =>date("d-M-Y H:i",strtotime($row["fecha_hora"]))."hs",
        "usuario"     =>$row["usuario"],
      ];
    }
    //echo json_encode($arrayAdjuntosPresupuestos);
    return json_encode($arrayAdjuntosPresupuestos);
  }

  public function agregarPresupuesto($id_pedido, $id_estado_pedido, $porcentaje_gastos_generales, $porcentaje_movilidad, $porcentaje_rentabilidad){
    $activo = 1;
    $id_usuario_alta=$_SESSION["rowUsers"]["id_usuario"];
    $id_empresa=$this->id_empresa;
    //id_estado_pedido
    //$id_estado=2;
    $nro_presupuesto="";
    $comentarios_presupuesto="";

    //$queryInsert = "INSERT INTO presupuestos (id_empresa, fecha_hora_alta, id_usuario_alta, id_pedido, id_estado_presupuesto, total_materiales, total_cargos, total_gastos, total_movilidad, total_rentabilidad, total_precio) VALUES ('$id_empresa', NOW(), '$id_usuario_alta', '$id_pedido', '$id_estado', '$total_materiales', '$total_cargos', '$porcentaje_gastos_generales', '$porcentaje_movilidad', '$porcentaje_rentabilidad', '$total_presupuesto')";
    $queryInsert = "UPDATE pedidos SET id_estado_pedido='$id_estado_pedido', id_usuario_cotizacion='$id_usuario_alta', fecha_hora_cotizacion=NOW(), nro_presupuesto='$nro_presupuesto', porcentaje_gastos='$porcentaje_gastos_generales', porcentaje_movilidad='$porcentaje_movilidad', porcentaje_rentabilidad='$porcentaje_rentabilidad', comentarios_presupuesto='$comentarios_presupuesto' WHERE id='$id_pedido'";
    //echo $queryInsert;
    $insertar= $this->conexion->consultaSimple($queryInsert);
    //$id_presupuesto=$this->conexion->conectar->insert_id;
    
    $mensajeError=$this->conexion->conectar->error;
    //echo $mensajeError;
    if($mensajeError!="")echo $mensajeError."<br><br>".$queryInsert."<br><br>";

    $this->updateTotales($id_pedido);

  }

  public function agregarMaterialesPresupuestos($id_calendario_mantenimiento, $id_almacen, $aItems){
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

  public function agregarAdjuntosPresupuestos($id_calendario_mantenimiento, $adjuntos, $cantAdjuntos){
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
        $directorio = "../views/presupuesto/";
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

  public function traerDetallePresupuesto($id_pedido){

    $filtros["id_pedido"]=$id_pedido;
    $datos_presupuesto=$this->traerPresupuestos($filtros);
    $datos_presupuesto=json_decode($datos_presupuesto,true);
    $datos_presupuesto=$datos_presupuesto[0];

    $tareas=new Tarea();
    $tareas_presupuesto=$tareas->traerTareas($filtros);
    $tareas_presupuesto=json_decode($tareas_presupuesto,true);

    $aDetallePresupuestos=[
      "datos_presupuesto"      =>$datos_presupuesto,
      "tareas_presupuesto" =>$tareas_presupuesto,
    ];
    return json_encode($aDetallePresupuestos);

  }

  public function eliminarAdjuntos($id_adjunto, $nombre_adjunto){

    $this->id_adj = $id_adjunto;

    $queryGet = "SELECT id_calendario_mantenimiento FROM adjuntos_tareas_mantenimiento WHERE id = $this->id_adj";
    $getDatos = $this->conexion->consultaRetorno($queryGet);
    $row = $getDatos->fetch_array();
    $id_calendario_mantenimiento=$row["id_calendario_mantenimiento"];

    $queryDelAdjunto= "DELETE FROM adjuntos_tareas_mantenimiento WHERE id = $this->id_adj";
    $delAdjunto = $this->conexion->consultaSimple($queryDelAdjunto);

    $directorio = "../views/presupuesto/";

    $rutaCompleta = $directorio."adj_".$id_calendario_mantenimiento."_".$nombre_adjunto;
    
    unlink($rutaCompleta);

  }

  public function updatePresupuesto($id_presupuesto, $id_elemento_cliente, $asunto, $detalle, $prioridad, $id_contacto_cliente, $id_almacen, $aItems, $adjuntos, $cantAdjuntos){

    echo 1;

  }

  public function eliminarPresupuesto($id_pedido){

    $id_estado=1;

    $queryInsert = "UPDATE pedidos SET id_estado_pedido='$id_estado', id_usuario_cotizacion=NULL, fecha_hora_cotizacion=NULL, nro_presupuesto=NULL, porcentaje_gastos=NULL, porcentaje_movilidad=NULL, porcentaje_rentabilidad=NULL, comentarios_presupuesto=NULL, total_cargos=NULL, total_materiales=NULL, total_precio=NULL WHERE id='$id_pedido'";
    //echo $queryInsert;
    $insertar= $this->conexion->consultaSimple($queryInsert);
    //$id_presupuesto=$this->conexion->conectar->insert_id;
    
    $mensajeError=$this->conexion->conectar->error;
    //echo $mensajeError;
    if($mensajeError!="")echo $mensajeError."<br><br>".$queryInsert."<br><br>";

    $tareas=new Tarea();
    $filtros["id_pedido"]=$id_pedido;
    $aTareas=json_decode($tareas->traerTareas($filtros),true);
    //var_dump($aTareas);
    foreach($aTareas as $tarea){
      //var_dump($tarea);
      $tareas->borrarTarea($tarea["id_tarea"]);
    }

  }

  public function updateTotales($id_pedido){
    $queryGet = "SELECT p.porcentaje_gastos,p.porcentaje_movilidad,p.porcentaje_rentabilidad,SUM(t.total_materiales) AS total_materiales,SUM(t.total_cargos) AS total_cargos FROM tareas t INNER JOIN pedidos p ON t.id_pedido=p.id WHERE id_pedido='$id_pedido'";
    //var_dump($queryGet);
    $getDatos = $this->conexion->consultaRetorno($queryGet);
    $mensajeError=$this->conexion->conectar->error;
    //echo $mensajeError;
    if($mensajeError!=""){
      echo $mensajeError."<br><br>".$queryGet."<br><br>";
    }
    $row = $getDatos->fetch_array();
    $total_materiales=$row["total_materiales"];
    $total_cargos=$row["total_cargos"];
    $total_presupuesto=$total_presupuesto_aux=$total_materiales+$total_cargos;
    $total_presupuesto+=$total_presupuesto_aux*$row["porcentaje_gastos"]/100;
    $total_presupuesto+=$total_presupuesto_aux*$row["porcentaje_movilidad"]/100;
    $total_presupuesto+=$total_presupuesto_aux*$row["porcentaje_rentabilidad"]/100;

    $queryInsert = "UPDATE pedidos SET total_cargos='$total_cargos', total_materiales='$total_materiales', total_precio='$total_presupuesto' WHERE id='$id_pedido'";
    //echo $queryInsert;
    $insertar= $this->conexion->consultaSimple($queryInsert);
    //$id_presupuesto=$this->conexion->conectar->insert_id;
    
    $mensajeError=$this->conexion->conectar->error;
    //echo $mensajeError;
    if($mensajeError!=""){
      echo $mensajeError."<br><br>".$queryInsert."<br><br>";
    }
  }

  public function updateEstadoPedido($id_pedido, $id_estado){

    $fecha_hora_desde= date("Y-m-d H:i:s");
    $fecha_hora_hasta= NULL;
    $id_usuario = $_SESSION['rowUsers']['id_usuario'];

    /*PRIMERO ACTUALIZO LA ORDEN DE COMPRA*/
    $sqlUpdateOrdenCompra = "UPDATE pedidos SET id_estado_pedido = $id_estado WHERE id = $id_pedido";
    $updateOrdenCompra = $this->conexion->consultaSimple($sqlUpdateOrdenCompra);

    /*GUARDO EN TABLA LOG_ORDENES_COMPRA*/
    /*$queryInsertLogsOC = "INSERT INTO log_ordenes_compra(id_orden_compra, id_estado, fecha_hora_desde, fecha_hora_hasta, id_usuario) values($this->id_orden, $id_estado, '$fecha_hora_desde', '$fecha_hora_hasta', $id_usuario)";
    $InsertLogsOC = $this->conexion->consultaSimple($queryInsertLogsOC);*/

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

  public function marcarPresupuestosRealizada($id_mantenimiento){
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

  public function traerStockMaterialesPresupuesto($id_pedido){
    
    $arrayMaterialesPresupuestos = [];

    $queryGet = "SELECT mt.id_tarea,mt.id_item,i.item,i.id_unidad_medida,um.unidad_medida,i.imagen,mt.id_proveedor,p.razon_social AS proveedor,SUM(cantidad_estimada) AS cantidad_estimada,mt.precio_unitario,s.cantidad_disponible,s.id_almacen,a.almacen,i.id_tipo,ti.tipo,i.id_categoria, ci.categoria
    FROM materiales_tarea mt 
      LEFT JOIN stock s ON mt.id_item=s.id_item AND mt.id_proveedor=s.id_proveedor 
      LEFT JOIN almacenes a ON s.id_almacen=a.id
      INNER JOIN item i ON mt.id_item=i.id
      INNER JOIN proveedores p ON mt.id_proveedor=p.id
      INNER JOIN unidades_medida um ON i.id_unidad_medida=um.id
      INNER JOIN tipos_items ti ON i.id_tipo=ti.id
      INNER JOIN categorias_item ci ON i.id_categoria=ci.id
    WHERE 
      FIND_IN_SET(id_tarea,(SELECT GROUP_CONCAT(id SEPARATOR ',') FROM tareas WHERE id_pedido=16)) AND 
      (
        s.id_almacen=(SELECT dc.id_almacen FROM pedidos p INNER JOIN direcciones_clientes dc ON p.id_direccion_cliente=dc.id WHERE p.id=$id_pedido) or 
        s.id_almacen IS NULL
      )
    GROUP BY id_item,id_proveedor
    ";
    //id_tarea IN (SELECT GROUP_CONCAT(id SEPARATOR ',') FROM tareas WHERE id_pedido=$id_pedido)
    //WHERE atm.id_calendario_mantenimiento = $id_presupuesto
    //var_dump($queryGet);
    //echo $queryGet;
    $getDatos = $this->conexion->consultaRetorno($queryGet);

    while ($row = $getDatos->fetch_array()) {
      $cantidad_disponible=$row["cantidad_disponible"];
      $cantidad_estimada=$row["cantidad_estimada"];

      $arrayMaterialesPresupuestos[] =[
        "id_tarea"                    =>$row["id_tarea"],
        "id_item"                     =>$row["id_item"],
        "item"                        =>$row["item"],
        "id_unidad_medida"            =>$row["id_unidad_medida"],
        "unidad_medida"               =>$row["unidad_medida"],
        "id_tipo"                     =>$row["id_tipo"],
        "tipo"                        =>$row["tipo"],
        "id_categoria"                =>$row["id_categoria"],
        "categoria"                   =>$row["categoria"],
        "imagen"                      =>$row["imagen"],
        "id_proveedor"                =>$row["id_proveedor"],
        "proveedor"                   =>$row["proveedor"],
        "precio_unitario"             =>$precio_unitario=$row["precio_unitario"],
        "precio_unitario_mostrar"     =>"$".number_format($precio_unitario,2,",","."),
        "id_almacen"                  =>$row["id_almacen"],
        "almacen"                     =>$row["almacen"],
        "cantidad_disponible"         =>$cantidad_disponible,
        "cantidad_disponible_mostrar" =>number_format($cantidad_disponible,2,",","."),
        "cantidad_estimada"           =>$cantidad_estimada,
        "cantidad_estimada_mostrar"   =>number_format($cantidad_estimada,2,",","."),
        "pedir"                       =>$pedir=($cantidad_disponible<$cantidad_estimada) ? $cantidad_estimada-$cantidad_disponible : 0,
        "pedir_mostrar"               =>number_format($pedir,2,",","."),
      ];
    }
    //echo json_encode($arrayMaterialesPresupuestos);
    return json_encode($arrayMaterialesPresupuestos);
  }

  public function generarOrdenesCompra($id_pedido){
    
    $stockMateriales=$this->traerStockMaterialesPresupuesto($id_pedido);
    $stockMateriales=json_decode($stockMateriales,true);

    $aItems=[];
    foreach ($stockMateriales as $mat) {
      $id_item=$mat["id_item"];
      $pedir=$mat["pedir"];
      $precio_unitario=$mat["precio_unitario"];
      if($pedir>0){
        if(isset($aItems[$mat["id_proveedor"]])){
          $nuevo_elemento=[
            "id"=>$id_item,
            "valor"=>$pedir,//cantidad
            "precio"=>$precio_unitario
          ];
          array_push($aItems[$mat["id_proveedor"]],$nuevo_elemento);
        }else{
          $aItems[$mat["id_proveedor"]][]=[
            "id"=>$id_item,
            "valor"=>$pedir,//cantidad
            "precio"=>$precio_unitario
          ];
        }
      }
    }
    //var_dump($aItems);
    
    $filtro["id_pedido"]=$id_pedido;
    $presupuesto=$this->traerPresupuestos($filtro);
    $presupuesto=json_decode($presupuesto,true);
    $presupuesto=$presupuesto[0];
    //var_dump($presupuesto);

    $id_almacen=$presupuesto["pedido"]["id_almacen"];
    $id_centro_costos=$presupuesto["pedido"]["id_centro_costos"];
    $comentarios="";
    $id_empresa=$this->id_empresa;
    $ordenCompra=new Ordenes();
    foreach ($aItems as $id_proveedor => $items) {
      $itemsJSON=json_encode($items);
      $total=0;
      foreach ($items as $key => $item) {
        $total+=$item["valor"]*$item["precio"];
      }
      
      $ordenCompra->agregarItemsOrdenes($itemsJSON, $id_proveedor, $id_almacen, $id_centro_costos, $total, $comentarios, $id_empresa, $id_pedido);
    }

    //echo json_encode($arrayMaterialesPresupuestos);
    //return json_encode($arrayMaterialesPresupuestos);
  }

}
/*
$stock = array(
  array(
    'id_item' => '21',
    'item' => 'Bolsa de cemento',
    'imagen' => '',
    'id_proveedor' => '1',
    'proveedor' => 'Poli cuyo',
    'id_almacen' => '1',
    'almacen' => 'DEPOSITO MEC',
    'cantidad_disponible' => '-19',
    'precio' => "6498"
  ),
  array(
    'id_item' => '22',
    'item' => 'Bolsa de cal',
    'imagen' => '22_flyer-webinar-1.png',
    'id_proveedor' => '1',
    'proveedor' => 'Poli cuyo',
    'id_almacen' => '1',
    'almacen' => 'DEPOSITO MEC',
    'cantidad_disponible' => '30',
    'precio' => "6498"
  ),
  array(
    'id_item' => '18',
    'item' => 'Listones hierro',
    'imagen' => '',
    'id_proveedor' => '1',
    'proveedor' => 'Poli cuyo',
    'id_almacen' => '1',
    'almacen' => 'DEPOSITO MEC',
    'cantidad_disponible' => '-67',
    'precio' => "6498"
  ),
  array(
    'id_item' => '19',
    'item' => 'Ladrillo visto',
    'imagen' => '19_flyer-webinar-1.png',
    'id_proveedor' => '1',
    'proveedor' => 'Poli cuyo',
    'id_almacen' => '1',
    'almacen' => 'DEPOSITO MEC',
    'cantidad_disponible' => '60000',
    'precio' => "6498"
  ),
  array(
    'id_item' => '28',
    'item' => 'Ripio',
    'imagen' => '',
    'id_proveedor' => '1',
    'proveedor' => 'Poli cuyo',
    'id_almacen' => '1',
    'almacen' => 'DEPOSITO MEC',
    'cantidad_disponible' => '10',
    'precio' => "6498"
  ),
  array(
    'id_item' => '22',
    'item' => 'Bolsa de cal',
    'imagen' => '22_flyer-webinar-1.png',
    'id_proveedor' => '3',
    'proveedor' => 'AlquiloTodo',
    'id_almacen' => '2',
    'almacen' => 'almacen2',
    'cantidad_disponible' => '500',
    'precio' => "6498"
  ),
  array(
    'id_item' => '22',
    'item' => 'Bolsa de cal',
    'imagen' => '22_flyer-webinar-1.png',
    'id_proveedor' => '1',
    'proveedor' => 'Poli cuyo',
    'id_almacen' => '3',
    'almacen' => 'DEPOSITO CBA.',
    'cantidad_disponible' => '500',
    'precio' => "6498"
  ),
  array(
    'id_item' => '20',
    'item' => 'Placa de hierro',
    'imagen' => '',
    'id_proveedor' => '3',
    'proveedor' => 'AlquiloTodo',
    'id_almacen' => '3',
    'almacen' => 'DEPOSITO CBA.',
    'cantidad_disponible' => '500',
    'precio' => "6498"
  ),
  array(
    'id_item' => '20',
    'item' => 'Placa de hierro',
    'imagen' => '',
    'id_proveedor' => '3',
    'proveedor' => 'AlquiloTodo',
    'id_almacen' => '4',
    'almacen' => 'hijole',
    'cantidad_disponible' => '500',
    'precio' => "6498"
  )
);
[
  "id_mantenimiento_preventivo"=> "27",
  "id_usuario_alta"=> "14",
  "fecha_alta"=> "2022-02-07",
  "fecha_alta_mostrar"=> "07/02/2022",
  "id_activo_cliente"=> "6",
  "descripcion_activo"=> "edificio gamez blano",
  "id_direccion_cliente"=> "10",
  "direccion"=> "Av. Cordoba 5653 Esq. Bonpland",
  "asunto"=> "No se",
  "detalle"=> "Veremos que hay para hacer",
  "fecha"=> "2022-04-15",
  "fecha_mostrar"=> "15/04/2022",
  "hora_desde"=> "15:07",
  "hora_desde_mostrar"=> "15:07hs",
  "hora_hasta"=> "15:07",
  "hora_hasta_mostrar"=> "15:07hs",
  "id_estado"=> "1",
  "estado"=> "Pendiente",
  "id_contacto_cliente"=> "1",
  "id_cliente"=> "1",
  "cliente"=> "Edificio Gámez",
  "contacto_cliente"=> "Alberto González",
  "id_prioridad"=> "5",
  "prioridad"=> "Critica / 3hs."
];

$aTareas=[
  [
    "id_tarea"=>"1",
    "id_elemento"=>"10",
    "elemento"=>"Ventana grande",
    "id_activo_cliente"=> "10",
    "descripcion_activo"=> "Ventana grande",
    "id_direccion_cliente"=> "10",
    "direccion"=> "Av. Cordoba 5653 Esq. Bonpland",
    "asunto"=>"Colocar abertura",
    "detalle"=>"Ventana de alumnio, color blanco, 1x1",
    "fecha_mostrar"=> "15/04/2022",
    "hora_desde_mostrar"=> "15:07hs",
    "hora_hasta_mostrar"=> "15:07hs",
    "materiales"=>[
      [
        "cantidad"=>5,
        "item"=>[
          "id_item"=> "21",
          "item"=> "Bolsa de cemento",
          "unidad_medida"=> "Bultos",
          "categoria"=> "Materiales de construccion",
          "tipo"=> "Tipo 2",
          "id_proveedor"=> "1",
          "proveedor"=> "Poli cuyo",
          "id_almacen"=> "1",
          "almacen"=> "DEPOSITO MEC",
          "cantDisp"=> "-19",
          "cantReserv"=> "60",
          "precio_unitario"=> "$500,45",
          "precio_unitario_sin_formato"=> "500.45",
          "punto_reposicion"=> "30",
          "hash"=> "",
          "fecha"=> "17/11/2021 23:11:33",
          "imagen"=> ""
        ],
        "stock"=>[
          [
            'id_item' => '21',
            'item' => 'Bolsa de cemento',
            'imagen' => '',
            'id_proveedor' => '1',
            'proveedor' => 'Poli cuyo',
            'id_almacen' => '1',
            'almacen' => 'DEPOSITO MEC',
            'cantidad_disponible' => '-19',
            'precio' => "6498",
          ]
        ],
      ],
      [
        "cantidad"=>4,
        "item"=>[
          "id_item"=> "22",
          "item"=> "Bolsa de cal",
          "unidad_medida"=> "Bultos",
          "categoria"=> "Materiales de construccion",
          "tipo"=> "Tipo 2",
          "id_proveedor"=> "1",
          "proveedor"=> "Poli cuyo",
          "id_almacen"=> "1",
          "almacen"=> "DEPOSITO MEC",
          "cantDisp"=> "30",
          "cantReserv"=> "57",
          "precio_unitario"=> "$55,00",
          "precio_unitario_sin_formato"=> "55",
          "punto_reposicion"=> "19",
          "hash"=> "",
          "fecha"=> "17/11/2021 23:11:33",
          "imagen"=> "22_flyer-webinar-1.png"
        ],
        "stock"=>[
          [
            'id_item' => '22',
            'item' => 'Bolsa de cal',
            'imagen' => '22_flyer-webinar-1.png',
            'id_proveedor' => '1',
            'proveedor' => 'Poli cuyo',
            'id_almacen' => '3',
            'almacen' => 'DEPOSITO CBA.',
            'cantidad_disponible' => '500',
            'precio' => "6498"
          ],
          [
            'id_item' => '22',
            'item' => 'Bolsa de cal',
            'imagen' => '22_flyer-webinar-1.png',
            'id_proveedor' => '1',
            'proveedor' => 'Poli cuyo',
            'id_almacen' => '1',
            'almacen' => 'DEPOSITO MEC',
            'cantidad_disponible' => '30',
            'precio' => "6498"
          ],
        ],
      ],
      [
        "cantidad"=>2,
        "item"=>[
          "id_item"=> "18",
          "item"=> "Listones hierro",
          "unidad_medida"=> "Mts.",
          "categoria"=> "Materiales de construccion",
          "tipo"=> "Tipo 1",
          "id_proveedor"=> "1",
          "proveedor"=> "Poli cuyo",
          "id_almacen"=> "1",
          "almacen"=> "DEPOSITO MEC",
          "cantDisp"=> "-67",
          "cantReserv"=> "90",
          "precio_unitario"=> "$2,50",
          "precio_unitario_sin_formato"=> "2.5",
          "punto_reposicion"=> "21",
          "hash"=> "",
          "fecha"=> "15/12/2021 17:12:53",
          "imagen"=> ""
        ],
        "stock"=>[
          [
            'id_item' => '18',
            'item' => 'Listones hierro',
            'imagen' => '',
            'id_proveedor' => '1',
            'proveedor' => 'Poli cuyo',
            'id_almacen' => '1',
            'almacen' => 'DEPOSITO MEC',
            'cantidad_disponible' => '-67',
            'precio' => "6498"
          ],
        ],
      ],
      [
        "cantidad"=>1000,
        "item"=>[
          "id_item"=> "19",
          "item"=> "Ladrillo visto",
          "unidad_medida"=> "M2",
          "categoria"=> "Materiales de construccion",
          "tipo"=> "Tipo 3",
          "id_proveedor"=> "1",
          "proveedor"=> "Poli cuyo",
          "id_almacen"=> "1",
          "almacen"=> "DEPOSITO MEC",
          "cantDisp"=> "60000",
          "cantReserv"=> "-7500",
          "precio_unitario"=> "$3,00",
          "precio_unitario_sin_formato"=> "3",
          "punto_reposicion"=> "10",
          "hash"=> "",
          "fecha"=> "24/03/2022 12:03:31",
          "imagen"=> "19_flyer-webinar-1.png"
        ],
        "stock"=>[
          [
            'id_item' => '19',
            'item' => 'Ladrillo visto',
            'imagen' => '19_flyer-webinar-1.png',
            'id_proveedor' => '1',
            'proveedor' => 'Poli cuyo',
            'id_almacen' => '1',
            'almacen' => 'DEPOSITO MEC',
            'cantidad_disponible' => '60000',
            'precio' => "6498"
          ],
        ]
      ],
      [
        "cantidad"=>1,
        "item"=>[
          "id_item"=> "28",
          "item"=> "Ripio",
          "unidad_medida"=> "M2",
          "categoria"=> "Materiales de construccion",
          "tipo"=> "Tipo 1",
          "id_proveedor"=> "1",
          "proveedor"=> "Poli cuyo",
          "id_almacen"=> "1",
          "almacen"=> "DEPOSITO MEC",
          "cantDisp"=> "10",
          "cantReserv"=> "0",
          "precio_unitario"=> "$2.300,00",
          "precio_unitario_sin_formato"=> "2300",
          "punto_reposicion"=> "50",
          "hash"=> "",
          "fecha"=> "24/03/2022 12:03:31",
          "imagen"=> ""
        ],
        "stock"=>[
          [
            'id_item' => '28',
            'item' => 'Ripio',
            'imagen' => '',
            'id_proveedor' => '1',
            'proveedor' => 'Poli cuyo',
            'id_almacen' => '1',
            'almacen' => 'DEPOSITO MEC',
            'cantidad_disponible' => '10',
            'precio' => "6498"
          ],
        ]
      ]
    ],
    "cargos"=>[
      [
        "id_cargo"=>"1",
        "cargo"=>"CEO",
        "precio_por_hora"=>"1500",
        "cantidad_personas"=>"1",
        "jornadas"=>"1",
        "horas_por_jornada"=>"2"
      ],
      [
        "id_cargo"=>"2",
        "cargo"=>"Operario",
        "precio_por_hora"=>"1500",
        "cantidad_personas"=>"2",
        "jornadas"=>"1",
        "horas_por_jornada"=>"8"
      ],
      [
        "id_cargo"=>"5",
        "cargo"=>"Especialista",
        "precio_por_hora"=>"1500",
        "cantidad_personas"=>"1",
        "jornadas"=>"1",
        "horas_por_jornada"=>"4"
      ],
    ]
  ],[
    "id_tarea"=>"2",
    "id_elemento"=>"11",
    "elemento"=>"Muro perimetral",
    "id_activo_cliente"=> "11",
    "descripcion_activo"=> "Muro perimetral",
    "id_direccion_cliente"=> "1",
    "direccion"=> "Sarmiento 1919",
    "asunto"=>"Extension muro hacia el norte",
    "detalle"=>"Muro de ladrillo visto, 2 x 23.5 mts",
    "fecha_mostrar"=> "15/04/2022",
    "hora_desde_mostrar"=> "15:07hs",
    "hora_hasta_mostrar"=> "15:07hs",
    "materiales"=>[
      [
        "cantidad"=>15,
        "item"=>[
          "id_item"=> "21",
          "item"=> "Bolsa de cemento",
          "unidad_medida"=> "Bultos",
          "categoria"=> "Materiales de construccion",
          "tipo"=> "Tipo 2",
          "id_proveedor"=> "1",
          "proveedor"=> "Poli cuyo",
          "id_almacen"=> "1",
          "almacen"=> "DEPOSITO MEC",
          "cantDisp"=> "-19",
          "cantReserv"=> "60",
          "precio_unitario"=> "$500,45",
          "precio_unitario_sin_formato"=> "500.45",
          "punto_reposicion"=> "30",
          "hash"=> "",
          "fecha"=> "17/11/2021 23:11:33",
          "imagen"=> ""
        ],
        "stock"=>[
          [
            'id_item' => '21',
            'item' => 'Bolsa de cemento',
            'imagen' => '',
            'id_proveedor' => '1',
            'proveedor' => 'Poli cuyo',
            'id_almacen' => '1',
            'almacen' => 'DEPOSITO MEC',
            'cantidad_disponible' => '-19',
            'precio' => "6498",
          ]
        ],
      ],
      [
        "cantidad"=>12,
        "item"=>[
          "id_item"=> "22",
          "item"=> "Bolsa de cal",
          "unidad_medida"=> "Bultos",
          "categoria"=> "Materiales de construccion",
          "tipo"=> "Tipo 2",
          "id_proveedor"=> "1",
          "proveedor"=> "Poli cuyo",
          "id_almacen"=> "1",
          "almacen"=> "DEPOSITO MEC",
          "cantDisp"=> "30",
          "cantReserv"=> "57",
          "precio_unitario"=> "$55,00",
          "precio_unitario_sin_formato"=> "55",
          "punto_reposicion"=> "19",
          "hash"=> "",
          "fecha"=> "17/11/2021 23:11:33",
          "imagen"=> "22_flyer-webinar-1.png"
        ],
        "stock"=>[
          [
            'id_item' => '22',
            'item' => 'Bolsa de cal',
            'imagen' => '22_flyer-webinar-1.png',
            'id_proveedor' => '1',
            'proveedor' => 'Poli cuyo',
            'id_almacen' => '3',
            'almacen' => 'DEPOSITO CBA.',
            'cantidad_disponible' => '500',
            'precio' => "6498"
          ],
          [
            'id_item' => '22',
            'item' => 'Bolsa de cal',
            'imagen' => '22_flyer-webinar-1.png',
            'id_proveedor' => '1',
            'proveedor' => 'Poli cuyo',
            'id_almacen' => '1',
            'almacen' => 'DEPOSITO MEC',
            'cantidad_disponible' => '30',
            'precio' => "6498"
          ],
        ],
      ],
      [
        "cantidad"=>10,
        "item"=>[
          "id_item"=> "18",
          "item"=> "Listones hierro",
          "unidad_medida"=> "Mts.",
          "categoria"=> "Materiales de construccion",
          "tipo"=> "Tipo 1",
          "id_proveedor"=> "1",
          "proveedor"=> "Poli cuyo",
          "id_almacen"=> "1",
          "almacen"=> "DEPOSITO MEC",
          "cantDisp"=> "-67",
          "cantReserv"=> "90",
          "precio_unitario"=> "$2,50",
          "precio_unitario_sin_formato"=> "2.5",
          "punto_reposicion"=> "21",
          "hash"=> "",
          "fecha"=> "15/12/2021 17:12:53",
          "imagen"=> ""
        ],
        "stock"=>[
          [
            'id_item' => '18',
            'item' => 'Listones hierro',
            'imagen' => '',
            'id_proveedor' => '1',
            'proveedor' => 'Poli cuyo',
            'id_almacen' => '1',
            'almacen' => 'DEPOSITO MEC',
            'cantidad_disponible' => '-67',
            'precio' => "6498"
          ],
        ],
      ],
      [
        "cantidad"=>5000,
        "item"=>[
          "id_item"=> "19",
          "item"=> "Ladrillo visto",
          "unidad_medida"=> "M2",
          "categoria"=> "Materiales de construccion",
          "tipo"=> "Tipo 3",
          "id_proveedor"=> "1",
          "proveedor"=> "Poli cuyo",
          "id_almacen"=> "1",
          "almacen"=> "DEPOSITO MEC",
          "cantDisp"=> "60000",
          "cantReserv"=> "-7500",
          "precio_unitario"=> "$3,00",
          "precio_unitario_sin_formato"=> "3",
          "punto_reposicion"=> "10",
          "hash"=> "",
          "fecha"=> "24/03/2022 12:03:31",
          "imagen"=> "19_flyer-webinar-1.png"
        ],
        "stock"=>[
          [
            'id_item' => '19',
            'item' => 'Ladrillo visto',
            'imagen' => '19_flyer-webinar-1.png',
            'id_proveedor' => '1',
            'proveedor' => 'Poli cuyo',
            'id_almacen' => '1',
            'almacen' => 'DEPOSITO MEC',
            'cantidad_disponible' => '60000',
            'precio' => "6498"
          ],
        ]
      ],
      [
        "cantidad"=>4,
        "item"=>[
          "id_item"=> "28",
          "item"=> "Ripio",
          "unidad_medida"=> "M2",
          "categoria"=> "Materiales de construccion",
          "tipo"=> "Tipo 1",
          "id_proveedor"=> "1",
          "proveedor"=> "Poli cuyo",
          "id_almacen"=> "1",
          "almacen"=> "DEPOSITO MEC",
          "cantDisp"=> "10",
          "cantReserv"=> "0",
          "precio_unitario"=> "$2.300,00",
          "precio_unitario_sin_formato"=> "2300",
          "punto_reposicion"=> "50",
          "hash"=> "",
          "fecha"=> "24/03/2022 12:03:31",
          "imagen"=> ""
        ],
        "stock"=>[
          [
            'id_item' => '28',
            'item' => 'Ripio',
            'imagen' => '',
            'id_proveedor' => '1',
            'proveedor' => 'Poli cuyo',
            'id_almacen' => '1',
            'almacen' => 'DEPOSITO MEC',
            'cantidad_disponible' => '10',
            'precio' => "6498"
          ],
        ]
      ]
    ],
    "cargos"=>[
      [
        "id_cargo"=>"1",
        "cargo"=>"CEO",
        "precio_por_hora"=>"1500",
        "cantidad_personas"=>"1",
        "jornadas"=>"1",
        "horas_por_jornada"=>"2"
      ],
      [
        "id_cargo"=>"2",
        "cargo"=>"Operario",
        "precio_por_hora"=>"1500",
        "cantidad_personas"=>"3",
        "jornadas"=>"2",
        "horas_por_jornada"=>"8"
      ],
    ]
  ]
];

$aPresupuestos=[
  [
    "id_presupuesto"=>1,
    "id_cliente"=> "2",
    "cliente"=> "AXXION",
    "id_direccion_cliente"=> "1",
    "direccion"=> "Av. Cordoba 5653 Esq. Bonpland",
    "id_contacto_cliente"=> "8",
    "contacto_cliente"=> "Aguas Hernan",
    "fecha"=> "2022-02-03",
    "fecha_mostrar"=> "03/02/2022",
    "total"=> "385889.40",
    "total_mostrar"=> "$ 385.889,40",
    "id_pedido"=>1,
    "id_estado"=> "1",
    "estado"=> "Borrador",
    "fecha_alta"=> "2022-02-01",
    "fecha_alta_mostrar"=> "01/02/2022",
    "id_usuario_alta"=> "12",
    "id_prioridad"=> "1",
    "prioridad"=>"Alta",
    "descripcion"=>"Arreglar canaleta",
    "numero"=>"40",
    "caducidad"=>"2022-06-30",
    "caducidad_mostrar"=>"30/06/2022",
    "tipo"=>"urgente",
    "comentarios"=>"algo mas",
    "tareas"=>$aTareas,
  ],
  [
    "id_presupuesto"=>2,
    "id_cliente"=> "2",
    "cliente"=> "AXXION",
    "id_direccion_cliente"=> "11",
    "direccion"=> "Av. La Plata 575 Esq. Alberdi",
    "id_contacto_cliente"=> "9",
    "contacto_cliente"=> "Quintero Juan",
    "fecha"=> "2022-02-03",
    "fecha_mostrar"=> "03/02/2022",
    "total"=> "200000",
    "total_mostrar"=> "$ 200.000",
    "id_pedido"=>2,
    "id_estado"=> "2",
    "estado"=> "Esperando aprobacion",
    "fecha_alta"=> "2022-02-01",
    "fecha_alta_mostrar"=> "01/02/2022",
    "id_usuario_alta"=> "12",
    "id_prioridad"=> "1",
    "prioridad"=>"Alta",
    "descripcion"=>"Arreglar canaleta",
    "numero"=>"40",
    "caducidad"=>"2022-06-30",
    "caducidad_mostrar"=>"30/06/2022",
    "tipo"=>"urgente",
    "comentarios"=>"algo mas",
    "tareas"=>$aTareas
  ],
  [
    "id_presupuesto"=>3,
    "id_cliente"=> "2",
    "cliente"=> "AXXION",
    "id_direccion_cliente"=> "10",
    "direccion"=> "Av. Cordoba 5653 Esq. Bonpland",
    "id_contacto_cliente"=> "8",
    "contacto_cliente"=> "Aguas Hernan",
    "fecha"=> "2022-02-03",
    "fecha_mostrar"=> "03/02/2022",
    "total"=> "200000",
    "total_mostrar"=> "$ 200.000",
    "id_pedido"=>3,
    "id_estado"=> "3",
    "estado"=> "Aprobado",
    "fecha_alta"=> "2022-02-01",
    "fecha_alta_mostrar"=> "01/02/2022",
    "id_usuario_alta"=> "12",
    "id_prioridad"=> "1",
    "prioridad"=>"Alta",
    "descripcion"=>"Arreglar canaleta",
    "numero"=>"40",
    "caducidad"=>"2022-06-30",
    "caducidad_mostrar"=>"30/06/2022",
    "tipo"=>"urgente",
    "comentarios"=>"algo mas",
    "tareas"=>$aTareas
  ],
  [
    "id_presupuesto"=>4,
    "id_cliente"=> "2",
    "cliente"=> "AXXION",
    "id_direccion_cliente"=> "10",
    "direccion"=> "Av. Cordoba 5653 Esq. Bonpland",
    "id_contacto_cliente"=> "8",
    "contacto_cliente"=> "Aguas Hernan",
    "fecha"=> "2022-02-03",
    "fecha_mostrar"=> "03/02/2022",
    "total"=> "200000",
    "total_mostrar"=> "$ 200.000",
    "id_pedido"=>3,
    "id_estado"=> "30",
    "estado"=> "OC completa",
    "fecha_alta"=> "2022-02-01",
    "fecha_alta_mostrar"=> "01/02/2022",
    "id_usuario_alta"=> "12",
    "id_prioridad"=> "1",
    "prioridad"=>"Alta",
    "descripcion"=>"Arreglar canaleta",
    "numero"=>"40",
    "caducidad"=>"2022-06-30",
    "caducidad_mostrar"=>"30/06/2022",
    "tipo"=>"urgente",
    "comentarios"=>"algo mas",
    "tareas"=>$aTareas
  ],
];*/

/*if(isset($id_presupuesto)){
  $aPresupuestos=$aPresupuestos[$id_presupuesto-1];
}*/

$filtros=[];
if(isset($id_pedido)) $filtros["id_pedido"]=$id_pedido;
if(isset($id_cliente)) $filtros["id_cliente"]=$id_cliente;
if(isset($id_ubicacion)) $filtros["id_ubicacion"]=$id_ubicacion;
if(isset($id_estado_pedido)) $filtros["id_estado_pedido"]=$id_estado_pedido;
if(isset($fecha)) $filtros["fecha"]=$fecha;

$presupuesto = new Presupuestos();
if (isset($accion)) {
  switch ($accion) {
    case 'traerDatosInicialesPresupuestos':
        echo $presupuesto->traerDatosIniciales();
    break;
    case 'traerPresupuestos':
      echo $presupuesto->traerPresupuestos($filtros);
    break;
    case 'cambiarEstadoPedido':
      $presupuesto->updateEstadoPedido($id_pedido, $id_estado);
    break;
    case 'traerDetallePresupuesto':
      echo $presupuesto->traerDetallePresupuesto($id_pedido);
    break;
    case 'editar_pedido_agregando_presupuesto':
      $presupuesto->agregarPresupuesto($id_pedido, $id_estado_pedido, $porcentaje_gastos_generales, $porcentaje_movilidad, $porcentaje_rentabilidad);
    break;
    case 'aprobarPresupuesto':
      $id_estado=4;//aprobado
      $presupuesto->updateEstadoPedido($id_pedido, $id_estado);
    break;
    case 'rechazarPresupuesto':
      $id_estado=5;//rechazado
      $presupuesto->updateEstadoPedido($id_pedido, $id_estado);
    break;
    case 'eliminarPresupuesto':
      $presupuesto->eliminarPresupuesto($id_pedido);
    break;
    case 'traerStockMaterialesPresupuesto':
      echo $presupuesto->traerStockMaterialesPresupuesto($id_pedido);
    break;
    case 'generarOrdenesCompra':
      echo $presupuesto->generarOrdenesCompra($id_pedido);
    break;
    case 'traerTareasCargosSinProgramar':
      $tareas=new Tarea();
      echo $tareas->traerTareasCargosSinProgramar($id_pedido);
      //echo json_encode($aTareas);
    break;
    case 'traerPresupuestosAprobados':
      echo json_encode([$aPresupuestos[2],$aPresupuestos[3]]);
    break;
    case 'traerOrdenesTrabajo':
      /*$mantenimientosPreventivo=$presupuesto->traerPresupuestos();
      $mantenimientosPreventivo=json_decode($mantenimientosPreventivo,true);

      $mantenimientoCalendario=[];
      foreach ($mantenimientosPreventivo as $mantenimiento) {
        $id_presupuesto= $mantenimiento['id_presupuesto'];
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
          "id"          =>$id_presupuesto,
          "title"       =>$descripcion_activo ?? "(Vacío)",
          //"url"       =>"verEnvioLogistica.php?id=".$row["id"],
          "start"       =>$fecha_hora_inicio,
          "end"         =>$fecha_hora_fin,
          "description" =>$descripcion,
          "estado"      =>$estado,
          "color"       =>$color,
          //"classNames"=>"bg-success border-success"
        ];

      }*/
      $ordenesTrabajoCalendario=[];
      echo json_encode($ordenesTrabajoCalendario);
    break;
    //case 'updatePresupuesto':
    /*case 'updatePresupuesto':
      $presupuesto->updatePresupuesto($id_presupuesto, $id_elemento_cliente, $asunto, $detalle, $prioridad, $id_contacto_cliente, $id_almacen, $aItems, $adjuntos, $cantAdjuntos);
    break;*/
    case 'traerDetallePresupuestos':
      //echo $presupuesto->traerDetallePresupuestos($id_presupuesto);
    break;
    case 'borrarAdjunto':
      //$presupuesto->eliminarAdjuntos($id_adjunto, $nombre_adjunto);
    break;
    case 'marcarPresupuestosRealizada':
      //$presupuesto->marcarPresupuestosRealizada($id_mantenimiento);
    break;
  }
}?>