<?php
	session_start();
	require_once('../../conexion.php');
	class ReportesIva{

		private $id_proveedor;
		private $id_orden;
		private $id_empresa;

		public function __construct(){
			$this->conexion = new Conexion();
			date_default_timezone_set("America/Buenos_Aires");
		}

		public function traerDatosIniciales($id_empresa){

			$this->id_empresa = $id_empresa;

			/*PROVEEDORES*/
			$queryProveedores = "SELECT id as id_proveedor, razon_social 
								FROM proveedores 
								WHERE id_empresa = $this->id_empresa
								AND activo = 1";
			$getProveedores = $this->conexion->consultaRetorno($queryProveedores);

			$datosIniciales = array();
			$proveedores = array();

			/*CARGO ARRAY PROVEEDORES*/
			while ($rowProveedores= $getProveedores->fetch_array()) {
				$id_proveedor = $rowProveedores['id_proveedor'];
				$razon_social = $rowProveedores['razon_social'];
				$proveedores[] = array('id_proveedor' => $id_proveedor, 'razon_social' =>$razon_social);
			}

			$datosIniciales["proveedores"] = $proveedores;

			echo json_encode($datosIniciales);
		}

		public function traerReportesIva($id_empresa){
			
			$this->id_empresa = $id_empresa;
			$arrayReportesIva = array();

			/*BUSCO ORDENES DE COMPRA*/
			$queryOrdenesCompra = "SELECT oc.id as id_oc, razon_social as proveedor,
								total, (total*0.21) as impuesto, fecha 
								FROM ordenes_compra oc JOIN proveedores prov
								ON(oc.id_proveedor = prov.id)
								WHERE oc.id_empresa = $this->id_empresa";
			$getOrdenes = $this->conexion->consultaRetorno($queryOrdenesCompra);

			/*LLENO ARRAY REPORTES CON ORDENES DE COMPRA*/
			while ($row = $getOrdenes->fetch_array()) {
				$origen= "<b>Orden de compra nro: </b>".$row['id_oc'];
				$proveedor= $row['proveedor'];
				$total= "$".number_format($row['total'],2,',','.');
				$impuesto = "$".number_format($row['impuesto'],2,',','.');
				$fecha= date("d/m/Y", strtotime($row['fecha']));
				$arrayReportesIva[] = array('origen'=>$origen, 'proveedor'=>$proveedor, 'total'=>$total, 'impuesto'=>$impuesto, 'fecha'=>$fecha, 'nro_comprobante'=>'');
			}


			/*BUSCO INGRESO EGRESO CAJA DIARIA*/
			/*$queryGetCajaDiaria = "SELECT cd.id as id_caja_diaria, tc.tipo, 
								(saldo_apertura - saldo_cierre) as total, 
								((saldo_apertura - saldo_cierre)*0.21) impuesto, 
								fecha
								FROM caja_diaria as cd JOIN tipos_caja as tc
								ON(cd.id_tipo_caja = tc.id)
								WHERE cd.id_empresa = $this->id_empresa
								and saldo_cierre < saldo_apertura";
			$getCajaDiaria = $this->conexion->consultaRetorno($queryGetCajaDiaria);*/

			$queryGetCajaDiaria = "SELECT tc.tipo, cdd.monto total,  
									cdd.importe_impuestos impuesto, cdd.fecha_hora as fecha, cdd.nro_comprobante
								FROM caja_diaria_detalles as cdd JOIN caja_diaria as cd
								ON(cdd.id_caja_diaria = cd.id)
								JOIN tipos_caja tc
								on(cd.id_tipo_caja = tc.id)
								WHERE cd.id_empresa = $this->id_empresa";
			$getCajaDiaria = $this->conexion->consultaRetorno($queryGetCajaDiaria);


			/*LLENO ARRAY REPORTES CON MOVIMIENTOS CAJA*/
			while ($row = $getCajaDiaria->fetch_array()) {
				$origen= "<b>Caja diaria: </b>".$row['tipo'];
				$proveedor= "";
				$total= "$".number_format($row['total'],2,',','.');
				$impuesto = "$".number_format($row['impuesto'],2,',','.');
				$fecha= date("d/m/Y", strtotime($row['fecha']));
				$nroComprobante = $row['nro_comprobante'];
				$arrayReportesIva[] = array('origen'=>$origen, 'proveedor'=>$proveedor, 'total'=>$total, 'impuesto'=>$impuesto, 'fecha'=>$fecha, 'nro_comprobante'=>$nroComprobante);
			}


			echo json_encode($arrayReportesIva);
		}

		public function traerReporteIvaFiltros($filtrosRecibidos, $id_empresa){
			
			$this->id_empresa = $id_empresa;
			$arrayReportesIva = array();


			$filtros = json_decode($filtrosRecibidos);
			$condicion_oc = "";
			$condicion_cd = "";
			$condicion_fechas_cd ="";
			$condicion_fechas_oc ="";

			if($filtros->origen!="nn"){

				if ($filtros->origen == "cd") {

					foreach ($filtros as $key => $value) {
						
						if ($key!="origen") {
							if($key == "fdesde"){
								$condicion_fechas_cd = "and date_format(fecha_hora, '%Y-%m-%d') between '".$value."' ";
							}
							if($key=="fhasta"){
								$condicion_fechas_cd = $condicion_fechas_cd." and '".$value."'";
							}
						}
					}


					/*BUSCO INGRESO EGRESO CAJA DIARIA*/

					$queryGetCajaDiaria = "SELECT tc.tipo, cdd.monto total,  
								cdd.importe_impuestos impuesto, 
								cdd.fecha_hora as fecha, cdd.nro_comprobante
								FROM caja_diaria_detalles as cdd JOIN caja_diaria as cd
								ON(cdd.id_caja_diaria = cd.id)
								JOIN tipos_caja tc
								on(cd.id_tipo_caja = tc.id)
								WHERE cd.id_empresa = $this->id_empresa ".$condicion_fechas_cd;
					$getCajaDiaria = $this->conexion->consultaRetorno($queryGetCajaDiaria);

					/*LLENO ARRAY REPORTES CON MOVIMIENTOS CAJA*/
					while ($row = $getCajaDiaria->fetch_array()) {
						$origen= "<b>Caja diaria: </b>".$row['tipo'];
						$proveedor= "";
						$total= "$".number_format($row['total'],2,',','.');
						$impuesto = "$".number_format($row['impuesto'],2,',','.');
						$fecha= date("d/m/Y", strtotime($row['fecha']));
						$nroComprobante = $row['nro_comprobante'];
						$arrayReportesIva[] = array('origen'=>$origen, 'proveedor'=>$proveedor, 'total'=>$total, 'impuesto'=>$impuesto, 'fecha'=>$fecha, 'nro_comprobante'=>$nroComprobante);
					}

				 }else if($filtros->origen == "oc" || (isset($filtros->id_proveedor) && $filtros->id_proveedor !=="")){

				 	foreach ($filtros as $key => $value) {
						
						if ($key!="origen") {
							if($key == "fdesde"){
								$condicion_fechas_oc = "and date_format(fecha, '%Y-%m-%d') between '".$value."' ";
							}
							if($key=="fhasta"){
								$condicion_fechas_oc = $condicion_fechas_oc." and '".$value."'";
							}
							if($key=="id_proveedor"){
								$condicion_oc = " and id_proveedor = ".$value;
							}
						}
					}

					/*BUSCO ORDENES DE COMPRA*/
					$queryOrdenesCompra = "SELECT oc.id as id_oc, razon_social as proveedor,
										total, (total*0.21) as impuesto, fecha 
										FROM ordenes_compra oc JOIN proveedores prov
										ON(oc.id_proveedor = prov.id)
										WHERE oc.id_empresa = $this->id_empresa ".$condicion_fechas_oc.$condicion_oc;
					$getOrdenes = $this->conexion->consultaRetorno($queryOrdenesCompra);

					/*LLENO ARRAY REPORTES CON ORDENES DE COMPRA*/
					while ($row = $getOrdenes->fetch_array()) {
						$origen= "<b>Orden de compra nro: </b>".$row['id_oc'];
						$proveedor= $row['proveedor'];
						$total= "$".number_format($row['total'],2,',','.');
						$impuesto = "$".number_format($row['impuesto'],2,',','.');
						$fecha= date("d/m/Y", strtotime($row['fecha']));
						$arrayReportesIva[] = array('origen'=>$origen, 'proveedor'=>$proveedor, 'total'=>$total, 'impuesto'=>$impuesto, 'fecha'=>$fecha, 'nro_comprobante'=>'');
					}	

				 }
				 }
			if($filtros->origen=="nn" && !isset($filtros->id_proveedor)){

				foreach ($filtros as $key => $value) {
						
						if ($key!="origen") {
							if($key == "fdesde"){
								$condicion_fechas_cd = "and date_format(fecha_hora, '%Y-%m-%d') between '".$value."' ";
							}
							if($key=="fhasta"){
								$condicion_fechas_cd = $condicion_fechas_cd." and '".$value."'";
							}
						}
					}

					foreach ($filtros as $key => $value) {
						
						if ($key!="origen") {
							if($key == "fdesde"){
								$condicion_fechas_oc = "and date_format(fecha, '%Y-%m-%d') between '".$value."' ";
							}
							if($key=="fhasta"){
								$condicion_fechas_oc = $condicion_fechas_oc." and '".$value."'";
							}
							if($key=="id_proveedor"){
								$condicion_oc = " and id_proveedor = ".$value;
							}
						}
					}

					/*BUSCO ORDENES DE COMPRA*/
					$queryOrdenesCompra = "SELECT oc.id as id_oc, razon_social as proveedor,
										total, (total*0.21) as impuesto, fecha 
										FROM ordenes_compra oc JOIN proveedores prov
										ON(oc.id_proveedor = prov.id)
										WHERE oc.id_empresa = $this->id_empresa ".$condicion_fechas_oc;
					$getOrdenes = $this->conexion->consultaRetorno($queryOrdenesCompra);

					/*LLENO ARRAY REPORTES CON ORDENES DE COMPRA*/
					while ($row = $getOrdenes->fetch_array()) {
						$origen= "<b>Orden de compra nro: </b>".$row['id_oc'];
						$proveedor= $row['proveedor'];
						$total= "$".number_format($row['total'],2,',','.');
						$impuesto = "$".number_format($row['impuesto'],2,',','.');
						$fecha= date("d/m/Y", strtotime($row['fecha']));
						$arrayReportesIva[] = array('origen'=>$origen, 'proveedor'=>$proveedor, 'total'=>$total, 'impuesto'=>$impuesto, 'fecha'=>$fecha, 'nro_comprobante'=>'');
					}


					/*BUSCO INGRESO EGRESO CAJA DIARIA*/
					$queryGetCajaDiaria = "SELECT tc.tipo, cdd.monto total,  
								cdd.importe_impuestos impuesto, 
								cdd.fecha_hora as fecha, cdd.nro_comprobante
								FROM caja_diaria_detalles as cdd JOIN caja_diaria as cd
								ON(cdd.id_caja_diaria = cd.id)
								JOIN tipos_caja tc
								on(cd.id_tipo_caja = tc.id)
								WHERE cd.id_empresa = $this->id_empresa ".$condicion_fechas_cd;
					$getCajaDiaria = $this->conexion->consultaRetorno($queryGetCajaDiaria);

					/*LLENO ARRAY REPORTES CON MOVIMIENTOS CAJA*/
					while ($row = $getCajaDiaria->fetch_array()) {
						$origen= "<b>Caja diaria: </b>".$row['tipo'];
						$proveedor= "";
						$total= "$".number_format($row['total'],2,',','.');
						$impuesto = "$".number_format($row['impuesto'],2,',','.');
						$fecha= date("d/m/Y", strtotime($row['fecha']));
						$nroComprobante = $row['nro_comprobante'];
						$arrayReportesIva[] = array('origen'=>$origen, 'proveedor'=>$proveedor, 'total'=>$total, 'impuesto'=>$impuesto, 'fecha'=>$fecha, 'nro_comprobante'=>$nroComprobante);
					}
			}
			echo json_encode($arrayReportesIva);
		}

	}

	if (isset($_POST['accion'])) {
		$reportesIva = new ReportesIva();
		switch ($_POST['accion']) {
			case 'traerDatosIniciales':
				$id_empresa = $_POST['id_empresa'];
				$reportesIva->traerDatosIniciales($id_empresa);
				break;
			default:
				// code...
				break;
		}
		
	}else{
		if (isset($_GET['accion'])) {
			$reportesIva = new ReportesIva();

			switch ($_GET['accion']) {
				case 'traerReporteIva':
					$id_empresa = $_GET['id_empresa'];
					$reportesIva->traerReportesIva($id_empresa);
					break;
				case 'traerReporteIvaFiltros':
					if(!isset($_GET['filtros'])){
						$filtros = "";
					}else{
						$filtros = $_GET['filtros'];
					}
					
					$id_empresa = $_GET['id_empresa'];
					$reportesIva->traerReporteIvaFiltros($filtros, $id_empresa);
					break;

			}
		}
	}
?>