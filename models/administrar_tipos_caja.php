<?php
	session_start();
	require_once('../../conexion.php');
	class TiposCaja{
		private $id_rubro;
		private $id_empresa;
		private $id_tipo_caja;
		
		public function __construct(){
				$this->conexion = new Conexion();
				date_default_timezone_set("America/Buenos_Aires");
		}

		public function traerDatosIniciales($id_empresa){

			$this->id_empresa = $id_empresa;
			$datosIniciales = array();
			$arrayTecnicos = array();

			/*TÉCNICOS*/
			$queryGetTecnicos ="SELECT id as id_tecnico, nombre_completo as tecnico 
								FROM tecnicos
								WHERE id_empresa = $this->id_empresa
								AND activo = 1
									";
			$getTecnicos = $this->conexion->consultaRetorno($queryGetTecnicos);

			/*COMPLETO ARRAY TECNICOS*/
			if($getTecnicos->num_rows > 0){
				while($rowTecnicos = $getTecnicos->fetch_assoc()){
					$id_tecnico = $rowTecnicos['id_tecnico'];
					$tecnico = $rowTecnicos['tecnico'];

					$arrayTecnicos[] = array('id_tecnico'=>$id_tecnico, 'tecnico'=>$tecnico);
				}
			}

			$datosIniciales['tecnicos'] = $arrayTecnicos;

			echo json_encode($datosIniciales);
		}

		Public function agregarTiposCaja($tipo_caja, $id_empresa, $id_tecnico){

			$this->id_empresa = $id_empresa;

			$sql = "INSERT INTO tipos_caja(tipo, id_tecnico, id_empresa) VALUES('$tipo_caja', '$id_tecnico', $this->id_empresa)";
			$insertTipoCaja = $this->conexion->consultaSimple($sql);

		}
		public function traerTiposCajas($id_empresa){

			$this->id_empresa = $id_empresa;

			$query = "SELECT id as id_tipo_caja, tipo 
					FROM tipos_caja
					WHERE id_empresa = $this->id_empresa";
			$getQuery = $this->conexion->consultaRetorno($query);

			$tipos_cajas = array(); //creamos un array

			while ($row = $getQuery->fetch_array()) {
            $id_tipo_caja = $row['id_tipo_caja'];
            $tipo = $row['tipo'];
            $tipos_cajas[] = array('id_tipo_caja'=> $id_tipo_caja, 'tipo'=>$tipo);
        }

        echo json_encode($tipos_cajas);

		}

		public function traerUpdateTipoCaja($id_tipo_caja){

			$this->id_tipo_caja = $id_tipo_caja;

			$query = "SELECT id as id_tipo_caja, tipo, id_tecnico 
					FROM tipos_caja
					WHERE id = $this->id_tipo_caja";
			$getQuery = $this->conexion->consultaRetorno($query);

			$tipos_cajas = array(); //creamos un array

			while ($row = $getQuery->fetch_array()) {
            $id_tipo_caja = $row['id_tipo_caja'];
            $tipo = $row['tipo'];
            $id_tecnico = $row['id_tecnico'];
            $tipos_cajas[] = array('id_tipo_caja'=> $id_tipo_caja, 'tipo'=>$tipo, 'id_tecnico'=>$id_tecnico);
        }

        echo json_encode($tipos_cajas);
		}

		public function updateTipoCaja($id_tipo_caja, $tipo_caja, $id_tecnico){

			$this->id_tipo_caja = $id_tipo_caja;

			$query = "UPDATE tipos_caja SET tipo ='$tipo_caja', id_tecnico = '$id_tecnico'
								WHERE id=$this->id_tipo_caja";
			$updateTipoCaja = $this->conexion->consultaSimple($query);
		}


		public function deleteTipoCaja($id_tipo_caja){
			$this->id_tipo_caja = $id_tipo_caja;

			/*ELIMINO RUBRO*/
			$query = "DELETE FROM tipos_caja WHERE id = $this->id_tipo_caja";
			$delTipoCaja = $this->conexion->consultaSimple($query);
		}
		
}	

	if (isset($_POST['accion'])) {
		$tiposCaja = new TiposCaja();
		switch ($_POST['accion']) {
			case 'addTipoCaja':
					$tipo_caja = $_POST['tipo_caja'];
					$id_empresa = $_POST['id_empresa'];
					$id_tecnico = $_POST['id_tecnico'];
					$tiposCaja->agregarTiposCaja($tipo_caja, $id_empresa, $id_tecnico);
				break;
			case 'traerCajaUpdate':
					$id_tipo_caja = $_POST['id_tipo_caja'];
					$tiposCaja->traerUpdateTipoCaja($id_tipo_caja);
				break;
			case 'updateTipoCaja':
					$id_tipo_caja = $_POST['id_tipo_caja'];
					$tipo_caja = $_POST['tipo_caja'];
					$id_tecnico = $_POST['id_tecnico'];
					$tiposCaja->updateTipoCaja($id_tipo_caja, $tipo_caja, $id_tecnico);
				break;
			case 'eliminarTipoCaja':
					$id_tipo_caja = $_POST['id_tipo_caja'];
					$tiposCaja->deleteTipoCaja($id_tipo_caja);
				break;
			case 'traerDatosIniciales':
				$id_empresa = $_POST['id_empresa'];
				$tiposCaja->traerDatosIniciales($id_empresa);
				break;
		}
	}else{
		if (isset($_GET['accion'])) {
			$tiposCaja = new TiposCaja();
			$id_empresa = $_GET['id_empresa'];
			$tiposCaja->traerTiposCajas($id_empresa);
		}
	}
?>