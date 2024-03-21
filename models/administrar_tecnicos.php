<?php
	//session_start();
	require_once('./conexion.php');
  if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    // session isn't started
    session_start();
  }
  require_once('./administrar_cargos.php');
  extract($_REQUEST);
	class Tecnico{

		private $id_tecnico;

		public function __construct(){
			$this->conexion = new Conexion();
      $this->id_empresa = isset($_SESSION["rowUsers"]["id_empresa"]) ? $_SESSION["rowUsers"]["id_empresa"] : "";
			date_default_timezone_set("America/Buenos_Aires");
		}

		public function traerDatosIniciales(){
      $datosIniciales = array();
      $tecnicos = array();
      $marcas = array();
			//$totalizadores = array();

      /*TECNICOS*/
			$queryTecnicos = "SELECT id as id_tecnico, nombre_completo FROM tecnicos WHERE activo = 1";
			$getTecnicos = $this->conexion->consultaRetorno($queryTecnicos);
      /*CARGO ARRAY TECNICOS*/
			while ($rowTecnico= $getTecnicos->fetch_array()) {
				$id_tecnico = $rowTecnico['id_tecnico'];
				$nombre_completo = $rowTecnico['nombre_completo'];
				$tecnicos[] = array('id_tecnico' => $id_tecnico, 'nombre_completo' =>$nombre_completo);
			}

      $datosIniciales["tecnicos"] = $tecnicos;
      $datosIniciales["marcas"] = $marcas;

			echo json_encode($datosIniciales);
		}

		public function traerTecnicos($filtro=0){

      $filtro_tecnico="";
      $filtro_cargos="";
      /*if($id_tecnico!=0){
        $filtro_tecnico=" AND t.id = $id_tecnico";
      }*/
      if(isset($filtro["id_tecnico"])){
        $filtro_tecnico=" AND t.id = $id_tecnico";
      }
      if(isset($filtro["cargos"])){
        $id_cargos=$filtro["cargos"];
        if($id_cargos=="") $id_cargos=0;
        $filtro_cargos=" AND t.id_cargo IN ($id_cargos)";
      }
			
			$arrayTecnicos = array();

			$queryGet = "SELECT t.id AS id_tecnico, nombre_completo, v.id AS id_vehiculo, t.id_cargo
      FROM tecnicos t 
      LEFT JOIN vehiculos v ON t.id=v.id_tecnico_asignado
      WHERE t.activo = 1 AND t.id_empresa = $this->id_empresa $filtro_tecnico $filtro_cargos GROUP BY t.id";
			$get = $this->conexion->consultaRetorno($queryGet);
      //var_dump($queryGet);

      $cargos = new Cargos();

			while ($row = $get->fetch_array()) {

        $filtro["id_cargo"]=$row['id_cargo'];
        $cargo=$cargos->traerCargos($filtro);
        $cargo=json_decode($cargo,true);
        $cargo=$cargo[0];

				$arrayTecnicos[] = array(
          "id_tecnico" => $row['id_tecnico'],
				  "nombre_completo" => $row['nombre_completo'],
          "id_vehiculo" => $row['id_vehiculo'],
          "id_cargo" => $row['id_cargo'],
          "cargo" => $cargo
        );
			}
			//echo json_encode($arrayTecnicos);
      return json_encode($arrayTecnicos);
		}

		public function agregarTecnico($patente, $marca, $modelo, $anio, $codigo_motor, $codigo_chasis, $nro_cedula_verde, $fecha_alta, $fecha_adquirido, $fecha_baja, $tecnico, $comentarios, $proximo_service_general, $km_adquirido, $proximo_vencimiento_vtv, $km_actuales){
			//$fecha_alta = date('Y-m-d H:i:s');
      $activo = 1;
      $id_usuario_ultima_actualizacion=$_SESSION["rowUsers"]["id_usuario"];

			/*GUARDO EN TABLA EMPRESA*/
			$queryInsert = "INSERT INTO vehiculos (patente, id_marca, modelo, anio, codigo_motor, codigo_chasis, nro_cedula_verde, fecha_alta, activo, fecha_adquirido, fecha_baja, id_tecnico_asignado, comentarios, proximo_service_general, km_adquirido, proximo_vencimiento_vtv, km_actuales, fecha_ultima_actualizacion,id_usuario_ultima_actualizacion) VALUES('$patente', '$marca', '$modelo', '$anio', '$codigo_motor', '$codigo_chasis', '$nro_cedula_verde', '$fecha_alta', '$activo', '$fecha_adquirido', '$fecha_baja', '$tecnico', '$comentarios','$proximo_service_general','$km_adquirido','$proximo_vencimiento_vtv','$km_actuales',NOW(),'$id_usuario_ultima_actualizacion')";
      //echo $queryInsert;
			$insertar= $this->conexion->consultaSimple($queryInsert);

		}

    public function updateTecnico($id_tecnico, $patente, $marca, $modelo, $anio, $codigo_motor, $codigo_chasis, $nro_cedula_verde, $fecha_adquirido, $fecha_baja, $tecnico, $comentarios, $proximo_service_general, $km_adquirido, $proximo_vencimiento_vtv, $km_actuales){

			$this->id_tecnico=$id_tecnico;
      $id_usuario_ultima_actualizacion=$_SESSION["rowUsers"]["id_usuario"];

			//Actualizo datos del tecnico
      $queryUpdate = "UPDATE vehiculos set patente = '$patente', id_marca = '$marca', modelo = '$modelo', anio = '$anio', codigo_motor = '$codigo_motor', codigo_chasis = '$codigo_chasis', nro_cedula_verde = '$nro_cedula_verde', fecha_adquirido = '$fecha_adquirido', fecha_baja = '$fecha_baja', id_tecnico_asignado = '$tecnico', comentarios = '$comentarios', proximo_service_general = '$proximo_service_general', km_adquirido = '$km_adquirido', proximo_vencimiento_vtv = '$proximo_vencimiento_vtv', km_actuales = '$km_actuales', fecha_ultima_actualizacion = NOW(), id_usuario_ultima_actualizacion = '$id_usuario_ultima_actualizacion'
      WHERE id = $this->id_tecnico";
      //echo $queryUpdate;
			$update = $this->conexion->consultaSimple($queryUpdate);
      //var_dump("afectados: ".$this->conexion->conectar->affected_rows);
      $mensajeError=$this->conexion->conectar->error;
      //var_dump($mensajeError);
      echo $mensajeError;
      /*$error=0;
      if($mensajeError!=""){
        $error=1;
      }*/

		}

    public function deleteTecnico($id_tecnico){
			$this->id_tecnico = $id_tecnico;

			/*Eliminamos registros de la base de datos*/

			/*Tabla tecnicos*/
			$queryDelete = "DELETE FROM tecnicos WHERE id=$this->id_tecnico";
			$delete = $this->conexion->consultaSimple($queryDelete);

		}

}

