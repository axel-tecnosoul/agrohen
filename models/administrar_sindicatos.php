<?php
	session_start();
	require_once('./conexion.php');
	class Sindicatos{
		private $conexion;
		private $id_sindicato;
		
		public function __construct(){
				$this->conexion = new Conexion();
				date_default_timezone_set("America/Buenos_Aires");
		}

		public function traerDatosIniciales(){

			/*JORNADAS*/
			$queryJornadas = "SELECT id, tipo_jornada FROM jornadas_laborales";
			$getJornadas = $this->conexion->consultaRetorno($queryJornadas);

			
			$datosIniciales = array();
			$arrayJornadas = array();


			/*CARGO ARRAY JORNADAS*/
			while ($rowsProv= $getJornadas->fetch_array()) {
				$id_jornada = $rowsProv['id'];
				$tipo_jornada = $rowsProv['tipo_jornada'];
				$arrayJornadas[] = array('id_jornada' => $id_jornada, 'tipo_jornada' =>$tipo_jornada);
			}


			$datosIniciales["jornadas"] = $arrayJornadas;
			
			echo json_encode($datosIniciales);
		}


		Public function agregarSindicato($sindicato, $jornadas){

			$jornadas = json_decode($jornadas);
			$fecha = date('Y-m-d H:i:s');

			$sql = "INSERT INTO sindicatos(sindicato, fecha_alta) VALUES('$sindicato', '$fecha')";
			$insertSindicato = $this->conexion->consultaSimple($sql);


			/*BUSCO EL SINDICATO CREADO PARA EL ID*/
			$queryGetSindicato = "SELECT id as id_sindicato FROM sindicatos 
								WHERE sindicato = '$sindicato'
								AND fecha_alta = '$fecha'";
			$getSindicato = $this->conexion->consultaRetorno($queryGetSindicato);

			if($getSindicato->num_rows > 0){

				$rowIdSindicato = $getSindicato->fetch_assoc();
				$idSindicato = $rowIdSindicato['id_sindicato'];

			}


			for ($i=0; $i < count($jornadas) ; $i++) { 
				
				$id_jornadas_laborales = $jornadas[$i]->id;
				$valor = $jornadas[$i]->valor;

				$queryInsertSueldos = "INSERT INTO sueldos_sindicatos(id_sindicato, id_jornadas_laborales, valor)VALUES($idSindicato, $id_jornadas_laborales, $valor)";
				$insertSueldos= $this->conexion->consultaSimple($queryInsertSueldos);

			}

			

		}
		public function traerSindicatos(){

			$sqlTraerSindicatos = "SELECT id as id_sindicato, sindicato 
									FROM sindicatos";
			$traerSindicatos = $this->conexion->consultaRetorno($sqlTraerSindicatos);

			$arraySindicatos = array(); //creamos un array

			while ($row = $traerSindicatos->fetch_array()) {
            $id_sindicato = $row['id_sindicato'];
            $sindicato = $row['sindicato'];
            $arraySindicatos[] = array('id_sindicato'=> $id_sindicato, 'sindicato'=>$sindicato);
        }

        echo json_encode($arraySindicatos);

		}
		public function traerSindicatoUpdate($id_sindicato){
			$this->id_sindicato = $id_sindicato;

			$sqlTraerSindicatos = "SELECT id as id_sindicato, sindicato
								FROM sindicatos
								WHERE id = $this->id_sindicato";
			$traerSindicatos = $this->conexion->consultaRetorno($sqlTraerSindicatos);

			$arraySindicatos = array(); //creamos un array
			$arraySueldos = array();

			while ($row = $traerSindicatos->fetch_array()) {
            $id_sindicato = $row['id_sindicato'];
            $sindicato = $row['sindicato'];
            $arraySindicatos[] = array('id_sindicato'=> $id_sindicato, 'sindicato'=>$sindicato);
        }


        $sqlTraerSueldos = "SELECT ss.id as id_sueldos, ss.id_sindicato,
        					ss.id_jornadas_laborales, jl.tipo_jornada, ss.valor
        					FROM sueldos_sindicatos ss join jornadas_laborales jl
        					ON(ss.id_jornadas_laborales = jl.id)
        					WHERE ss.id_sindicato = $this->id_sindicato";
        $traerSueldos = $this->conexion->consultaRetorno($sqlTraerSueldos);

        while($rowSueldos = $traerSueldos->fetch_assoc()){
        	
        	$id_sueldos = $rowSueldos['id_sueldos'];
        	$id_sindicato = $rowSueldos['id_sindicato'];
        	$id_jornadas_laborales = $rowSueldos['id_jornadas_laborales'];
        	$valor = $rowSueldos['valor'];
        	$tipo_jornada = $rowSueldos['tipo_jornada'];

        	$arraySueldos[] = array('id_sueldos'=>$id_sueldos, 'id_sindicato'=>$id_sindicato, 'id_jornadas_laborales'=>$id_jornadas_laborales, 'valor'=>$valor, 'tipo_jornada'=>$tipo_jornada);
        }


        $datosSindicatos['sindicatos']=$arraySindicatos;
        $datosSindicatos['sueldos']=$arraySueldos;

        echo json_encode($datosSindicatos);

		}
		public function updateSindicato($id_sindicato, $sindicato, $jornadas, $jornadasDel){

			$this->id_sindicato = $id_sindicato;
			$jornadas = json_decode($jornadas);
			$jornadasDel = json_decode($jornadasDel);


			$sqlUpdateSindicato = "UPDATE sindicatos SET sindicato = '$sindicato'
								WHERE id= $id_sindicato";
			$updateSindicato = $this->conexion->consultaSimple($sqlUpdateSindicato);

			/*Actualizo o agrego sueldos*/

			if(count($jornadas) > 0){

				for ($i=0; $i < count($jornadas) ; $i++) { 
					
					$id_jornada = $jornadas[$i]->id;
					$sqlTraerSueldos = "SELECT id FROM sueldos_sindicatos
									WHERE id_jornadas_laborales = $id_jornada 
									AND id_sindicato = $id_sindicato";
					$traerSueldos = $this->conexion->consultaRetorno($sqlTraerSueldos);
					print_r($traerSueldos);
					echo "</br>";
					echo $id_jornada."</br>";
					if($traerSueldos->num_rows > 0){
						$valor = $jornadas[$i]->valor;
						$queryUpdateSueldo = "UPDATE sueldos_sindicatos set valor =$valor
							WHERE id_jornadas_laborales=$id_jornada
							AND id_sindicato = $id_sindicato";
						$updateSueldo = $this->conexion->consultaSimple($queryUpdateSueldo);
					}else{
						$valor = $jornadas[$i]->valor;
						$queryInsertSueldo = "INSERT INTO sueldos_sindicatos(id_sindicato, id_jornadas_laborales, valor)VALUES($id_sindicato, $id_jornada, $valor)";
						$insertSueldo = $this->conexion->consultaSimple($queryInsertSueldo);
					}
				}
			}

			if(count($jornadasDel)>0){

				for ($i=0; $i < count($jornadasDel); $i++) { 

					$id_sindicato = $jornadasDel[$i]->id_sindicato;
					$id_jornadas_laborales = $jornadasDel[$i]->id;

					$queryDelJornada = "DELETE FROM sueldos_sindicatos 
										WHERE id_sindicato = $id_sindicato
										AND id_jornadas_laborales = $id_jornadas_laborales";
					$delJornada = $this->conexion->consultaSimple($queryDelJornada);
				}

			}
		}


		public function deleteSindicato($id_sindicato){
			$this->id_sindicato = $id_sindicato;

			/*ELIMINO SINDICATO*/
			$sqlDeleteSindicato = "DELETE FROM sindicatos WHERE id = $this->id_sindicato";
			$delSindicato = $this->conexion->consultaSimple($sqlDeleteSindicato);

			/*ELIMINO SUELDOS_SINDICATO*/
			$sqlDeleteSueldos = "DELETE FROM sueldos_sindicatos WHERE id_sindicato = $this->id_sindicato";
			$delSueldo = $this->conexion->consultaSimple($sqlDeleteSueldos);
		}
		
}	

	if (isset($_POST['accion'])) {
		$sindicatos = new Sindicatos();
		switch ($_POST['accion']) {
			case 'traerAlmacenes':
				$sindicatos->traerTodosClientes();
				break;
			case 'traerSindicatoUpdate':
					$id_sindicato = $_POST['id_sindicato'];
					$sindicatos->traerSindicatoUpdate($id_sindicato);
				break;
			case 'updateSindicato':
					$id_sindicato = $_POST['id_sindicato'];
					$sindicato = $_POST['sindicato'];
					$jornadas = $_POST['jornadas'];
					$jornadasDel = $_POST['jornadasDel'];
					$sindicatos->updateSindicato($id_sindicato, $sindicato, $jornadas, $jornadasDel);
				break;
			case 'addSindicato':
					$sindicato = $_POST['sindicato'];
					$jornadas = $_POST['jornadas'];
					$sindicatos->agregarSindicato($sindicato, $jornadas);
				break;
			case 'eliminarSindicato':
					$id_sindicato = $_POST['id_sindicato'];
					$sindicatos->deleteSindicato($id_sindicato);
				break;
			case 'traerDatosIniciales':
				$sindicatos->traerDatosIniciales();
				break;
		}
	}else{
		if (isset($_GET['accion'])) {
			$sindicatos = new Sindicatos();
			$sindicatos->traerSindicatos();
		}
	}
?>