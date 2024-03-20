<?php
	session_start();
	require_once('../../conexion.php');
	class Cajas{
		private $id_empresa;
		private $id_tipo_caja;
		private $id_usuario;
		private $id_caja_diaria;
		
		public function __construct(){
				$this->conexion = new Conexion();
				date_default_timezone_set("America/Buenos_Aires");
		}

		public function traerDatosIniciales(){

			$datosIniciales = array();
			$tiposMovCaja = array();

			/*TIPOS DE MOVIMIENTOS DE CAJA*/
			$queryTiposMovimientos ="SELECT id as id_tipo_mov, tipo 
									FROM tipos_movimientos_caja";
			$getTiposMovimientos = $this->conexion->consultaRetorno($queryTiposMovimientos);

			/*COMPLETO ARRAY TIPOS MOVIMIENTOS CAJA*/
			if($getTiposMovimientos->num_rows > 0){
				while($rowMovimientos = $getTiposMovimientos->fetch_assoc()){
					$id_tipo_mov = $rowMovimientos['id_tipo_mov'];
					$tipo_mov = $rowMovimientos['tipo'];

					$tiposMovCaja[] = array('id_tipo_mov'=>$id_tipo_mov, 'tipo_mov'=>$tipo_mov);
				}
			}

			$datosIniciales['tipos_mov'] = $tiposMovCaja;

			echo json_encode($datosIniciales);

		}


		Public function abrirCaja($id_tipo_caja, $id_empresa, $importe_apertura){

			$this->id_empresa = $id_empresa;
			$this->id_tipo_caja = $id_tipo_caja;
			$this->id_usuario = $_SESSION['rowUsers']['id_usuario'];
			$fecha = date('Y-m-d');
			$hora_apertura = date('H:i:s');

			$sql = "INSERT INTO caja_diaria(id_tipo_caja, fecha, horario_apertura, id_usuario_apertura, id_usuario_cierre, saldo_apertura, saldo_cierre, id_empresa, estado) VALUES($this->id_tipo_caja, '$fecha', '$hora_apertura', $this->id_usuario, $this->id_usuario, $importe_apertura, $importe_apertura, $this->id_empresa, 1)";
			$insertApertura = $this->conexion->consultaSimple($sql);

		}
		public function traerCajas($id_empresa){

			$this->id_empresa = $id_empresa;

			$query = "SELECT cd.id_caja_diaria, tc.id as id_tipo, tc.tipo, tc.id_empresa, max(cd.fecha) as fecha, 
						CASE 
							WHEN cd.estado IS NULL THEN 0
        					ELSE cd.estado
        				END estado,
        				CASE
        					WHEN cd.saldo_cierre IS NULL THEN 0
        					ELSE cd.saldo_cierre
        				END saldo
					FROM (
						SELECT id, tipo, id_empresa 
						FROM tipos_caja
						) AS tc LEFT JOIN
						(
						SELECT id as id_caja_diaria, id_tipo_caja, max(fecha) fecha, saldo_cierre, estado 
						FROM caja_diaria
						group by id, id_tipo_caja, fecha, saldo_cierre, estado
						) as cd
					ON(tc.id = cd.id_tipo_caja)
					WHERE tc.id_empresa = $this->id_empresa
					group by tc.tipo";
			$getCajas = $this->conexion->consultaRetorno($query);

			$cajas = array(); //creamos un array

			while ($row = $getCajas->fetch_array()) {
            $id_caja_diaria = $row['id_caja_diaria'];
            $id_tipo_caja = $row['id_tipo'];
            $tipo = $row['tipo'];
            $id_empresa = $row['id_empresa'];
            $estado = $row['estado'];
            $saldo = $row['saldo'];

            if($estado == 0){
            	$estado_mostrar = "Cerrada";
            }else{
            	$estado_mostrar = "Abierta";
            }

            $cajas[] = array('id_caja_diaria'=> $id_caja_diaria, 'id_tipo_caja'=>$id_tipo_caja, 'tipo'=>$tipo, 'id_empresa'=>$id_empresa, 'estado'=>$estado, 'saldo'=>$saldo, 'estado_mostar'=>$estado_mostrar);
        }

        echo json_encode($cajas);

		}
		public function cerrarCaja($id_caja_diaria){

			$this->id_caja_diaria = $id_caja_diaria;
			$this->id_usuario = $_SESSION['rowUsers']['id_usuario'];

			$query = "UPDATE caja_diaria SET estado = 0, 
					id_usuario_cierre = $this->id_usuario
					WHERE id=$this->id_caja_diaria";
			$cerrarCaja = $this->conexion->consultaSimple($query);
		}


		public function deleteTipoCaja($id_tipo_caja){
			$this->id_tipo_caja = $id_tipo_caja;

			/*ELIMINO RUBRO*/
			$query = "DELETE FROM tipos_caja WHERE id = $this->id_tipo_caja";
			$delTipoCaja = $this->conexion->consultaSimple($query);
		}
		
		public function agregarMovimiento($idCajaDiaria, $idTipoMovimiento, $importeNetoMovimiento, $importeImpuestosMovmiento, $nroComprobante, $importeMovimiento, $archivo){

			$this->id_caja_diaria = $idCajaDiaria;
			$fecha_hora = date('Y-m-d H:i:s');


			/*INSERTO EL MOVIMIENTO*/
			$queryInsertDetalle = "INSERT INTO caja_diaria_detalles(id_caja_diaria, id_tipo_movimiento, importe_neto, importe_impuestos, monto, nro_comprobante, fecha_hora)VALUES($this->id_caja_diaria, $idTipoMovimiento, $importeNetoMovimiento, $importeImpuestosMovmiento, $importeMovimiento, '$nroComprobante', '$fecha_hora')";
			$insertDetalle = $this->conexion->consultaSimple($queryInsertDetalle);


			if($archivo !=""){

				$nombreImagen = $archivo['name'];
				$directorio = "../views/adjuntosCajaDiaria/";
				$nombreFinalArchivo = $nombreImagen;

				/*BUSCO EL ID DEL ITEM CREADO PARA COLOCARLO COMO IDENTIFICADOR EN LA FOTO*/
				$queryGetIdMov = "SELECT id as id_movimiento 
								FROM caja_diaria_detalles
								WHERE id_caja_diaria = $this->id_caja_diaria
								AND id_tipo_movimiento = $idTipoMovimiento
								AND importe_neto = $importeNetoMovimiento
								AND importe_impuestos = $importeImpuestosMovmiento
								AND monto = $importeMovimiento
								AND nro_comprobante = '$nroComprobante'
								AND fecha_hora = '$fecha_hora'";
				$getIdMov = $this->conexion->consultaRetorno($queryGetIdMov);

				if ($getIdMov->num_rows > 0 ) {
					$idRow = $getIdMov->fetch_assoc();
					$id_movimiento = $idRow['id_movimiento'];
				}

				move_uploaded_file($archivo['tmp_name'], $directorio.$id_movimiento."_".$nroComprobante."_".$nombreFinalArchivo);
				//$ruta_completa_imagen = $directorio.$nombreFinalArchivo;
				$archivo = $id_movimiento."_".$nroComprobante."_".$nombreFinalArchivo;

				/*ACTUALIZO NOMBRE DE LA IMAGEN EN TABLA*/
				$queryUpdateImageName = "UPDATE caja_diaria_detalles SET adjunto = '$archivo' WHERE id = $id_movimiento";
				$updateImageName = $this->conexion->consultaSimple($queryUpdateImageName);
			}



			/*ACTUALIZO SALDO*/
			$queryUpdateSaldo ="UPDATE caja_diaria SET saldo_cierre = saldo_cierre - $importeMovimiento 
				WHERE id = $this->id_caja_diaria";
			$updateSaldo = $this->conexion->consultaSimple($queryUpdateSaldo);

		}

		public function traerMovimientosCaja($id_caja_diaria){
			$this->id_caja_diaria = $id_caja_diaria;

			$queryGetDetalleCaja = "SELECT cdd.id as id_cdd, tmp.tipo, monto,
									detalle, fecha_hora, nro_comprobante, adjunto
									FROM caja_diaria_detalles as cdd INNER JOIN tipos_movimientos_caja as tmp
									ON(cdd.id_tipo_movimiento = tmp.id)
									WHERE id_caja_diaria = $this->id_caja_diaria";
			$getDetalleCaja = $this->conexion->consultaRetorno($queryGetDetalleCaja);

			$arrayMovimientosCaja = array();

			while($row = $getDetalleCaja->fetch_assoc()){
				$id_cdd = $row['id_cdd'];
				$tipo = $row['tipo'];
				$monto = $row['monto'];
				$detalle = $row['detalle'];
				$fecha_hora = date("d/m/Y H:i:s", strtotime($row['fecha_hora']));
				$comprobante = $row['nro_comprobante'];
				$adjunto = $row['adjunto'];

				$arrayMovimientosCaja[]=array('id_cdd'=>$id_cdd, 'tipo'=>$tipo, 'monto'=>$monto, 'detalle'=>$detalle, 'feha_hora'=>$fecha_hora, 'comprobante'=>$comprobante, 'adjunto'=>$adjunto);
			}
			echo json_encode($arrayMovimientosCaja);
		}
}	

	if (isset($_POST['accion'])) {
		$cajas = new Cajas();
		switch ($_POST['accion']) {
			case 'abrirCaja':
					$id_tipo_caja = $_POST['id_tipo_caja'];
					$id_empresa = $_POST['id_empresa'];
					$importe_apertura = $_POST['saldo_apertura'];
					$cajas->abrirCaja($id_tipo_caja, $id_empresa, $importe_apertura);
				break;
			case 'cerrarCaja':
					$id_caja_diaria = $_POST['id_caja_diaria'];
					$cajas->cerrarCaja($id_caja_diaria);
				break;
			case 'eliminarTipoCaja':
					$id_tipo_caja = $_POST['id_tipo_caja'];
					$cajas->deleteTipoCaja($id_tipo_caja);
				break;
			case 'traerDatosIniciales':
				$cajas->traerDatosIniciales();
				break;
			case 'agregarMovimiento':
					$idCajaDiaria = $_POST['idCajaDiaria'];
					$idTipoMovimiento = $_POST['idTipoMovimiento'];
					$importeNetoMovimiento = $_POST['importeNetoMovimiento'];
					$importeImpuestosMovmiento = $_POST['importeImpuestosMovmiento'];
					$nroComprobante = $_POST['nroComprobante'];
					$importeMovimiento = $_POST['importeMovimiento'];
					
					if(isset($_FILES['file'])) {
						$archivo = $_FILES['file'];
					}else{
						$archivo = "";
					}


					$cajas->agregarMovimiento($idCajaDiaria, $idTipoMovimiento, $importeNetoMovimiento, $importeImpuestosMovmiento, $nroComprobante, $importeMovimiento, $archivo);
				break;
		}
	}else{
		if (isset($_GET['accion'])) {
			$cajas = new Cajas();

			switch ($_GET['accion']) {
				case 'traerTiposCaja':
					$id_empresa = $_GET['id_empresa'];
					$cajas->traerCajas($id_empresa);
					break;
				case 'traerMovimientos':
					$id_caja_diaria = $_GET['id_caja_diaria'];
					$cajas->traerMovimientosCaja($id_caja_diaria);
					break;
				default:
					// code...
					break;
			}
		}
	}
?>