<?php
	$datosExportar = json_decode($_GET['stock']);

	$fecha = date('Ymd');
	$filename = "Stock_".$fecha;
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename='.$filename.'.xls'); //Especifica el nombre del archivo a descargar
	header('Cache-Control: max-age=0');
	require_once('models/PHPExcel/Classes/PHPExcel.php');
	
	
	

	$excel = new PHPExcel();

	$excel->getProperties()->setCreator('')->setLastModifiedBy('')->setTitle('Lista');
	$excel->setActiveSheetIndex(0);

	$pagina = $excel->getActiveSheet();

	$pagina->setTitle('Stock');


	$pagina->setCellValue('A1', 'ID');
	$pagina->setCellValue('B1', 'ITEM');
	$pagina->setCellValue('C1', 'PROVEEDOR');
	$pagina->setCellValue('D1', 'ALMACEN');
	$pagina->setCellValue('E1', 'CANTIDAD DISPONIBLE');
	$pagina->setCellValue('F1', 'CANTIDAD RESERVADA');
	$pagina->setCellValue('G1', 'PUNTO REPOSICIÓN');
	$pagina->setCellValue('H1', 'HASH');
	$pagina->setCellValue('I1', 'PRECIO UNITARIO');
	$pagina->setCellValue('J1', 'ULTIMA ACTUALIZACIÓN');


	$pagina->getStyle('A1:J1')->getFont()->setBold(true);
	/*$pagina->getStyle('A1:C1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);*/

	$pagina->getStyle('A1:J1')->applyFromArray(
		array(
			'borders'=> array(
				'allborders' => array(
					'style'=> PHPExcel_Style_Border::BORDER_THIN
				)
			)
		)
	);

	$pagina->getStyle('A1:J1')->applyFromArray( array( 'fill' => array( 'type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'EFF1F1') ) ) );


		$j=2;
		for ($i=0; $i < count($datosExportar) ; $i++){
			$pagina->setCellValue('A'.($j), $datosExportar[$i]->id_item);
			$pagina->setCellValue('B'.($j), $datosExportar[$i]->item);
			$pagina->setCellValue('C'.($j), $datosExportar[$i]->proveedor);
			$pagina->setCellValue('D'.($j), $datosExportar[$i]->almacen);
			$pagina->setCellValue('E'.($j), $datosExportar[$i]->cantDisp);
			$pagina->setCellValue('F'.($j), $datosExportar[$i]->cantReserv);
			$pagina->setCellValue('G'.($j), $datosExportar[$i]->punto_reposicion);
			$pagina->setCellValue('H'.($j), $datosExportar[$i]->hash);
			$pagina->setCellValue('I'.($j), $datosExportar[$i]->precio_unitario);
			$pagina->setCellValue('J'.($j), $datosExportar[$i]->fecha);

			$pagina->getStyle('A'.$j.':J'.$j)->applyFromArray(
			array(
				'borders'=> array(
					'allborders' => array(
						'style'=> PHPExcel_Style_Border::BORDER_THIN
					)
				)
			)
		);


			$j=$j+1;
		}

		foreach (range('A', 'J') as $column) {
			$pagina->getColumnDimension($column)->setAutoSize(true);
		}

	$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
	$objWriter->save('php://output');

?>