<?php
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
  // session isn't started
  session_start();
}
require_once('./conexion.php');
require_once('administrar_clientes.php');
require_once('administrar_cargos.php');

extract($_REQUEST);
class Pedidos{

  private $id_pedido;
  private $id_empresa;

  public function __construct(){
    $this->conexion = new Conexion();
    //var_dump($_SESSION["rowUsers"]);
    $this->id_empresa = $_SESSION["rowUsers"]["id_empresa"];
    $this->ruta_adjuntos = "../views/adjuntosPedidos/";
    date_default_timezone_set("America/Buenos_Aires");
  }

  public function traerDatosIniciales(){
    $datosIniciales = array();

    $cliente = new Clientes();
    $listaClientes=$cliente->traerClientes($this->id_empresa);
    $listaClientes=json_decode($listaClientes,true);

    /*PRIORIDADES*/
    $prioridades=[];
    $query = "SELECT id as id_prioridad, prioridad, horas_caducidad FROM prioridades_pedido";
    $get = $this->conexion->consultaRetorno($query);
    $mensajeError=$this->conexion->conectar->error;
    //echo $mensajeError;
    if($mensajeError!=""){
      echo $mensajeError."<br><br>".$queryGet."<br><br>";
    }
    while ($row= $get->fetch_array()) {
      $horas_caducidad=$row['horas_caducidad'];
      if(empty($horas_caducidad)){

      }
      $prioridades[] = array(
        'id_prioridad' => $row['id_prioridad'],
        'prioridad' =>$row['prioridad'],
        'prioridad_con_horas' =>$row['prioridad']." / ".((empty($horas_caducidad)) ? "Programar" : $horas_caducidad."hs."),
        'horas_caducidad' =>$horas_caducidad,
      );
    }

    /*$almacenes = new Almacenes();
    $listaAlmacenes=$almacenes->traerAlmacenes($this->id_empresa);
    $listaAlmacenes=json_decode($listaAlmacenes,true);

    $proveedores = new Proveedores();
    $listaProveedores=$proveedores->traerProveedores($this->id_empresa);
    $listaProveedores=json_decode($listaProveedores,true);*/

    //CENTRO DE COSTOS
    /*$queryCcosto = "SELECT id as id_cc, nombre FROM centro_costos WHERE id_empresa = $this->id_empresa";
    $getCCostos = $this->conexion->consultaRetorno($queryCcosto);
    $centroCostos = [];
    //CARGO ARRAY CON CENTRO DE COSTOS
    while ($rowCC = $getCCostos->fetch_assoc()) {
      $centroCostos[]=array(
        'id_cc'=>$rowCC['id_cc'],
        'nombreCC'=>$rowCC['nombre']
      );
    }

    $categoria = new Categoria();
    $listaCategorias=$categoria->traerCategorias($this->id_empresa);
    $listaCategorias=json_decode($listaCategorias,true);

    $unidades = new Unidades();
    $listaUnidades=$unidades->traerUnidades($this->id_empresa);
    $listaUnidades=json_decode($listaUnidades,true);

    $tipoItems = new TipoItems();
    $listaTipoItems=$tipoItems->traerTipoItems($this->id_empresa);
    $listaTipoItems=json_decode($listaTipoItems,true);*/

    $cargos = new Cargos();
    $listaCargos=$cargos->traerCargos($this->id_empresa);
    $listaCargos=json_decode($listaCargos,true);

    $datosIniciales["clientes"] = $listaClientes;
    $datosIniciales["prioridades"] = $prioridades;
    /*$datosIniciales["almacenes"] = $listaAlmacenes;
    $datosIniciales["proveedores"] = $listaProveedores;
    $datosIniciales["centroCostos"] = $centroCostos;
    $datosIniciales["categorias"] = $listaCategorias;
    $datosIniciales["unidad_medida"] = $listaUnidades;
    $datosIniciales["tipo_items"] = $listaTipoItems;*/
    $datosIniciales["cargos"] = $listaCargos;

    return json_encode($datosIniciales);
  }

