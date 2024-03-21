<?php
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
  // session isn't started
  session_start();
}
require_once('./conexion.php');
class TipoItems{
  private $id_tipo_item;
  
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

  Public function agregarTipoItem($tipoItem){

    $sql = "INSERT INTO tipos_items(tipo) VALUES('$tipoItem')";
    $insertTipoItem = $this->conexion->consultaSimple($sql);

  }
  
  public function traerTipoItems(){

    $sqlTraerTipoItems = "SELECT id as id_tipo_item, tipo FROM tipos_items";
    $traerTipoItems = $this->conexion->consultaRetorno($sqlTraerTipoItems);

    $arrayTipoItems = array(); //creamos un array

    while ($row = $traerTipoItems->fetch_array()) {
          $id_tipo_item = $row['id_tipo_item'];
          $tipo = $row['tipo'];
          $arrayTipoItems[] = array('id_tipo_item'=> $id_tipo_item, 'tipo'=>$tipo);
      }

      return json_encode($arrayTipoItems);

  }
  
  public function traerTipoItemUpdate($id_tipo){
    $this->id_tipo_item = $id_tipo;

    $sqlTraerTipoItems = "SELECT id as id_tipo_item, tipo FROM tipos_items	WHERE id = $this->id_tipo_item";
    $traerTipoItems = $this->conexion->consultaRetorno($sqlTraerTipoItems);

    $arrayTipoItems = array(); //creamos un array

    while ($row = $traerTipoItems->fetch_array()) {
          $id_tipo_item = $row['id_tipo_item'];
          $tipo = $row['tipo'];
          $arrayTipoItems[] = array('id_tipo_item'=> $id_tipo_item, 'tipo'=>$tipo);

    }

    echo json_encode($arrayTipoItems);

  }
  
  public function updateTipoItem($id_tipo, $tipoItem){

    $this->id_tipo_item = $id_tipo;

    $sqlUpdateTipoItem = "UPDATE tipos_items SET tipo ='$tipoItem'
              WHERE id=$this->id_tipo_item";
    $updateTipoItem = $this->conexion->consultaSimple($sqlUpdateTipoItem);
  }

  public function deleteTipoItem($id_tipo){
    $this->id_tipo_item = $id_tipo;

    /*ELIMINO CATEGORIA*/
    $sqlDeleteTipoItem = "DELETE FROM tipos_items WHERE id = $this->id_tipo_item";
    $delTipoItem = $this->conexion->consultaSimple($sqlDeleteTipoItem);
  }
}	

if (isset($_POST['accion'])) {
  $tipoItems = new TipoItems();
  switch ($_POST['accion']) {
    case 'traerAlmacenes':
      $tipoItems->traerTodosClientes();
      break;
    case 'traerTItemUpdate':
        $id_tipo = $_POST['id_tipo'];
        $tipoItems->traerTipoItemUpdate($id_tipo);
      break;
    case 'updateTipoItem':
        $id_tipo = $_POST['id_tipo'];
        $tipoItem = $_POST['tipoItem'];
        $tipoItems->updateTipoItem($id_tipo, $tipoItem);
      break;
    case 'addTipoItem':
        $tipoItem = $_POST['tipoItem'];
        $tipoItems->agregarTipoItem($tipoItem);
      break;
    case 'eliminarTipoItem':
        $id_tipo = $_POST['id_tipo'];
        $tipoItems->deleteTipoItem($id_tipo);
      break;
    case 'traerDatosIniciales':
      $tipoItems->traerDatosIniciales();
      break;
  }
}else{
  if (isset($_GET['accion']) and $_GET['accion']=="traerTipoItems") {
    $tipoItems = new TipoItems();
    echo $tipoItems->traerTipoItems();
  }
}
?>