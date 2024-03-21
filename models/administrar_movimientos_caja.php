<?php
	session_start();
	require_once('./conexion.php');
	class MovimientosCaja{

		private $id_empresa;

		public function __construct(){
			$this->conexion = new Conexion();
			date_default_timezone_set("America/Buenos_Aires");
		}

		public function traerDatosIniciales($id_empresa){

			$this->id_empresa = $id_empresa;
			$datosIniciales = array();
			$tiposMovCaja = array();
			$tiposCaja = array();

			/*TIPOS DE MOVIMIENTOS DE CAJA*/
			$queryTiposMovimientos ="SELECT id as id_tipo_mov, tipo 
									FROM tipos_movimientos_caja";
			$getTiposMovimientos = $this->conexion->consultaRetorno($queryTiposMovimientos);

			/*TIPOS DE CAJA*/
			$queryTiposCaja = "SELECT id as id_tipo_caja, tipo as tipo_caja 
							FROM tipos_caja
							WHERE id_empresa = $this->id_empresa";
			$getTiposCaja = $this->conexion->consultaRetorno($queryTiposCaja);

			/*COMPLETO ARRAY TIPOS MOVIMIENTOS CAJA*/
			if($getTiposMovimientos->num_rows > 0){
				while($rowMovimientos = $getTiposMovimientos->fetch_assoc()){
					$id_tipo_mov = $rowMovimientos['id_tipo_mov'];
					$tipo_mov = $rowMovimientos['tipo'];

					$tiposMovCaja[] = array('id_tipo_mov'=>$id_tipo_mov, 'tipo_mov'=>$tipo_mov);
				}
			}

			/*COMPLETO ARRAY TIPOS DE CAJA*/
			while ($rowTiposCaja = $getTiposCaja->fetch_assoc()) {
				$id_tipo_caja = $rowTiposCaja['id_tipo_caja'];
				$tipo_caja = $rowTiposCaja['tipo_caja'];

				$tiposCaja[]=array('id_tipo_caja'=>$id_tipo_caja, 'tipo_caja'=>$tipo_caja);
			}

			$datosIniciales['tipos_mov'] = $tiposMovCaja;
			$datosIniciales['tipos_caja'] = $tiposCaja;

			echo json_encode($datosIniciales);

		}

		public function traerMovimientos(){
			
			$arrayMovimientos = array();

			$queryGetMovimientos = "SELECT cdd.id as id_cdd, tmp.tipo, 
								tc.tipo as caja, monto, detalle, fecha_hora,
								cdd.nro_comprobante, cdd.adjunto
								FROM caja_diaria_detalles as cdd JOIN tipos_movimientos_caja as tmp
								ON(cdd.id_tipo_movimiento = tmp.id)
								JOIN caja_diaria as cd
								ON(cdd.id_caja_diaria=cd.id)
								JOIN tipos_caja as tc
								ON( cd.id_tipo_caja= tc.id)";
			$getMovimientos = $this->conexion->consultaRetorno($queryGetMovimientos);

			while ($rowMovimiento = $getMovimientos->fetch_assoc()) {
				$id_cdd= $rowMovimiento['id_cdd'];
				$tipo= $rowMovimiento['tipo'];
				$caja= $rowMovimiento['caja'];
				$monto = "$".number_format($rowMovimiento['monto'],2,',','.');
				$detalle = $rowMovimiento['detalle'];
				$fecha_hora= date("d/m/Y H:m:s", strtotime($rowMovimiento['fecha_hora']));
				$nroComprobante = $rowMovimiento['nro_comprobante'];
				$adjunto = $rowMovimiento['adjunto'];
				$arrayMovimientos[] = array('id_cdd'=>$id_cdd, 'tipo'=>$tipo, 'caja'=>$caja, 'monto'=>$monto, 'detalle'=>$detalle, 'fecha_hora'=>$fecha_hora, 'nroComprobante'=>$nroComprobante, 'adjunto'=>$adjunto);
			}

			echo json_encode($arrayMovimientos);
		}

		public function traerMovimientosFiltros($filtrosRecibidos, $id_empresa){
			
			$this->id_empresa = $id_empresa;
			$arrayMovimientos = array();
			$condicion = "";
			$filtros = json_decode($filtrosRecibidos);
			//var_dump($filtro);

			if($filtros != ""){
			foreach ($filtros as $key => $value) {
					if($key == "id_tipo_movimiento"){
						$condicion=$condicion."and cdd.".$key."=".$value." ";
					}
					if($key == "id_tipo_caja"){
						$condicion=$condicion."and cd.".$key."=".$value." ";
					}
					if($key == "fecha_hora"){
						$condicion=$condicion."and date_format(cdd.".$key.",'%Y-%m-%d')='".$value."' ";
					}
					
				}
			}

			
			$queryGetMovimientos = "SELECT cdd.id as id_cdd, tmp.tipo, 
								tc.tipo as caja, monto, detalle, fecha_hora,
								cdd.nro_comprobante, cdd.adjunto
								FROM caja_diaria_detalles as cdd JOIN tipos_movimientos_caja as tmp
								ON(cdd.id_tipo_movimiento = tmp.id)
								JOIN caja_diaria as cd
								ON(cdd.id_caja_diaria=cd.id)
								JOIN tipos_caja as tc
								ON( cd.id_tipo_caja= tc.id)
								WHERE cd.id_empresa = $this->id_empresa ".$condicion;
			$getMovimientos = $this->conexion->consultaRetorno($queryGetMovimientos);

			while ($rowMovimiento = $getMovimientos->fetch_assoc()) {
				$id_cdd= $rowMovimiento['id_cdd'];
				$tipo= $rowMovimiento['tipo'];
				$caja= $rowMovimiento['caja'];
				$monto = "$".number_format($rowMovimiento['monto'],2,',','.');
				$detalle = $rowMovimiento['detalle'];
				$fecha_hora= date("d/m/Y H:m:s", strtotime($rowMovimiento['fecha_hora']));
				$nroComprobante = $rowMovimiento['nro_comprobante'];
				$adjunto = $rowMovimiento['adjunto'];
				$arrayMovimientos[] = array('id_cdd'=>$id_cdd, 'tipo'=>$tipo, 'caja'=>$caja, 'monto'=>$monto, 'detalle'=>$detalle, 'fecha_hora'=>$fecha_hora, 'nroComprobante'=>$nroComprobante, 'adjunto'=>$adjunto);
			}

			echo json_encode($arrayMovimientos);
		}
}

	if (isset($_POST['accion'])) {
		$movimientosCaja = new MovimientosCaja();
		switch ($_POST['accion']) {
			case 'traerDatosIniciales':
				$id_empresa = $_POST['id_empresa'];
				$movimientosCaja->traerDatosIniciales($id_empresa);
				break;
			case 'actualizarTotalizadores':
				$almacen = $_POST['almacen'];
				$proveedor = $_POST['proveedor'];
				$movimientosCaja->traerTotalizadoresFiltro($almacen, $proveedor);
				break;
		}
	}else{
		if (isset($_GET['accion'])) {
			$movimientosCaja = new MovimientosCaja();
			switch ($_GET['accion']) {
				case 'traerMovimientos':
					$movimientosCaja->traerMovimientos();
					break;
				case 'traerMovimientosFiltro':
					if(!isset($_GET['filtros'])){
						$filtros = "";
					}else{
						$filtros = $_GET['filtros'];
					}
					
					$id_empresa = $_GET['id_empresa'];
					$movimientosCaja->traerMovimientosFiltros($filtros, $id_empresa);
					break;
			}
		}
	}
?>