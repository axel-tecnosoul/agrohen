<?php
	session_start();
	require_once('../../conexion.php');
	class Items{
		private $id_item;
		private $id_stock;
		private $id_empresa;

		public function __construct(){
			$this->conexion = new Conexion();
      $this->id_empresa = $_SESSION["rowUsers"]["id_empresa"];
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

			/*CATEGORÍA*/
			$queryCategoria = "SELECT id as id_categoria, categoria FROM categorias_item";
			$getCategoria = $this->conexion->consultaRetorno($queryCategoria);

			/*UNIDAD DE MEDIDA*/
			$queryUmedida = "SELECT id as id_medida, unidad_medida FROM unidades_medida";
			$getUmedida = $this->conexion->consultaRetorno($queryUmedida);

			/*TIPO DE ITEM*/
			$queryTipoItem = "SELECT id as id_tipo, tipo as nombre_tipo FROM tipos_items";
			$getTipoItem = $this->conexion->consultaRetorno($queryTipoItem);

			/*CENTRO DE COSTOS*/
			$queryCcosto = "SELECT id as id_cc, nombre FROM centro_costos WHERE id_empresa = $this->id_empresa";
			$getCCostos = $this->conexion->consultaRetorno($queryCcosto);

			$datosIniciales = array();
			$almacenes = array();
			$proveedores = array();
			$arrayCategoria = array();
			$arrayUnidMedida = array();
			$arrayTipoIetm = array();
			$centroCostos = array();


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

			/*CARGO ARRAY CON CENTRO DE COSTOS*/
			while ($rowCC = $getCCostos->fetch_assoc()) {
				$id_cc = $rowCC['id_cc'];
				$nombreCC = $rowCC['nombre'];
				$centroCostos[]=array('id_cc'=>$id_cc, 'nombreCC'=>$nombreCC);
			}


			$datosIniciales["almacenes"] = $almacenes;
			$datosIniciales["proveedores"] = $proveedores;
			$datosIniciales["categorias"] = $arrayCategoria;
			$datosIniciales["unidad_medida"] = $arrayUnidMedida;
			$datosIniciales["tipo_items"] = $arrayTipoIetm;
			$datosIniciales["centroCostos"] = $centroCostos;

			echo json_encode($datosIniciales);
		}

		public function traerArticulos($id_empresa){

      //si no recibimos una empresa dejamos la empresa del constructor de la clase que
      if($id_empresa!=0){
			  $this->id_empresa = $id_empresa;
      }

			$queryTraerArticulos = "SELECT it.id id_item, it.item,  tp.tipo, cat.categoria, punto_reposicion, IF(it.activo = 1,'Activo','Inactivo') AS activo, unidad_medida, imagen
			FROM item as it 
        JOIN tipos_items as tp ON (it.id_tipo = tp.id)
				JOIN categorias_item as cat ON (it.id_categoria = cat.id)
				JOIN unidades_medida as um ON (it.id_unidad_medida = um.id)
			WHERE it.id_empresa = $this->id_empresa";
			$getArticulos = $this->conexion->consultaRetorno($queryTraerArticulos);

			$arrayArticulos = array();

			while ($rowArticulos = $getArticulos->fetch_array()) {
				$arrayArticulos[] = array(
          'id_item'=>$rowArticulos['id_item'],
          'item'=>$rowArticulos['item'],
          'tipo'=>$rowArticulos['tipo'],
          'categoria'=>$rowArticulos['categoria'],
          'preposicion'=>$rowArticulos['punto_reposicion'],
          'activo'=>$rowArticulos['activo'],
          'unidad_medida'=>$rowArticulos['unidad_medida'],
          'imagen'=>$rowArticulos['imagen'],
        );
			}

			echo json_encode($arrayArticulos);

		}

		public function agregarItemManual($descripcion, $categoria, $Umedida, $tipo, $preposicion, $estado, $linkVideo, $archivo, $id_empresa, $id_cc){
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
			$queryGetIdItem = "SELECT id as id_item FROM item 
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
			}

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
			
		}

		public function importarArticulos($id_empresa){
			if (isset($_FILES['file0'])) {
				include_once('./PHPExcel/Classes/PHPExcel.php');
				$archivo = $_FILES['file0']['tmp_name'];
				$nombreArchivo = $_FILES['file0']['name'];
				$tipoArchivo = PHPExcel_IOFactory::identify($archivo);
				$objectReader = PHPExcel_IOFactory::createReader($tipoArchivo);
				$objPHPExcel = $objectReader->load($archivo);
				$sheet = $objPHPExcel->getSheet(0);
				$filaMasAlta = $sheet->getHighestRow();
				$colMasAlta = $sheet->getHighestColumn();

				for ($row=2; $row <= $filaMasAlta ; $row++) {

				if($sheet->getCell("A".$row)->getFormattedValue()!=''){
					$descripcion = $sheet->getCell("A".$row)->getFormattedValue();
					$categoria = $sheet->getCell("B".$row)->getFormattedValue();
					$Umedida = $sheet->getCell("C".$row)->getFormattedValue();
					$tipo = $sheet->getCell("D".$row)->getFormattedValue();
					$estado = $sheet->getCell("E".$row)->getFormattedValue();
					$preposicion = $sheet->getCell("F".$row)->getFormattedValue();
					$linkVideo = $sheet->getCell("G".$row)->getFormattedValue();
					$id_cc = $sheet->getCell("H".$row)->getFormattedValue();
					$archivo = "";
					$this->agregarItemManual($descripcion, $categoria, $Umedida, $tipo, $preposicion, $estado, $linkVideo, $archivo, $id_empresa, $id_cc);
				}
			}
			}
		}

		public function deleteItem($id_item){
			$this->id_item = $id_item;
			$directorio = "../views/img_items/";
			$nombreFoto = "";

			//OBTENGO NOMBRE DE LA FOTO PARA BORRARLA DEL REPOSITORIO
			$queryGetNombre = "SELECT imagen FROM item
							WHERE id = $this->id_item";
			$getNombre = $this->conexion->consultaRetorno($queryGetNombre);

			if($getNombre->num_rows > 0){
				$rowNombre = $getNombre->fetch_assoc();
				$nombreFoto = $directorio.$rowNombre['imagen'];
				unlink($nombreFoto) ;
			}

			/*BORRO DATOS DE LA TABLA ITEMS*/
			$queryDelItem = "DELETE FROM item WHERE id =$this->id_item";
			$delItem = $this->conexion->consultaSimple($queryDelItem);

		}

		public function traerItemUpdate($id_item){
			$this->id_item = $id_item;

			$queryGetItemUpdate = "SELECT item, id_tipo, id_categoria, id_unidad_medida, 				imagen, link_video, punto_reposicion, hash,
								case 
									when activo = 0 then 'Inactivo'
									when activo = 1 then 'Activo'
								end activo, id_cc
								FROM item
								WHERE id = $this->id_item";
			$getItemUpdate= $this->conexion->consultaRetorno($queryGetItemUpdate);

			$arrayItem = array();

			while ($row = $getItemUpdate->fetch_assoc()) {
				$item  = $row['item'];
				$id_tipo  = $row['id_tipo'];
				$id_categoria  = $row['id_categoria'];
				$id_unidad_medida  = $row['id_unidad_medida'];
				$imagen  = $row['imagen'];
				$link_video  = $row['link_video'];
				$punto_reposicion  = $row['punto_reposicion'];
				$hash  = $row['hash'];
				$activo  = $row['activo'];
				$id_cc = $row['id_cc'];

				$arrayItem[] = array('item' => $item, 'id_tipo'=>$id_tipo, 'id_categoria'=>$id_categoria, 'unidad_medida' =>$id_unidad_medida, 'imagen'=>$imagen, 'link_video'=>$link_video, 'punto_reposicion'=>$punto_reposicion, 'hash'=>$hash, 'activo'=>$activo, 'id_cc'=>$id_cc);
			}
			echo json_encode($arrayItem);
		}

		public function eliminarArchivoItem($id_item, $nombreFoto){
			
			$this->id_item = $id_item;
			$directorio = "../views/img_items/";

			//ACTUALIZO NOMBRE IMAGEN DEL ITEM EN EMPTY
			$queryUpdateImageName = "UPDATE item SET imagen=''
									WHERE id = $this->id_item";
			$updateImageName = $this->conexion->consultaSimple($queryUpdateImageName);

			//ELIMINO IMAGEN DEL REPOSITORIO
			unlink($directorio.$nombreFoto);
		}

		public function updateItem($id_item, $descripcion, $categoria, $Umedida, $tipo, $preposicion, $estado, $linkVideo, $archivo, $id_cc, $id_empresa){
			$this->id_item=$id_item;
			if ($estado == 'Activo') {
				$estado = 1;
			}else{
				$estado = 0;
			}

			//GUARDO IMAGEN EN EL DIRECTORIO
			if($archivo !=""){

				$nombreImagen = $archivo['name'];
				$directorio = "../views/img_items/";
				$nombreFinalArchivo = $nombreImagen;

				/*VERIFICO SI TENGO IMAGEN EN TABLA Y REPOSITORIO*/
				$queryGetImagen = "SELECT imagen FROM item WHERE id = $this->id_item";
				$getImagen=$this->conexion->consultaRetorno($queryGetImagen);

				if($getImagen->num_rows > 0){
					$rowNombreImagen = $getImagen->fetch_assoc();
					$nombreImagen = $rowNombreImagen['imagen'];

					if ($nombreImagen !="") {
						unlink($directorio.$nombreImagen);
					}
				}

				move_uploaded_file($archivo['tmp_name'], $directorio.$id_item."_".$nombreFinalArchivo);
				//$ruta_completa_imagen = $directorio.$nombreFinalArchivo;
				$archivo = $id_item."_".$nombreFinalArchivo;

				//ACTUALIZO ITEM E IMAGEN
				$queryUpdateImageName = "UPDATE item SET item='$descripcion', 
									id_tipo=$tipo, id_categoria = $categoria,
									id_unidad_medida = $Umedida, imagen = '$archivo', 
									link_video='$linkVideo', punto_reposicion = $preposicion, activo = $estado, id_cc = $id_cc
										WHERE id = $this->id_item";
				$updateImageName = $this->conexion->consultaSimple($queryUpdateImageName);
			}else{
				//ACTUALIZO ITEM E IMAGEN
				$queryUpdateImageName = "UPDATE item SET item='$descripcion', 
									id_tipo=$tipo, id_categoria = $categoria,
									id_unidad_medida = $Umedida, link_video='$linkVideo',
									punto_reposicion = $preposicion, activo = $estado, id_cc = $id_cc
									WHERE id = $this->id_item";
				$updateImageName = $this->conexion->consultaSimple($queryUpdateImageName);
			}
		}

		public function traerFotoProducto($id_item){

			$this->id_item = $id_item;

			/*TRAER FOTO*/
			$queryGetFotoImagen = "SELECT imagen FROM item WHERE id = $this->id_item";
			$getFotoImagen = $this->conexion->consultaRetorno($queryGetFotoImagen);

			$arrayFotos = array();

			while ($rowImagen= $getFotoImagen->fetch_array()) {
				$imagen = $rowImagen['imagen'];
				$arrayFotos[]=array('fotos'=>$imagen);
			}

			echo json_encode($arrayFotos);
		}

		public function updateEstado($id_item, $estado){

			$this->id_item = $id_item;

			/*ACTUALIZO ESTADO DE ITEM*/
			$sqlUpdateEstadoItem = "UPDATE item SET activo = $estado
									WHERE id = $this->id_item";
			$updateEstadoItem = $this->conexion->consultaSimple($sqlUpdateEstadoItem);

		}

	}

