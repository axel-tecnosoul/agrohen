<?php
  if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    // session isn't started
    session_start();
  }
	require_once('./conexion.php');
	class Rubros{
		private $id_rubro;
		
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


		Public function agregarRubros($rubro, $comentarios){

			$sql = "INSERT INTO rubros(rubro, comentarios) VALUES('$rubro', '$comentarios')";
			$insertRubro = $this->conexion->consultaSimple($sql);

		}

		public function traerRubros($filtros=[]){

      $filtro_rubro="";
      if($filtros!=[]){
        //var_dump($filtros);
        if(isset($filtros["id_rubro"]) and $filtros["id_rubro"]!=""){
          $filtro_rubro=" AND id IN (".$filtros["id_rubro"].")";
        }
      }

			$sqlTraerRubros = "SELECT id as id_rubro, rubro, comentarios FROM rubros WHERE 1 $filtro_rubro";
			$traerRubros = $this->conexion->consultaRetorno($sqlTraerRubros);

			$rubros = array(); //creamos un array

			while ($row = $traerRubros->fetch_array()) {
            $id_rubro = $row['id_rubro'];
            $rubro = $row['rubro'];
            $comentarios = $row['comentarios'];
            $rubros[] = array('id_rubro'=> $id_rubro, 'rubro'=>$rubro, 'comentarios'=> $comentarios);
        }

        return json_encode($rubros);

		}

		public function traerRubroUpdate($id_rubro){
			$this->id_rubro = $id_rubro;

			$sqlTraerRubro = "SELECT id as id_rubro, rubro, comentarios
								FROM rubros
								WHERE id = $this->id_rubro";
			$traerRubros = $this->conexion->consultaRetorno($sqlTraerRubro);

			$rubro = array(); //creamos un array

			while ($row = $traerRubros->fetch_array()) {
            $id_rubro = $row['id_rubro'];
            $rubro = $row['rubro'];
            $comentarios = $row['comentarios'];
            $rubros[] = array('id_rubro'=> $id_rubro, 'rubro'=>$rubro, 'comentarios'=> $comentarios);
        }

        echo json_encode($rubros);

		}

		public function updateRubro($id_rubro, $rubro, $comentarios){

			$this->id_rubro = $id_rubro;

			$sqlUpdateRubro = "UPDATE rubros SET rubro ='$rubro', comentarios= '$comentarios'
								WHERE id=$this->id_rubro";
			$updateRubro = $this->conexion->consultaSimple($sqlUpdateRubro);
		}

		public function deleteRubro($id_rubro){
			$this->id_rubro = $id_rubro;

			/*ELIMINO RUBRO*/
			$sqlDeleteRubro = "DELETE FROM rubros WHERE id = $this->id_rubro";
			$delRubro = $this->conexion->consultaSimple($sqlDeleteRubro);
		}
		
}	
$filtros=[];
if(isset($id_rubro)) $filtros["id_rubro"]=$id_rubro;

if (isset($_POST['accion'])) {
  $rubros = new Rubros();
  switch ($_POST['accion']) {
    case 'traerAlmacenes':
      $rubros->traerTodosClientes();
      break;
    case 'traerRubroUpdate':
        $id_rubro = $_POST['id_rubro'];
        $rubros->traerRubroUpdate($id_rubro);
      break;
    case 'updateRubro':
        $id_rubro = $_POST['id_rubro'];
        $rubro = $_POST['rubro'];
        $comentarios = $_POST['comentarios'];
        $rubros->updateRubro($id_rubro, $rubro, $comentarios);
      break;
    case 'addRubro':
        $rubro = $_POST['rubro'];
        $comentarios = $_POST['comentarios'];
        $rubros->agregarRubros($rubro, $comentarios);
      break;
    case 'eliminarRubro':
        $id_rubro = $_POST['id_rubro'];
        $rubros->deleteRubro($id_rubro);
      break;
    case 'traerDatosIniciales':
      $rubros->traerDatosIniciales();
      break;
  }
}else{
  if (isset($_GET['accion']) and $_GET['accion']=="traerRubros") {
    $rubros = new Rubros();
    echo $rubros->traerRubros($filtros);
  }
}?>