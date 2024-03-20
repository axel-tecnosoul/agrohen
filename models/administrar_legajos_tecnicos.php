<?php
	session_start();
	require_once('../../conexion.php');
	class LejagosTecnicos{
		private $id_empresa;
		private $id_tecnico;

		public function __construct(){
			$this->conexion = new Conexion();
				date_default_timezone_set("America/Buenos_Aires");
		}

		public function traerDatosIniciales(){

					
			/*PROVINCIAS*/
			$queryProvincias = "SELECT id as id_provincia, provincia 
									FROM provincias";
			$getProvincias = $this->conexion->consultaRetorno($queryProvincias);

			/*TIPOS DE IVA*/
			$queryTipoIva = "SELECT id as idIva, tipo FROM tipos_iva_responsable";
			$getTipoIva = $this->conexion->consultaRetorno($queryTipoIva);

			/*CARGOS*/
			$queryCargos = "SELECT id as idCargo, cargo FROM cargos";
			$getCargos = $this->conexion->consultaRetorno($queryCargos);

			$datosIniciales = array();
			$arrayProvincias = array();
			$arrayCargos = array();
			$arrayTiposIva = array();


			/*CARGO PROVINCIAS*/
			while ($rowsProv= $getProvincias->fetch_array()) {
				$id_provincia = $rowsProv['id_provincia'];
				$provincia = $rowsProv['provincia'];
				$arrayProvincias[] = array('id_provincia' => $id_provincia, 'provincia' =>$provincia);
			}

			/*CARGO ARRAY TIPO IVA*/
			while ($rowsIva= $getTipoIva->fetch_array()) {
				$id_iva = $rowsIva['idIva'];
				$tipoIva = $rowsIva['tipo'];
				$arrayTiposIva[] = array('id_iva' => $id_iva, 'tipoIva' =>$tipoIva);
			}

			/*CARGO ARRAY CARGOS*/
			while ($rowCargos = $getCargos->fetch_array()) {
				$id_cargo = $rowCargos['idCargo'];
				$cargo = $rowCargos['cargo'];
				$arrayCargos[]= array('id_cargo' => $id_cargo, 'cargo' =>$cargo);
			}

			$datosIniciales["provincias"] = $arrayProvincias;
			$datosIniciales["condicion_iva"] = $arrayTiposIva;
			$datosIniciales["cargos"] = $arrayCargos;

			echo json_encode($datosIniciales);
		}

		public function traerTecnicos($id_empresa){

			$this->id_empresa = $id_empresa;

			$queryTraerTecnicos = "SELECT tc.id, tc.nombre_completo, tc.nro_legajo
							, tc.cuil, tc.telefono, tc.email, cg.cargo, 
							tc.fecha_alta, tc.activo, iva.tipo as tipo_iva, 
							tc.valor_hora, tc.direccion, pcias.provincia, tc.saldo, adjunto
							FROM tecnicos as tc JOIN cargos as cg
							ON (id_cargo = cg.id)
							JOIN tipos_iva_responsable as iva
							ON(tc.id_tipo_iva_responsable = iva.id)
							JOIN provincias as pcias
							ON(tc.id_provincia = pcias.id)
							WHERE tc.id_empresa = $this->id_empresa";
			$getTecnicos = $this->conexion->consultaRetorno($queryTraerTecnicos);

			$arrayTecnicos = array();

			while ($rowTecnicos = $getTecnicos->fetch_array()) {
				$id_tecnico = $rowTecnicos['id']	;
				$legajo = $rowTecnicos['nro_legajo'];
				$nombre = $rowTecnicos['nombre_completo'];
				$cuil = $rowTecnicos['cuil'];
				$telefono = $rowTecnicos['telefono'];
				$email = $rowTecnicos['email'];
				$cargo = $rowTecnicos['cargo'];
				$activo = $rowTecnicos['activo'];
				$fecha_alta = date("d/m/Y", strtotime($rowTecnicos['fecha_alta']));
				$tipo_iva = $rowTecnicos['tipo_iva'];
				$valor_hora = "$".number_format($rowTecnicos['valor_hora'],2,',','.');
				$direccion = $rowTecnicos['direccion'];
				$provincia = $rowTecnicos['provincia'];
				$saldo = $rowTecnicos['saldo'];
				$imagen = $rowTecnicos['adjunto'];
				$arrayTecnicos[] = array('id_tecnico'=>$id_tecnico, 'legajo'=>$legajo, 'nombre'=>$nombre, 'cuil'=>$cuil, 'telefono'=>$telefono, 'email'=>$email, 'cargo'=>$cargo, 'activo'=>$activo, 'fecha_alta'=>$fecha_alta, 'tipo_iva'=>$tipo_iva, 'valor_hora'=>$valor_hora, 'direccion'=>$direccion, 'provincia'=>$provincia, 'saldo'=>$saldo, 'imagen'=>$imagen);
			}

			echo json_encode($arrayTecnicos);

		}

		public function agregarTecnico($nombre, $cuit, $telefono, $email, $cargo, $iva, $direccion, $provincia, $valHora, $id_empresa, $archivo){
			$fecha_alta = date('Y-m-d');


			/*GUARDO EN TABLA TECNICOS*/
			$queryInsertTecnico = "INSERT INTO tecnicos(nombre_completo, cuil, telefono, email, id_cargo, fecha_alta, activo, id_tipo_iva_responsable, valor_hora, direccion, id_provincia, saldo, id_empresa)VALUES('$nombre', $cuit, '$telefono', '$email', $cargo, '$fecha_alta', 0, $iva, $valHora, '$direccion', $provincia, 0, $id_empresa)";
			$insertTecnico= $this->conexion->consultaSimple($queryInsertTecnico);

			if($archivo !=""){

				$nombreImagen = $archivo['name'];
				$directorio = "../views/adjuntosTecnicos/";
				$nombreFinalArchivo = $nombreImagen;

				/*BUSCO EL ID DEL ITEM CREADO PARA COLOCARLO COMO IDENTIFICADOR EN LA FOTO*/
				$queryGetIdTecnico = "SELECT id as id_tecnico 
								FROM tecnicos
								WHERE nombre_completo = '$nombre'
								AND cuil = '$cuit'
								AND telefono = '$telefono'
								AND email = '$email'
								AND id_cargo = $cargo
								AND id_tipo_iva_responsable = $iva
								AND fecha_alta= '$fecha_alta'
								AND id_empresa = $id_empresa";
				$getIdTecnico = $this->conexion->consultaRetorno($queryGetIdTecnico);

				if ($getIdTecnico->num_rows > 0 ) {
					$idRow = $getIdTecnico->fetch_assoc();
					$id_tecnico = $idRow['id_tecnico'];
				}

				move_uploaded_file($archivo['tmp_name'], $directorio.$id_tecnico."_".$nombreFinalArchivo);
				//$ruta_completa_imagen = $directorio.$nombreFinalArchivo;
				$archivo = $id_tecnico."_".$nombreFinalArchivo;

				/*ACTUALIZO NOMBRE DE LA IMAGEN EN TABLA*/
				$queryUpdateImageName = "UPDATE tecnicos SET adjunto = '$archivo' WHERE id = $id_tecnico";
				$updateImageName = $this->conexion->consultaSimple($queryUpdateImageName);
			}


		}

		public function deleteTecnico($id_tecnico){
			$this->id_tecnico = $id_tecnico;
			$directorio = "../views/adjuntosTecnicos/";
			$nombreFoto = "";

			//OBTENGO NOMBRE DE LA FOTO PARA BORRARLA DEL REPOSITORIO
			$queryGetNombre = "SELECT adjunto FROM tecnicos
							WHERE id = $this->id_tecnico";
			$getNombre = $this->conexion->consultaRetorno($queryGetNombre);

			if($getNombre->num_rows > 0){
				$rowNombre = $getNombre->fetch_assoc();
				$nombreFoto = $directorio.$rowNombre['adjunto'];
				unlink($nombreFoto) ;
			}

			/*Tabla tecnicos*/
			$queryDelTecnico = "DELETE FROM tecnicos WHERE id=$this->id_tecnico";
			$delTecnico = $this->conexion->consultaSimple($queryDelTecnico);



		}

		public function traerTecnicoUpdate($id_tecnico){

			$this->id_tecnico = $id_tecnico;

			$queryTraerTecnicos = "SELECT id, nombre_completo, nro_legajo, cuil,
								telefono, email, id_cargo, activo, 
								id_tipo_iva_responsable as tipo_iva, valor_hora,
								direccion, id_provincia as provincia, saldo, adjunto
							FROM tecnicos
							WHERE id = $this->id_tecnico";
			$getTecnicos = $this->conexion->consultaRetorno($queryTraerTecnicos);

			$arrayDatosTecnico= array();
			$arrayTecnicos = array();

			while ($rowTecnicos = $getTecnicos->fetch_array()) {
				$id_tecnico = $rowTecnicos['id']	;
				$legajo = $rowTecnicos['nro_legajo'];
				$nombre = $rowTecnicos['nombre_completo'];
				$cuil = $rowTecnicos['cuil'];
				$telefono = $rowTecnicos['telefono'];
				$email = $rowTecnicos['email'];
				$cargo = $rowTecnicos['id_cargo'];
				$activo = $rowTecnicos['activo'];
				$tipo_iva = $rowTecnicos['tipo_iva'];
				$valor_hora = $rowTecnicos['valor_hora'];
				$direccion = $rowTecnicos['direccion'];
				$provincia = $rowTecnicos['provincia'];
				$saldo = $rowTecnicos['saldo'];
				$imagen = $rowTecnicos['adjunto'];
				$arrayTecnicos[] = array('id_tecnico'=>$id_tecnico, 'legajo'=>$legajo, 'nombre'=>$nombre, 'cuil'=>$cuil, 'telefono'=>$telefono, 'email'=>$email, 'cargo'=>$cargo, 'activo'=>$activo, 'tipo_iva'=>$tipo_iva, 'valor_hora'=>$valor_hora, 'direccion'=>$direccion, 'provincia'=>$provincia, 'saldo'=>$saldo, 'imagen'=>$imagen);
			}
			

			$arrayDatosTecnicos['datos_tecnico'] = $arrayTecnicos;
			echo json_encode($arrayDatosTecnicos);
		}

		public function updateTecnico($nombre, $cuit, $telefono, $email, $cargo, $iva, $direccion, $provincia, $valHora, $id_tecnico, $archivo){

			$this->id_tecnico=$id_tecnico;


			if($archivo !=""){

				$nombreImagen = $archivo['name'];
				$directorio = "../views/adjuntosTecnicos/";
				$nombreFinalArchivo = $nombreImagen;

				/*VERIFICO SI TENGO IMAGEN EN TABLA Y REPOSITORIO*/

				$queryGetImagen = "SELECT adjunto FROM tecnicos
							WHERE id = $this->id_tecnico";
				$getImagen = $this->conexion->consultaRetorno($queryGetImagen);

				if($getImagen->num_rows > 0){
					$rowNombreImagen = $getImagen->fetch_assoc();
					$nombreImagen = $rowNombreImagen['adjunto'];

					if ($nombreImagen !="") {
						unlink($directorio.$nombreImagen);
					}
				}

				move_uploaded_file($archivo['tmp_name'], $directorio.$id_tecnico."_".$nombreFinalArchivo);
				//$ruta_completa_imagen = $directorio.$nombreFinalArchivo;
				$archivo = $id_tecnico."_".$nombreFinalArchivo;

				//ACTUALIZO TECNICO E IMAGEN
				$queryUpdateTecnico = "UPDATE tecnicos SET nombre_completo = '$nombre', cuil='$cuit', telefono = '$telefono', email='$email', id_cargo=$cargo, id_tipo_iva_responsable=$iva, valor_hora= $valHora, direccion= '$direccion', id_provincia = $provincia,
					adjunto = '$archivo'
				WHERE id = $this->id_tecnico";

			$updateTecnico = $this->conexion->consultaSimple($queryUpdateTecnico);

			}else{
				//ACTUALIZO SOLO TECNICO
				$queryUpdateTecnico = "UPDATE tecnicos SET nombre_completo = '$nombre', cuil='$cuit', telefono = '$telefono', email='$email', id_cargo=$cargo, id_tipo_iva_responsable=$iva, valor_hora= $valHora, direccion= '$direccion', id_provincia = $provincia
				WHERE id = $this->id_tecnico";

				$updateTecnico = $this->conexion->consultaSimple($queryUpdateTecnico);

			}
		
		}

		public function cambiarEstado($id_tecnico, $estado){

			$this->id_tecnico = $id_tecnico;

			$queryUpdateEstado = "UPDATE tecnicos SET activo = $estado 
								WHERE id = $this->id_tecnico";
			$updateEstado = $this->conexion->consultaSimple($queryUpdateEstado);
		}

		public function eliminarArchivoTecnico($id_tecnico, $nombreFoto){
			$this->id_tecnico = $id_tecnico;
			$directorio = "../views/adjuntosTecnicos/";

			//ACTUALIZO NOMBRE IMAGEN DEL TECNICO EN EMPTY
				$queryUpdateImageName = "UPDATE tecnicos SET adjunto = '' 
										WHERE id = $this->id_tecnico";
				$updateImageName = $this->conexion->consultaSimple($queryUpdateImageName);

			//ELIMINO IMAGEN DEL REPOSITORIO
			unlink($directorio.$nombreFoto);
		}

	}

