<?php
	$datosExportar = json_decode($_GET['items']);

	$fecha = date('Ymd');
	$filename = "Lista_de_precios_".$fecha;
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename='.$filename.'.xls'); //Especifica el nombre del archivo a descargar
	header('Cache-Control: max-age=0');
	require_once('models/PHPExcel/Classes/PHPExcel.php');
	
	
	

	$excel = new PHPExcel();

	$excel->getProperties()->setCreator('')->setLastModifiedBy('')->setTitle('Lista');
	$excel->setActiveSheetIndex(0);

	$pagina = $excel->getActiveSheet();

	$pagina->setTitle('Lista de precios');


	$pagina->setCellValue('A1', 'ID');
	$pagina->setCellValue('B1', 'ITEM');
	$pagina->setCellValue('C1', 'PRECIO');
	$pagina->setCellValue('D1', 'PROVEEDOR');
	$pagina->setCellValue('E1', 'ULTIMA ACTUALIZACIÓN');
	$pagina->setCellValue('F1', 'CODIGO PROVEEDOR');

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
		for ($i=0; $i < count($datosExportar) ; $i++){
			$pagina->setCellValue('A'.($j), $datosExportar[$i]->id_item);
			$pagina->setCellValue('B'.($j), $datosExportar[$i]->item);
			$pagina->setCellValue('C'.($j), $datosExportar[$i]->precio);
			$pagina->setCellValue('D'.($j), $datosExportar[$i]->proveedor);
			$pagina->setCellValue('E'.($j), $datosExportar[$i]->ultima_actualizacion);
			$pagina->setCellValue('F'.($j), $datosExportar[$i]->codigo_proveedor);

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
		}

		foreach (range('A', 'F') as $column) {
			$pagina->getColumnDimension($column)->setAutoSize(true);
		}

	$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
	$objWriter->save('php://output');

?>