<?php
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
  // session isn't started
  session_start();
}
require_once('./conexion.php');
extract($_REQUEST);
class Cargos{
  private $id_cargo;
  
  public function __construct(){
      $this->conexion = new Conexion();
      date_default_timezone_set("America/Buenos_Aires");
  }

  public function traerDatosIniciales(){

    /*SINDICATOS*/
    $querySindicatos = "SELECT id, sindicato FROM sindicatos";
    $getSindicatos = $this->conexion->consultaRetorno($querySindicatos);

    

    $datosIniciales = array();
    $arraySindicatos = array();


    /*CARGO ARRAY SINDICATOS*/
    while ($rowsProv= $getSindicatos->fetch_array()) {
      $id_sindicato = $rowsProv['id'];
      $sindicato = $rowsProv['sindicato'];
      $arraySindicatos[] = array('id_sindicato' => $id_sindicato, 'sindicato' =>$sindicato);
    }


    $datosIniciales["sindicatos"] = $arraySindicatos;
    echo json_encode($datosIniciales);
  }

  Public function agregarCargos($cargo, $id_sindicato){

    $sql = "INSERT INTO cargos(cargo, id_sindicato) VALUES('$cargo', $id_sindicato)";
    $insertCargo= $this->conexion->consultaSimple($sql);

  }
  
  public function traerCargos($filtros=0){

    /*$sqlTraerCargos = "SELECT cr.id as id_cargo, cr.cargo, cr.id_sindicato,
            sind.sindicato 
            FROM cargos as cr LEFT JOIN sindicatos sind
            ON(cr.id_sindicato = sind.id)";*/

    $filtro_cargo="";
    if($filtros!=0){
        //var_dump($filtros);
        if(isset($filtros["id_cargo"]) and $filtros["id_cargo"]!=""){
            $filtro_cargo=" AND id IN (".$filtros["id_cargo"].")";
        }
    }
    $sqlTraerCargos = "SELECT id as id_cargo, cargo FROM cargos WHERE 1 $filtro_cargo";
    //var_dump($sqlTraerCargos);
    
    $traerCargos = $this->conexion->consultaRetorno($sqlTraerCargos);

    $arrayCargos = array(); //creamos un array

    while ($row = $traerCargos->fetch_array()) {
          $arrayCargos[] = array(
            'id_cargo'=> $row['id_cargo'],
            'cargo'=>$row['cargo'],
            //'sindicato'=> $sindicato
          );
      }

      return json_encode($arrayCargos);

  }
  
  public function traerCargoUpdate($id_cargo){
    $this->id_cargo = $id_cargo;

      $sqlTraerCargos = "SELECT cr.id as id_cargo, cr.cargo, cr.id_sindicato
            FROM cargos as cr
            WHERE cr.id = $this->id_cargo";
    $traerCargos = $this->conexion->consultaRetorno($sqlTraerCargos);

    $arrayCargos = array(); //creamos un array

    while ($row = $traerCargos->fetch_array()) {
          $id_cargo = $row['id_cargo'];
          $id_sindicato = $row['id_sindicato'];
          $cargo = $row['cargo'];
          $arrayCargos[] = array('id_cargo'=> $id_cargo, 'id_sindicato'=>$id_sindicato, 'cargo'=>$cargo);
      }

      echo json_encode($arrayCargos);



  }
  
  public function updateCargo($id_cargo, $cargo, $id_sindicato){

    $this->id_cargo = $id_cargo;

    $sqlUpdateCargo = "UPDATE cargos SET cargo ='$cargo', id_sindicato= $id_sindicato
              WHERE id=$this->id_cargo";
    $updateCargo = $this->conexion->consultaSimple($sqlUpdateCargo);
  }

  public function deleteRubro($id_cargo){
    $this->id_cargo = $id_cargo;

    /*ELIMINO CARGOS*/
    $sqlDeleteCargo = "DELETE FROM cargos WHERE id = $this->id_cargo";
    $delCargo = $this->conexion->consultaSimple($sqlDeleteCargo);
  }
}	

$filtros=[];
if(isset($id_cargo)) $filtros["id_cargo"]=$id_cargo;

if (isset($_POST['accion'])) {
  $cargos = new Cargos();
  switch ($_POST['accion']) {
    case 'traerCargos':
      echo $cargos->traerCargos($filtros);
      break;
    case 'traerCargoUpdate':
        $id_cargo = $_POST['id_cargo'];
        $cargos->traerCargoUpdate($id_cargo);
      break;
    case 'updateCargo':
        $id_cargo = $_POST['id_cargo'];
        $cargo = $_POST['cargo'];
        $id_sindicato = $_POST['id_sindicato'];
        $cargos->updateCargo($id_cargo, $cargo, $id_sindicato);
      break;
    case 'addCargo':
        $cargo = $_POST['cargo'];
        $id_sindicato = $_POST['id_sindicato'];
        $cargos->agregarCargos($cargo, $id_sindicato);
      break;
    case 'eliminarCargo':
        $id_cargo = $_POST['id_cargo'];
        $cargos->deleteRubro($id_cargo);
      break;
    case 'traerDatosIniciales':
      $cargos->traerDatosIniciales();
      break;
  }
}else{
  if (isset($_GET['accion']) and $_GET['accion']=="traerCargos") {
    $cargos = new Cargos();
    echo $cargos->traerCargos($filtros);
  }
}
?>