  public function traerPedidos($filtros=0){

    /*$filtro_pedido="";
    if($id_pedido!=0){
      $filtro_pedido=" AND cm.id = $id_pedido";
    }*/
    $filtro_pedido="";
    $filtro_cliente="";
    $filtro_ubicacion="";
    $filtro_estado="";
    $filtro_fecha="";
    $filtro_desde="";
    $filtro_hasta="";
    if($filtros!=0){
        //var_dump($filtros);
        if(isset($filtros["id_pedido"]) and $filtros["id_pedido"]!=""){
            $filtro_pedido=" AND p.id IN (".$filtros["id_pedido"].")";
        }
        if(isset($filtros["id_cliente"]) and $filtros["id_cliente"]!=""){
            $filtro_cliente=" AND dc.id_cliente = ".$filtros["id_cliente"];
        }
        if(isset($filtros["id_ubicacion"]) and $filtros["id_ubicacion"]!=""){
            $filtro_ubicacion=" AND dc.id = ".$filtros["id_ubicacion"];
        }
        if(isset($filtros["id_estado_pedido"]) and $filtros["id_estado_pedido"]!=""){
            $filtro_estado=" AND p.id_estado_pedido IN (".$filtros["id_estado_pedido"].")";
        }
        if(isset($filtros["fecha"]) and $filtros["fecha"]!=""){
            $filtro_fecha=" AND '".$filtros["fecha"]."' BETWEEN DATE(p.fecha) AND DATE(p.fecha)";
        }
        if(isset($filtros["fecha_desde"]) and $filtros["fecha_desde"]!=""){
            $filtro_desde=" AND p.fecha >= '".$filtros["fecha_desde"]."'";
        }
        if(isset($filtros["fecha_hasta"]) and $filtros["fecha_hasta"]!=""){
            $filtro_hasta=" AND p.fecha <= '".$filtros["fecha_hasta"]."'";
        }
    }
    
    $arrayPedidos = [];

    $queryGet = "SELECT p.id AS id_pedido,p.fecha,p.nro_ticket,p.asunto,p.descripcion,p.fecha_caducidad,p.comentarios,p.id_direccion_cliente,dc.direccion,p.id_contacto_cliente,cc.nombre_completo AS contacto_cliente,c.id AS id_cliente,c.razon_social AS cliente,p.id_prioridad,pp.prioridad,p.id_tipo_pedido,tp.tipo,p.id_estado_pedido,ep.estado,dc.id_centro_costos,dc.id_almacen,a.almacen
    FROM pedidos p 
      INNER JOIN prioridades_pedido pp ON p.id_prioridad=pp.id 
      INNER JOIN tipos_pedidos tp ON p.id_tipo_pedido=tp.id
      INNER JOIN direcciones_clientes dc ON p.id_direccion_cliente=dc.id
      LEFT JOIN contactos_clientes cc ON p.id_contacto_cliente=cc.id 
      INNER JOIN clientes c ON dc.id_cliente=c.id
      INNER JOIN estados_pedidos ep ON p.id_estado_pedido=ep.id
      INNER JOIN almacenes a ON dc.id_almacen=a.id
    WHERE p.id_empresa = $this->id_empresa $filtro_pedido $filtro_cliente $filtro_ubicacion $filtro_estado $filtro_fecha $filtro_desde $filtro_hasta
    ORDER BY p.fecha DESC, p.id DESC";
    //var_dump($queryGet);
    //echo $queryGet;
    $getDatos = $this->conexion->consultaRetorno($queryGet);
    $mensajeError=$this->conexion->conectar->error;
    //echo $mensajeError;
    if($mensajeError!=""){
      echo $mensajeError."<br><br>".$queryGet."<br><br>";
    }

    while ($row = $getDatos->fetch_array()) {
      $fecha_caducidad=$row["fecha_caducidad"];
      $fecha=$row["fecha"];
      $arrayPedidos[] =[
        "id_pedido"               =>$row["id_pedido"],
        //"id_usuario_alta"         =>$row["id_usuario_alta"],
        "fecha"                   =>($fecha == 0) ? "" : date("Y-m-d\TH:i", strtotime($fecha)),
        "fecha_mostrar"           =>($fecha == 0) ? "" : date("d/m/Y", strtotime($fecha)),
        "fecha_caducidad"         =>($fecha_caducidad == 0) ? "" : date("Y-m-d\TH:i", strtotime($fecha_caducidad)),
        "fecha_caducidad_mostrar" =>($fecha_caducidad == 0) ? "" : date("d/m/Y", strtotime($fecha_caducidad)),
        "nro_ticket"              =>$row["nro_ticket"],
        "id_cliente"              =>$row["id_cliente"],
        "cliente"                 =>$row["cliente"],
        "id_direccion_cliente"    =>$row["id_direccion_cliente"],
        "direccion"               =>$row["direccion"],
        "id_centro_costos"        =>$row["id_centro_costos"],
        "id_almacen"              =>$row["id_almacen"],
        "almacen"                 =>$row["almacen"],
        "id_contacto_cliente"     =>$row["id_contacto_cliente"],
        "contacto_cliente"        =>$row["contacto_cliente"],
        "asunto"                  =>$row["asunto"],
        "descripcion"             =>$row["descripcion"],
        "id_tipo_pedido"          =>$row["id_tipo_pedido"],
        "tipo"                    =>$row["tipo"],
        "id_prioridad"            =>$row["id_prioridad"],
        "prioridad"               =>$row["prioridad"],
        "comentarios"             =>$row["comentarios"],
        "id_estado_pedido"        =>$row["id_estado_pedido"],
        "estado"                  =>$row["estado"],
      ];
    }
    
    //echo json_encode($arrayPedidos);
    return json_encode($arrayPedidos);
  }

