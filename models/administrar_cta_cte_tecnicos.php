<?php
	session_start();
	require_once('./conexion.php');
	class CtaCteTecnicos{
		private $id_empresa;
		private $id_tipo_caja;
		private $id_usuario;
		private $id_tecnico;
		
		public function __construct(){
				$this->conexion = new Conexion();
				date_default_timezone_set("America/Buenos_Aires");
		}

		public function traerDatosIniciales($id_empresa){

			$this->id_empresa = $id_empresa;

			/*PROVEEDORES*/
			$queryTecnicos = "SELECT id as id_tecnico, nombre_completo 
								FROM tecnicos 
								WHERE id_empresa = $this->id_empresa
								AND activo = 1";
			$getTecnicos = $this->conexion->consultaRetorno($queryTecnicos);

			$datosIniciales = array();
			$arrayTecnicos = array();

			/*CARGO ARRAY PROVEEDORES*/
			while ($rowTecnicos= $getTecnicos->fetch_array()) {
				$id_tecnico = $rowTecnicos['id_tecnico'];
				$nombre = $rowTecnicos['nombre_completo'];
				$arrayTecnicos[] = array('id_tecnico' => $id_tecnico, 'nombre' =>$nombre);
			}

			$datosIniciales["tecnicos"] = $arrayTecnicos;
			echo json_encode($datosIniciales);

		}


		public function traerCtaCteTecnico($id_empresa, $id_tecnico){

			$this->id_empresa = $id_empresa;
			$this->id_tecnico = $id_tecnico;

			$query = "SELECT mtc.id as id_tecnico, tc.nombre_completo, 
					sum(monto) as saldo
					FROM movimientos_tecnicos as mtc JOIN tecnicos as tc
					ON(mtc.id_tecnico = tc.id)
					WHERE mtc.id_tecnico = $this->id_tecnico
					AND tc.id_empresa = $this->id_empresa
					group by tc.nombre_completo;";
			$getCtaCteTecnico = $this->conexion->consultaRetorno($query);

			$arrayCtaCteTecnico = array(); //creamos un array

			while ($row = $getCtaCteTecnico->fetch_array()) {
            $id_tecnico = $row['id_tecnico'];
            $tecnico = $row['nombre_completo'];
            $saldo = $row['saldo'];

            $arrayCtaCteTecnico[] = array('id_tecnico'=> $id_tecnico, 'tecnico'=>$tecnico, 'saldo'=>$saldo);
        }

        echo json_encode($arrayCtaCteTecnico);

		}


		public function emitirPago($id_tecnico, $importe, $detalle){

			$this->id_tecnico = $id_tecnico;

			$fecha_hora = date("Y-m-d H:i:s");

			$queryInsertCtaCte = "INSERT INTO movimientos_tecnicos(id_tecnico, id_tipo_movimiento, detalle, monto, fecha_hora)VALUES($this->id_tecnico, 6, '$detalle', $importe, '$fecha_hora')";

			$insertCtaCte = $this->conexion->consultaSimple($queryInsertCtaCte);

		}

		public function traerDetalleCtaCte($id_tecnico){
			$this->id_tecnico = $id_tecnico;

			
			$arrayDetalleCtaCte = array();

			$queryDetalleCtaCte = "SELECT mt.id, mcta.tipo, mt.detalle, 
								mt.monto as saldo 
								FROM movimientos_tecnicos as mt 
								JOIN tipos_movimientos_ctacte as mcta
								ON(mt.id_tipo_movimiento = mcta.id)
								WHERE  id_tecnico = $this->id_tecnico";
			$getDetalleCtaCte = $this->conexion->consultaRetorno($queryDetalleCtaCte);

			while ($rowDetalle = $getDetalleCtaCte->fetch_assoc()) {
				
				$id_movimiento = $rowDetalle['id'];
				$tipo = $rowDetalle['tipo'];
				$detalle = $rowDetalle['detalle'];
				$monto = $rowDetalle['saldo'];

				$arrayDetalleCtaCte[] = array('id_movimiento'=>$id_movimiento, 'tipo'=>$tipo, 'detalle'=>$detalle, 'monto'=>$monto);
			}

			echo json_encode($arrayDetalleCtaCte);

		}
}	

	if (isset($_POST['accion'])) {
		$ctaCteTecnicos = new CtaCteTecnicos();
		switch ($_POST['accion']) {
			case 'emitirPago':
					$id_tecnico = $_POST['id_tecnico'];
					$importe = $_POST['importe'];
					$detalle  =$_POST['detalle'];
					$ctaCteTecnicos->emitirPago($id_tecnico, $importe, $detalle);
				break;
			case 'traerDatosIniciales':
				$id_empresa = $_POST['id_empresa'];
				$ctaCteTecnicos->traerDatosIniciales($id_empresa);
				break;
		}
	}else{
		if (isset($_GET['accion'])) {
			$ctaCteTecnicos = new CtaCteTecnicos();

			switch ($_GET['accion']) {
				case 'traerCtacCteTec':
					$id_empresa = $_GET['id_empresa'];
					$id_tecnico = $_GET['id_tecnico'];
					$ctaCteTecnicos->traerCtaCteTecnico($id_empresa, $id_tecnico);
					break;
				case 'traerDetalleCtaCte':
					$id_tecnico = $_GET['id_tecnico'];
					$ctaCteTecnicos->traerDetalleCtaCte($id_tecnico);
					break;
				default:
					// code...
					break;
			}
		}
	}
?>