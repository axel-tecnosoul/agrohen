<?php
	//session_start();
	require_once('../../conexion.php');
  if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    // session isn't started
    session_start();
  }
	class Almacenes{
		private $id_almacen;
		
		public function __construct(){
				$this->conexion = new Conexion();
				date_default_timezone_set("America/Buenos_Aires");
		}

		public function traerDatosIniciales(){

			/*PROVINCAIS*/
			$queryProvincia = "SELECT id, provincia FROM provincias";
			$getProvincias = $this->conexion->consultaRetorno($queryProvincia);

			/*TIPOS DE IVA*/
			$queryTipoIva = "SELECT id as idIva, tipo FROM tipos_iva_responsable";
			$getTipoIva = $this->conexion->consultaRetorno($queryTipoIva);

			/*CARGOS*/
			$queryCargos = "SELECT id as idCargo, cargo FROM cargos";
			$getCargos = $this->conexion->consultaRetorno($queryCargos);

			/*TIPO DIRECCIÓN*/
			$queryTipoDireccion = "SELECT id as idTipoDireccion, tipo as tipoDireccion
									FROM tipos_direccion";
			$getTipoDireccion = $this->conexion->consultaRetorno($queryTipoDireccion);


			$datosIniciales = array();
			$pvcias = array();
			$tiposIva = array();
			$arrayCargos = array();
			$arrayTipoDireccion = array();


			/*CARGO ARRAY PROVINCIAS*/
			while ($rowsProv= $getProvincias->fetch_array()) {
				$id_provincia = $rowsProv['id'];
				$provincia = $rowsProv['provincia'];
				$pvcias[] = array('id_provincia' => $id_provincia, 'nombreProv' =>$provincia);
			}

			/*CARGO ARRAY TIPO IVA*/
			while ($rowsIva= $getTipoIva->fetch_array()) {
				$id_iva = $rowsIva['idIva'];
				$tipoIva = $rowsIva['tipo'];
				$tiposIva[] = array('id_iva' => $id_iva, 'tipoIva' =>$tipoIva);
			}

			/*CARGO ARRAY CARGOS*/
			while ($rowCargos = $getCargos->fetch_array()) {
				$id_cargo = $rowCargos['idCargo'];
				$cargo = $rowCargos['cargo'];
				$arrayCargos[]= array('id_cargo' => $id_cargo, 'cargo' =>$cargo);
			}

			/*CARGO ARRAY TIPO DIRECCION*/
			while ($rowTipoDir = $getTipoDireccion->fetch_array()) {
				$idTipoDireccion = $rowTipoDir['idTipoDireccion'];
				$tipoDireccion = $rowTipoDir['tipoDireccion'];
				$arrayTipoDireccion[]= array('idTipoDireccion' => $idTipoDireccion, 'tipoDireccion' =>$tipoDireccion);
			}


			$datosIniciales["provincias"] = $pvcias;
			$datosIniciales["condicion_iva"] = $tiposIva;
			$datosIniciales["cargos"] = $arrayCargos;
			$datosIniciales["tipo_domicilio"] = $arrayTipoDireccion;
			echo json_encode($datosIniciales);
		}

		Public function agregarAlmacen($almacen, $direccion, $id_provincia, $estado){
			$fecha_alta = date('Y-m-d');

			if ($estado == 'Activo') {
				$estado = 1;
			}else{
				$estado = 0;
			}

			$sql = "INSERT INTO almacenes(almacen, direccion, id_provincia, fecha_alta, activo) VALUES('$almacen', '$direccion', $id_provincia, '$fecha_alta', $estado)";
			$insertAlmacen = $this->conexion->consultaSimple($sql);

		}

		public function traerAlmacenes(){

			$sqlTraerAlmacenes = "SELECT al.id as id_almacen, almacen, direccion, id_provincia, provincia, IF(activo=1,'Activo','Inactivo') as activa, fecha_alta
      FROM almacenes al join provincias p ON(al.id_provincia = p.id)";
			$traerAlmacenes = $this->conexion->consultaRetorno($sqlTraerAlmacenes);

			$almacenes = array(); //creamos un array

			while ($row = $traerAlmacenes->fetch_array()) {
            /*$fechaAlataDate = new DateTime($row['fecha_alta']);
            $fecha_alta = date_format($fechaAlataDate, "d/m/Y");*/
            $almacenes[] = array(
              'id_almacen'=> $row['id_almacen'],
              'almacen'=>$row['almacen'],
              'direccion'=> $row['direccion'],
              'id_provincia'=> $row['id_provincia'],
              'provincia'=> $row['provincia'],
              'activa'=>$row['activa'],
              'fecha_alta'=> date("d/m/Y",strtotime($row['fecha_alta']))
            );
        }

        return json_encode($almacenes);

		}
		public function traerAlmacenUpdate($id_almacen){
			$this->id_almacen = $id_almacen;
			$sqlTraerAlmacen = "SELECT id as id_almacen, almacen, direccion, 						id_provincia, case
											when activo = 1 then 'Activo'
											else 'Inactivo'
											end activa, fecha_alta
								FROM almacenes
								WHERE id = $this->id_almacen";
			$traerAlmacen = $this->conexion->consultaRetorno($sqlTraerAlmacen);

			$almacenes = array(); //creamos un array

			while ($row = $traerAlmacen->fetch_array()) {
            $id_almacen = $row['id_almacen'];
            $almacen = $row['almacen'];
            $direccion = $row['direccion'];
            $id_provincia = $row['id_provincia'];
            $activa = $row['activa'];
            $almacenes[] = array('id_almacen'=> $id_almacen, 'almacen'=>$almacen, 'direccion'=> $direccion, 'id_provincia'=> $id_provincia, 'activa'=>$activa);
        }

        echo json_encode($almacenes);
		}
		public function almacenUpdate($id_almacen, $almacen, $direccion, $provincia, $estado){

			if ($estado == 'Activo') {
				$estado = 1;
			}else{
				$estado = 0;
			}

			$sqlUpdateAlmacen = "UPDATE almacenes SET almacen ='$almacen', direccion= '$direccion', id_provincia = $provincia, activo = $estado
								WHERE id=$id_almacen";
			$updateAlmacen = $this->conexion->consultaSimple($sqlUpdateAlmacen);
		}


		public function deleteAlmacen($id_almacen){
			$this->id_almacen = $id_almacen;

			/*ELIMINO ALMACEN*/
			$sqlDeleteAlmacen = "DELETE FROM almacenes WHERE id = $this->id_almacen";
			$delAlmacen = $this->conexion->consultaSimple($sqlDeleteAlmacen);
		}
		
}	

	if (isset($_POST['accion'])) {
		$almacenes = new Almacenes();
		switch ($_POST['accion']) {
			case 'traerAlmacenes':
        echo $almacenes->traerAlmacenes();
				break;
			case 'traerAlmacenUpdate':
					$id_almacen = $_POST['id_almacen'];
					$almacenes->traerAlmacenUpdate($id_almacen);
				break;
			case 'updateAlmacen':
					$id_almacen = $_POST['id_almacen'];
					$almacen = $_POST['almacen'];
					$direccion = $_POST['direccion'];
					$provincia = $_POST['provincia'];
					$estado = $_POST['estado'];
					$almacenes->almacenUpdate($id_almacen, $almacen, $direccion, $provincia, $estado);
				break;
			case 'addAlmacen':
					$almacen = $_POST['almacen'];
					$direccion = $_POST['direccion'];
					$id_provincia = $_POST['provincia'];
					$estado = $_POST['estado'];
					$almacenes->agregarAlmacen($almacen, $direccion, $id_provincia, $estado);
				break;
			case 'eliminarAlmacen':
					$id_almacen = $_POST['id_almacen'];
					$almacenes->deleteAlmacen($id_almacen);
				break;
			case 'traerDatosIniciales':
				$almacenes->traerDatosIniciales();
				break;
		}
	}else{
		if (isset($_GET['accion']) and $_GET['accion']=="traerAlmacenes") {
			$almacenes = new Almacenes();
			echo $almacenes->traerAlmacenes();
		}
	}
?>