  public function traerAdjuntosPedidos($id_pedido){

    $arrayAdjuntosPedidos = [];

    $queryGet = "SELECT ap.id AS id_adjunto,ap.archivo,ap.fecha_hora,u.email AS usuario,comentarios
    FROM adjuntos_pedidos ap 
      INNER JOIN usuarios u ON ap.id_usuario=u.id 
    WHERE ap.id_pedido = $id_pedido";
    //var_dump($queryGet);
    $getDatos = $this->conexion->consultaRetorno($queryGet);

    while ($row = $getDatos->fetch_array()) {
      $arrayAdjuntosPedidos[] =[
        "id_adjunto"  =>$row["id_adjunto"],
        "archivo"     =>$row["archivo"],
        "fecha_hora"  =>date("d-M-Y H:i",strtotime($row["fecha_hora"]))."hs",
        "usuario"     =>$row["usuario"],
        "comentarios" =>$row["comentarios"],
      ];
    }
    //echo json_encode($arrayAdjuntosPedidos);
    return json_encode($arrayAdjuntosPedidos);
  }

  public function agregarPedidos($ubicacion, $id_contacto_cliente, $numero, $asunto, $descripcion, $fecha, $prioridad, $caducidad, $tipo, $comentarios, $adjuntos, $cantAdjuntos){
    $id_usuario_alta=$_SESSION["rowUsers"]["id_usuario"];
    $id_estado=1;
    if($tipo==2){
      $id_estado=4;
    }

    /*GUARDO EN TABLA EMPRESA*/
    $queryInsert = "INSERT INTO pedidos (id_empresa, id_usuario_alta, fecha_hora_alta, id_direccion_cliente, id_contacto_cliente, nro_ticket, asunto, descripcion, fecha, id_prioridad, fecha_caducidad, id_tipo_pedido, comentarios, id_estado_pedido) VALUES ($this->id_empresa, '$id_usuario_alta', NOW(), '$ubicacion', $id_contacto_cliente, '$numero', '$asunto', '$descripcion', '$fecha', '$prioridad', '$caducidad', '$tipo', '$comentarios', '$id_estado')";
    //echo $queryInsert;
    $insertar= $this->conexion->consultaSimple($queryInsert);
    //var_dump($insertar);
    $id_pedido=$this->conexion->conectar->insert_id;
    
    $mensajeError=$this->conexion->conectar->error;
    echo $mensajeError;
    if($mensajeError!=""){
      echo "<br><br>".$queryInsert;
    }

    $this->agregarAdjuntosPedidos($id_pedido, $adjuntos, $cantAdjuntos);

  }

