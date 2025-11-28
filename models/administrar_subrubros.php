<?php
extract($_REQUEST);
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
  // session isn't started
  session_start();
}
require_once('./conexion.php');
require_once('administrar_rubros.php');
class Subrubros{
		private $conexion;
		private $id_subrubro;
		
		public function __construct(){
				$this->conexion = new Conexion();
				date_default_timezone_set("America/Buenos_Aires");
		}


		Public function addSubrubro($id_rubro, $subrubro, $comentarios){

			$sql = "INSERT INTO subrubros(id_rubro, subrubro, comentarios) VALUES('$id_rubro', '$subrubro', '$comentarios')";
			$insertRubro = $this->conexion->consultaSimple($sql);

		}

		public function traerSubrubros($filtros=[]){

      $filtro_subrubro=$filtro_rubro="";
      if($filtros!=[]){
        //var_dump($filtros);
        if(isset($filtros["id_subrubro"]) and $filtros["id_subrubro"]!=""){
          $filtro_subrubro=" AND id IN (".$filtros["id_subrubro"].")";
        }
        if(isset($filtros["id_rubro"]) and $filtros["id_rubro"]!=""){
          $filtro_rubro=" AND id_rubro IN (".$filtros["id_rubro"].")";
        }
      }

			$sqlTraerSubrubros = "SELECT id as id_subrubro, subrubro, id_rubro, comentarios 
      FROM subrubros s 
      WHERE 1 $filtro_subrubro $filtro_rubro";
      //var_dump($sqlTraerSubrubros);
      
			$traerSubrubros = $this->conexion->consultaRetorno($sqlTraerSubrubros);

			$subrubros = array(); //creamos un array

      $rubros = new Rubros();

			while ($row = $traerSubrubros->fetch_array()) {
        $filtro["id_rubro"]=$row['id_rubro'];
        
        $rubro=$rubros->traerRubros($filtro);
        $rubro=json_decode($rubro,true);

        $subrubros[] = array(
          'id_subrubro' =>$row['id_subrubro'],
          'subrubro'    =>$row['subrubro'],
          'comentarios' =>$row['comentarios'],
          'rubro'       =>$rubro[0]
        );
      }

      return json_encode($subrubros);

		}

		public function updateSubrubro($id_subrubro, $id_rubro, $subrubro, $comentarios){

			$this->id_subrubro = $id_subrubro;

			$sqlUpdateRubro = "UPDATE subrubros SET subrubro ='$subrubro', comentarios= '$comentarios', id_rubro='$id_rubro'
								WHERE id=$this->id_subrubro";
			$updateSubrubro = $this->conexion->consultaSimple($sqlUpdateRubro);
		}

		public function eliminarSubrubro($id_subrubro){
			$this->id_subrubro = $id_subrubro;

			/*ELIMINO RUBRO*/
			$sqlDeleteRubro = "DELETE FROM subrubros WHERE id = $this->id_subrubro";
			$delRubro = $this->conexion->consultaSimple($sqlDeleteRubro);
		}
		
}	

$filtros=[];
if(isset($id_rubro)) $filtros["id_rubro"]=$id_rubro;

$subrubros = new Subrubros();
if (isset($_POST['accion'])) {
  switch ($_POST['accion']) {
    case 'traerSubrubros':
      echo $subrubros->traerSubrubros($filtros);
    break;
    case 'updateSubrubro':
      $subrubros->updateSubrubro($id_subrubro, $id_rubro, $subrubro, $comentarios);
    break;
    case 'addSubrubro':
      $subrubros->addSubrubro($id_rubro, $subrubro, $comentarios);
    break;
    case 'eliminarSubrubro':
      $subrubros->eliminarSubrubro($id_subrubro);
    break;
  }
}else{
  if (isset($_GET['accion']) and $_GET["accion"]=="traerSubrubros") {
    echo $subrubros->traerSubrubros($filtros);
  }
}
?>