if (isset($_POST['accion'])) {
		$lejagosTecnicos = new LejagosTecnicos();
		switch ($_POST['accion']) {
			case 'traerTecnicoUpdate':
					$id_tecnico = $_POST['id_tecnico'];
					$lejagosTecnicos->traerTecnicoUpdate($id_tecnico);
				break;
			case 'updateTecnico':
					$nombre = $_POST['nombre'];
					$cuit = $_POST['cuit'];
					$telefono = $_POST['telefono'];
					$email = $_POST['email'];
					$cargo = $_POST['cargo'];
					$iva = $_POST['iva'];
					$direccion = $_POST['direccion'];
					$provincia = $_POST['provincia'];
					$valHora = $_POST['valHora'];
					$id_tecnico = $_POST['id_tecnico'];

					if(isset($_FILES['file'])) {
						$archivo = $_FILES['file'];
					}else{
						$archivo = "";
					}

					$lejagosTecnicos->updateTecnico($nombre, $cuit, $telefono, $email, $cargo, $iva, $direccion, $provincia, $valHora, $id_tecnico, $archivo);
				break;
			case 'addTecnico':
					$nombre = $_POST['nombre'];
					$cuit = $_POST['cuit'];
					$telefono = $_POST['telefono'];
					$email = $_POST['email'];
					$cargo = $_POST['cargo'];
					$iva = $_POST['iva'];
					$direccion = $_POST['direccion'];
					$provincia = $_POST['provincia'];
					$valHora = $_POST['valHora'];
					$id_empresa = $_POST['id_empresa'];

					if(isset($_FILES['file'])) {
						$archivo = $_FILES['file'];
					}else{
						$archivo = "";
					}

					$lejagosTecnicos->agregarTecnico($nombre, $cuit, $telefono, $email, $cargo, $iva, $direccion, $provincia, $valHora, $id_empresa, $archivo);
				break;
			case 'eliminarTecnico':
					$id_tecnico = $_POST['id_tecnico'];
					$lejagosTecnicos->deleteTecnico($id_tecnico);
				break;
			case 'traerDatosIniciales':
				$lejagosTecnicos->traerDatosIniciales();
				break;
			case 'cambiarEstado':
					$id_tecnico = $_POST['id_tecnico'];
					$estado = $_POST['estado'];
					$lejagosTecnicos->cambiarEstado($id_tecnico, $estado);
			break;
			case 'eliminarArchivoTecnico':
					$id_tecnico = $_POST['id_tecnico'];
					$nombreFoto = $_POST['nombreFoto'];
					$lejagosTecnicos->eliminarArchivoTecnico($id_tecnico, $nombreFoto);
				break;
		}
	}else{
		if (isset($_GET['accion'])) {

			$lejagosTecnicos = new LejagosTecnicos();

			switch ($_GET['accion']) {
				case 'traerTecnicos':
					$id_empresa = $_GET['id_empresa'];
					$lejagosTecnicos->traerTecnicos($id_empresa);
					break;
				
				default:
					// code...
					break;
			}

		}
	}

?>