  public function agregarAdjuntosPedidos($id_pedido, $adjuntos, $cantAdjuntos, $comentarios=""){
    $id_usuario_alta=$_SESSION["rowUsers"]["id_usuario"];
    //SI VIENEN ADJUNTOS LOS GUARDO.
    if ($adjuntos > 0) {
      //$comentarios="";
      $etiquetas="";
      for ($i=0; $i < $cantAdjuntos; $i++) { 
        $indice = "file".$i;
        $nombreADJ = $_FILES[$indice]['name'];

        //INSERTO DATOS EN LA TABLA ADJUNTOS ORDEN_COMPRA
        $queryInsertAdjuntos = "INSERT INTO adjuntos_pedidos (id_pedido, archivo, fecha_hora, id_usuario, comentarios, etiquetas)VALUES($id_pedido, '$nombreADJ',NOW(),'$id_usuario_alta','$comentarios', '$etiquetas')";
        var_dump($queryInsertAdjuntos);
        $insertAdjuntos = $this->conexion->consultaSimple($queryInsertAdjuntos);

        $mensajeError=$this->conexion->conectar->error;
    
        echo $mensajeError;
        if($mensajeError!=""){
          echo "<br><br>".$queryInsertAdjuntos;
        }
        
        //INGRESO ARCHIVOS EN EL DIRECTORIO
        $directorio = $this->ruta_adjuntos;
        $nombre_archivo_guardado=$directorio."adj_".$id_pedido."_".$nombreADJ;
        var_dump($nombre_archivo_guardado);

        $imagenGuardada=move_uploaded_file($_FILES[$indice]['tmp_name'], $nombre_archivo_guardado);
        var_dump($imagenGuardada);
        

      }
    }
  }

  public function traerDetallePedidos($id_pedido){

    $filtros["id_pedido"]=$id_pedido;
    $datos_pedido=$this->traerPedidos($filtros);
    $datos_pedido=json_decode($datos_pedido,true);
    $datos_pedido=$datos_pedido[0];

    $adjuntos_pedido=$this->traerAdjuntosPedidos($id_pedido);
    $adjuntos_pedido=json_decode($adjuntos_pedido,true);

    $aDetallePedidos=[
      "datos_pedido"      =>$datos_pedido,
      "adjuntos_pedido"   =>$adjuntos_pedido,
    ];
    return json_encode($aDetallePedidos);

  }

  public function eliminarAdjuntos($id_adjunto, $nombre_adjunto){

    $this->id_adj = $id_adjunto;

    $queryGet = "SELECT id_pedido FROM adjuntos_pedidos WHERE id = $this->id_adj";
    $getDatos = $this->conexion->consultaRetorno($queryGet);
    $row = $getDatos->fetch_array();
    $id_pedido=$row["id_pedido"];

    $queryDelAdjunto= "DELETE FROM adjuntos_pedidos WHERE id = $this->id_adj";
    $delAdjunto = $this->conexion->consultaSimple($queryDelAdjunto);

    $directorio = "../views/adjuntosPedidos/";

    $rutaCompleta = $directorio."adj_".$id_pedido."_".$nombre_adjunto;
    
    unlink($rutaCompleta);

  }

  public function updatePedidos($id_pedido, $ubicacion, $id_contacto_cliente, $numero, $asunto, $descripcion, $fecha, $prioridad, $caducidad, $tipo, $comentarios, $adjuntos, $cantAdjuntos){
    $id_estado=1;

    //Actualizo datos de la tarea de mantenimiento preventivo
    $query = "UPDATE pedidos SET id_direccion_cliente='$ubicacion', id_contacto_cliente='$id_contacto_cliente', nro_ticket='$numero', asunto='$asunto', descripcion='$descripcion', fecha='$fecha', id_prioridad='$prioridad', fecha_caducidad='$caducidad', id_tipo_pedido='$tipo', comentarios='$comentarios', id_estado_pedido='$id_estado' WHERE id = $id_pedido";
    //echo $query;
    $update = $this->conexion->consultaSimple($query);
    
    $filasAfectadas=$this->conexion->conectar->affected_rows;
    $mensajeError=$this->conexion->conectar->error;
    //var_dump($mensajeError);
    echo $mensajeError;
    if($mensajeError!=""){
      echo "<br><br>".$query;
    }

    $this->agregarAdjuntosPedidos($id_pedido, $adjuntos, $cantAdjuntos);

  }

