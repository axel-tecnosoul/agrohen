<?php 

session_start();
  if (!isset($_SESSION['rowUsers']['id_usuario'])) {
      header("location:./redireccionar.php");
  }else{
	include_once('../../conexion.php');  	
  }

class Descargas{
	
	public function __construct(){
			$this->conexion = new Conexion();
			date_default_timezone_set("America/Buenos_Aires");
		}

			public function traerReporteIva($filtrosRecibidos, $id_empresa){
			
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
						$origen= "Caja diaria: ".$row['tipo'];
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
						$origen= "Orden de compra nro: ".$row['id_oc'];
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
						$origen= "Orden de compra nro: ".$row['id_oc'];
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
						$origen= "Caja diaria: ".$row['tipo'];
						$proveedor= "";
						$total= "$".number_format($row['total'],2,',','.');
						$impuesto = "$".number_format($row['impuesto'],2,',','.');
						$fecha= date("d/m/Y", strtotime($row['fecha']));
						$nroComprobante = $row['nro_comprobante'];
						$arrayReportesIva[] = array('origen'=>$origen, 'proveedor'=>$proveedor, 'total'=>$total, 'impuesto'=>$impuesto, 'fecha'=>$fecha, 'nro_comprobante'=>$nroComprobante);
					}
			}
			$this->descargarArchivosIva($arrayReportesIva);
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
								tc.tipo as caja, monto, detalle, fecha_hora, cdd.nro_comprobante 
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
				$arrayMovimientos[] = array('id_cdd'=>$id_cdd, 'tipo'=>$tipo, 'caja'=>$caja, 'monto'=>$monto, 'detalle'=>$detalle, 'fecha_hora'=>$fecha_hora, 'nro_comprobante'=>$nroComprobante);
			}

			//echo json_encode($arrayMovimientos);
			$this->descargarArchivosMivimientosCaja($arrayMovimientos);
		}

	public function descargarArchivosIva($arrayReportesIva){
		

		$filename = "Reporte_IVA";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$filename.'.xls'); //Especifica el nombre del archivo a descargar
		header('Cache-Control: max-age=0');
		require_once('./PHPExcel/Classes/PHPExcel.php');
		

		$excel = new PHPExcel();

		$excel->getProperties()->setCreator('')->setLastModifiedBy('')->setTitle('Lista');
		$excel->setActiveSheetIndex(0);

		$pagina = $excel->getActiveSheet();

		$pagina->setTitle('Reporte_IVA');


		$pagina->setCellValue('A1', 'FECHA');
		$pagina->setCellValue('B1', 'ORIGEN');
		$pagina->setCellValue('C1', 'NRO. COMPROBANTE');
		$pagina->setCellValue('D1', 'PROVEEDOR');
		$pagina->setCellValue('E1', 'TOTAL');
		$pagina->setCellValue('F1', 'TOTAL IMPUESTOS');

		$pagina->getStyle('A1:F1')->getFont()->setBold(true);
		/*$pagina->getStyle('A1:C1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);*/

		$pagina->getStyle('A1:F1')->applyFromArray(
			array(
				'borders'=> array(
					'allborders' => array(
						'style'=> PHPExcel_Style_Border::BORDER_THIN
					)
				)
			)
		);

		$pagina->getStyle('A1:F1')->applyFromArray( array( 'fill' => array( 'type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'EFF1F1') ) ) );


			$j=2;
			$i=0;

			foreach ($arrayReportesIva as $value) {
				$pagina->setCellValue('A'.($j), $value['fecha']);
				$pagina->setCellValue('B'.($j), $value['origen']);
				$pagina->setCellValue('C'.($j), $value['nro_comprobante']);
				$pagina->setCellValue('D'.($j), $value['proveedor']);
				$pagina->setCellValue('E'.($j), $value['total']);
				$pagina->setCellValue('F'.($j), $value['impuesto']);
				
				$pagina->getStyle('A'.$j.':F'.$j)->applyFromArray(
				array(
					'borders'=> array(
						'allborders' => array(
							'style'=> PHPExcel_Style_Border::BORDER_THIN
						)
					)
				)
			);


				$j=$j+1;
				$i+=$i;
			}


			foreach (range('A', 'F') as $column) {
				$pagina->getColumnDimension($column)->setAutoSize(true);
			}

		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
		$objWriter->save('php://output');


	}
	public function descargarArchivosMivimientosCaja($arrayMovimientos){

		$filename = "Movimientos_caja";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$filename.'.xls'); //Especifica el nombre del archivo a descargar
		header('Cache-Control: max-age=0');
		require_once('./PHPExcel/Classes/PHPExcel.php');
		

		$excel = new PHPExcel();

		$excel->getProperties()->setCreator('')->setLastModifiedBy('')->setTitle('Lista');
		$excel->setActiveSheetIndex(0);

		$pagina = $excel->getActiveSheet();

		$pagina->setTitle('Movimientos_caja');


		$pagina->setCellValue('A1', '#ID');
		$pagina->setCellValue('B1', 'TIPO');
		$pagina->setCellValue('C1', 'NRO. COMPROBANTE');
		$pagina->setCellValue('D1', 'CAJA');
		$pagina->setCellValue('E1', 'MONTO');
		$pagina->setCellValue('F1', 'DETALLE');
		$pagina->setCellValue('G1', 'FECHA');

		$pagina->getStyle('A1:G1')->getFont()->setBold(true);
		/*$pagina->getStyle('A1:C1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);*/

		$pagina->getStyle('A1:G1')->applyFromArray(
			array(
				'borders'=> array(
					'allborders' => array(
						'style'=> PHPExcel_Style_Border::BORDER_THIN
					)
				)
			)
		);

		$pagina->getStyle('A1:G1')->applyFromArray( array( 'fill' => array( 'type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'EFF1F1') ) ) );


			$j=2;
			$i=0;

			foreach ($arrayMovimientos as $value) {
				$pagina->setCellValue('A'.($j), $value['id_cdd']);
				$pagina->setCellValue('B'.($j), $value['tipo']);
				$pagina->setCellValue('C'.($j), $value['nro_comprobante']);
				$pagina->setCellValue('D'.($j), $value['caja']);
				$pagina->setCellValue('E'.($j), $value['monto']);
				$pagina->setCellValue('F'.($j), $value['detalle']);
				$pagina->setCellValue('G'.($j), $value['fecha_hora']);
				
				$pagina->getStyle('A'.$j.':G'.$j)->applyFromArray(
				array(
					'borders'=> array(
						'allborders' => array(
							'style'=> PHPExcel_Style_Border::BORDER_THIN
						)
					)
				)
			);


				$j=$j+1;
				$i+=$i;
			}


			foreach (range('A', 'G') as $column) {
				$pagina->getColumnDimension($column)->setAutoSize(true);
			}

		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
		$objWriter->save('php://output');
	}
}

if (isset($_GET['accion'])) {
			$descargas = new Descargas();

			switch ($_GET['accion']) {
				case 'exportarReporteIva':
					if(!isset($_GET['filtros'])){
						$filtros = "";
					}else{
						$filtros = $_GET['filtros'];
					}
					
					$id_empresa = $_GET['id_empresa'];
					$descargas->traerReporteIva($filtros, $id_empresa);
					break;
				case 'exportarReporteMovCajas':
					if(!isset($_GET['filtros'])){
						$filtros = "";
					}else{
						$filtros = $_GET['filtros'];
					}
					
					$id_empresa = $_GET['id_empresa'];
					$descargas->traerMovimientosFiltros($filtros, $id_empresa);
					break;

			}
		}

?>