$filtro=[];
if (isset($_POST['accion'])) {
  $tecnico = new Tecnico();
  switch ($_POST['accion']) {
    case 'traerDatosIniciales':
        $tecnico->traerDatosIniciales();
    break;
    case 'addTecnico':
        $fecha_alta = date('Y-m-d H:i:s');
        
        $tecnico->agregarTecnico($patente, $marca, $modelo, $anio, $codigo_motor, $codigo_chasis, $nro_cedula_verde, $fecha_alta, $fecha_adquirido, $fecha_baja, $tecnico, $comentarios, $proximo_service_general, $km_adquirido, $proximo_vencimiento_vtv, $km_actuales);
    break;
    case 'updateTecnico':
      $tecnico->updateTecnico($id_tecnico, $patente, $marca, $modelo, $anio, $codigo_motor, $codigo_chasis, $nro_cedula_verde, $fecha_adquirido, $fecha_baja, $tecnico, $comentarios, $proximo_service_general, $km_adquirido, $proximo_vencimiento_vtv, $km_actuales);
    break;
    case 'eliminarTecnico':
      //$id_tecnico = $_POST['id_tecnico'];
      $tecnico->deleteTecnico($id_tecnico);
    break;
  }
}else{
  if (isset($_GET['accion'])) {
    $tecnico = new Tecnico();

    switch ($_GET['accion']) {
      case 'traerTecnicos':
        if(isset($cargos)) $filtro["cargos"]=$cargos;
        if(isset($id_tecnico)) $filtro["id_tecnico"]=$id_tecnico;
        echo $tecnico->traerTecnicos($filtro);
      break;
    }
  }
}?>