  public function eliminarPedidos($id_pedido){
    $this->id_pedido = $id_pedido;

    /*Eliminamos registros de la base de datos*/
    $this->eliminarAdjuntosPedidos($id_pedido);

    /*Tabla calendario_mantenimiento*/
    $queryDelelte = "DELETE FROM pedidos WHERE id=$this->id_pedido";
    //echo $queryDelelte."<br><br>";
    $delete = $this->conexion->consultaSimple($queryDelelte);

  }

  public function eliminarAdjuntosPedidos($id_pedido){
    /*Tabla adjuntos_tareas_mantenimiento*/
    $queryGet = "SELECT archivo FROM adjuntos_pedidos WHERE id_pedido = $id_pedido";
    //var_dump($queryGet);
    $getDatos = $this->conexion->consultaRetorno($queryGet);
    $directorio = $this->ruta_adjuntos;
    while ($row = $getDatos->fetch_array()) {
      unlink($directorio."adj_".$id_pedido."_".$row["archivo"]);
    }

    $queryDelelte = "DELETE FROM adjuntos_pedidos WHERE id_pedido=$id_pedido";
    $delete = $this->conexion->consultaSimple($queryDelelte);
  }

  public function marcarPedidosRealizada($id_mantenimiento){
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

$aPedidos=[
  [
    "id_pedido"=>1,
    "id_cliente"=> "2",
    "cliente"=> "AXXION",
    "id_direccion_cliente"=> "10",
    "direccion"=> "Av. Cordoba 5653 Esq. Bonpland",
    "id_contacto_cliente"=> "8",
    "contacto_cliente"=> "Aguas Hernan",
    "descripcion"=> "Pintura de pasillo latex blanco hasta 1m  de pared 25mts de pared",
    "id_prioridad"=> "2",
    "prioridad"=> "Normal",
    "fecha"=> "2022-02-03",
    "fecha_mostrar"=> "03/02/2022",
    "numero"=> "01-21-28184/00",
    "caducidad"=> "2022-02-03",
    "caducidad_mostrar"=> "03/02/2022",
    "tipo"=> "Normal",
    "comentarios"=> "Pintar látex blanco hasta 1 m pared se necesita 20l de látex blanco",
    "id_estado"=> "1",
    "estado"=> "Pendiente",
    "fecha_alta"=> "2022-02-01",
    "fecha_alta_mostrar"=> "01/02/2022",
    "id_usuario_alta"=> "12",
  ],
  [
    "id_pedido"=>2,
    "id_cliente"=> "2",
    "cliente"=> "AXXION",
    "id_direccion_cliente"=> "11",
    "direccion"=> "Av. La Plata 575 Esq. Alberdi",
    "id_contacto_cliente"=> "9",
    "contacto_cliente"=> "Quintero Juan",
    "descripcion"=> "Pintar pasillo de ingreso a baños y vestuarios",
    "id_prioridad"=> "3",
    "prioridad"=> "Media",
    "fecha"=> "2022-02-03",
    "fecha_mostrar"=> "03/02/2022",
    "numero"=> "01-45-28186/00",
    "caducidad"=> "2022-02-03",
    "caducidad_mostrar"=> "03/02/2022",
    "tipo"=> "Normal",
    "comentarios"=> "Pintar pasillo con latex gris espacial 15m2 y además pintar 2 puertas de baños públicos con sintético contacto admistradora marisa +54 9 11 5374-9680",
    "id_estado"=> "1",
    "estado"=> "Pendiente",
    "fecha_alta"=> "2022-02-01",
    "fecha_alta_mostrar"=> "01/02/2022",
    "id_usuario_alta"=> "12",
  ],
  [
    "id_pedido"=>3,
    "id_cliente"=> "2",
    "cliente"=> "AXXION",
    "id_direccion_cliente"=> "12",
    "direccion"=> "Av. Rivadavia 3199 Esq. Anchorena",
    "id_contacto_cliente"=> "10",
    "contacto_cliente"=> "Quirno Horacio",
    "descripcion"=> "Pintar 100mts2 de medianera arregla y pintar color gris claro y gris oscuro",
    "id_prioridad"=> "5",
    "prioridad"=> "Critica",
    "fecha"=> "2022-02-03",
    "fecha_mostrar"=> "03/02/2022",
    "numero"=> "01-44-28178/00",
    "caducidad"=> "2022-02-03",
    "caducidad_mostrar"=> "03/02/2022",
    "tipo"=> "Urgente",
    "comentarios"=> "Arreglar y pintar medianera  cors anchorena pedido por el ingeniero ",
    "id_estado"=> "1",
    "estado"=> "Pendiente",
    "fecha_alta"=> "2022-02-01",
    "fecha_alta_mostrar"=> "01/02/2022",
    "id_usuario_alta"=> "12",
  ],
];

/*if(isset($id_pedido) and $id_pedido>0){
  $aPedidos=$aPedidos[$id_pedido-1];
}*/

$filtros=[];
if(isset($id_pedido)) $filtros["id_pedido"]=$id_pedido;
if(isset($id_cliente)) $filtros["id_cliente"]=$id_cliente;
if(isset($id_ubicacion)) $filtros["id_ubicacion"]=$id_ubicacion;
if(isset($id_estado_pedido)) $filtros["id_estado_pedido"]=$id_estado_pedido;
if(isset($fecha)) $filtros["fecha"]=$fecha;
if(isset($fDesde)) $filtros["fecha_desde"]=$fDesde;
if(isset($fHasta)) $filtros["fecha_hasta"]=$fHasta;

$pedido = new Pedidos();
if (isset($_POST['accion'])) {
  switch ($_POST['accion']) {
    case 'traerDatosInicialesPedidos':
      echo $pedido->traerDatosIniciales();
    break;
    case 'traerPedidos':
      //echo json_encode($aPedidos);
      echo $pedido->traerPedidos($filtros);
    break;
    case 'addPedido':
      //var_dump($_FILES);
      if(isset($_FILES['file0'])) {
        $adjuntos = 1;
      }else{
        $adjuntos = 0;
      }
      /*if(!isset($numero)) $numero="";
      if(!isset($id_contacto_cliente)) $id_contacto_cliente="NULL";*/
      if(!isset($cantAdjuntos)) $cantAdjuntos=0;

      $pedido->agregarPedidos($ubicacion, $id_contacto_cliente, $numero, $asunto, $descripcion, $fecha, $prioridad, $caducidad, $tipo, $comentarios, $adjuntos, $cantAdjuntos);
    break;
    case 'updatePedido':
      if(isset($_FILES['file0'])) {
        $adjuntos = 1;
      }else{
        $adjuntos = 0;
      }

      $pedido->updatePedidos($id_pedido, $ubicacion, $id_contacto_cliente, $numero, $asunto, $descripcion, $fecha, $prioridad, $caducidad, $tipo, $comentarios, $adjuntos, $cantAdjuntos);
    break;
    case 'traerAdjuntosPedidos':
      echo $pedido->traerAdjuntosPedidos($id_pedido);
    break;
    case 'adjuntarArchivo':
      var_dump($_FILES);
      
      if(isset($_FILES['file0'])) {
        $adjuntos = 1;
      }else{
        $adjuntos = 0;
      }
      if(!isset($cantAdjuntos)) $cantAdjuntos=1;
      $pedido->agregarAdjuntosPedidos($id_pedido, $adjuntos, $cantAdjuntos, $comentarios);
    break;
    case 'traerDetallePedidos':
      echo $pedido->traerDetallePedidos($id_pedido);
    break;
    case 'borrarAdjunto':
      $pedido->eliminarAdjuntos($id_adjunto, $nombre_adjunto);
    break;
    case 'eliminarPedido':
      $pedido->eliminarPedidos($id_pedido);
    break;
    case 'marcarPedidosRealizada':
      //$pedido->marcarPedidosRealizada($id_mantenimiento);
    break;
  }
}else{
  if (isset($_GET['accion'])) {
    switch ($_GET['accion']) {
      case 'traerPedidos':
        echo $pedido->traerPedidos($filtros);
      break;
      case 'traerPedidosCalendario':
        /*$mantenimientosPreventivo=$pedido->traerPedidos();
        $mantenimientosPreventivo=json_decode($mantenimientosPreventivo,true);

        $mantenimientoCalendario=[];
        foreach ($mantenimientosPreventivo as $mantenimiento) {
          $id_pedido= $mantenimiento['id_pedido'];
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
            "id"          =>$id_pedido,
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
        echo json_encode($mantenimientoCalendario);*/
      break;
    }
  }
}?>