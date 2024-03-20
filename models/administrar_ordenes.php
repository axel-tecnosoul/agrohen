<?php
	if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    // session isn't started
    session_start();
  }
	require_once('../../conexion.php');
  require_once('administrar_stock.php');
	class Ordenes{

		private $id_proveedor;
		private $id_orden;
		private $id_almacen;
		private $id_item;
		private $id_adj;
		private $id_empresa;

		public function __construct(){
			$this->conexion = new Conexion();
			date_default_timezone_set("America/Buenos_Aires");
		}

		public function traerDatosIniciales($id_empresa){

			$this->id_empresa = $id_empresa;

			/*ALMACEN*/
			$queryAlmacenes = "SELECT id, almacen FROM almacenes WHERE activo = 1";
			$getAlmacenes = $this->conexion->consultaRetorno($queryAlmacenes);

			/*PROVEEDORES*/
			$queryProveedores = "SELECT id as id_proveedor, razon_social FROM proveedores WHERE activo = 1";
			$getProveedores = $this->conexion->consultaRetorno($queryProveedores);

			/*CENTRO DE COSTOS*/
			$queryCcosto = "SELECT id as id_cc, nombre FROM centro_costos WHERE id_empresa = $this->id_empresa";

			$getCCostos = $this->conexion->consultaRetorno($queryCcosto);


			/*CATEGORÍA*/
			$queryCategoria = "SELECT id as id_categoria, categoria FROM categorias_item";
			$getCategoria = $this->conexion->consultaRetorno($queryCategoria);

			/*UNIDAD DE MEDIDA*/
			$queryUmedida = "SELECT id as id_medida, unidad_medida
									FROM unidades_medida";
			$getUmedida = $this->conexion->consultaRetorno($queryUmedida);

			/*TIPO DE ITEM*/
			$queryTipoItem = "SELECT id as id_tipo, tipo as nombre_tipo
									FROM tipos_items";
			$getTipoItem = $this->conexion->consultaRetorno($queryTipoItem);



			$datosIniciales = array();
			$almacenes = array();
			$proveedores = array();
			$centroCostos = array();
			$arrayCategoria = array();
			$arrayUnidMedida = array();
			$arrayTipoIetm = array();

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

			/*CARGO ARRAY CON CENTRO DE COSTOS*/
			while ($rowCC = $getCCostos->fetch_assoc()) {
				$id_cc = $rowCC['id_cc'];
				$nombreCC = $rowCC['nombre'];
				$centroCostos[]=array('id_cc'=>$id_cc, 'nombreCC'=>$nombreCC);
			}

			/*CARGO ARRAY CATEGORÍA*/
			while ($rowCategoria = $getCategoria->fetch_array()) {
				$id_categoria = $rowCategoria['id_categoria'];
				$categoria = $rowCategoria['categoria'];
				$arrayCategoria[]= array('id_categoria' => $id_categoria, 'categoria' =>$categoria);
			}

			/*CARGO ARRAY UNIDAD DE MEDIDA*/
			while ($rowUnidMed = $getUmedida->fetch_array()) {
				$id_medida = $rowUnidMed['id_medida'];
				$unidad_medida = $rowUnidMed['unidad_medida'];
				$arrayUnidMedida[]= array('id_medida' => $id_medida, 'unidad_medida' =>$unidad_medida);
			}

			/*CARGO ARRAY TIPO ITEMS*/
			while ($rowTipoItem = $getTipoItem->fetch_array()) {
				$id_tipo = $rowTipoItem['id_tipo'];
				$nombre_tipo = $rowTipoItem['nombre_tipo'];
				$arrayTipoIetm[]= array('id_tipo' => $id_tipo, 'nombre_tipo' =>$nombre_tipo);
			}


			$datosIniciales["almacenes"] = $almacenes;
			$datosIniciales["proveedores"] = $proveedores;
			$datosIniciales["centroCostos"] = $centroCostos;
			$datosIniciales["categorias"] = $arrayCategoria;
			$datosIniciales["unidad_medida"] = $arrayUnidMedida;
			$datosIniciales["tipo_items"] = $arrayTipoIetm;

			return json_encode($datosIniciales);
		}

		public function traerOrdenes($id_empresa){
			
			$ordenes_de_compra = array();

			$queryGetOC = "SELECT oc.id as id_oc, razon_social as proveedor, almacen, 				total, fecha, email as usuario, eoc.id as id_estado, eoc.estado 
							FROM ordenes_compra oc JOIN proveedores prov
							ON(oc.id_proveedor = prov.id)
							JOIN estados_orden_compra eoc
							ON(oc.id_estado = eoc.id)
							JOIN usuarios usr
							ON(oc.id_usuario_alta = usr.id)
							JOIN almacenes alm
							ON(oc.id_almacen = alm.id)
							WHERE oc.id_empresa = $id_empresa";
			$getOc = $this->conexion->consultaRetorno($queryGetOC);

			while ($rowOC = $getOc->fetch_array()) {
				$id_oc= $rowOC['id_oc'];
				$proveedor= $rowOC['proveedor'];
				$almacen = $rowOC['almacen'];
				$total= "$".number_format($rowOC['total'],2,',','.');
				$fecha= date("d/m/Y", strtotime($rowOC['fecha']));
				$usuario= $rowOC['usuario'];
				$id_estado = $rowOC['id_estado'];
				$estado= $rowOC['estado'];
				$ordenes_de_compra[] = array('id_oc'=>$id_oc, 'proveedor'=>$proveedor, 'almacen'=>$almacen, 'total'=>$total, 'fecha'=>$fecha, 'usuario'=>$usuario, 'id_estado'=>$id_estado, 'estado'=>$estado);
			}

			echo json_encode($ordenes_de_compra);
		}

		public function traerItems($id_proveedor){
			$this->id_proveedor = $id_proveedor;

			$queryTraerItems="SELECT it.id id_item, imagen, it.item, lp.precio
							FROM item as it JOIN lista_precios as lp
							ON(it.id = lp.id_item)
							WHERE lp.id_proveedor = $this->id_proveedor";
			$traerItems = $this->conexion->consultaRetorno($queryTraerItems);

			$arrayItems = array();

			if ($traerItems->num_rows > 0) {
				while ($rowItems= $traerItems->fetch_array()) {
						$id_item = $rowItems['id_item'];
						$imagen = $rowItems['imagen'];
						$item = $rowItems['item'];
						$precio = $rowItems['precio'];
						$arrayItems[] = array("id_item"=>$id_item, "imagen"=>$imagen, "item"=>$item, "precio"=>$precio);
					}	
			}

			echo json_encode($arrayItems);
		}
		public function agregarItemsOrdenes($items, $proveedor, $almacen, $cCostos, $total, $comentarios, $id_empresa,$id_pedido="NULL"){
			
			$datos = json_decode($items);
			$fecha = date('Y-m-d H:i:s');
			$usuario = $_SESSION['rowUsers']['id_usuario'];
			$this->id_empresa = $id_empresa;
			
			/*INSERTAR DATOS EN TABLA ORDENES DE COMPRA*/
			$queryInsertOC = "INSERT INTO ordenes_compra(id_proveedor, fecha, id_usuario_alta, total, id_almacen, id_estado, comentarios, id_cc, id_empresa, id_pedido) VALUES($proveedor, '$fecha', $usuario, $total, $almacen,  1, '$comentarios', $cCostos, $this->id_empresa, $id_pedido)";
			$insertOC = $this->conexion->consultaSimple($queryInsertOC);
      $mensajeError=$this->conexion->conectar->error;
      //echo $mensajeError;
      $mensajeError=$this->conexion->conectar->error;
      if($mensajeError!=""){
        echo $mensajeError."<br><br>".$queryInsertOC."<br><br>";
      }

			/*OBTENGO EL ID DE LA ORDEN DE COMPRA CREADA*/
			$queryGetIdOC="SELECT id FROM ordenes_compra
						WHERE id_proveedor = $proveedor
						AND id_usuario_alta = $usuario
						AND fecha = '$fecha'
						AND total = $total
						AND id_almacen = $almacen";
			$getIdOC = $this->conexion->consultaRetorno($queryGetIdOC);
      $mensajeError=$this->conexion->conectar->error;
      //echo $mensajeError;
      $mensajeError=$this->conexion->conectar->error;
      if($mensajeError!=""){
        echo $mensajeError."<br><br>".$queryGetIdOC."<br><br>";
      }

			if ($getIdOC->num_rows > 0) {
				$IdOCRow = $getIdOC->fetch_assoc();
				$id_orden_compra = $IdOCRow['id'];
			}

			/*GUARDO EN TABLA LOG_ORDENES_COMPRA*/
			$fecha_hora_desde= date("Y-m-d H:i:s");
			$fecha_hora_hasta= NULL;
			$id_usuario = $_SESSION['rowUsers']['id_usuario'];

			$queryInsertLogsOC = "INSERT INTO log_ordenes_compra(id_orden_compra, id_estado, fecha_hora_desde, fecha_hora_hasta, id_usuario) values($id_orden_compra, 1, '$fecha_hora_desde', '$fecha_hora_hasta', $id_usuario)";
			$InsertLogsOC = $this->conexion->consultaSimple($queryInsertLogsOC);
      $mensajeError=$this->conexion->conectar->error;
      //echo $mensajeError;
      $mensajeError=$this->conexion->conectar->error;
      if($mensajeError!=""){
        echo $mensajeError."<br><br>".$queryInsertLogsOC."<br><br>";
      }


			for ($i=0; $i < count($datos) ; $i++) {

				/*GUARDO DETALLE ORDEN DE COMPRA*/
				$id_item = $datos[$i]->id;
				$cantidad = $datos[$i]->valor;
				$precio = $datos[$i]->precio;
				$queryInsertDOC = "INSERT INTO ordenes_compra_detalle(id_orden_compra, id_item, cantidad, precio_unitario)VALUES($id_orden_compra, $id_item, $cantidad, $precio)";
				$insertDOC = $this->conexion->consultaSimple($queryInsertDOC);
			}

			echo $id_orden_compra;
		}

		public function delOrden($id_orden){
			
			$this->id_orden = $id_orden;
			
			/*Eliminamos primero el detalle de la orden por la relacion*/
			$queryDelDetalleOrden = "DELETE FROM ordenes_compra_detalle WHERE id_orden_compra = $this->id_orden";
			$delDetalleOrden = $this->conexion->consultaSimple($queryDelDetalleOrden);

			/*Eliminamos logs orden de compra por la relacion*/
			$queryDelOrdenLogs = "DELETE FROM log_ordenes_compra 
								WHERE id_orden_compra = $this->id_orden";
			$delOrdenLogs = $this->conexion->consultaSimple($queryDelOrdenLogs);

			/*Eliminamos orden de compra*/
			$queryDelOrden = "DELETE FROM ordenes_compra WHERE id = $this->id_orden";
			$delOrden = $this->conexion->consultaSimple($queryDelOrden);
		}

		public function traerOrdenesUpdate($id_orden){
			$this->id_orden = $id_orden;

			$datosOrdenCompra = array();
			$itemPrecio = array();
			$orden = array();

			/*Obtengo orden de compra*/
			$queryGetOrdenUpdate="SELECT id_proveedor, id_almacen, total, comentarios, id_cc
								FROM ordenes_compra
								WHERE id = $this->id_orden";
			$getOrdenUpdate = $this->conexion->consultaRetorno($queryGetOrdenUpdate);

			/*Traigo items y precios de la orden para cargar en mapa de javascript*/
			$queryGetCdadPrecioOrden = "SELECT id_item, (precio_unitario*cantidad) 								as precio_total, precio_unitario as precio, 							cantidad 
									FROM ordenes_compra_detalle
									WHERE id_orden_compra = $this->id_orden";
			$getCdadPrecioOrden=$this->conexion->consultaRetorno($queryGetCdadPrecioOrden);


			/*LLeno array con datos orden de compra*/
			while ($rowOrdenUpdate = $getOrdenUpdate->fetch_array()) {
				$id_proveedor = $rowOrdenUpdate['id_proveedor'];
				$id_almacen = $rowOrdenUpdate['id_almacen'];
				$total = $rowOrdenUpdate['total'];
				$comentarios = $rowOrdenUpdate['comentarios'];
				$id_cc = $rowOrdenUpdate['id_cc'];
				$orden[] = array("id_proveedor"=>$id_proveedor, "id_almacen"=>$id_almacen, "total"=>$total, "comentarios"=>$comentarios, "id_cc"=>$id_cc);
			}

			/*Lleno array con items y precios ya cargados*/
			while ($rowPrecioCant = $getCdadPrecioOrden->fetch_array()) {
				$id_item = $rowPrecioCant['id_item'];
				$precio_total = $rowPrecioCant['precio_total'];
				$precio = $rowPrecioCant['precio'];
				$cantidad = $rowPrecioCant['cantidad'];
				$itemPrecio[] = array('id_item'=>$id_item, 'precio_total'=>$precio_total, 'precio'=>$precio, 'cantidad'=>$cantidad);
			}


			$datosOrdenCompra["orden"] = $orden;
			$datosOrdenCompra["precioCant"] = $itemPrecio;
			echo json_encode($datosOrdenCompra);

		}

		public function traerItemsUpdateOrden($id_orden, $id_proveedor){

			$this->id_orden = $id_orden;
			$this->id_proveedor = $id_proveedor;

			/*Traigo detalle orden de compra*/
			$queryGetDetalleOrden = "SELECT id_item, it.item, dc.cantidad, precio_unitario, it.imagen
									FROM ordenes_compra_detalle dc
									JOIN item as it
									ON(dc.id_item = it.id)
									WHERE id_orden_compra = $this->id_orden";
			$getDetalleOrden = $this->conexion->consultaRetorno($queryGetDetalleOrden);

			$detalleOrden = array();
			$itemsDetalleOrden = array();

			/*Lleno array con detalle orden de compra*/
			while ($rowDetallOrden=$getDetalleOrden->fetch_array()) {
				$id_item = $rowDetallOrden['id_item'];
				$item = $rowDetallOrden['item'];
				$cantidad = $rowDetallOrden['cantidad'];
				$precio_unitario = $rowDetallOrden['precio_unitario'];
				$imagen = $rowDetallOrden['imagen'];
				$itemsDetalleOrden[] = array("id_item"=>$id_item, "item"=>$item, "cantidad"=>$cantidad, "precio"=>$precio_unitario, 'imagen'=>$imagen);
			}

			/*Traigo items del proveedor menos los que tiene pedidos
			$queryGetItemProv ="SELECT it.id id_item, it.item, lp.precio, 0 cantidad
							FROM item as it JOIN lista_precios as lp
							ON(it.id = lp.id_item)
							WHERE lp.id_proveedor = $this->id_proveedor
							AND it.id not in(SELECT id_item FROM ordenes_compra_detalle WHERE id_orden_compra = $this->id_orden)";
			$getItemProv = $this->conexion->consultaRetorno($queryGetItemProv);*/


			/*Completo array con items de proveedor faltante
			while ($rowItemProv = $getItemProv->fetch_array()) {
				$id_item = $rowItemProv['id_item'];
				$item = $rowItemProv['item'];
				$cantidad = $rowItemProv['cantidad'];
				$precio_unitario = $rowItemProv['precio'];
				$itemsDetalleOrden[] = array("id_item"=>$id_item, "item"=>$item, "cantidad"=>$cantidad, "precio"=>$precio_unitario);
			}*/



			$detalleOrden = $itemsDetalleOrden;
			echo json_encode($detalleOrden);


		}

		function updateOrdenes($items, $proveedor, $almacen, $total, $id_orden, $comentarios, $cCostos){
			$this->id_proveedor = $proveedor;
			$this->id_almacen = $almacen;
			$this->id_orden = $id_orden;


			/*PRIMERO ACTUALIZO LA ORDEN DE COMPRA*/

			$sqlUpdateOrdenCompra = "UPDATE ordenes_compra SET total = $total, 								id_almacen = $almacen, comentarios = '$comentarios', id_cc = $cCostos
									WHERE id = $this->id_orden";
			$updateOrdenCompra = $this->conexion->consultaSimple($sqlUpdateOrdenCompra);


			/*ACTUALIZO DETALLE ORDEN DE COMPRA*/

			$itemsUpdate = json_decode($items);

			for ($i=0; $i < count($itemsUpdate) ; $i++) {

				if($itemsUpdate[$i]->valor == NULL || $itemsUpdate[$i]->valor ==0){
					
					$id_item_buscar=$itemsUpdate[$i]->id;

					//echo "se eliminará item ".$id_item_buscar."</br>";
					//echo "con la orden: ".$this->id_orden."</br>";

					/*DEBO VERIFICAR SI EL ITEM QUE VIAJA EN 0 ESTÁ EN LA TABLA DETALLE ITEMS*/
					$queryGetIdItemDel = "SELECT id_item FROM ordenes_compra_detalle
										WHERE id_item = $id_item_buscar
										AND id_orden_compra = $this->id_orden";
					$getIdItemDel = $this->conexion->consultaRetorno($queryGetIdItemDel);

					
					/*ELIMINO LOS ITEMS QUE ME MANDARON EN 0 Y ESTÉN EN LA TABLA ORDENES DETALLE ITEMS*/

					if ($getIdItemDel->num_rows > 0) {
						
						$id_itemRow = $getIdItemDel->fetch_assoc();
						$id_item = $id_itemRow['id_item'];

						$queryDelItemsEnCero = "DELETE FROM ordenes_compra_detalle
											WHERE id_item = $id_item_buscar
											AND id_orden_compra = $this->id_orden";
						$delItemsEnCero = $this->conexion->consultaSimple($queryDelItemsEnCero);
					}

				}else{

					$id_item_buscar=$itemsUpdate[$i]->id;


					/*VERIFICO SI EXISTE EN TABLA ITEMS PARA ACTUALIZAR*/
					$queryGetIdItem = "SELECT id_item FROM ordenes_compra_detalle
										WHERE id_item = $id_item_buscar
										AND id_orden_compra = $this->id_orden";
					$getIdItem = $this->conexion->consultaRetorno($queryGetIdItem);

					/*SIENDO CANTIDAD MAYOR A 0 VERIFICO SI ACTUALIZO O INSERTO ITEMS*/
					if($getIdItem->num_rows > 0){

						$id_itemRow = $getIdItem->fetch_assoc();
						$id_item = $id_itemRow['id_item'];


						$cantidad = $itemsUpdate[$i]->valor;

						$queryUpdateDetalleOrden= "UPDATE ordenes_compra_detalle SET 						cantidad=$cantidad 
												WHERE id_item = $id_item 
												AND id_orden_compra = $this->id_orden";
						$UpdateDetalleOrden=$this->conexion->consultaSimple($queryUpdateDetalleOrden);

					}else{

						$id_item_insert=$itemsUpdate[$i]->id;
						$cantidad = $itemsUpdate[$i]->valor;
						$precio = $itemsUpdate[$i]->precio;

						$queryInsertItemUpdate = "INSERT INTO ordenes_compra_detalle(id_orden_compra, id_item, cantidad, precio_unitario)VALUES($this->id_orden, $id_item_insert, $cantidad, $precio)";
						$insertItemUpdate = $this->conexion->consultaSimple($queryInsertItemUpdate);

					}


				}

			}
			echo $id_orden;
		}

		public function updateEstado($id_orden, $id_estado){

			$this->id_orden = $id_orden;
			$fecha_hora_desde= date("Y-m-d H:i:s");
			$fecha_hora_hasta= NULL;
			$id_usuario = $_SESSION['rowUsers']['id_usuario'];

			/*PRIMERO ACTUALIZO LA ORDEN DE COMPRA*/
			$sqlUpdateOrdenCompra = "UPDATE ordenes_compra SET id_estado = $id_estado
									WHERE id = $this->id_orden";
			$updateOrdenCompra = $this->conexion->consultaSimple($sqlUpdateOrdenCompra);


			/*GUARDO EN TABLA LOG_ORDENES_COMPRA*/
			$queryInsertLogsOC = "INSERT INTO log_ordenes_compra(id_orden_compra, id_estado, fecha_hora_desde, fecha_hora_hasta, id_usuario) values($this->id_orden, $id_estado, '$fecha_hora_desde', '$fecha_hora_hasta', $id_usuario)";
			$InsertLogsOC = $this->conexion->consultaSimple($queryInsertLogsOC);

		}

		public function traerEstadosOrdenes($id_orden){
			$this->id_orden = $id_orden;

			$queryGetEstados="SELECT id_orden_compra, estado, fecha_hora_desde, email
								FROM log_ordenes_compra loc JOIN estados_orden_compra eoc
								ON(loc.id_estado = eoc.id)
								JOIN usuarios usr
								ON(loc.id_usuario = usr.id)
								WHERE loc.id_orden_compra = $this->id_orden
								order by fecha_hora_desde";
			$getEstados = $this->conexion->consultaRetorno($queryGetEstados);

			$estados = array();

			while ($rowGetEstados = $getEstados->fetch_array()) {
				$id_orden = $rowGetEstados['id_orden_compra'];
				$estado =  $rowGetEstados['estado'];
				$fecha_formato = date("d/m/Y H:i:s", strtotime($rowGetEstados['fecha_hora_desde']));
				$fecha = $fecha_formato;
				$usuario = $rowGetEstados['email'];
				$estados[]=array('id_orden'=>$id_orden, 'estado'=>$estado, 'fecha'=>$fecha, 'usuario'=>$usuario);
			}

			echo json_encode($estados);
		}

		public function traerOrdenesDetalle($id_orden){

			$this->id_orden = $id_orden;

			$queryGetDetalleOrden = "SELECT id_item, item, precio_unitario as precio, 						cantidad, (precio_unitario*cantidad)as precio_total
									FROM ordenes_compra_detalle ocd JOIN item it
									ON(ocd.id_item = it.id)
									WHERE id_orden_compra = $this->id_orden";
			$getDetalleOrde = $this->conexion->consultaRetorno($queryGetDetalleOrden);

			$ArrayDetalleOrden = array();

			while ($rowDO = $getDetalleOrde->fetch_array()) {
				
				$id_item = $rowDO['id_item'];
				$item = $rowDO['item'];
				$precioUnitario = $rowDO['precio'];
				$cantidad = $rowDO['cantidad'];
				$precio_total = $rowDO['precio_total'];

				$ArrayDetalleOrden[]=array('id_item'=>$id_item, 'item'=>$item, 'precioUnitario'=>$precioUnitario, 'cantidad'=>$cantidad, "precio_total"=>$precio_total);
			}

			echo json_encode($ArrayDetalleOrden);


		}

		public function traerEstadosGrafico($fDesde, $fHasta, $id_empresa){

			$this->id_empresa = $id_empresa;
			$arrayEstadoOrdenes = array();

			if($fDesde != "" && $fHasta != ""){

				$queryGetOrdenesEstados="SELECT estado, sum(1) cantidad 
					FROM ordenes_compra oc JOIN estados_orden_compra as eoc
					ON(oc.id_estado = eoc.id)
					WHERE oc.id_empresa = $this->id_empresa
					AND date_format(fecha, '%Y-%m-%d') between '$fDesde' and '$fHasta'
					group by estado";
				$getOrdenesEstados = $this->conexion->consultaRetorno($queryGetOrdenesEstados);

			}else{

				$queryGetOrdenesEstados="SELECT estado, sum(1) cantidad 
					FROM ordenes_compra oc JOIN estados_orden_compra as eoc
					ON(oc.id_estado = eoc.id)
					WHERE oc.id_empresa = $this->id_empresa
					group by estado";
				$getOrdenesEstados = $this->conexion->consultaRetorno($queryGetOrdenesEstados);

			}

			while ($rowOrdenDetalles = $getOrdenesEstados->fetch_array()){
				$estado = $rowOrdenDetalles['estado'];
				$cantidad = $rowOrdenDetalles['cantidad'];
				$arrayEstadoOrdenes[] = array('estado'=> $estado, 'cantidad'=>$cantidad);
			}

			echo json_encode($arrayEstadoOrdenes);
		}

		public function traerOrdenesFiltro($fDesde, $fHasta){
			$ordenes_de_compra = array();

			$queryGetOC = "SELECT oc.id as id_oc, razon_social as proveedor, almacen, 				total, fecha, email as usuario, eoc.id as id_estado, eoc.estado 
							FROM ordenes_compra oc JOIN proveedores prov
							ON(oc.id_proveedor = prov.id)
							JOIN estados_orden_compra eoc
							ON(oc.id_estado = eoc.id)
							JOIN usuarios usr
							ON(oc.id_usuario_alta = usr.id)
							JOIN almacenes alm
							ON(oc.id_almacen = alm.id)
							WHERE date_format(fecha, '%Y-%m-%d') between '$fDesde' and '$fHasta'";
			$getOc = $this->conexion->consultaRetorno($queryGetOC);

			while ($rowOC = $getOc->fetch_array()) {
				$id_oc= $rowOC['id_oc'];
				$proveedor= $rowOC['proveedor'];
				$almacen = $rowOC['almacen'];
				$total= "$".number_format($rowOC['total'],2,',','.');
				$fecha= date("d/m/Y", strtotime($rowOC['fecha']));
				$usuario= $rowOC['usuario'];
				$id_estado = $rowOC['id_estado'];
				$estado= $rowOC['estado'];
				$ordenes_de_compra[] = array('id_oc'=>$id_oc, 'proveedor'=>$proveedor, 'almacen'=>$almacen, 'total'=>$total, 'fecha'=>$fecha, 'usuario'=>$usuario, 'id_estado'=>$id_estado, 'estado'=>$estado);
			}

			echo json_encode($ordenes_de_compra);
		}

		public function traerContactos($id_orden){
			
			$this->id_orden = $id_orden;

			/*BUSCO PROVEEDOR DE LA ORDEN*/
			$queryGetIdProveedor = "SELECT id_proveedor FROM ordenes_compra 
								WHERE id = $this->id_orden";
			$getIdProveedor = $this->conexion->consultaRetorno($queryGetIdProveedor);

			$rowIdProv = $getIdProveedor->fetch_assoc();

			$this->id_proveedor = $rowIdProv['id_proveedor'];


			$query = "SELECT ccl.id as id_contacto, nombre_completo, email, telefono, 
					cg.cargo cargo, cg.id as id_cargo, case
														when activo = 1 then 'Activo'
            											else 'Inactivo'
														end activo
					FROM contactos_proveedores ccl join cargos cg
					ON(ccl.id_cargo = cg.id) 
					WHERE id_proveedor = $this->id_proveedor";
			$getContactos = $this->conexion->consultaRetorno($query);

			$contactos = array(); //creamos un array
			
			if($getContactos){
				while ($row = $getContactos->fetch_array()) {
            	$id_contacto = $row['id_contacto'];
            	$nombre_completo = $row['nombre_completo'];
            	$email = $row['email'];
            	$telefono = $row['telefono'];
            	$cargo = $row['cargo'];
            	$id_cargo = $row['id_cargo'];
            	$activo = $row['activo'];
            	$contactos[] = array('id_contacto'=> $id_contacto, 'nombre_completo'=>$nombre_completo, 'email'=>$email, 'telefono'=>$telefono, 'cargo'=>$cargo, 'id_cargo' =>$id_cargo, 'activo'=> $activo);
        		}
			}
			echo json_encode($contactos);
		}

		public function traerItemsRecibir($id_orden){

			$this->id_orden = $id_orden;

			$queryTraerItems="SELECT it.id id_item, it.item, ocd.cantidad as 						cantidad_pedida, ocd.cantidad_recibida
							FROM ordenes_compra_detalle as ocd JOIN item as it 
							ON(ocd.id_item = it.id)
							WHERE ocd.id_orden_compra = $this->id_orden";
			$traerItems = $this->conexion->consultaRetorno($queryTraerItems);

			$arrayItems = array();

			if ($traerItems->num_rows > 0) {
				while ($rowItems= $traerItems->fetch_array()) {
						$id_item = $rowItems['id_item'];
						$item = $rowItems['item'];
						$cantPedida = $rowItems['cantidad_pedida'];
						$cantRecibida = $rowItems['cantidad_recibida'];
						$arrayItems[] = array("id_item"=>$id_item, "item"=>$item, "cantPedida"=>$cantPedida, "cantRecibida"=>$cantRecibida);
					}	
			}

			echo json_encode($arrayItems);
		}

		public function recibirPedido($id_orden, $itemsRecibir){
			
			$this->id_orden = $id_orden;
			$fecha = date('Y-m-d H:i:s');
			$usuario = $_SESSION['rowUsers']['id_usuario'];
			$datos = json_decode($itemsRecibir);

      $queryGetItemStock = "SELECT id_proveedor,id_almacen FROM ordenes_compra WHERE id = $id_orden";
			$getItemStock = $this->conexion->consultaRetorno($queryGetItemStock);
      $mensajeError=$this->conexion->conectar->error;
      if($mensajeError!="") echo $mensajeError."<br><br>".$queryGetItemStock."<br><br>";
      $row = $getItemStock->fetch_array();
      $id_proveedor=$row["id_proveedor"];
      $id_almacen=$row["id_almacen"];
      $stock=new Stock();

			for ($i=0; $i < count($datos); $i++) { 
				
				$id_item = $datos[$i]->id;
				$cantidad = $datos[$i]->cantidad;


				/*ACTUALIZO CANTIDAD RECIBIDA EN ORDEN DE COMPRA DETALLE*/
				$queryUpdateOCDETALLE = "UPDATE ordenes_compra_detalle SET cantidad_recibida = cantidad_recibida + $cantidad WHERE id_item = $id_item AND id_orden_compra = $this->id_orden";
				$updateOCDETALLE = $this->conexion->consultaSimple($queryUpdateOCDETALLE);
        $mensajeError=$this->conexion->conectar->error;
        if($mensajeError!="") echo $mensajeError."<br><br>".$queryUpdateOCDETALLE."<br><br>";

				/*INSERTO O ACTUALIZO DATOS EN TABLA STOCK*/
        $stock->aumentarStock($id_item,$id_proveedor,$id_almacen,$cantidad,$tipo_movimiento="Ingreso por OC");

				/*VERIFICO SI EXISTE EL ITEM EN LA TABLA STOCK*/

				/*$queryGetItemStock = "SELECT id FROM stock WHERE id_item = $id_item AND id_proveedor = $id_proveedor AND id_almacen = $id_almacen";
				$getItemStock = $this->conexion->consultaRetorno($queryGetItemStock);
        $mensajeError=$this->conexion->conectar->error;
        if($mensajeError!="") echo $mensajeError."<br><br>".$queryGetItemStock."<br><br>";
        $row = $getItemStock->fetch_array();

				if($getItemStock->num_rows > 0){
          $id_stock=$row["id"];

					//SI EXISTE ACTUALIZO

					$queryGetDatosItem = "SELECT cantidad_disponible FROM stock WHERE id_item = $id_item";
					$getDatosItem = $this->conexion->consultaRetorno($queryGetDatosItem);
          $mensajeError=$this->conexion->conectar->error;
          if($mensajeError!="") echo $mensajeError."<br><br>".$queryGetDatosItem."<br><br>";

					$rowDatosItems = $getDatosItem->fetch_assoc();

					$cantidad_disponible = $rowDatosItems['cantidad_disponible']+$cantidad;

					$queryUpdateStock = "UPDATE stock SET cantidad_disponible = cantidad_disponible + $cantidad WHERE id = $id_stock";
					$updateStock = $this->conexion->consultaSimple($queryUpdateStock);
          $mensajeError=$this->conexion->conectar->error;
          if($mensajeError!="") echo $mensajeError."<br><br>".$queryUpdateStock."<br><br>";

					//OBTENGO ID DE STOCK
					$queryGetIdStock = "SELECT id as id_stock FROM stock WHERE id_item = $id_item";
					$getIdStock = $this->conexion->consultaRetorno($queryGetIdStock);
          $mensajeError=$this->conexion->conectar->error;
          if($mensajeError!="") echo $mensajeError."<br><br>".$queryGetIdStock."<br><br>";

					$rowIdStock = $getIdStock->fetch_assoc();

					$id_stock = $rowIdStock['id_stock'];

				}else{

					//SI NO EXISTE INSERTO
					$queryGetDatosItem = "SELECT oc.id_proveedor, oc.id_almacen, ocd.precio_unitario FROM ordenes_compra as oc JOIN ordenes_compra_detalle as ocd ON(oc.id = ocd.id_orden_compra) WHERE oc.id = $this->id_orden AND ocd.id_item = $id_item";
					$getDatosItem = $this->conexion->consultaRetorno($queryGetDatosItem);
          $mensajeError=$this->conexion->conectar->error;
          if($mensajeError!="") echo $mensajeError."<br><br>".$queryGetDatosItem."<br><br>";

					$rowDatosItems = $getDatosItem->fetch_assoc();

					$id_proveedor = $rowDatosItems['id_proveedor'];
					$id_almacen = $rowDatosItems['id_almacen'];
					$precio_unitario = $rowDatosItems['precio_unitario'];
          $precio_unitario = 0;

					//echo "id_proveedor = ".$id_proveedor." id_almacen= ".$id_almacen." precio_unitario= ".$precio_unitario." Fecha= ".$fecha." id_item=".$id_item." cantidad= ".$cantidad."</br>";

					$queryInsertItemStock = "INSERT INTO stock (id_item, id_proveedor, id_almacen, cantidad_disponible, precio_unitario,fecha_hora_ultima_actualizacion) VALUES ($id_item, $id_proveedor, $id_almacen, $cantidad, $precio_unitario, NOW())";
					$insertItemStock = $this->conexion->consultaSimple($queryInsertItemStock);
          $mensajeError=$this->conexion->conectar->error;
          if($mensajeError!="") echo $mensajeError."<br><br>".$queryInsertItemStock."<br><br>";

					//OBTENGO ID DE STOCK
					$queryGetIdStock = "SELECT id as id_stock FROM stock WHERE id_item = $id_item AND id_proveedor = $id_proveedor AND id_almacen = $id_almacen";
					$getIdStock = $this->conexion->consultaRetorno($queryGetIdStock);
          $mensajeError=$this->conexion->conectar->error;
          if($mensajeError!="") echo $mensajeError."<br><br>".$queryGetIdStock."<br><br>";

					$rowIdStock = $getIdStock->fetch_assoc();

					$id_stock = $rowIdStock['id_stock'];

				}

        //INSERTO MOVIMIENTOS STOCK
        $queryInsertMS = "INSERT INTO movimientos_stock (id_stock, cantidad, id_usuario, fecha_hora, tipo_movimiento) VALUES($id_stock, $cantidad, $usuario, '$fecha', 'Ingreso por OC')";
        $insertMS = $this->conexion->consultaSimple($queryInsertMS);
        $mensajeError=$this->conexion->conectar->error;
        if($mensajeError!="") echo $mensajeError."<br><br>".$queryInsertMS."<br><br>";*/


			}

			/*VEO SI ACTUALIZO ESTADO, LOG DE COMPRA Y GENERO MOVIMIENTO EN CTA CTE*/

			$queryGetCantidadOC = "SELECT SUM(cantidad) - sum(cantidad_recibida) as total FROM ordenes_compra_detalle WHERE id_orden_compra = $this->id_orden";
			$getCantidadOC = $this->conexion->consultaRetorno($queryGetCantidadOC);
      $mensajeError=$this->conexion->conectar->error;
      if($mensajeError!="") echo $mensajeError."<br><br>".$queryGetCantidadOC."<br><br>";

			$rowTotal = $getCantidadOC->fetch_assoc();

			$total = $rowTotal['total'];

			if($total == 0){
				
				/*ACTUALIZO ESTADO EN TABLA ORDEN DE COMPRA*/
				$queryUpdateEstadoODC = "UPDATE ordenes_compra SET id_estado = 6 WHERE id = $this->id_orden";
				$updateEstadoODC = $this->conexion->consultaSimple($queryUpdateEstadoODC);
        $mensajeError=$this->conexion->conectar->error;
        if($mensajeError!="") echo $mensajeError."<br><br>".$queryUpdateEstadoODC."<br><br>";

				/*GENERO UN LOG EN LOG_ORDENES_COMPRA*/
				$fecha_hora_desde= date("Y-m-d H:i:s");
				$fecha_hora_hasta= NULL;
				$id_usuario = $_SESSION['rowUsers']['id_usuario'];

				$queryInsertLogsOC = "INSERT INTO log_ordenes_compra(id_orden_compra, id_estado, fecha_hora_desde, fecha_hora_hasta, id_usuario) values($this->id_orden, 6, '$fecha_hora_desde', '$fecha_hora_hasta', $id_usuario)";
				$InsertLogsOC = $this->conexion->consultaSimple($queryInsertLogsOC);
        $mensajeError=$this->conexion->conectar->error;
        if($mensajeError!="") echo $mensajeError."<br><br>".$queryInsertLogsOC."<br><br>";

				/*OBTENGO EL MONTO DE LA ORDEN DE COMPRA*/
				$queryGetDetalleOC = "SELECT total, id_proveedor FROM ordenes_compra WHERE id = $this->id_orden";
				$getDetalleOC = $this->conexion->consultaRetorno($queryGetDetalleOC);
        $mensajeError=$this->conexion->conectar->error;
        if($mensajeError!="") echo $mensajeError."<br><br>".$queryGetDetalleOC."<br><br>";

				$rowDetalleOC = $getDetalleOC->fetch_assoc();

				/*GUARDO DATOS EN EL MOVIMIENTO CTA CTE*/
				
				$id_proveedor = $rowDetalleOC['id_proveedor'];
				$monto = -($rowDetalleOC['total']);
				$copiaMonto = $rowDetalleOC['total'];
				$fecha_hora = date("Y-m-d H:i:s");

				$queryInsertCtaCte = "INSERT INTO movimientos_proveedores(id_proveedor, id_tipo_movimiento, monto, fecha_hora, id_origen, monto_cancelado)VALUES($id_proveedor, 1, $monto, '$fecha_hora', $this->id_orden, 0)";
        $mensajeError=$this->conexion->conectar->error;
        if($mensajeError!="") echo $mensajeError."<br><br>".$queryGet."<br><br>";

				$insertCtaCte = $this->conexion->consultaSimple($queryInsertCtaCte);
			}
			
		}

	public function traerAdjuntos($id_orden){
			$this->id_orden = $id_orden;

			$queryGetAdjuntos = "SELECT adj.id as id_adjunto, adj.archivo, usr.email, adj.comentarios, 
								adj.fecha_hora as fecha
								FROM adjuntos_orden_compra as adj JOIN usuarios as usr
								ON(adj.id_usuario = usr.id)
								WHERE id_orden_compra = $this->id_orden";
			$getAdjuntos = $this->conexion->consultaRetorno($queryGetAdjuntos);

			$arrayAdjuntos = array();

			while($rowAdj = $getAdjuntos->fetch_array()){
				$id_adjunto = $rowAdj['id_adjunto'];
				$archivo = $rowAdj['archivo'];
				$email = $rowAdj['email'];
				$comentarios = $rowAdj['comentarios'];
				$fechaFormat = $fecha= date("d/m/Y H:m:s", strtotime($rowAdj['fecha']));
				$fecha = $fechaFormat;
				$arrayAdjuntos[]=array("id_adjunto"=>$id_adjunto, "archivo"=>$archivo, "email"=>$email, "comentarios"=>$comentarios, "fecha"=>$fecha);
			}

			echo json_encode($arrayAdjuntos);

		}

		public function EliminarAdjuntos($id_adjunto, $nombre_adjunto){

			$this->id_adj = $id_adjunto;

			$queryDelAdjunto= "DELETE FROM adjuntos_orden_compra WHERE id = $this->id_adj";
			$delAdjunto = $this->conexion->consultaSimple($queryDelAdjunto);

			$directorio = "../views/adjuntosOC/";

			$rutaCompleta = $directorio.$nombre_adjunto;
			
			unlink($rutaCompleta);

		}

		public function adjuntarArchivo($id_orden, $archivo, $comentarios){

			$this->id_orden = $id_orden;


			$nombreImagen = $archivo['name'];
			$directorio = "../views/adjuntosOC/";
			$nombreFinalArchivo = $nombreImagen;
			$id_usuario = $_SESSION['rowUsers']['id_usuario'];
			$fecha= date("Y-m-d H:i:s");

			$nombreEnv = $id_orden."_".$nombreFinalArchivo;

			move_uploaded_file($archivo['tmp_name'], $directorio.$id_orden."_".$nombreFinalArchivo);

			/*BUSCO ID_PROVEEDOR*/

			$queryGetProveedor = "SELECT id_proveedor FROM ordenes_compra 
								WHERE id = $this->id_orden";
			$getProveedor = $this->conexion->consultaRetorno($queryGetProveedor);

			$rowIdProv = $getProveedor->fetch_assoc();

			$id_proveedor = $rowIdProv['id_proveedor'];


			/*ACTUALIZO ADJUNTOS*/
			$queryUpdateAdjuntos = "INSERT INTO adjuntos_orden_compra(id_orden_compra, archivo, fecha_hora, id_usuario, id_proveedor, comentarios)VALUES($this->id_orden, '$nombreEnv', '$fecha', $id_usuario, $id_proveedor, '$comentarios')";
			$updateAdjuntos= $this->conexion->consultaSimple($queryUpdateAdjuntos);
		}

		public function agregarItemManual($descripcion, $categoria, $Umedida, $tipo, $preposicion, $estado, $linkVideo, $archivo, $id_empresa, $id_cc, $precio, $id_proveedor){
			$fecha_alta = date('Y-m-d');

			$id_item= "";

			if ($estado == 'Activo') {
				$estado = 1;
			}else{
				$estado = 0;
			}


			/*GUARDO EN TABLA ITEM*/
			$queryInsertItem = "INSERT INTO item(item, id_tipo, id_categoria, id_unidad_medida, link_video, punto_reposicion, fecha_alta, activo, id_cc, id_empresa)VALUES('$descripcion', $tipo, $categoria, $Umedida, '$linkVideo', $preposicion, '$fecha_alta',$estado, $id_cc, $id_empresa)";
			$insertarItem= $this->conexion->consultaSimple($queryInsertItem);

			/*BUSCO EL ID DEL ITEM CREADO PARA COLOCARLO COMO IDENTIFICADOR EN LA FOTO*/
			/*$queryGetIdItem = "SELECT id as id_item FROM item 
							WHERE item = '$descripcion'
							AND id_tipo = $tipo
							AND id_categoria = $categoria
							AND id_unidad_medida = $Umedida
							AND punto_reposicion = $preposicion
							AND fecha_alta = '$fecha_alta'";
			$getIdItem = $this->conexion->consultaRetorno($queryGetIdItem);

			if ($getIdItem->num_rows > 0 ) {
				$idRow = $getIdItem->fetch_assoc();
				$id_item = $idRow['id_item'];
			}*/
      $id_item=$this->conexion->conectar->insert_id;

			//GUARDO IMAGEN EN EL DIRECTORIO
			if($archivo !=""){
				$nombreImagen = $archivo['name'];
				$directorio = "../views/img_items/";
				$nombreFinalArchivo = $nombreImagen;
				move_uploaded_file($archivo['tmp_name'], $directorio.$id_item."_".$nombreFinalArchivo);
				//$ruta_completa_imagen = $directorio.$nombreFinalArchivo;
				$archivo = $id_item."_".$nombreFinalArchivo;
			}

			//ACTUALIZO NOMBRE IMAGEN DEL ITEM
			$queryUpdateImageName = "UPDATE item SET imagen='$archivo'
									WHERE id = $id_item";
			$updateImageName = $this->conexion->consultaSimple($queryUpdateImageName);

			/*Inserto el movimiento en la tabla lista_precios*/
			$queryInsertLP = "INSERT INTO lista_precios(id_item, id_proveedor,  
							precio, fecha_hora_ultima_actualizacion) VALUES($id_item, $id_proveedor, $precio, '$fecha_alta')";
			$insertLP = $this->conexion->consultaSimple($queryInsertLP);

      return $id_item;

		}

	}

	if (isset($_POST['accion'])) {
		$ordenes = new Ordenes();
		switch ($_POST['accion']) {
			case 'eliminarOrdenCompra':
				$id_orden= $_POST['id_orden'];
				$ordenes->delOrden($id_orden);
				break;
			case 'addOrdenCompra':
					$items = $_POST['items'];
					$total = $_POST['total'];
					$proveedor= $_POST['proveedor'];
					$almacen = $_POST['almacen'];
					$cCostos = $_POST['cCostos'];
					$comentarios = $_POST['comentarios'];
					$id_empresa = $_POST['id_empresa'];
					$ordenes->agregarItemsOrdenes($items, $proveedor, $almacen, $cCostos, $total, $comentarios, $id_empresa);
				break;
			case 'traerDatosInicialesOC':
				$id_empresa = $_POST['id_empresa'];
				echo $ordenes->traerDatosIniciales($id_empresa);
				break;
			case 'traerOrdenUpdate':
				$id_orden = $_POST['id_orden'];
				$ordenes->traerOrdenesUpdate($id_orden);
				break;
			case 'UpdateOrden':
				$items = $_POST['items'];
				$total = $_POST['total'];
				$proveedor= $_POST['proveedor'];
				$almacen = $_POST['almacen'];
				$id_orden = $_POST['id_orden'];
				$comentarios = $_POST['comentarios'];
				$cCostos = $_POST['cCostos'];
				$ordenes->updateOrdenes($items, $proveedor, $almacen, $total, $id_orden, $comentarios, $cCostos);
				break;
			case 'cambiarEstado':
				$id_orden=$_POST['id_orden'];
				$id_estado=$_POST['estado'];
				$ordenes->updateEstado($id_orden, $id_estado);
				break;
			case 'traerEstados':
				$id_orden=$_POST['id_orden'];
				$ordenes->traerEstadosOrdenes($id_orden);
				break;
			case 'traerDetalle':
				$id_orden=$_POST['id_orden'];
				$ordenes->traerOrdenesDetalle($id_orden);
				break;
			case 'traerEstadosGrafico':
				$fDesde = $_POST['fdesde'];
				$fHasta = $_POST['fhasta'];
				$id_empresa = $_POST['id_empresa'];
				$ordenes->traerEstadosGrafico($fDesde, $fHasta, $id_empresa);
				break;
			case 'traerContactosOrden':
				$id_orden=$_POST['id_orden'];
				$ordenes->traerContactos($id_orden);
				break;
			case 'recibirPedido':
				$id_orden=$_POST['id_orden'];
				$itemsRecibir=$_POST['itemsRecibir'];
				$ordenes->recibirPedido($id_orden, $itemsRecibir);
				break;
			case 'traerAdjuntos':
				$id_orden = $_POST['id_orden'];
				$ordenes->traerAdjuntos($id_orden);
				break;
			case 'borrarAdjunto':
				$id_adjunto = $_POST['id_adjunto'];
				$nombre_adjunto = $_POST['nombre_adjunto'];
				$ordenes->EliminarAdjuntos($id_adjunto, $nombre_adjunto);
				break;
			case 'adjuntarArchivo':
				$id_orden = $_POST['id_orden'];
				$archivo = $_FILES['file'];
				$comentarios = $_POST['comentarios'];
				$ordenes->adjuntarArchivo($id_orden, $archivo, $comentarios);
				break;
			case 'traerItems':
					$id_proveedor = $_POST['id_proveedor'];
					$ordenes->traerItems($id_proveedor);
				break;
			case 'addArticulo':
					$descripcion = $_POST['descripcion'];
					$categoria = $_POST['categoria'];
					$Umedida = $_POST['Umedida'];
					$tipo = $_POST['tipo'];
					$preposicion = $_POST['preposicion'];
					$estado = $_POST['estado'];
					$linkVideo = $_POST['linkVideo'];
					$id_item = $_POST['id_item'];
					$id_empresa = $_POST['id_empresa'];
					$id_cc = $_POST['id_cc'];
					$precio = $_POST['precio'];
					$id_proveedor = $_POST['id_proveedor'];

					if(isset($_FILES['file'])) {
						$archivo = $_FILES['file'];
					}else{
						$archivo = "";
					}
					echo $ordenes->agregarItemManual($descripcion, $categoria, $Umedida, $tipo, $preposicion, $estado, $linkVideo, $archivo, $id_empresa, $id_cc, $precio, $id_proveedor);
				break;
		}
	}else{
		if (isset($_GET['accion'])) {
			$ordenes = new Ordenes();

			switch ($_GET['accion']) {
				case 'traerItems':
					$id_proveedor = $_GET['id_proveedor'];
					$ordenes->traerItems($id_proveedor);
				break;
				case 'traerOrdenes':
					$id_empresa = $_GET['id_empresa'];
					$ordenes->traerOrdenes($id_empresa);
					break;
				case 'traerItemsUpdateOrden':
					$id_orden = $_GET['id_orden'];
					$id_proveedor = $_GET['id_proveedor'];
					$ordenes->traerItemsUpdateOrden($id_orden, $id_proveedor);
					break;
				case 'traerOrdenesFiltro':
					$fDesde = $_GET['fdesde'];
					$fHasta = $_GET['fhasta'];
					$ordenes->traerOrdenesFiltro($fDesde, $fHasta);
					break;
				case 'traerItemsRecibir':
					$id_orden = $_GET['id_orden'];
					$ordenes->traerItemsRecibir($id_orden);
					break;

			}
		}
	}
?>