if (isset($_POST['accion'])) {
		$items = new Items();
		switch ($_POST['accion']) {
			case 'traerAlmacenes':
				$items->traerTodosClientes();
				break;
			case 'traerItemUpdate':
					$id_item = $_POST['id_item'];
					$items->traerItemUpdate($id_item);
				break;
			case 'updateItem':
					$id_item = $_POST['id_item'];
					$descripcion = $_POST['descripcion'];
					$categoria = $_POST['categoria'];
					$Umedida = $_POST['Umedida'];
					$tipo = $_POST['tipo'];
					$preposicion = $_POST['preposicion'];
					$estado = $_POST['estado'];
					$linkVideo = $_POST['linkVideo'];
					$id_empresa = $_POST['id_empresa'];
					$id_cc = $_POST['id_cc'];
					if(isset($_FILES['file'])) {
						$archivo = $_FILES['file'];
					}else{
						$archivo = "";
					}
					$items->updateItem($id_item, $descripcion, $categoria, $Umedida, $tipo, $preposicion, $estado, $linkVideo, $archivo, $id_cc, $id_empresa);
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
					if(isset($_FILES['file'])) {
						$archivo = $_FILES['file'];
					}else{
						$archivo = "";
					}
					$items->agregarItemManual($descripcion, $categoria, $Umedida, $tipo, $preposicion, $estado, $linkVideo, $archivo, $id_empresa, $id_cc);
				break;
			case 'eliminarItem':
					$id_item = $_POST['id_item'];
					$items->deleteItem($id_item);
				break;
			case 'importarArticulos':
					$id_empresa = $_POST['id_empresa'];
					$items->importarArticulos($id_empresa);
				break;
			case 'traerDatosInicialesItems':
				$id_empresa = $_POST['id_empresa'];
				$items->traerDatosIniciales($id_empresa);
				break;
			case 'eliminarArchivoItem':
				$id_item = $_POST['id_item'];
				$nombreFoto = $_POST['nombreFoto'];
				$items->eliminarArchivoItem($id_item, $nombreFoto);
				break;
			case 'trerFotosProducto':
				$id_item = $_POST['id_item'];
				$items->traerFotoProducto($id_item);
				break;
			case 'cambiarEstado':
				$id_item=$_POST['id_item'];
				$estado=$_POST['estado'];
				$items->updateEstado($id_item, $estado);
				break;
		}
	}else{
		if (isset($_GET['accion'])) {
			$items = new Items();
      $id_empresa=0;
			if(isset($_GET['id_empresa'])) $id_empresa = $_GET['id_empresa'];
			$items->traerArticulos($id_empresa);
		}
	}

?>