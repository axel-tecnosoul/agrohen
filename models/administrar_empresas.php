<?php
	session_start();
	require_once('./conexion.php');
	class Empresas{
		private $id_empresa;
		private $id_adjunto;

		public function __construct(){
			$this->conexion = new Conexion();
				date_default_timezone_set("America/Buenos_Aires");
		}

		public function traerDatosIniciales(){

					
			/*TIPO DE ITEM*/
			$queryProvincias = "SELECT id as id_provincia, provincia 
									FROM provincias";
			$getProvincias = $this->conexion->consultaRetorno($queryProvincias);


			$datosIniciales = array();
			
			$arrayProvincias = array();


			/*CARGO ARRAY ALMACENES*/
			while ($rowsProv= $getProvincias->fetch_array()) {
				$id_provincia = $rowsProv['id_provincia'];
				$provincia = $rowsProv['provincia'];
				$arrayProvincias[] = array('id_provincia' => $id_provincia, 'provincia' =>$provincia);
			}

			$datosIniciales["provincias"] = $arrayProvincias;
			echo json_encode($datosIniciales);
		}

		public function traerEmpresas(){

			$queryTraerEmpresas = "SELECT emp.id, emp.nombre, 
								concat(emp.domicilio, ', ', emp.localidad, ', ', prov.provincia) as direccion, 
								emp.telefono, 
								case
									when emp.activo = 0 then 'Inactivo'
                    				else 'Activo'
								end activo, emp.fecha_alta
								FROM empresas as emp JOIN provincias as prov
								ON(emp.id_provincia = prov.id)";
			$getEmpresas = $this->conexion->consultaRetorno($queryTraerEmpresas);

			$arrayEmpresas = array();

			while ($rowEmpresas = $getEmpresas->fetch_array()) {
				$id_empresa = $rowEmpresas['id']	;
				$nombre = $rowEmpresas['nombre'];
				$direccion = $rowEmpresas['direccion'];
				$telefono = $rowEmpresas['telefono'];
				$activo = $rowEmpresas['activo'];
				$fecha_alta = date("d/m/Y", strtotime($rowEmpresas['fecha_alta']));
				$arrayEmpresas[] = array('id_empresa'=>$id_empresa, 'nombre'=>$nombre, 'direccion'=>$direccion, 'telefono'=>$telefono, 'activo'=>$activo, 'fecha_alta'=>$fecha_alta);
			}

			echo json_encode($arrayEmpresas);

		}

		public function agregarEmpresa($nombre, $cuit, $domicilio, $localidad, $provincia, $pais, $telefono, $email, $archivo, $adjuntos, $cantAdjuntos){
			$fecha_alta = date('Y-m-d H:i:s');


			/*GUARDO EN TABLA EMPRESA*/
			$queryInsertEmpresa = "INSERT INTO empresas(nombre, domicilio, cuit, localidad, id_provincia, pais, telefono, email, activo, fecha_alta)VALUES('$nombre', '$domicilio', '$cuit', '$localidad', $provincia, '$pais', '$telefono','$email', 0, '$fecha_alta')";
			$insertarEmpresa= $this->conexion->consultaSimple($queryInsertEmpresa);

			/*BUSCO EL ID DEL ITEM CREADO PARA COLOCARLO COMO IDENTIFICADOR EN LA FOTO*/
			$queryGetIdEmpresa = "SELECT id as id_empresa FROM empresas 
							WHERE nombre = '$nombre'
							AND fecha_alta = '$fecha_alta'";
			$getIdIEmpresa = $this->conexion->consultaRetorno($queryGetIdEmpresa);

			if ($getIdIEmpresa->num_rows > 0 ) {
				$idRow = $getIdIEmpresa->fetch_assoc();
				$id_empresa = $idRow['id_empresa'];
			}

			/*GUARDO IMAGEN EN EL DIRECTORIO*/
			if($archivo !=""){
				$nombreImagen = $archivo['name'];
				$directorio = "../views/empresas/";
				$nombreFinalArchivo = $nombreImagen;
				move_uploaded_file($archivo['tmp_name'], $directorio."logo_".$id_empresa."_".$nombreFinalArchivo);
				//$ruta_completa_imagen = $directorio.$nombreFinalArchivo;
				$archivo = "logo_".$id_empresa."_".$nombreFinalArchivo;
			}

			//ACTUALIZO NOMBRE IMAGEN DEL ITEM
			$queryUpdateLogoName = "UPDATE empresas SET imagen='$archivo'
									WHERE id = $id_empresa";
			$updateLogoName = $this->conexion->consultaSimple($queryUpdateLogoName);



			//SI VIENEN ADJUNTOS LOS GUARDO.
			if ($adjuntos > 0) {
				for ($i=0; $i < $cantAdjuntos; $i++) { 
					$indice = "file".$i;
					$nombreADJ = $_FILES[$indice]['name'];

					//INSERTO DATOS EN LA TABLA ADJUNTOS ORDEN_COMPRA
					$queryInsertAdjuntos = "INSERT INTO adjuntos_empresa(id_empresa, archivo)VALUES($id_empresa, '$nombreADJ')";
					$insertAdjuntos = $this->conexion->consultaSimple($queryInsertAdjuntos);
					
					//INGRESO ARCHIVOS EN EL DIRECTORIO
					$directorio = "../views/empresas/";
					move_uploaded_file($_FILES[$indice]['tmp_name'], $directorio."adj_".$id_empresa."_".$nombreADJ);
					//$ruta_completa_imagen = $directorio.$nombreFinalArchivo;
				}
			}

			
		}

		public function deleteEmpresa($id_empresa){
			$this->id_empresa = $id_empresa;
			$directorio = "../views/empresas/";
			

			/*Verificamos si existe el logo, en caso de que si, lo eliminamos*/
			$queryGetLogoName = "SELECT imagen FROM empresas 
								WHERE id = $this->id_empresa";
			$getLogoName = $this->conexion->consultaRetorno($queryGetLogoName);

			if($getLogoName->num_rows > 0){
				
				$logoNameRow = $getLogoName->fetch_assoc();

				if($logoNameRow['imagen'] !=''){

					unlink($directorio.$logoNameRow['imagen']);
				}
			}	

			/*Verificamos que existan adjuntos, en caso de que si, lo eliminamos*/
			$queryGetAdjuntos="SELECT archivo FROM adjuntos_empresa
							WHERE id_empresa = $this->id_empresa";

			$getAdjuntos = $this->conexion->consultaRetorno($queryGetAdjuntos);

			if ($getAdjuntos->num_rows > 0) {
				
				While($adjuntos = $getAdjuntos->fetch_assoc()){

						unlink($directorio."adj_".$this->id_empresa."_".$adjuntos['archivo']);

				}
			}

			/*Eliminamos registros de la base de datos*/

			/*Tabla empresas*/
			$queryDelEmpresa = "DELETE FROM empresas WHERE id=$this->id_empresa";
			$delEmpresa = $this->conexion->consultaSimple($queryDelEmpresa);

			/*Tabla adjuntos_empresas*/
			$queryDelAdjuntos = "DELETE FROM adjuntos_empresa WHERE id_empresa=$this->id_empresa";
			$delAdjunto = $this->conexion->consultaSimple($queryDelAdjuntos);

		}

		public function traerEmpresaUpdate($id_empresa){
			$this->id_empresa = $id_empresa;

			/*TRAIGO DATOS DE EMPRESA*/
			$queryGetEmpresaUpdate = "SELECT id, nombre, domicilio, cuit,
								localidad, id_provincia, pais, telefono, email, 
								activo, imagen
								FROM empresas
								WHERE id = $this->id_empresa";
			$getEmpresaUpdate= $this->conexion->consultaRetorno($queryGetEmpresaUpdate);

			/*BUSCO ADJUNTOS DE LA EMPRESA*/
			$queryGetAdjuntos = "SELECT id as id_adjunto, archivo 
								FROM adjuntos_empresa 
								WHERE id_empresa = $this->id_empresa";
			$getAdjuntos = $this->conexion->consultaRetorno($queryGetAdjuntos);


			$arrayDatosEmpresas= array();
			$arrayAdjuntosEmpresas = array();
			$arrayEmpresas = array();

			/*CARGO ARRAY CON DATOS DE LA EMPRESA*/
			while ($rowEmpresas = $getEmpresaUpdate->fetch_assoc()) {
				$id_empresa = $rowEmpresas['id'];
				$nombre = $rowEmpresas['nombre'];
				$domicilio = $rowEmpresas['domicilio'];
				$cuit = $rowEmpresas['cuit'];
				$localidad = $rowEmpresas['localidad'];
				$provincia = $rowEmpresas['id_provincia'];
				$pais = $rowEmpresas['pais'];
				$telefono = $rowEmpresas['telefono'];
				$email = $rowEmpresas['email'];
				$activo = $rowEmpresas['activo'];
				$imagen = $rowEmpresas['imagen'];
				$arrayEmpresas[] = array('id_empresa'=>$id_empresa, 'nombre'=>$nombre, 'domicilio'=>$domicilio, 'cuit'=>$cuit, 'localidad'=>$localidad, 'provincia'=>$provincia, 'pais'=>$pais, 'telefono'=>$telefono, 'email'=>$email, 'activo'=>$activo);
			}

			/*CARGO ARRAY CON LOS ADJUNTOS*/
			if ($getAdjuntos->num_rows > 0) {

				while($rowAdjuntos = $getAdjuntos->fetch_assoc()){

					$id_adjunto = $rowAdjuntos['id_adjunto'];
					$archivo = "adj_".$this->id_empresa."_".$rowAdjuntos['archivo'];

					$arrayAdjuntosEmpresas[]= array('id_adjunto'=>$id_adjunto, 'archivos'=>$archivo);
				}

			}else{
				
			}


			if($imagen !=""){
			$arrayAdjuntosEmpresas[]= array('id_adjunto'=>0, 'archivos'=>$imagen);
			}


			$arrayDatosEmpresas['adjuntosEmpresas']=$arrayAdjuntosEmpresas;
			$arrayDatosEmpresas['datos_empresas'] = $arrayEmpresas;
			echo json_encode($arrayDatosEmpresas);
		}

		public function eliminarArchivos($id_adjunto, $nombre_adjunto, $id_empresa){
			
			$this->id_adjunto = $id_adjunto;
			$this->id_empresa = $id_empresa;
			$directorio = "../views/empresas/";

			if($this->id_adjunto == 0){

				$queryDelLogo = "UPDATE empresas SET imagen = '' WHERE imagen ='$nombre_adjunto'";
				$delLogo= $this->conexion->consultaSimple($queryDelLogo);

				unlink($directorio.$nombre_adjunto);


			}else{
				
				$queryDelAdjuntos = "DELETE FROM adjuntos_empresa WHERE id = $this->id_adjunto";
				$delAdjuntos = $this->conexion->consultaSimple($queryDelAdjuntos);

				unlink($directorio.$nombre_adjunto);
			}
		}

		public function updateEmpresa($id_empresa, $nombre, $cuit, $domicilio, $localidad, $provincia, $pais, $telefono, $email, $logo, $cantAdjuntos){

			$this->id_empresa=$id_empresa;

			//Actualizo datos de empresa
			$queryUpdateEmpresa = "UPDATE empresas set nombre = '$nombre', cuit = '$cuit', domicilio = '$domicilio', localidad = '$localidad', id_provincia = $provincia, pais = '$pais', telefono = '$telefono', email = '$email'
			WHERE id = $this->id_empresa";

			$updateEmpresa = $this->conexion->consultaSimple($queryUpdateEmpresa);
			
			
			//Actualizamos logo
			//Si el input file logo viene con datos verificamos que tenga logo en la tabla, sino insertamos en tabla y directorio

			if($logo !=""){

				$directorio = "../views/empresas/";
				$queryGetLogo = "SELECT imagen FROM empresas WHERE id= $this->id_empresa";
				$getLogo = $this->conexion->consultaRetorno($queryGetLogo);
				
				$nombreRow = $getLogo->fetch_assoc();
				$nombreAnterior = $nombreRow['imagen'];

				if($nombreAnterior['imagen'] != ""){

					//Actualizo nombre en base de datos
					$nombre_completo =  "logo_".$this->id_empresa."_".$logo['name'];
					$queryUpdateLogo = "UPDATE empresas SET imagen = '$nombre_completo'
						WHERE id = $this->id_empresa";
					$updateLogo = $this->conexion->consultaSimple($queryUpdateLogo);

					//Elimino archivo existente en directorio y agrego el nuevo
					
					unlink($directorio.$nombreAnterior);
					move_uploaded_file($logo['tmp_name'], $directorio.$nombre_completo);

				}else{

					//Actualizo nombre en base de datos
					$nombre_completo =  "logo_".$this->id_empresa."_".$logo['name'];
					$queryUpdateLogo = "UPDATE empresas SET imagen = '$nombre_completo'
						WHERE id = $this->id_empresa";
					$updateLogo = $this->conexion->consultaSimple($queryUpdateLogo);

					//Agrego nueva imagen en directorio
					move_uploaded_file($logo['tmp_name'], $directorio.$nombre_completo);
				}

			}


			//Actualizo archivos adjuntos en directorio y tabla
			if($cantAdjuntos > 0){

				$queryGetAdjuntos = "SELECT archivo FROM adjuntos_empresa
										WHERE id_empresa = $this->id_empresa";
				$getAdjuntos = $this->conexion->consultaRetorno($queryGetAdjuntos);


				if($getAdjuntos->num_rows > 0){

					/*VERIFICO SI EXISTE EL ARCHIVO Y ACTUALIZO*/
					for ($i=0; $i < $cantAdjuntos; $i++) { 
					$indice = "file".$i;
					$nombreADJ = $_FILES[$indice]['name'];

					//Verifico si el archivo existe y modifico

					$queryGetAdjuntosExist = "SELECT archivo FROM adjuntos_empresa
										WHERE archivo = '$nombreADJ'";
					$getAdjuntosExists = $this->conexion->consultaRetorno($queryGetAdjuntosExist);

					if($getAdjuntosExists->num_rows > 0){
						
						//Actualizo archivo en directorio
						$directorio = "../views/empresas/";
						
						unlink($directorio."adj_".$id_empresa."_".$nombreADJ) ;

						move_uploaded_file($_FILES[$indice]['tmp_name'], $directorio."adj_".$id_empresa."_".$nombreADJ);
					}else{
						
						$queryInsertNewAdjunto = "INSERT INTO adjuntos_empresa(id_empresa, archivo) VALUES($this->id_empresa, '$nombreADJ')";
						$insertNewAdjunto = $this->conexion->consultaSimple($queryInsertNewAdjunto);

						move_uploaded_file($_FILES[$indice]['tmp_name'], $directorio."adj_".$id_empresa."_".$nombreADJ);
					}

				}


				}else{

					for ($i=0; $i < $cantAdjuntos; $i++) { 
					$indice = "file".$i;
					$nombreADJ = $_FILES[$indice]['name'];

					//INSERTO DATOS EN LA TABLA ADJUNTOS ORDEN_COMPRA
					$queryInsertAdjuntos = "INSERT INTO adjuntos_empresa(id_empresa, archivo)VALUES($this->id_empresa, '$nombreADJ')";
					$insertAdjuntos = $this->conexion->consultaSimple($queryInsertAdjuntos);
					
					//INGRESO ARCHIVOS EN EL DIRECTORIO
					$directorio = "../views/empresas/";
					move_uploaded_file($_FILES[$indice]['tmp_name'], $directorio."adj_".$this->id_empresa."_".$nombreADJ);
					//$ruta_completa_imagen = $directorio.$nombreFinalArchivo;
				}
				}
			}else{

				/*INSERTO ADJUNTOS*/
				for ($i=0; $i < $cantAdjuntos; $i++) { 
					$indice = "file".$i;
					$nombreADJ = $_FILES[$indice]['name'];

					//INSERTO DATOS EN LA TABLA ADJUNTOS ORDEN_COMPRA
					$queryInsertAdjuntos = "INSERT INTO adjuntos_empresa(id_empresa, archivo)VALUES($this->id_empresa, '$nombreADJ')";
					$insertAdjuntos = $this->conexion->consultaSimple($queryInsertAdjuntos);
					
					//INGRESO ARCHIVOS EN EL DIRECTORIO
					$directorio = "../views/empresas/";
					move_uploaded_file($_FILES[$indice]['tmp_name'], $directorio."adj_".$this->id_empresa."_".$nombreADJ);
					//$ruta_completa_imagen = $directorio.$nombreFinalArchivo;
				}
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

	}

if (isset($_POST['accion'])) {
		$empresas = new Empresas();
		switch ($_POST['accion']) {
			case 'empresas':
				$items->traerTodosClientes();
				break;
			case 'traerEmpresaUpdate':
					$id_empresa = $_POST['id_empresa'];
					$empresas->traerEmpresaUpdate($id_empresa);
				break;
			case 'updateEmpresa':
					$id_empresa = $_POST['id_empresa'];
					$nombre = $_POST['nombre'];
					$cuit = $_POST['cuit'];
					$domicilio = $_POST['domicilio'];
					$localidad = $_POST['localidad'];
					$provincia = $_POST['provincia'];
					$pais = $_POST['pais'];
					$telefono = $_POST['telefono'];
					$email = $_POST['email'];
					if(isset($_FILES['file'])) {
						$logo = $_FILES['file'];
					}else{
						$logo = "";
					}
					if(isset($_FILES['file0'])) {
						$adjuntos = 1;
					}else{
						$adjuntos = 0;
					}

					if(isset($_POST['cantAdjuntos'])){
						$cantAdjuntos = $_POST['cantAdjuntos'];
					}else{
						$cantAdjuntos = 0;
					}

					$empresas->updateEmpresa($id_empresa, $nombre, $cuit, $domicilio, $localidad, $provincia, $pais, $telefono, $email, $logo, $cantAdjuntos);
				break;
			case 'addEmpresa':
					$nombre = $_POST['nombre'];
					$cuit = $_POST['cuit'];
					$domicilio = $_POST['domicilio'];
					$localidad = $_POST['localidad'];
					$provincia = $_POST['provincia'];
					$pais = $_POST['pais'];
					$telefono = $_POST['telefono'];
					$email = $_POST['email'];
					if(isset($_FILES['file'])) {
						$archivo = $_FILES['file'];
					}else{
						$archivo = "";
					}
					if(isset($_FILES['file0'])) {
						$adjuntos = 1;
					}else{
						$adjuntos = 0;
					}

					if(isset($_POST['cantAdjuntos'])){
						$cantAdjuntos = $_POST['cantAdjuntos'];
					}else{
						$cantAdjuntos = 0;
					}

					$empresas->agregarEmpresa($nombre, $cuit, $domicilio, $localidad, $provincia, $pais, $telefono, $email, $archivo, $adjuntos, $cantAdjuntos);
				break;
			case 'eliminarEmpresa':
					$id_empresa = $_POST['id_empresa'];
					$empresas->deleteEmpresa($id_empresa);
				break;
			case 'traerDatosIniciales':
				$empresas->traerDatosIniciales();
				break;
			case 'eliminarArchivos':
				$id_adjunto = $_POST['id_adjunto'];
				$nombre_adjunto = $_POST['nombreArchivo'];
				$id_empresa = $_POST['id_empresa'];
				$empresas->eliminarArchivos($id_adjunto, $nombre_adjunto, $id_empresa);
				break;
			case 'trerFotosProducto':
				$id_item = $_POST['id_item'];
				$empresas->traerFotoProducto($id_item);
				break;
		}
	}else{
		if (isset($_GET['accion'])) {
			$empresas = new Empresas();
			$empresas->traerEmpresas();
		}
	}

?>